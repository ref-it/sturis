<?php

namespace App\Services\Dokuwiki;

use App\Models\Committee;
use App\Models\CurrentMember;
use App\Models\Meeting;
use App\Models\Term;
use App\Models\Template;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class Minutes
{
    public function __construct(
        private Client $dokuwiki,
    ) {}

    public function generate(int $committeeID, int $meetingID): void
    {
        $committee = Committee::find($committeeID);
        $meeting = Meeting::find($meetingID);

        $term = Term::where('start', '<=', $meeting->date)
            ->where('end', '>=', $meeting->date)
            ->first();

        $meetingNumber = Meeting::where('committee', $committeeID)
            ->where('date', '>=', $term?->start)
            ->where('date', '<=', $term?->end)
            ->count() + 1;

        $members = CurrentMember::where('committee', $committeeID)->get();

        match ($committee->minutes_type) {
            'dokuwiki' => $this->generateDokuWikiMinutes($committee, $meeting, $term, $meetingNumber, $members),
            default => null,
        };
    }

    private function generateDokuWikiMinutes(
        Committee $committee,
        Meeting $meeting,
        ?Term $term,
        int $meetingNumber,
        $members
    ): void {
        $page = $this->buildPagePath($committee, $meeting, $term);
        $text = $this->renderTemplate($committee, $meeting, $meetingNumber, $members);

        $this->dokuwiki->savePage(
            $page,
            $text,
            trans('messages.createdWithSturisBy', ['name' => auth()->user()?->name ?? '']),
            false
        );
    }

    private function buildPagePath(Committee $committee, Meeting $meeting, ?Term $term): string
    {
        $page = $committee->wiki_path_internal;

        if ($committee->minutes_separated_by_year) {
            $page .= ':' . $meeting->date->year . ':' . $meeting->date->toDateString();
        } elseif ($committee->minutes_separated_by_term && $term) {
            $page .= ':' . $term->name . ':' . $meeting->date->toDateString();
        } else {
            $page .= ':' . $meeting->date->toDateString();
        }

        return $page;
    }

    private function renderTemplate(Committee $committee, Meeting $meeting, int $meetingNumber, $members): string
    {
        $template = Template::where('committee', $committee->id)
            ->where('type', 'dokuwiki')
            ->firstOrFail();

        $templateContent = Storage::get($template->path);

        $membersElected = $members->where('role', 'elected')->map->name->implode(', ');
        $membersActive = $members->where('role', 'active')->map->name->implode(', ');
        $membersStaff = $members->where('role', 'staff')->map->name->implode(', ');

        return Blade::render($templateContent, [
            'committeeName' => $committee->name,
            'meetingNumber' => $meetingNumber,
            'meetingDate' => $meeting->date,
            'meetingTime' => $meeting->time,
            'meetingChair' => $meeting->chair,
            'minuteTaker' => $meeting->minute_taker,
            'membersElected' => $membersElected,
            'membersActive' => $membersActive,
            'membersStaff' => $membersStaff,
        ]);
    }







    public function getProtocoll(int $committeeID, int $meetingID): array
    {
        $committee = Committee::find($committeeID);
        $meeting = Meeting::find($meetingID);

        $term = Term::where('start', '<=', $meeting->date)
            ->where('end', '>=', $meeting->date)
            ->first();

        $this->extractResolutions($committeeID, $meetingID);
        $this->extractTodos($committeeID, $meetingID);
        $this->extractInternalNotes($committeeID, $meetingID);

        return [
            'committee' => $committee,
            'meeting' => $meeting,
            'term' => $term,
            'resolutions' => $this->categorizeResolutions(),
            'todos' => $this->todos,
            'internalNotes' => $this->internalNotes,
        ];
    }

    public function publishProtocoll(int $committeeID, int $meetingID): void
    {
        $committee = Committee::find($committeeID);
        $meeting = Meeting::find($meetingID);

        $term = Term::where('start', '<=', $meeting->date)
            ->where('end', '>=', $meeting->date)
            ->first();

        $this->extractResolutions($committeeID, $meetingID);

        $page = $this->buildPublishPagePath($committee, $meeting, $term);

        $content = $this->buildProtocollContent($committeeID, $meetingID);

        $this->dokuwiki->savePage(
            $page,
            $content,
            trans('messages.publishedProtocoll'),
            false
        );
    }

    private function extractResolutions(int $committeeID, int $meetingID): void
    {
        $items = AgendaItem::where('committee', $committeeID)
            ->where('meeting', $meetingID)
            ->get();

        foreach ($items as $item) {
            if ($item->resolutions || $item->decisions) {
                $this->parseResolution($item);
            }
        }
    }

    private function parseResolution(AgendaItem $item): void
    {
        $text = $item->resolutions ?? $item->decisions;
        $lines = array_filter(array_map('trim', explode("\n", $text)));

        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            $resolution = [
                'text' => $line,
                'type' => $this->detectResolutionType($line),
                'itemID' => $item->id,
                'title' => $item->title,
                'internal' => $item->internal ?? false,
                'votes' => $this->parseVotes($line),
            ];

            $this->resolutions[] = $resolution;
        }
    }

    private function detectResolutionType(string $text): string
    {
        $patterns = [
            'protocol' => ['protokoll', 'minutes', 'record'],
            'agenda' => ['agenda', 'tagesordnung', 'to'],
            'finance' => ['budget', 'finanzen', 'kosten', 'ausgaben'],
            'ordinance' => ['ordnung', 'regel', 'bestimmung'],
            'election' => ['wahl', 'gewählt', 'elected'],
            'motion' => ['antrag', 'motion', 'resolution'],
        ];

        $lowerText = strtolower($text);

        foreach ($patterns as $type => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($lowerText, $keyword)) {
                    return $type;
                }
            }
        }

        return 'other';
    }

    private function parseVotes(string $text): ?array
    {
        if (!preg_match('/\((\d+)\s*(?:yes|ja|for|für|pro)[,\s]+(\d+)\s*(?:no|nein|against|gegen|contra)[,\s]+(\d+)\s*(?:abstain|enthalt)/i', $text, $matches)) {
            return null;
        }

        return [
            'yes' => (int) $matches[1],
            'no' => (int) $matches[2],
            'abstain' => (int) $matches[3],
        ];
    }

    private function categorizeResolutions(): array
    {
        $categorized = [];

        foreach ($this->resolutions as $resolution) {
            $type = $resolution['type'];
            if (!isset($categorized[$type])) {
                $categorized[$type] = [];
            }
            $categorized[$type][] = $resolution;
        }

        return $categorized;
    }

    private function extractTodos(int $committeeID, int $meetingID): void
    {
        $items = AgendaItem::where('committee', $committeeID)
            ->where('meeting', $meetingID)
            ->get();

        foreach ($items as $item) {
            if (preg_match_all('/TODO:\s*(.+?)(?:\n|$)/i', $item->text ?? '', $matches)) {
                foreach ($matches[1] as $todo) {
                    $this->todos[] = [
                        'text' => trim($todo),
                        'itemID' => $item->id,
                        'title' => $item->title,
                        'status' => 'open',
                    ];
                }
            }
        }
    }

    private function extractInternalNotes(int $committeeID, int $meetingID): void
    {
        $items = AgendaItem::where('committee', $committeeID)
            ->where('meeting', $meetingID)
            ->where('internal', true)
            ->get();

        foreach ($items as $item) {
            $this->internalNotes[] = [
                'title' => $item->title,
                'text' => $item->text,
                'itemID' => $item->id,
            ];
        }
    }

    private function buildPublishPagePath(Committee $committee, Meeting $meeting, ?Term $term): string
    {
        $page = $committee->wiki_path_public;

        if ($committee->minutes_separated_by_year) {
            $page .= ':' . $meeting->date->year . ':' . $meeting->date->toDateString();
        } elseif ($committee->minutes_separated_by_term && $term) {
            $page .= ':' . $term->name . ':' . $meeting->date->toDateString();
        } else {
            $page .= ':' . $meeting->date->toDateString();
        }

        return $page;
    }

    private function buildProtocollContent(int $committeeID, int $meetingID): string
    {
        $content = '';

        foreach ($this->categorizeResolutions() as $type => $resolutions) {
            if (empty($resolutions)) {
                continue;
            }

            $content .= "=== " . ucfirst($type) . " ===\n\n";

            foreach ($resolutions as $i => $resolution) {
                $content .= "  * " . $resolution['text'];

                if ($resolution['votes']) {
                    $votes = $resolution['votes'];
                    $content .= " (" . $votes['yes'] . "/" . $votes['no'] . "/" . $votes['abstain'] . ")";
                }

                $content .= "\n";
            }

            $content .= "\n";
        }

        if (!empty($this->todos)) {
            $content .= "=== TODOs ===\n\n";
            foreach ($this->todos as $todo) {
                $content .= "  * " . $todo['text'] . " (from: " . $todo['title'] . ")\n";
            }
            $content .= "\n";
        }

        return $content;
    }
}

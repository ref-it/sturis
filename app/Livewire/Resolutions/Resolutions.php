<?php

namespace App\Livewire\Resolutions;

use App\Models\Committee;
use App\Models\Meeting;
use App\Models\Resolution;
use App\Models\Term;
use App\Services\Pdf\ResolutionsPdf;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Resolutions extends Component
{
    use WithPagination;

    const RESOLUTION_TYPES = [
        [ 'id' => 1, 'title' => 'A (Allgemein)' ],
        [ 'id' => 2, 'title' => 'E (Extern)' ],
        [ 'id' => 3, 'title' => 'F (Finanzen/Mitgliedschaft/Flyer)' ],
        [ 'id' => 4, 'title' => 'H (Finanzen)' ],
        [ 'id' => 5, 'title' => 'I (Intern)' ],
        [ 'id' => 6, 'title' => 'K (Konsul/Kooperation)' ],
        [ 'id' => 7, 'title' => 'O (Ordnung)' ],
        [ 'id' => 8, 'title' => 'P (Protokoll)' ],
        [ 'id' => 9, 'title' => 'R (Referate)' ],
        [ 'id' => 10, 'title' => 'S (Sonstiges/StuRa/Sitzung/Semesterbeitrag)' ],
        [ 'id' => 11, 'title' => 'T (Tagesordnung)' ],
        [ 'id' => 12, 'title' => 'U (Urabstimmung)' ],
        [ 'id' => 13, 'title' => 'V (Verträge/Vorschlag)' ],
        [ 'id' => 14, 'title' => 'W (Wahl)' ]
    ];

    public $committee;
    public $committeeID;
    private ?object $cachedTerm = null;

    #[Url]
    public $term;

    #[Url]
    public array $meeting = [];

    #[Url]
    public $number;

    #[Url]
    public $text;

    #[Url]
    public array $type = [];

    private function buildResolutionsQuery()
    {
        $resolutionsQuery = Resolution::where('resolutions.committee', $this->committeeID)
            ->join('meetings', 'meetings.id', '=', 'resolutions.meeting')
            ->select(
                'resolutions.id',
                'resolutions.committee',
                'resolutions.meeting',
                'resolutions.number',
                'resolutions.text',
                'resolutions.yes',
                'resolutions.no',
                'resolutions.abstention',
                'resolutions.result',
                'meetings.date'
            )
            ->orderBy('resolutions.meeting', 'desc');

        if ($this->number !== '') {
            $resolutionsQuery->where('resolutions.number', 'like', '%' . $this->number . '%');
        }

        if ($this->text !== '') {
            $resolutionsQuery->where('resolutions.text', 'like', '%' . $this->text . '%');
        }

        if (count($this->meeting) > 0) {
            $resolutionsQuery->whereIn('meetings.id', $this->meeting);
        }

        if ($this->term) {
            $selectedTerm = $this->getSelectedTerm();
            $resolutionsQuery->where('meetings.date', '>=', $selectedTerm->start)
                ->where('meetings.date', '<=', $selectedTerm->end);
        }

        if (count($this->type) > 0) {
            $resolutionsQuery->whereIn('resolutions.type', $this->type);
        }

        return $resolutionsQuery;
    }

    private function getSelectedTerm()
    {
        if ($this->term && $this->cachedTerm === null) {
            $this->cachedTerm = Term::where('number', $this->term)->first();
        }
        return $this->cachedTerm;
    }

    public function mount($committee)
    {
        $this->committee = $committee;
        $committeeData = Committee::where('token', $committee)->first();
        $this->committeeID = $committeeData->id;
    }

    public function render()
    {
        $resolutionsQuery = $this->buildResolutionsQuery();
        $resolutions = $resolutionsQuery->paginate(10);

        $terms = Term::orderBy('number', 'desc')->get();
        $meetingsQuery = Meeting::select('id', 'date')
            ->where('committee', $this->committeeID)
            ->orderBy('date', 'desc');
        if ($this->term) {
            $selectedTerm = $this->getSelectedTerm();
            $meetingsQuery->where('date', '>=', $selectedTerm->start)->where('date', '<=', $selectedTerm->end);
        }
        $meetings = $meetingsQuery->get();
        $types = self::RESOLUTION_TYPES;

        return view('livewire.resolutions.resolutions', [
            'meetings' => $meetings,
            'resolutions' => $resolutions,
            'terms' => $terms,
            'types' => $types,
        ]);
    }

    public function saveAsPdf()
    {
        $resolutionsQuery = $this->buildResolutionsQuery();
        $resolutions = $resolutionsQuery->get();

        $committeeData = Committee::where('token', $this->committee)->first();

        $meetingsQuery = Meeting::select('id', 'date')
            ->where('committee', $this->committeeID)
            ->orderBy('date', 'desc');
        if ($this->term) {
            $selectedTerm = $this->getSelectedTerm();
            $meetingsQuery->where('date', '>=', $selectedTerm->start)->where('date', '<=', $selectedTerm->end);
        }
        $meetings = $meetingsQuery->get();

        $types = self::RESOLUTION_TYPES;

        return (new ResolutionsPdf(
            $committeeData->name,
            $resolutions,
            $meetings,
            $this->meeting,
            $types,
            $this->type,
            $this->term,
        ))->download(strtolower(__('messages.resolutions')) . '.pdf');

    }
}

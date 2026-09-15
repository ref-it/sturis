<?php

namespace Tests\Feature\Pdf;

use App\Models\Meeting;
use App\Services\Pdf\AgendaPdf;
use App\Services\Pdf\ResolutionsPdf;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * The documents have to come out as tagged PDF/UA-2. The markers checked here
 * are the ones that carry the conformance; run veraPDF (`-f ua2`) against the
 * output for a full validation.
 */
class AccessiblePdfTest extends TestCase
{
    /**
     * @return array<string, array{0: callable(): string}>
     */
    public static function documents(): array
    {
        return [
            'agenda' => [fn () => self::agenda()],
            'resolutions' => [fn () => self::resolutions()],
        ];
    }

    #[DataProvider('documents')]
    public function test_it_declares_pdf_ua_2_conformance(callable $document): void
    {
        $pdf = $document();

        $this->assertStringStartsWith('%PDF-2.0', $pdf);
        $this->assertStringContainsString('pdfuaid', $pdf);
        $this->assertStringContainsString('part>2', $pdf);
    }

    #[DataProvider('documents')]
    public function test_it_is_tagged_and_carries_the_document_language(callable $document): void
    {
        $pdf = $document();

        $this->assertStringContainsString('/StructTreeRoot', $pdf);
        $this->assertStringContainsString('/MarkInfo', $pdf);
        $this->assertStringContainsString('/Marked true', $pdf);
        $this->assertStringContainsString('/DisplayDocTitle true', $pdf);
        $this->assertStringContainsString('/Lang', $pdf);
    }

    public function test_it_embeds_every_font(): void
    {
        // A font without a descriptor of its own is one of the standard-14,
        // which PDF/UA does not allow.
        $this->assertStringNotContainsString('/BaseFont /Helvetica', self::agenda());
    }

    private static function agenda(): string
    {
        $meeting = new Meeting;
        $meeting->forceFill([
            'date' => '2026-09-22',
            'time' => '18:00',
            'address' => 'Musterstraße 1',
            'room' => 'Sitzungssaal 2',
            'meeting_chairs' => json_encode(['Anna Müller']),
            'minute_takers' => json_encode(['Ben Schäfer']),
        ]);

        $item = (object) [
            'title' => 'Begrüßung',
            'children' => [],
            'editable' => true,
            'contentExists' => true,
            'expected_duration' => 15,
            'people' => ['Anna Müller'],
            'goals' => [],
            'guest' => false,
            'internal' => true,
        ];

        return (new AgendaPdf('Studierendenrat', $meeting, [$item]))->get();
    }

    private static function resolutions(): string
    {
        $resolutions = new Collection([
            (object) [
                'number' => '2026/001',
                'text' => 'Der Studierendenrat beschließt die Änderung der Finanzordnung.',
                'yes' => 12,
                'no' => 1,
                'abstention' => 2,
                'result' => 'angenommen',
            ],
        ]);

        $meetings = new Collection([(object) ['id' => 1, 'date' => '2026-09-22']]);

        return (new ResolutionsPdf(
            'Studierendenrat',
            $resolutions,
            $meetings,
            [1],
            [['id' => 1, 'title' => 'Finanzbeschluss']],
            [1],
            '2026/2027',
        ))->get();
    }
}

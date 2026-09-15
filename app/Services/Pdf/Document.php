<?php

namespace App\Services\Pdf;

use Com\Tecnick\Pdf\Tcpdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Base for the documents the application offers as a PDF download.
 *
 * A subclass describes one document - its title, its subject and its body as
 * markup - and this class turns that into a tagged PDF/UA-2 (ISO 14289-2) file.
 *
 * tc-lib-pdf writes the structure tree, the ParentTree, MarkInfo, /Lang and
 * DisplayDocTitle on its own once the pdfua2 mode is active, and maps every
 * HTML element onto a structure role. What the markup has to get right is the
 * semantics: real headings without skipping a level, and `th` with a `scope`
 * in every table.
 */
abstract class Document
{
    /**
     * Headings are set in Merriweather, body copy in Adwaita Sans. PDF/UA
     * requires every font to be embedded, so the standard-14 fonts cannot be
     * used here; see resources/fonts/pdf/README.md.
     */
    private const TEXT_FONT = 'adwaitasans';

    private const HEADING_FONT = 'merriweather';

    private const FONT_SIZE = 11.0;

    private const FOOTER_FONT_SIZE = 8.0;

    /**
     * Passed to Com\Tecnick\Pdf\Page::add(). Margins use that method's own keys
     * and are given in the document unit, millimetres here:
     *
     *   PL/PR  page left and right margin, measured from the page edge
     *   PT/HB  top of the header and bottom of the header, from the top edge
     *   CT/CB  top of the content, and the page breaking point from the bottom
     *   FT/PB  top of the footer and bottom of the footer, from the bottom edge
     */
    private const PAGE = [
        'format' => 'A4',
        'orientation' => 'P',
        'margin' => [
            'PL' => 25.0,
            'PR' => 20.0,
            'PT' => 20.0,
            'HB' => 20.0,
            'CT' => 20.0,
            'CB' => 28.0,
            'FT' => 28.0,
            'PB' => 20.0,
        ],
    ];

    abstract protected function title(): string;

    abstract protected function subject(): string;

    abstract protected function markup(): string;

    public function get(): string
    {
        return $this->render();
    }

    public function download(string $filename): StreamedResponse
    {
        $pdf = $this->render();

        return response()->streamDownload(
            fn () => print ($pdf),
            $filename,
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * A table of labels and their values, every label a row header. Rows
     * without a value are left out.
     *
     * @param  array<string, ?string>  $rows
     */
    protected function definitionTable(array $rows): string
    {
        $markup = '<table width="100%">';

        foreach ($rows as $label => $value) {
            if (blank($value)) {
                continue;
            }

            $markup .= '<tr>'
                .'<th scope="row" class="label">'.e($label).'</th>'
                .'<td class="value">'.e($value).'</td>'
                .'</tr>';
        }

        return $markup.'</table>';
    }

    private function render(): string
    {
        if (! defined('K_PATH_FONTS')) {
            define('K_PATH_FONTS', resource_path('fonts/pdf'));
        }

        $pdf = new Tcpdf(
            unit: 'mm',
            isunicode: true,
            subsetfont: true,
            compress: true,
            mode: 'pdfua2',
        );

        $pdf->setTitle($this->title());
        $pdf->setSubject($this->subject());
        $pdf->setCreator(config('app.name'));
        $pdf->setLanguage(str_replace('_', '-', app()->getLocale()));

        $page = $pdf->addPage(self::PAGE);

        $font = $pdf->font->insert($pdf->pon, self::TEXT_FONT, '', self::FONT_SIZE);
        $pdf->page->addContent($font['out']);

        $pdf->addHTMLCell(
            html: $this->styles().$this->markup(),
            posx: $page['margin']['PL'],
            posy: $page['margin']['CT'],
            width: $page['ContentWidth'],
        );

        $this->addFooters($pdf);

        return $pdf->getOutPDFString();
    }

    /**
     * A hairline and the page number on every page, right aligned on the text
     * column. Added once the content is laid out, because only then is it known
     * how many pages there are. Marked as a pagination artifact, as PDF/UA
     * requires for content that repeats on every page.
     */
    private function addFooters(Tcpdf $pdf): void
    {
        // The text is measured with whatever font is current on the stack, so
        // the footer font stays selected for the whole loop.
        $font = $pdf->font->insert($pdf->pon, self::TEXT_FONT, '', self::FOOTER_FONT_SIZE);

        foreach (array_keys($pdf->page->getPages()) as $pid) {
            // Page dimensions and margins are both in the document unit, and
            // the graph component needs the page it is drawing on to be current.
            $page = $pdf->setCurrentPage($pid);
            $left = $page['margin']['PL'];
            $right = $page['width'] - $page['margin']['PR'];
            $top = $page['height'] - $page['margin']['FT'];

            $footer = $pdf->graph->getStartTransform()
                .$pdf->graph->getLine($left, $top, $right, $top, [
                    'lineWidth' => 0.2,
                    'lineColor' => '#999999',
                ])
                .$font['out']
                .$pdf->color->getPdfFillColor('#333333')
                .$pdf->getTextCell(
                    txt: (string) ($pid + 1),
                    posx: $left,
                    posy: $top + 1.5,
                    width: $right - $left,
                    valign: 'T',
                    halign: 'R',
                )
                .$pdf->graph->getStopTransform();

            $pdf->addArtifactContent($footer, $pid, 'Pagination', 'Footer');
        }

        $pdf->font->popLastFont();
    }

    /**
     * The shared stylesheet.
     *
     * tc-lib-pdf ignores a CSS width on the table element itself, so the tables
     * carry width="100%" as an attribute. Every column then needs a width of
     * its own, or the last one is laid out too narrow.
     */
    private function styles(): string
    {
        $text = self::TEXT_FONT;
        $heading = self::HEADING_FONT;
        $size = self::FONT_SIZE;

        return <<<CSS
            <style>
                body { font-family: {$text}; font-size: {$size}pt; }
                h1, h2 { font-family: {$heading}; }
                h1 { font-size: 16pt; }
                h2 { font-size: 13pt; }
                th { text-align: left; vertical-align: top; }
                td { vertical-align: top; }
                .label { width: 22%; padding-bottom: 1mm; }
                .value { width: 78%; padding-bottom: 1mm; }
                .number { width: 15%; border-bottom: 0.2mm solid #dddddd; padding-top: 1.5mm; padding-bottom: 1.5mm; }
                .text { width: 85%; border-bottom: 0.2mm solid #dddddd; padding-top: 1.5mm; padding-bottom: 1.5mm; }
                .item-details { font-size: 9pt; }
                ol { list-style-type: none; margin-left: 0; }
                li { margin-top: 1mm; margin-bottom: 1mm; }
            </style>
            CSS;
    }
}

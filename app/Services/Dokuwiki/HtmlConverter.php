<?php

namespace App\Services\Dokuwiki;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

class HtmlConverter
{
    private array $listStack = [];

    public function convert(string $html): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;

        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        $body = $dom->getElementsByTagName('body')->item(0);

        if (!$body) {
            return '';
        }

        return $this->processNode($body);
    }

    private function processNode(DOMNode $node): string
    {
        $output = '';

        foreach ($node->childNodes as $child) {
            if ($child instanceof DOMText) {
                $text = trim($child->nodeValue);
                if (!empty($text)) {
                    $output .= $text;
                }
            } elseif ($child instanceof DOMElement) {
                $output .= $this->processElement($child);
            }
        }

        return $output;
    }

    private function processElement(DOMElement $element): string
    {
        $tag = strtolower($element->nodeName);

        return match ($tag) {
            'p' => $this->processParagraph($element),
            'br' => "\n",
            'strong', 'b' => '**' . $this->processNode($element) . '**',
            'em', 'i' => '//' . $this->processNode($element) . '//',
            'u' => '__' . $this->processNode($element) . '__',
            'del', 's' => '~~' . $this->processNode($element) . '~~',
            'sup' => '^' . $this->processNode($element) . '^',
            'sub' => '~~' . $this->processNode($element) . '~~',
            'code' => '%%' . trim($this->processNode($element)) . '%%',
            'pre' => $this->processCode($element),
            'blockquote' => $this->processBlockquote($element),
            'h1' => '===== ' . $this->processNode($element) . " =====\n\n",
            'h2' => '==== ' . $this->processNode($element) . " ====\n\n",
            'h3' => '=== ' . $this->processNode($element) . " ===\n\n",
            'h4' => '== ' . $this->processNode($element) . " ==\n\n",
            'h5' => '= ' . $this->processNode($element) . " =\n\n",
            'h6' => '^ ' . $this->processNode($element) . " ^\n\n",
            'ul' => $this->processList($element, false),
            'ol' => $this->processList($element, true),
            'a' => $this->processLink($element),
            'img' => $this->processImage($element),
            'table' => $this->processTable($element),
            'hr' => "----\n\n",
            'div', 'span' => $this->processNode($element),
            default => $this->processNode($element),
        };
    }

    private function processParagraph(DOMElement $element): string
    {
        $content = trim($this->processNode($element));
        return !empty($content) ? $content . "\n\n" : '';
    }

    private function processCode(DOMElement $element): string
    {
        $content = trim($element->textContent);
        return "<code>\n" . $content . "\n</code>\n\n";
    }

    private function processBlockquote(DOMElement $element): string
    {
        $content = trim($this->processNode($element));
        $lines = explode("\n", $content);
        $quoted = array_map(fn($line) => '> ' . $line, $lines);
        return implode("\n", $quoted) . "\n\n";
    }

    private function processList(DOMElement $element, bool $ordered): string
    {
        $items = [];
        $level = count($this->listStack);

        foreach ($element->getElementsByTagName('li') as $li) {
            if ($li->parentNode === $element) {
                $marker = $ordered ? '-' : '*';
                $indent = str_repeat('  ', $level + 1);
                $content = trim($this->processNode($li));
                $items[] = $indent . $marker . ' ' . $content;
            }
        }

        return implode("\n", $items) . "\n\n";
    }

    private function processLink(DOMElement $element): string
    {
        $href = $element->getAttribute('href') ?? '';
        $text = trim($this->processNode($element));

        if (empty($href)) {
            return $text;
        }

        if (empty($text)) {
            $text = $href;
        }

        return "[[{$href}|{$text}]]";
    }

    private function processImage(DOMElement $element): string
    {
        $src = $element->getAttribute('src') ?? '';
        $alt = $element->getAttribute('alt') ?? 'image';

        if (empty($src)) {
            return '';
        }

        return "{{" . $src . "|" . $alt . "}}";
    }

    private function processTable(DOMElement $element): string
    {
        $output = '';

        foreach ($element->getElementsByTagName('tr') as $tr) {
            $row = [];

            $cells = $tr->getElementsByTagName('td');
            if ($cells->length === 0) {
                $cells = $tr->getElementsByTagName('th');
                $isHeader = true;
            } else {
                $isHeader = false;
            }

            foreach ($cells as $cell) {
                $content = trim($this->processNode($cell));
                $row[] = $content;
            }

            $separator = $isHeader ? '^' : '|';
            $output .= $separator . ' ' . implode(' ' . $separator . ' ', $row) . " " . $separator . "\n";
        }

        return $output . "\n";
    }
}

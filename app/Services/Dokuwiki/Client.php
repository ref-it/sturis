<?php

namespace App\Services\Dokuwiki;

use App\Services\DokuWiki\HtmlConverter;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client
{
    public function __construct(
        private string $url,
        private string $username,
        private string $password,
    ) {}

    public function call(string $method, array $params = []): mixed
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->post($this->url, [
                'jsonrpc' => '2.0',
                'method' => $method,
                'params' => $params,
                'id' => uniqid(),
            ]);

        $this->throwIfError($response);

        $data = $response->json();

        if (isset($data['error'])) {
            throw new DokuWikiException($data['error']['message'], $data['error']['code']);
        }

        return $data['result'] ?? null;
    }

    public function getPage(string $id): string
    {
        return $this->call('core.getPage', [$id]);
    }

    public function savePage(string $page, string $text, string $summary, bool $isminor): bool
    {
        return $this->call('core.savePage', [$page, $text, $summary, $isminor]);
    }

    public function listMedia(string $namespace, string $pattern, int $depth, bool $hash)
    {
        return $this->call('core.listMedia', [$namespace, $pattern, $depth, $hash]);
    }

    public function saveMedia(string $media, string $base64, bool $overwrite)
    {
        return $this->call('core.saveMedia', [$media, $base64, $overwrite]);
    }

    public function convertHtmlToDokuWiki(string $html): string
    {
        $converter = new HtmlConverter();
        return $converter->convert($html);
    }

    private function throwIfError(Response $response): void
    {
        // TODO Handle error
    }
}

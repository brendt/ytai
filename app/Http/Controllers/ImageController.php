<?php

namespace App\Http\Controllers;

use League\CommonMark\MarkdownConverter;
use Spatie\Browsershot\Browsershot;

final class ImageController
{
    public function __invoke(MarkdownConverter $markdown)
    {
        $code = $markdown->convert(<<<MD
        ```php
        <hljs keyword>enum</hljs> <hljs type>Status</hljs> <hljs keyword>implements</hljs> <hljs type>HasColor</hljs>
        {
            case <hljs prop>DRAFT</hljs>;
            case <hljs prop>PUBLISHED</hljs>;
            case <hljs prop>ARCHIVED</hljs>;

            public function color(): string { /* ... */ } // hello world
        }
        ```
        MD)->getContent();

        $html = view('image', [
            'code' => $code,
        ])->render();

        if (request()->query->has('html')) {
            return $html;
        }

        $path = storage_path('tmp/image.jpg');

        Browsershot::html($html)
            ->deviceScaleFactor(2)
            ->windowSize(1080, 1920)
            ->setOption('args', ['--disable-web-security'])
            ->delay(500)
            ->save($path);

        return response()->file($path);
    }
}

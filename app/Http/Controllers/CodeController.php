<?php

namespace App\Http\Controllers;

use League\CommonMark\MarkdownConverter;

final class CodeController
{
    public function __construct(private MarkdownConverter $markdown) {}

    public function __invoke(string $slug)
    {
        $code = $this->markdown->convert(file_get_contents(__DIR__ . "/code/{$slug}.md"))->getContent();

        return view('code', [
            'code' => $code,
        ]);
    }
}

<?php

namespace App\Actions;

use League\CommonMark\MarkdownConverter;
use Spatie\Browsershot\Browsershot;

final readonly class RenderSlideImageAction
{
    public function __construct(
        private MarkdownConverter $markdown
    ) {}

    public function __invoke(string $code, string $imagePath): void
    {
        @unlink($imagePath);

        $code = $this->markdown->convert($code)->getContent();

        $html = view('image', [
            'code' => $code,
        ])->render();

        Browsershot::html($html)
            ->deviceScaleFactor(2)
            ->windowSize(1920, 1080)
            ->setOption('args', ['--disable-web-security'])
            ->delay(1000)
            ->save($imagePath);
    }
}

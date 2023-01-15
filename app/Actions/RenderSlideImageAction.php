<?php

namespace App\Actions;

use App\Data\Dimensions;
use League\CommonMark\MarkdownConverter;
use Spatie\Browsershot\Browsershot;

final readonly class RenderSlideImageAction
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {}

    public function __invoke(
        string $code,
        string $imagePath,
        Dimensions $dimensions,
    ): void {
        @unlink($imagePath);

        $code = $this->markdown->convert($code)->getContent();

        $html = view('image', [
            'code' => $code,
        ])->render();

        Browsershot::html($html)
            ->deviceScaleFactor(2)
            ->windowSize($dimensions->width, $dimensions->height)
            ->setOption('args', ['--disable-web-security'])
            ->delay(1000)
            ->save($imagePath);
    }
}

<?php

namespace App\Markdown;

class SrcsetVariation
{
    public function __construct(
        public readonly string $url,
        public readonly int $width,
        public readonly int $height,
    ) {
    }

    public static function fromBaseUrl(string $url, int $width, int $height): self
    {
        [
            'dirname' => $dirname,
            'filename' => $filename,
            'extension' => $extension,
        ] = pathinfo($url);

        return new self(
            "{$dirname}/{$filename}-{$width}x{$height}.{$extension}",
            $width,
            $height,
        );
    }

    public function __toString(): string
    {
        return "{$this->url} {$this->width}w";
    }
}

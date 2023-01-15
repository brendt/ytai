<?php

namespace App\Actions;

final class SaveSubtitlesAction
{
    public function __invoke(array $parts, string $subtitlesPath): void
    {
        @unlink($subtitlesPath);

        file_put_contents($subtitlesPath, implode(PHP_EOL, $parts));
    }
}

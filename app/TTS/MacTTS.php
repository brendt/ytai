<?php

namespace App\TTS;

final class MacTTS implements TTSDriver
{
    public function convert(string $text, string $outputPath): void
    {
        $command = implode(' ', [
            'say',
            '-o "' . $outputPath . '"',
            '--file-format WAVE',
            '--data-format I16',
            '"' . $text . '"',
        ]);

        passthru($command);
    }
}

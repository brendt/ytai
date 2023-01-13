<?php

namespace App\TTS;

final class PythonTTS implements TTSDriver
{
    public function convert(string $text, string $outputPath): void
    {
//        tts --text "P.H.P. 8.2" --out_path ~/tts-test.wav

        $command = implode(' ', [
            'tts',
            '--text "' . $text . '"',
            "--out_path {$outputPath}",
        ]);

        passthru($command);
    }
}

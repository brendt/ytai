<?php

namespace App\Actions;

use App\TTS\TTSDriver;

final class TextToSpeechAction
{
    public function __construct(
        private readonly TTSDriver $tts,
    ) {}

    public function __invoke(
        string $text,
        string $outputPath,
    ): void
    {
        $this->tts->convert($text, $outputPath);
    }
}

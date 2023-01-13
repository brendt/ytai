<?php

namespace App\TTS;

interface TTSDriver
{
    public function convert(string $text, string $outputPath): void;
}

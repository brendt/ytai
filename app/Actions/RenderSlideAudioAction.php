<?php

namespace App\Actions;

use Spatie\Browsershot\Browsershot;

final class RenderSlideAudioAction
{
    public function __construct(
        private readonly TextToSpeechAction $ttsAction,
    ) {}

    public function __invoke(string $text, string $audioPath): void
    {
        @unlink($audioPath);

        ($this->ttsAction)($text, $audioPath);
    }
}

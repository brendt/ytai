<?php

namespace App\Data;

final readonly class ParsedParagraph
{
    public function __construct(
        public string $textForSubs,
        public string $textForAudio,
        public string $code,
    ) {}
}

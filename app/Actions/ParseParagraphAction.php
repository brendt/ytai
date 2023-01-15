<?php

namespace App\Actions;

use App\Data\ParsedParagraph;

final class ParseParagraphAction
{
    private static array $phonetics = [
        'PHP' => 'Pee aitch pee',
        'enum' => 'eenum',
        'Enum' => 'eenum',
    ];

    public function __invoke(string $paragraph): ParsedParagraph
    {
        [$text, $code] = explode('```php', $paragraph);

        $text = $this->prepareText($text);

        $code = '```php' . $code;

        return new ParsedParagraph(
            textForSubs: $this->parseTextForSubs($text),
            textForAudio: $this->parseTextForAudio($text),
            code: $code,
        );
    }

    private function prepareText(string $text): string
    {
        // TODO: allow case insensitive replaces
        // TODO: Prevent manual phonetics to be overwritten (so if /PHP/Pee aitch pee/ is present, ignore it)
        foreach (self::$phonetics as $search => $phonetic) {
            $text = str_replace($search, "/{$search}/{$phonetic}/", $text);
        }

        return $text;

    }

    private function parseTextForSubs(string $text): string
    {
        return preg_replace_callback(
            pattern: '/\/([\w\s]+)\/([\w\s]+)\//',
            callback: function ($matches) {
                return $matches[1];
            },
            subject: $text
        );
    }

    private function parseTextForAudio(string $text): string
    {
        return preg_replace_callback(
            pattern: '/\/([\w\s]+)\/([\w\s]+)\//',
            callback: function ($matches) {
                return $matches[2];
            },
            subject: $text
        );
    }
}

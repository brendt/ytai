<?php

namespace App\Console\Commands;

use App\Actions\ParseParagraphAction;
use App\Actions\RenderSlideAudioAction;
use App\Actions\RenderSlideImageAction;
use App\Actions\RenderVideoAction;
use App\Actions\SaveSubtitlesAction;
use App\Enums\Format;
use Illuminate\Console\Command;

class MakeVideoCommand extends Command
{
    protected $signature = 'make:video {id} {formats=youtube}';

    public function handle(
        ParseParagraphAction $parseParagraph,
        RenderSlideImageAction $renderSlideImage,
        RenderSlideAudioAction $renderSlideAudio,
        RenderVideoAction $renderVideo,
        SaveSubtitlesAction $saveSubtitles,
    ): void
    {
        $id = $this->argument('id');

        /** @var Format[] $formats */
        $formats = array_map(
            fn(string $format) => Format::from($format),
            explode(',', $this->argument('formats')),
        );

        $paragraphs = explode('---', file_get_contents(storage_path("videos/{$id}/{$id}.md")));

        $subtitles = [];

        foreach ($paragraphs as $index => $paragraph) {
            $parsedParagraph = $parseParagraph($paragraph);
            $subtitles[] = $parsedParagraph->textForSubs;

            // Image
            foreach ($formats as $format) {
                $imagePath = storage_path("videos/{$id}/{$index}-{$format->getDimensions()}.jpg");
                $this->comment("Creating {$imagePath}");
                $renderSlideImage($parsedParagraph->code, $imagePath, $format->getDimensions());
            }

            // Audio
            $audioPath = storage_path("videos/{$id}/{$index}.wav");
            $this->comment("Creating {$audioPath}");
            $renderSlideAudio($parsedParagraph->textForAudio, $audioPath);
        }

        $outputPaths = [];

        foreach ($formats as $format) {
            $this->comment("Rendering Video for {$format->getDimensions()}");
            $outputPath = storage_path("videos/{$id}/{$id}-{$format->getDimensions()}-finished.mp4");
            $renderVideo($id, $outputPath, $format->getDimensions());
            $outputPaths[] = $outputPath;
        }

        $this->comment("Saving subtitles");
        $subtitlesPath = storage_path("videos/{$id}/{$id}-subs.txt");
        $saveSubtitles($subtitles, $subtitlesPath);

        // TODO: thumbnail

        $this->info("Done");
        foreach ($outputPaths as $outputPath) {
            $this->comment(" - {$outputPath}");
        }
        $this->comment(" - {$subtitlesPath}");
    }
}

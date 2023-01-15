<?php

namespace App\Console\Commands;

use App\Actions\RenderSlideAudioAction;
use App\Actions\RenderSlideImageAction;
use App\Actions\RenderVideoAction;
use App\Actions\SaveSubtitlesAction;
use Illuminate\Console\Command;

class MakeVideoCommand extends Command
{
    protected $signature = 'make:video {id}';

    public function handle(
        RenderSlideImageAction $renderSlideImage,
        RenderSlideAudioAction $renderSlideAudio,
        RenderVideoAction $renderVideo,
        SaveSubtitlesAction $saveSubtitles,
    ): void
    {
        $id = $this->argument('id');

        $paragraphs = explode('---', file_get_contents(storage_path("videos/{$id}/{$id}.md")));

        $subtitles = [];

        foreach ($paragraphs as $index => $paragraph) {
            [$text, $code] = explode('```php', $paragraph);
            $subtitles[] = $text;
            $code = '```php' . $code;

            // Image
            $imagePath = storage_path("videos/{$id}/{$index}.jpg");
            $this->comment("Creating {$imagePath}");
            $renderSlideImage($code, $imagePath);

            // Audio
            $audioPath = storage_path("videos/{$id}/{$index}.wav");
            $this->comment("Creating {$audioPath}");
            $renderSlideAudio($text, $audioPath);
        }

        $this->comment("Rendering Video");
        $outputPath = storage_path("videos/{$id}/{$id}-finished.mp4");
        $renderVideo($id, $outputPath);

        $this->comment("Saving subtitles");
        $subtitlesPath = storage_path("videos/{$id}/{$id}-subs.txt");
        $saveSubtitles($subtitles, $subtitlesPath);

        // TODO: thumbnail

        $this->info("Done");
        $this->comment(" - {$outputPath}");
        $this->comment(" - {$subtitlesPath}");
    }
}

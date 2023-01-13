<?php

namespace App\Console\Commands;

use App\Actions\RenderSlideAudioAction;
use App\Actions\RenderSlideImageAction;
use App\Actions\RenderVideoAction;
use Illuminate\Console\Command;

class MakeVideoCommand extends Command
{
    protected $signature = 'make:video {id}';

    public function handle(
        RenderSlideImageAction $renderSlideImage,
        RenderSlideAudioAction $renderSlideAudio,
        RenderVideoAction $renderVideo,
    ): void
    {
        $id = $this->argument('id');

        // 1. Split input script into paragraphs and code blocks
        // 2. Render code blocks as PNGs
        // 3. Convert each paragraph to WAV
        // 4. Stitch WAVs and PNGs together ✅
        // 5. Merge all separate parts into one ✅

        $paragraphs = explode('---', file_get_contents(storage_path("videos/{$id}/{$id}.md")));

        foreach ($paragraphs as $index => $paragraph) {
            [$text, $code] = explode('```php', $paragraph);
            $code = '```php' . $code;

            // Image
            $imagePath = storage_path("videos/{$id}/{$index}.jpg");
            $this->info("Creating {$imagePath}");
            $renderSlideImage($code, $imagePath);

            // Audio
            $audioPath = storage_path("videos/{$id}/{$index}.wav");
            $this->info("Creating {$audioPath}");
            $renderSlideAudio($text, $audioPath);
        }

        $renderVideo($id);

        $this->info('Done');
    }
}

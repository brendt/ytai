<?php

namespace App\Console\Commands;

use App\Jobs\RenderVideoJob;
use App\Jobs\TextToSpeechJob;
use Illuminate\Console\Command;

class MakeAudioCommand extends Command
{
    protected $signature = 'make:audio {text} {outputPath}';

    public function handle(): void
    {
        dispatch(new TextToSpeechJob(
            $this->argument('text'),
            $this->argument('outputPath'),
        ));

        $this->info('Done');
    }
}

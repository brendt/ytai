<?php

namespace App\Actions;

final class RenderVideoAction
{
    public function __invoke(string $videoId): void
    {
        $parts = [];
        $filesPath = storage_path("videos/{$videoId}/files.txt");
        $outputPath = storage_path("videos/{$videoId}/{$videoId}-finished.mp4");

        @unlink($outputPath);
        @unlink($filesPath);

        foreach (glob(storage_path("videos/{$videoId}/*.wav")) as $path) {
            $filename = pathinfo($path, PATHINFO_FILENAME);
            $imagePath = storage_path("videos/{$videoId}/{$filename}.jpg");
            $audioPath = storage_path("videos/{$videoId}/{$filename}.wav");
            $combinedPath = storage_path("videos/{$videoId}/{$filename}-combined.mp4");

            @unlink($combinedPath);

            $this->ffmpeg(
                '-loop 1',
                '-framerate 60',
                "-i {$imagePath}",
                "-i {$audioPath}",
                "-c:v libx264",
                "-strict experimental",
                "-tune stillimage",
                "-c:a aac",
                "-b:a 256k",
                "-pix_fmt yuv420p",
                '-shortest',
                '-fflags +shortest',
                $combinedPath,
            );

            $parts[] = "file {$combinedPath}";
        }

        file_put_contents($filesPath, implode(PHP_EOL, $parts));

        $this->ffmpeg(
            '-f concat',
            '-safe 0 ',
            "-i {$filesPath}",
            '-c copy ',
            $outputPath,
        );
    }

    private function ffmpeg(string ...$args): void
    {
        $command = implode(' ', ['ffmpeg', ...$args]);

        passthru($command);
    }
}

<?php

namespace App\Markdown;

use Intervention\Image\Image;

class SrcsetScaler
{
    public function __construct(
        private float $stepModifier = 0.2
    ) {
    }

    /**
     * @return \App\Markdown\SrcsetVariation[]
     */
    public function getVariations(string $url, Image $scaleableImage): array
    {
        $fileSize = $scaleableImage->filesize();
        $width = $scaleableImage->width();

        $ratio = $scaleableImage->height() / $width;
        $area = $width * $width * $ratio;
        $pixelPrice = $fileSize / $area;

        $stepAmount = $fileSize * $this->stepModifier;

        $variations = [];

        do {
            $newWidth = (int) floor(sqrt(($fileSize / $pixelPrice) / $ratio));

            if ($newWidth < 50) {
                break;
            }

            $variations[$newWidth] = SrcsetVariation::fromBaseUrl(
                $url,
                $newWidth,
                $newWidth * $ratio
            );

            $fileSize -= $stepAmount;
        } while ($fileSize > 0);

        return $variations;
    }
}

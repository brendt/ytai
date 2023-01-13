<?php

namespace App\Markdown;

use App\Jobs\GenerateSrcsetVariations;
use Exception;
use File;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManager;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class ImageRenderer implements NodeRendererInterface
{
    public function __construct(
        private ImageManager $imageManager,
        private SrcsetScaler $scaler,
        private bool $cacheImages,
    ) {
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (! $node instanceof Image) {
            throw new Exception("Node is not an image");
        }

        $url = $node->getUrl();

        $variations = $this->createVariations($url);

        $this->dispatchVariationJob($url, $variations);

        $alt = $node->firstChild();

        return new HtmlElement(
            tagName: 'img',
            attributes: [
                'src' => $url,
                'srcset' => $this->createSrcset($variations),
                'alt' => $alt instanceof Text ? $alt->getLiteral() : '',
            ],
            selfClosing: true
        );
    }

    /**
     * @param \App\Markdown\SrcsetVariation[] $variations
     */
    private function createSrcset(array $variations): string
    {
        return implode(
            ', ',
            array_map(
                fn (SrcsetVariation $variation) => (string) $variation,
                $variations
            ),
        );
    }

    /**
     * @return \App\Markdown\SrcsetVariation[] $variations
     */
    private function createVariations(string $url): array
    {
        try {
            $image = $this->imageManager->make(content_path($url));
        } catch (NotReadableException) {
            return [];
        }

        return $this->scaler->getVariations($url, $image);
    }

    /**
     * @return \App\Markdown\SrcsetVariation[] $variations
     */
    private function dispatchVariationJob(string $url, array $variations): void
    {
        if ($this->cacheImages && file_exists(content_destination_path($url))) {
            return;
        }

        dispatch(new GenerateSrcsetVariations($url, $variations));
    }
}

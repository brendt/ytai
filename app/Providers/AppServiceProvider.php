<?php

namespace App\Providers;

use App\Markdown\HighlightCodeBlockRenderer;
use App\Markdown\HighlightInlineCodeRenderer;
use App\TTS\MacTTS;
use App\TTS\PythonTTS;
use App\TTS\TTSDriver;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\FrontMatter\Data\SymfonyYamlFrontMatterParser;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterParser;
use League\CommonMark\MarkdownConverter;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(
            TTSDriver::class,
            fn() => match (config('services.tts.driver')) {
                'python' => new PythonTTS(),
                'mac' => new MacTTS(),
            },
        );

        $this->app->singleton(
            MarkdownConverter::class,
            function () {
                $environment = new Environment();

                $environment
                    ->addExtension(new CommonMarkCoreExtension())
                    ->addExtension(new FrontMatterExtension())
                    ->addRenderer(FencedCode::class, app(HighlightCodeBlockRenderer::class))
                    ->addRenderer(Code::class, app(HighlightInlineCodeRenderer::class));

                return new MarkdownConverter($environment);
            },
        );

        $this->app->singleton(
            FrontMatterParser::class,
            fn () => new FrontMatterParser(new SymfonyYamlFrontMatterParser()),
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

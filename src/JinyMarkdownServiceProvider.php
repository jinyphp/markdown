<?php
namespace Jiny\Markdown;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;

class JinyMarkdownServiceProvider extends ServiceProvider
{
    private $package = "jiny-markdown";
    public function boot()
    {
        // 모듈: 라우트 설정
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->package);

        // 데이터베이스
        $this->loadMigrationsFrom(__DIR__.'/../databases/migrations');

        /**
         * Markdown Directive
         */
        Blade::directive('markdownText', function ($args) {
            $body = Blade::stripParentheses($args);
            return (new \Parsedown())->text($body);
        });

        Blade::directive('markdownFile', function ($args) {
            $args = Blade::stripParentheses($args);
            $args = trim($args,'"');
            if($args[0] == ".") {
                $path = str_replace(".", DIRECTORY_SEPARATOR, $args).".md";
                $realPath = dirname(Blade::getPath()).$path;
            }

            if (file_exists($realPath)) {
                $body = file_get_contents($realPath);
                return (new \Parsedown())->text($body);
            } else {
                return "cannot find markdown resource ".$realPath."<br>";
            }
        });

        Blade::directive('codeFile', function ($args) {
            $expression = Blade::stripParentheses($args);

            return "<?php echo \$__env->make({$expression}, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>";
        });

        // 마크다운 컴포넌트
        Blade::component(\Jiny\Markdown\View\Markdown::class,'markdown');







    }



    public function register()
    {

        /* 라이브와이어 컴포넌트 등록 */
        $this->app->afterResolving(BladeCompiler::class, function () {
            Livewire::component('quill-editor',
                \Jiny\Markdown\Http\Livewire\QuillEditor::class);
        });
    }
}

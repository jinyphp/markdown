<?php

namespace Jiny\Markdown\View;

use Illuminate\View\Component;

class Markdown extends Component
{

    public function __construct()
    {
    }

    public function toHtml(string $markdown): string
    {
        $markdown = htmlspecialchars($markdown, ENT_QUOTES, 'UTF-8');
        return (new \Parsedown())->text($markdown);
        return $markdown;
    }

    public function render()
    {
        return <<<'blade'
        <div {{ $attributes }}>{!! $toHtml($slot) !!}</div>
    blade;
    }
}

<?php

if (! function_exists('is_markdown_installed')) {
    function is_markdown_installed() {
        return ture;
    }
}


if (! function_exists('markdown')) {
	function markdown($body) {
        $Parsedown = new \Parsedown();
        $html = $Parsedown->parse($body);

		return $html;
	}
}

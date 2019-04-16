<?php

namespace Jiny;

use \Jiny\Markdown\Markdown;

if (! function_exists('markdown')) {
	function markdown($body) {
		if (func_num_args()) {
            $obj = Markdown::instance();
            return $obj->render($body);
		} else {
			return $obj;
		}
	}
}
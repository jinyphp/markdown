<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny;

if (! function_exists('markdown')) {
	function markdown($body) {
		if (func_num_args()) {
            $obj = \Jiny\Markdown\Markdown::instance();
            return $obj->render($body);
		} else {
			// 인자값이 업는 경우, 객체를 반환합니다.
			return $obj;
		}
	}
}
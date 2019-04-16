<?php

/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Markdown;

class Markdown
{
    /**
     * 인스턴스
     */
    private static $Instance;

    /**
     * 싱글턴 인스턴스를 생성합니다.
     */
    public static function instance()
    {
        if (!isset(self::$Instance)) {
            // 자기 자신의 인스턴스를 생성합니다.                
            self::$Instance = new self();

            // 마크다운 인스턴스
            self::$Instance->Parsedown();

            return self::$Instance;
        } else {
            // 인스턴스가 중복
            return self::$Instance; 
        }
    }

    private $Parsedown;
    private function Parsedown()
    {
        if (!isset($this->Parsedown)) {
            // 컴포저 패키지 참고
            $this->Parsedown = new \Parsedown();
        }

        return $this;
    }
 
    public function render($body)
    {
        return $this->Parsedown->text($body);
    }
        
    /**
     * 
     */
}
<?php
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

            return self::$Instance;
        } else {
            // 인스턴스가 중복
            return self::$Instance;
        }
    }

    // 싱글턴 공유데이터
    public $bookmark = [];

}

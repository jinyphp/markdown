<?php
namespace Jiny\Markdown;

class MarkdownPage
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

    public $filename;
    public $content;
    public $data=[];
    public $html;
    public $bookmark=[];

    public function load($file)
    {
        // 일치하는 마크다운 파일이 있는 경우
        if(file_exists($file)) {
            return file_get_contents($file);
        }

        return false;
    }

    /**
     * 프론트메터 분리
     */
    public function parser($txt="")
    {
        $frontMatter = \Webuni\FrontMatter\FrontMatterChain::create();
        $obj = $frontMatter->parse($txt);

        $this->data = $obj->getData();
        $this->content = $obj->getContent();

        return $this;
    }

    public function view($path=null)
    {
        // 마크다운 변환
        $html = (new \Parsedown())->parse($this->content);

        // fontmatter에 layout이 있는 경우
        if(isset($this->data['layout'])) {
            $viewFile = $this->data['layout'];
        } else {
            // 레이아웃 지정 파일
            if($path) {
                $viewFile = $path;
            } else {
                // 기본 레이아웃
                $viewFile = "jiny-markdown::layout";
            }
        }

        // 북마크
        $bookmark = $this->parseBookmark($html);
        $html = $this->html; // 북마크 포함 html 반환

        return view($viewFile,[
            'slot' => $html,
            'bookmark' => $bookmark, // 북마크
            'data' => $this->data, // 프론트메터 데이터
            'filename' => $this->filename // 파일명
        ]);
    }

    /**
     * 북마크 추출
     */
    public function parseBookmark($html)
    {
        // HTML 콘텐츠를 DOMDocument 개체에 로드
        $doc = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);

        // Load HTML with the correct encoding
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        // DOMXPath를 사용하여 h2 요소 찾기
        $xpath = new \DOMXPath($doc);
        $h2Elements = $xpath->query('//h2');

        // 북마크 삽입 및 h2 텍스트 추출
        $h2Array = [];
        $bookmarkCount = 1;
        foreach ($h2Elements as $h2) {
            $id = 'bookmark' . sprintf('%02d', $bookmarkCount++);

            // Assign ID to the h2 tag
            $h2->setAttribute('id', $id);

            // Add the h2 text to the array
            $h2Array[] = [
                'title' => $h2->textContent,
                'id' => $id
            ];
        }

        $bodyContent = '';

        $body = $doc->getElementsByTagName('body')->item(0);
        if ($body) {
            $bodyChildren = $body->childNodes;
            foreach ($bodyChildren as $child) {
                $bodyContent .= $doc->saveHTML($child);
            }
        }

        $this->html = $bodyContent;

        return $h2Array;
    }

}

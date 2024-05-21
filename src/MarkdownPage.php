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

            // 마크다운 인스턴스
            //self::$Instance->Parsedown();

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
        if(file_exists($file.".md")) {
            $this->filename = $file.".md";
            return file_get_contents($file.".md");
        }

        // 일치하는 폴더가 존재하는 경우,
        // index로 대체합니다.
        else
        if(file_exists($file.DIRECTORY_SEPARATOR."index.md")) {
            $this->filename = $file.DIRECTORY_SEPARATOR."index.md";
            return file_get_contents($file.DIRECTORY_SEPARATOR."index.md");
        }

        return false;
    }

    // 프론트메터 분리
    public function parser($txt="")
    {
        $frontMatter = \Webuni\FrontMatter\FrontMatterChain::create();
        $obj = $frontMatter->parse($txt);

        $this->data = $obj->getData();
        $this->content = $obj->getContent();

        return $this;
    }

    public function render()
    {
        $Parsedown = new \Parsedown();
        $html = $Parsedown->parse($this->content);


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
            $h2Array[] = $h2->textContent;
        }

        $this->bookmark = $h2Array;


        $bodyContent = '';
        $bodyChildren = $doc->getElementsByTagName('body')->item(0)->childNodes;
        foreach ($bodyChildren as $child) {
            $bodyContent .= $doc->saveHTML($child);
        }

        $this->html = $bodyContent;

        return $this->html;
    }

    public function view($path)
    {
        $view = view("jiny-markdown::pages",[
            //'slot' => $this->html,
            //'bookmark' => $this->bookmark,
            'filename' => $this->filename
        ]);

        if(isset($this->data['layout'])) {
            $layout = $path.$this->data['layout'];
            if(view()->exists($layout)) {
                return view($layout,[
                    'slot'=>$view,
                    'bookmark' => $this->bookmark
                ]);

            }
        }

        $layout = $path."markdown";
        if(view()->exists($layout)) {
            // 변수를 템플릿에 전달하고 컴파일된 결과를 반환합니다.
            return view($layout,[
                'slot'=>$view,
                'bookmark' => $this->bookmark
            ]);

        }

        return $this->html;
    }

}

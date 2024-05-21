<?php
namespace Jiny\Markdown\Http\Livewire;

use Livewire\Component;

class MarkdownEdit extends Component
{
    public $filename;
    public $body;
    public $markdown;
    public $bookmark=[];
    public $mode = false;

    public $data;
    //public $content;

    use \Jiny\Widgets\Http\Trait\DesignMode;

    public function mount()
    {
        // 마크다운 파일 읽기
        $str = file_get_contents($this->filename);

        // 프론트메터 분리
        $frontMatter = \Webuni\FrontMatter\FrontMatterChain::create();
        $obj = $frontMatter->parse($str);
        $this->data = $obj->getData();
        $this->markdown = $obj->getContent();

        // 마크다운 변환
        $this->body = $this->parse($this->markdown);

        // 북마크 싱글턴 변수에 저장
        // 라이브와이어 북마크로 데이터 공유
        $mk = \Jiny\Markdown\Markdown::instance();
        $mk->bookmark = $this->bookmark;
    }

    public function render()
    {
        $viewFile = 'jiny-markdown::livewire.markdown';
        return view($viewFile);
    }


    public function edit()
    {
        // $this->markdown 의 데이터 수정모드
        $this->mode = "edit";
    }

    private function convertToYamal()
    {
        $str = "---\n";
        $str .= \Jiny\Config\Yaml\Yaml::dump($this->data);
        $str .= "---\n";
        return $str;
    }

    public function update()
    {
        // 데이터 ymal 타입변환
        $yaml = $this->convertToYamal();

        // 본문결합
        $body = $yaml.$this->markdown;

        //dd($body);

        //파일저장
        file_put_contents($this->filename, $body);


        // 수정된 마크다운 재변환
        $this->body = $this->parse($this->markdown);

        // 갱신 이벤트 발생
        $this->dispatch('bookmark-update', $this->bookmark);

        // 편집모드 종료
        $this->mode = false;
    }

    // 마크다운을 변환합니다.
    public function parse($txt)
    {
        $Parsedown = new \Parsedown();
        $html = $Parsedown->parse($txt);

        return $this->addBookMark($html);
    }

    private function addBookMark($html)
    {
        $doc = $this->htmlToObj($html);

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

        return $this->objToHtml($doc);
    }

    private function htmlToObj($html)
    {
        // HTML 콘텐츠를 DOMDocument 개체에 로드
        $doc = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);

        // Load HTML with the correct encoding
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        return $doc;
    }

    private function objToHtml($doc)
    {
        $bodyContent = '';
        $bodyChildren = $doc->getElementsByTagName('body')->item(0)->childNodes;
        foreach ($bodyChildren as $child) {
            $bodyContent .= $doc->saveHTML($child);
        }

        // mb_convert_encoding($modifiedHtml, 'UTF-8', 'HTML-ENTITIES');

        return $bodyContent;
    }

}

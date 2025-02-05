<?php
namespace Jiny\Markdown\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;

/**
 * 마크다운 페이지 라이브와이어 컴포넌트
 */
class SitePageMarkdown extends Component
{
    public $uri;
    public $filepath;

    public $mode;
    public $markdown;

    public $design;

    #[On('design-mode')]
    public function designMode($mode=null)
    {
        if($this->design) {
            $this->design = false;
            $this->mode = false;
        } else {
            $this->design = true;
            $this->mode = "edit";
        }
    }

    public function mount()
    {
        $this->uri = Request::path();
        $this->filepath = $this->getFilePath($this->uri);

    }

    /**
     * 파일 경로 조회
     */
    private function getFilePath($uri)
    {
        $prefix_www = "www";
        $filename = str_replace('/', DIRECTORY_SEPARATOR, $uri);
        $filename = ltrim($filename, DIRECTORY_SEPARATOR);

        $slot = www_slot();
        $slotKey = $prefix_www.DIRECTORY_SEPARATOR.$slot; // slot path
        $path = resource_path($slotKey);

        $filePath = $path.DIRECTORY_SEPARATOR.$filename;

        if(file_exists($filePath.".md")) {
            return $filePath.".md";

        } else if(is_dir($filePath)) {
            $filePath = $filePath.DIRECTORY_SEPARATOR."index";
            if(file_exists($filePath.".md")) {
                return $filePath.".md";
            }
        }

        return false;
    }

    // private function load($uri)
    // {
    //     $prefix_www = "www";
    //     $filename = str_replace('/', DIRECTORY_SEPARATOR, $uri);
    //     $filename = ltrim($filename, DIRECTORY_SEPARATOR);

    //     $slotKey = $prefix_www.DIRECTORY_SEPARATOR.$slot; // slot path
    //     $path = resource_path($slotKey);

    //     $filePath = $path.DIRECTORY_SEPARATOR.$filename;

    //     $body = null;
    //     $mk = \Jiny\Markdown\MarkdownPage::instance();
    //     if(file_exists($filePath.".md")) {
    //         $body = $mk->load($filePath.".md");
    //         $this->markdown = file_get_contents($filePath.".md");
    //     } else if(is_dir($filePath)) {
    //         $filePath = $filePath.DIRECTORY_SEPARATOR."index";
    //         if(file_exists($filePath.".md")) {
    //             $body = $mk->load($filePath.".md");
    //             $this->markdown = file_get_contents($filePath.".md");
    //         }
    //     }

    //     dd($this->markdown);
    // }

    public function render()
    {
        if($this->filepath) {
            // 원문 파일 읽기기
            $body = file_get_contents($this->filepath);
            $this->markdown = $body;

            // 프론트메터 파싱
            $mk = \Jiny\Markdown\MarkdownPage::instance();
            $mk->parser($body);

            // 마크다운 변환
            $html = (new \Parsedown())->parse($mk->content);

            // 북마크
            $bookmark = $mk->parseBookmark($html);
            $html = $mk->html; // 북마크 포함 html 반환



            if($this->mode == "edit") {
                // 편집 모드
                $viewFile = 'jiny-markdown::site.markdown.edit';
            } else {
                // 읽기 모드
                $viewFile = 'jiny-markdown::site.markdown.markdown';
            }

            return view($viewFile,[
                'html'=>$html,
                'bookmark'=>$bookmark,
                    'data'=>$mk->data
                ]);

        } else {
            // 파일이 존재하지 않는 경우
            return view('jiny-markdown::site.markdown.404');
        }
    }

    public function apply()
    {
        //$this->mode = null;
        file_put_contents($this->filepath, $this->markdown);
    }

    public function save()
    {
        $this->mode = null;
        $this->design = false;
        file_put_contents($this->filepath, $this->markdown);
    }

    public $deleteConfirmPopup = false;
    public function delete()
    {
        if(file_exists($this->filepath)) {
            unlink($this->filepath);
            $this->filepath = null;

            $this->dispatch('page-realod');
        }
    }

    public function confirmDelete()
    {
        $this->deleteConfirmPopup = true;
    }


}

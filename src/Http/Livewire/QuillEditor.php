<?php
namespace Jiny\Markdown\Http\Livewire;

use Livewire\Component;

class QuillEditor extends Component
{
    public $path;
    public $filename;
    public $body;

    public $mode=false;

    public function mount()
    {
        $path = resource_path('content');
        if(!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        if($this->filename) {
            $this->filename = ltrim($this->filename, '/');
        }

        if($this->filename) {
            $filePath = $path.DIRECTORY_SEPARATOR.$this->filename;
            if(file_exists($filePath)) {
                $this->body = file_get_contents($filePath);
            }
        }
    }

    public function render()
    {
        return view('jiny-markdown::livewire.quill');
    }

    public function edit()
    {
        $this->mode = "edit";
    }

    public function store()
    {
        $this->parseImage();

        $path = resource_path('content');
        if($this->filename) {
            $filePath = $path.DIRECTORY_SEPARATOR.$this->filename;
            file_put_contents($filePath, $this->body);
        }

        $this->mode = false;
    }

    private function parseImage()
    {
        // 정규식을 사용하여 이미지 파일명 추출
        preg_match_all('/<img.*?src=["\']([^"\']+)/', $this->body, $matches);

        // 추출된 이미지 파일명 목록
        $imageFileNames = $matches[1];

        // 파일명 출력
        foreach ($imageFileNames as $img) {
            dump($img);
        }

        //dd($imageFileNames);
    }
}

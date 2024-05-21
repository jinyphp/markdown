<?php
namespace Jiny\Markdown\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Bookmark extends Component
{
    public $bookmark=[];

    public function mount()
    {
        // 공용 인스턴스에서 bookmark 정보 초기화
        $mk = \Jiny\Markdown\Markdown::instance();
        $this->bookmark = $mk->bookmark;
    }

    public function render()
    {
        return view('jiny-markdown::livewire.bookmark');
    }

    #[On('bookmark-update')]
    public function reflash($book)
    {
        // 이벤트 갱신
        // 마크다운 수정시, 변경된 북마트 반영
        $this->bookmark = $book;
    }

}

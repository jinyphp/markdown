<?php
namespace Jiny\Markdown\Http\Livewire;

use Livewire\Component;

class QuillEditor extends Component
{
    public $quillContent;

    public function render()
    {

        return view('jiny-markdown::livewire.quill');
    }

    public function upload()
    {
        // Quill 에디터에서 전송된 데이터 받기
        $content = json_decode($this->quillContent, true);

        // 이제 $content를 데이터베이스에 저장하거나 필요한 작업을 수행할 수 있습니다.
        // 예를 들어, 데이터베이스에 저장한다면:
        // $db->query("INSERT INTO posts (content) VALUES ('$content')");

        // 업로드가 성공적으로 처리되었음을 알립니다.
        session()->flash('message', '업로드가 성공적으로 완료되었습니다.');
    }
}

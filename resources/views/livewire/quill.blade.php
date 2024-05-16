<!-- quill.blade.php -->

<div>
    <!-- Quill 에디터 컨테이너 -->
    <div wire:ignore id="editor-container"></div>

    <!-- 글 업로드 폼 -->
    <form wire:submit.prevent="upload">
        @csrf
        <input type="hidden" name="quill_content" id="quill-content">
        <button type="submit">글 업로드</button>
    </form>


</div>

@push('scripts')
    <!-- Include Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script>
        document.addEventListener('livewire:load', function () {
            // Quill 에디터 초기화
            var quill = new Quill('#editor-container', {
                theme: 'snow'
            });

            // 글 업로드 버튼 클릭 시 Quill 내용을 숨겨진 필드에 저장
            document.querySelector('form').addEventListener('submit', function() {
                var quillContent = document.querySelector('input[name=quill_content]');
                quillContent.value = JSON.stringify(quill.getContents());
                @this.call('upload');
            });
        });
    </script>
@endpush

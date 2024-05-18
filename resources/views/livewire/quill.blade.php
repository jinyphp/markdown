@once
    @push('css')
    <!-- Include stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    @endpush
@endonce

<article>
    @if($mode)
    <div wire:ignore class="mb-2 bg-white">
        <div x-data
            x-ref="editor"
            x-init="


                const quill = new Quill($refs.editor,{
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'header': 1 }, { 'header': 2 }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],

                            [{ 'color': [] }, { 'background': [] }],
                            ['image', 'link'],

                        ]
                    },
                    theme: 'snow'
                });

                quill.on('text-change',()=>{
                    $wire.set('body', quill.root.innerHTML)
                });

// 이미지 업로드 핸들러 설정
quill.getModule('toolbar').addHandler('image', function() {
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = async function() {
        var file = input.files[0];
        var formData = new FormData();
        formData.append('image', file);

        // CSRF 토큰 추가
        formData.append('_token', '{{ csrf_token() }}');

        try {
            // 이미지를 서버에 업로드
            var response = await fetch('/upload-image', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                // 서버에서 반환된 이미지 URL을 Quill 에디터에 삽입
                var imageUrl = await response.json();
                var range = quill.getSelection();
                quill.insertEmbed(range.index, 'image', imageUrl.url);
            } else {
                console.error('이미지 업로드 실패');
            }
        } catch (error) {
            console.error('이미지 업로드 오류:', error);
        }
    };
});

                // 초기 높이 설정
                $refs.editor.style.minHeight = '20em'; // 또는 다른 높이 단위로 설정
            ">
                {!! $body !!}
        </div>

    </div>
    <x-flex-between>
        <div></div>
        <div>
            <button class="btn btn-primary" wire:click="store">저장</button>
        </div>
    </x-flex-between>

    @else

    {{-- <div style="position: relative;">
        <div style="position: absolute;top:4px;right:4px;z-index:50;">
            <span class="text-primary" wire:click="edit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2"/>
                </svg>
            </span>
        </div>
        {!! $body !!}
    </div> --}}
    {!! $body !!}
    <x-flex-between>
        <div></div>
        <div>
            <button class="btn btn-info" wire:click="edit">수정</button>
        </div>
    </x-flex-between>
    @endif
</article>

@once
    @push('script')
    <!-- Include the Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    @endpush
@endonce

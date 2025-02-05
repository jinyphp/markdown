<div>
    <div class="row">
        <div class="col-10">
            <article class="markdown" id="markdown-content">
                {!! $html !!}
            </article>
        </div>
        <div class="col-2">
            @if(isset($bookmark) && count($bookmark) > 0)
                <h5 class="mb-3 font-weight-bold">북마크</h5>
                <hr class="my-2">
                <ul class="list-unstyled">
                    @foreach ($bookmark as $item)
                    <li class="">
                        <a href="#{{$item['id']}}"
                            class="text-decoration-none text-body d-block rounded hover-bg-light">
                            <i class="bi bi-bookmark-fill text-primary mr-2"></i>
                            {{$item['title']}}
                        </a>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

{{-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const markdownContent = document.getElementById('markdown-content');

    markdownContent.addEventListener('click', function() {
        this.contentEditable = true;
        this.focus();
    });

    markdownContent.addEventListener('blur', function() {
        this.contentEditable = false;
        // 여기에 변경된 내용을 저장하는 로직을 추가할 수 있습니다.
        console.log('편집된 내용:', this.innerHTML);
    });
});
</script> --}}

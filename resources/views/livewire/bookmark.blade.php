<div>

    <ul>
        @foreach($bookmark as $i =>$item)
        <li class="mb-2">
            <a href="#bookmark{{sprintf('%02d', $i)}}">{{$item}}</a>
        </li>
        @endforeach
    </ul>

</div>

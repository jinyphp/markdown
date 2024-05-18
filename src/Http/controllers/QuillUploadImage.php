<?php
namespace Jiny\Markdown\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuillUploadImage extends Controller
{


    // 이미지 업로드를 처리하는 컨트롤러 메서드
    public function uploadImage(Request $request) {

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 이미지 파일 저장
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->extension();
        $image->move(public_path('images/quill'), $imageName);

        // 이미지 URL 반환
        $imageUrl = asset('images/quill/' . $imageName);
        return response()->json(['url' => $imageUrl]);


    }

}

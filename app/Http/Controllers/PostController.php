<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;


class PostController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function show($id)
    {
        return Post::find($id);
    }

    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'body' => 'required|string',
		]);

		if ($validator->fails()) {
			return response()->json($validator->errors(), 400);
		}

		return Post::create($request->all());
	}

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'body' => 'required|string',
		]);

		if ($validator->fails()) {
			return response()->json($validator->errors(), 400);
		}

		$post = Post::find($id);
		if (!$post) {
			return response()->json(['error' => 'Post not found'], 404);
		}

		$post->update($request->all());
		return $post;
    }

    public function delete($id)
    {
        return Post::destroy($id);
    }
}

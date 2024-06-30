<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use Carbon\Carbon;

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
          $data = $request->all();

        // Validate each item in the array
        $validator = Validator::make(['posts' => $data], [
            'posts.*.title' => 'required|string|max:255',
            'posts.*.body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
		
		$now = Carbon::now();
        foreach ($data as &$post) {
            $post['created_at'] = $now;           
        }

        // Perform bulk insert
        Post::insert($data);

        return response()->json(['message' => 'Posts created successfully'], 201);
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

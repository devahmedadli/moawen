<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostCollection;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    protected array $allowedRelationships = [
        'user',
        'comments',
        'likes',
    ];

    protected array $countableRelationships = [
        'comments',
        'likes',
    ];

    protected string $defaultSortField = 'created_at';
    protected string $defaultSortOrder = 'desc';


   
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Post::query();
            $query = $this->buildQuery($request, $query);
            $posts = $query->paginate($request->get('per_page', 25));

            return $this->success(
                PostResource::collection($posts),
                'تم جلب البوستات بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {

        try {
            $query = Post::query();
            $query = $this->buildQuery($request, $query);
            $post = $query->findOrFail($id);
            return $this->success(
                new PostResource($post),
                'تم جلب البوست بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();
            return $this->success(
                null,
                'تم حذف البوست بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

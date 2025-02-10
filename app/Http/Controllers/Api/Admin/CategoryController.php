<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\Admin\StoreCategoryRequest;
use App\Http\Requests\Api\Admin\UpdateCategoryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CategoryController extends Controller
{

    protected array $allowedRelationships = [
        'services',
        'specializations',
        'freelancers'
    ];

    protected array $countableRelationships = [
        'services',
        'specializations',
        'freelancers'
    ];

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Category::query();
            $query = $this->buildQuery($request, $query);

            $categories = $query->paginate($request->get('per_page', 25));

            return $this->success(
                CategoryResource::collection($categories),
                'تم جلب الاقسام بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());
            return $this->success(
                new CategoryResource($category),
                'تم اضافة القسم بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, string $id)
    {
        try {
            $query = Category::query();
            $query = $this->buildQuery($request, $query);
            $category = $query->findOrFail($id);
            return $this->success(
                new CategoryResource($category),
                'تم جلب القسم بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Update the specified resource in storage.
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->update($request->validated());
            return $this->success(
                new CategoryResource($category),
                'تم تعديل القسم بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return $this->success(null, 'تم حذف القسم بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

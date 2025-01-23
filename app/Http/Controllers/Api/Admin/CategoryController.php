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
     * Store a newly created category
     * 
     * Create a new category in the system.
     *
     * @bodyParam name string required The name of the category. Example: البرمجة والتطوير
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return $this->success(
            new CategoryResource($category),
            'تم اضافة القسم بنجاح',
        );
    }

    /**
     * Display the specified category
     * 
     * Get the details of a specific category.
     *
     * @urlParam id required The ID of the category. Example: 1
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
     * Update the specified category
     * 
     * Update the details of a specific category.
     *
     * @bodyParam name string required The name of the category. Example: البرمجة والتطوير
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return apiResponse(new CategoryResource($category), 'تم تعديل القسم بنجاح', 200);
    }

    /**
     * Remove the specified category
     * 
     * Delete a specific category from the system.
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

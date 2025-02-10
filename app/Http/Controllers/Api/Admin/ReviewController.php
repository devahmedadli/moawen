<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Order;
use App\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReviewResource;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected array $allowedRelationships = [
        'user',
        'service',
        'order',
    ];


    protected string $defaultSortField = 'created_at';
    protected string $defaultSortOrder = 'desc';


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Review::query();
            $query = $this->buildQuery($request, $query);
            $reviews = $query->paginate($request->get('per_page', 25));

            return $this->success(
                ReviewResource::collection($reviews),
                'تم جلب التقييمات بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreReviewRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreReviewRequest $request)
    {
        try {
            $order = Order::findOrFail($request->order_id);

            $review = Review::create([
                'user_id' => Auth::id(),
                'service_id' => $order->service_id,
                'order_id' => $order->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            // Update service average rating
            $service = $order->service;
            $service->average_rating = $service->reviews()->avg('rating');
            $service->save();
            return $this->success(
                new ReviewResource($review),
                'تم إضافة التقييم بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $query = Review::query();
            $query = $this->buildQuery($request, $query);
            $review = $query->findOrFail($id);

            return $this->success(
                new ReviewResource($review),
                'تم جلب التقييم بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateReviewRequest $request
     * @param Review $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $review->update($request->validated());

        // Update service average rating
        $service = $review->service;
        $service->average_rating = $service->reviews()->avg('rating');
        $service->save();

        return $this->success(
            new ReviewResource($review),
            'تم تحديث التقييم بنجاح'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $query = Review::query();
            $query = $this->buildQuery($request, $query);
            $review = $query->findOrFail($id);

            $service = $review->service;
            $review->delete();

            if ($service) {
                $service->average_rating = $service->reviews()->avg('rating');
                $service->save();
            }

            return $this->success(
                null,
                'تم حذف التقييم بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

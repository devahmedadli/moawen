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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
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
    public function destroy(Review $review)
    {
        $service = $review->service;
        $review->delete();

        // Update service average rating
        $service->average_rating = $service->reviews()->avg('rating');
        $service->save();

        return $this->success(
            null,
            'تم حذف التقييم بنجاح'
        );
    }
}

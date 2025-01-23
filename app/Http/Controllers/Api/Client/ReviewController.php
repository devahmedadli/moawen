<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Http\Requests\Api\Client\StoreReviewRequest;
use App\Http\Requests\Api\Client\UpdateReviewRequest;

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
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        try {
            $order = Order::findOrFail($request->order_id);
            if ($order->buyer_id !== auth()->id()) {
                return $this->error('لا يمكنك إضافة تقييم لهذا الطلب.');
            }
            if ($order->rating_given) {
                return $this->error('تم اضافة تقييم بالفعل.');
            }

            $review = Review::create($request->validated());
            $order->update(['rating_given' => true]);
            return $this->success(new ReviewResource($review), 'تم إنشاء التقييم بنجاح.');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, string $id)
    {
        try {
            $review = Review::findOrFail($id);
            if ($review->user_id !== auth()->id()) {
                return $this->error('لا يمكنك تعديل هذا التقييم.');
            }
            $review->update($request->validated());
            return $this->success(new ReviewResource($review), 'تم تعديل التقييم بنجاح.');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $review = Review::findOrFail($id);
            if ($review->user_id !== auth()->id()) {
                return $this->error('لا يمكنك حذف هذا التقييم.');
            }
            $review->delete();
            return $this->success(null, 'تم حذف التقييم بنجاح.');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

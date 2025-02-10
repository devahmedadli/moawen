<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

/**
 * @group Admin Order Management
 *
 * APIs for managing orders
 */
class OrderController extends Controller
{
    protected array $allowedRelationships = [
        'service',
        'service.category',
        'review',
        'client',
        'freelancer',
        'payment',
    ];

    protected string $defaultSortField = 'created_at';
    protected string $defaultSortOrder = 'desc';
    
    public function index(Request $request)
    {
        try {
            $query = Order::query();

            $query = $this->buildQuery($request, $query);

            $orders = $query->paginate($request->get('per_page', 25));

            return $this->success(
                OrderResource::collection($orders),
                'تم جلب الطلبات بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $order = Order::create($request->validated());

        return $this->success(
            new OrderResource($order),
                'تم إنشاء الطلب بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified order
     */
    public function show(Request $request, string $id)
    {
        try {
            $query = Order::query();
            $query = $this->buildQuery($request, $query);
            $order = $query->findOrFail($id);
            return $this->success(
                new OrderResource($order),
                'تم جلب الطلب بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified order
     */
    public function update(UpdateOrderRequest $request, string $id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->update($request->validated());

        return $this->success(
            new OrderResource($order),
                'تم تحديث الطلب بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Remove the specified order
     */
    public function destroy(string $id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return $this->success(
                null,
                'تم حذف الطلب بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

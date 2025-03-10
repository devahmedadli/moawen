<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    protected array $allowedRelationships = [
        'service',
        'service.category',
        'review',
        'client',
        'freelancer',
    ];


    protected string $defaultSortField = 'created_at';
    protected string $defaultSortOrder = 'desc';


    public function index(Request $request)
    {
        try {

            $query = Order::query();

            $query = $query->where('seller_id', auth()->user()->id);
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

    public function show(Request $request, $id)
    {
        try {
            $order = Order::query();
            $order = $order->where('seller_id', auth()->user()->id)->where('id', $id);
            $order = $this->buildQuery($request, $order);
            $order = $order->firstOrFail();
            return $this->success(new OrderResource($order), 'تم جلب الطلب بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

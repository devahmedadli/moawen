<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class PurchaseController extends Controller
{
    protected array $allowedRelationships = [
        'service',
        'service.category',
        'review',
    ];


    protected string $defaultSortField = 'created_at';
    protected string $defaultSortOrder = 'desc';


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $query = Order::query();

            $query = $query->where('buyer_id', auth()->user()->id);
            $query = $this->buildQuery($request, $query);

            $orders = $query->paginate($request->get('per_page', 25));

            return $this->success(
                OrderResource::collection($orders),
                'تم جلب المشتريات بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $order = Order::query();
            $order = $order->where('buyer_id', auth()->user()->id)->where('id', $id);
            $order = $this->buildQuery($request, $order);
            $order = $order->firstOrFail();
            return $this->success(new OrderResource($order), 'تم جلب المشترية بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

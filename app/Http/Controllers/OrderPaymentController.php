<?php

namespace App\Http\Controllers;

use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\Http\Resources\OrderPaymentResource;

class OrderPaymentController extends Controller
{
    protected array $allowedRelationships = [
        'order',
        'order.service',
        'order.buyer',
        'order.seller',
    ];

    protected string $defaultSortField = 'created_at';
    protected string $defaultSortOrder = 'desc';

    public function index(Request $request)
    {
        try {
            $orderPayments = OrderPayment::filter($request->all())
                ->with($this->allowedRelationships)
                ->paginate($request->input('per_page', 10));

            return $this->success(OrderPaymentResource::collection($orderPayments));
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Payments\TapPaymentService;

class PaymentController extends Controller
{
    protected $tapService;

    public function __construct(TapPaymentService $tapService)
    {
        $this->tapService = $tapService;
    }


    public function createCharge (Request $request)
    {
        $order = Order::find($request->id);
        if ($order->buyer_id !== auth()->user()->id)
        {
            return $this->error('ليس لديك صلاحية الوصول لهذا الطلب');
        }
        if($order->status !== 'pending')
        {
            return $this->error('لقد تم دفع هذا الطلب بالتأكيد');
        }

        try {
            $response = $this->tapService->createCharge($order, $request->token);
            
            if (isset($response['transaction']['url'])) {
                // Redirect to payment page
                return $this->success([
                    'payment_url' => $response['transaction']['url']
                ]);
            }
    
            // Handle error response
            $error = $response['errors'][0]['description'] ?? 'Payment failed';
            return $this->error($error);
    
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

    }
}

<?php

namespace App\Http\Controllers\Api;

use Pusher\Webhook;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\WebhookLog;
use App\Services\Payments\TapPaymentService;
use Exception;

class WebhookController extends Controller
{

    protected $tapService;

    public function __construct(TapPaymentService $tapService)
    {
        $this->tapService = $tapService;
    }
    /**
     * A method to handle the Tap Payment webhook events
     * @param $request
     */
    public function handleWebhook(Request $request)
    {
        // Verify HMAC signature
        $signature = $request->header('X-Tap-Signature');
        $payload = $request->getContent();

        if (!$this->verifySignature($payload, $signature)) {
            abort(400, 'Invalid signature');
        }

        $chargeId = $request->input('id');
        $charge = $this->tapService->retrieveCharge($chargeId);

        // Handle different charge statuses
        $order = Order::findOrFail(str_replace('ord_', '', $charge['reference']['order']));

        switch ($charge['status']) {
            case 'CAPTURED':
                try {
                    DB::beginTransaction();
                    $order->update(['status' => 'completed']);
                    WebhookLog::create(
                        [
                            'event_id'      => $chargeId,
                            'order'         => $order->id,
                            'processed_at'  => now(),
                        ]
                    );
                    DB::commit();
                    break;
                } catch (\Exception $e) {
                    DB::rollBack();
                    break;
                }
            case 'DECLINED':
            case 'ABANDONED':
            case 'RESTRICTED':
            case 'VOID':
            case 'TIMEDOUT':
            case 'FAILED':
                try {
                    DB::beginTransaction();
                    $order->update(['status' => 'failed']);
                    WebhookLog::create(
                        [
                            'event_id'      => $chargeId,
                            'order'         => $order->id,
                            'processed_at'  => now(),
                        ]
                    );
                    DB::commit();
                    break;
                } catch (\Exception $e) {
                    DB::rollBack();
                    break;
                }
            case 'CANCELLED':
                try {
                    DB::beginTransaction();
                    $order->update(['status' => 'cancelled']);
                    WebhookLog::create(
                        [
                            'event_id'      => $chargeId,
                            'order'         => $order->id,
                            'processed_at'  => now(),
                        ]
                    );
                    DB::commit();
                    break;
                } catch (\Exception $e) {
                    DB::rollBack();
                    break;
                }
            case 'REFUNDED':
                try {
                    DB::beginTransaction();
                    $order->update(['status' => 'refunded']);
                    WebhookLog::create(
                        [
                            'event_id'      => $chargeId,
                            'order'         => $order->id,
                            'processed_at'  => now(),
                        ]
                    );
                    DB::commit();
                    break;
                } catch (\Exception $e) {
                    DB::rollBack();
                    break;
                }
        }

        return response()->json(['success' => true]);
    }

    private function verifySignature($payload, $signature)
    {
        $computedSignature = hash_hmac('sha256', $payload, config('services.tap.webhook_secret'));
        return hash_equals($computedSignature, $signature);
    }
}

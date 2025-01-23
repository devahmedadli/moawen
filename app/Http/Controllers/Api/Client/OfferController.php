<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    // accept offer and place an order with the offer price
    public function acceptOffer($id)
    {
        try {
            $offer = Offer::findOrFail($id);

            if ($offer->client_id !== auth()->user()->id) {
                return $this->unauthorized();
            }

            if ($offer->status == 'accepted') {
                return $this->error('العرض مقبول من قبل');
            }

            $offer->update(['status' => 'accepted']);

            if ($offer->status == 'accepted') {
                // create an order with the offer price
                $order = Order::createor([
                    'buyer_id'      => $offer->client_id,
                    'seller_id'     => $offer->freelancer_id,
                    'service_id'    => $offer->service_id,
                    'price'         => $offer->price,
                    'deadline'      => $offer->deadline,
                    'status'        => 'pending',
                ]);
            }

            return $this->success($order, 'تم انشاء الطلب بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function rejectOffer($id)
    {
        try {
            $offer = Offer::findOrFail($id);
            if ($offer->client_id !== auth()->user()->id) {
                return $this->unauthorized();
            }

            if ($offer->status == 'rejected') {
                return $this->error('العرض مرفوض من قبل');
            }

            $offer->update([
                'status' => 'rejected',
            ]);

            return $this->success($offer, 'تم رفض العرض بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

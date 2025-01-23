<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Models\User;
use App\Models\Offer;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Http\Requests\Api\Freelancer\StoreOfferRequest;

class OfferController extends Controller
{

    protected array $allowedRelationships = [
        'service',
        'client',
        'freelancer'
    ];


    protected string $defaultSortField = 'created_at';
    protected string $defaultSortOrder = 'desc';


    public function index(Request $request)
    {
        try {
            $freelancer = User::findOrFail(auth()->user()->id);
            $offers = Offer::query();
            $offers = $offers->where('freelancer_id', $freelancer->id);
            $offers = $this->buildQuery($request, $offers);
            $offers = $offers->paginate($request->get('per_page', 25));
            return $this->success(OfferResource::collection($offers), 'تم جلب العروض بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(StoreOfferRequest $request)
    {
        try {
            $offer = Offer::create($request->validated());
            return $this->success($offer, 'تم إرسال العرض بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }



    public function destroy($id)
    {
        try {
            $offer = Offer::findOrFail($id);
            if ($offer->freelancer_id !== auth()->user()->id) {
                return $this->unauthorized();
            }
            $offer->delete();
            return $this->success($offer, 'تم حذف العرض بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

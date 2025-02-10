<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WithdrawalResource;
use App\Http\Requests\Api\Freelancer\StoreWithdrawalRequest;

class WithdrawalController extends Controller
{

    protected string $defaultSortField = 'created_at';
    protected string $defaultSortOrder = 'desc';


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query          = Withdrawal::where('user_id', auth()->id());
            $withdrawals    = $this->buildQuery($request, $query);
            $withdrawals    = $withdrawals->paginate($request->get('per_page', 25));
            return $this->success(WithdrawalResource::collection($withdrawals), 'تم جلب طلبات السحب بنجاح');
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreWithdrawalRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreWithdrawalRequest $request)
    {
        try {
            $withdrawal = Withdrawal::create($request->validated());
            return $this->success($withdrawal, 'تم إرسال طلب السحب بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Cancel the request withdrawal
     * @param Withdrawal $withdrawal
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        try {
            $withdrawal = Withdrawal::findOrFail($id);
            if ($withdrawal->status !== 'pending') {
                return $this->error('لا يمكن إلغاء طلب السحب الذي لم يعد قيد المراجعة');
            }
            if ($withdrawal->user_id !== auth()->id()) {
                return $this->forbidden();
            }
            $withdrawal->update(['status' => 'canceled']);
            return $this->success($withdrawal, 'تم إلغاء طلب السحب بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

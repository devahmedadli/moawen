<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Resources\WithdrawalResource;
use App\Http\Requests\Api\Admin\UpdateWithdrawalStatusRequest;
use App\Http\Controllers\Controller;

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
            $query = Withdrawal::query();

            $query = $this->buildQuery($request, $query);

            $withdrawals = $query->paginate($request->get('per_page', 25));

            return $this->success(
                WithdrawalResource::collection($withdrawals),
                'تم جلب طلبات السحب بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Update the specified resource in storage.
     * @param UpdateWithdrawalStatusRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(UpdateWithdrawalStatusRequest $request, $id)
    {
        try {
            $withdrawal = Withdrawal::findOrFail($id);
            $withdrawal->update($request->validated());
            return $this->success($withdrawal, 'تم تحديث حالة السحب بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

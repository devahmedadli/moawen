<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
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
            $query = Transaction::query();

            $query = $this->buildQuery($request, $query);

            $transactions = $query->paginate($request->get('per_page', 25));

            return $this->success(
                TransactionResource::collection($transactions),
                'تم جلب المعاملات بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            return $this->success(new TransactionResource($transaction), 'تم جلب المعاملة بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

}

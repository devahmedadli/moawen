<?php

namespace App\Http\Controllers;

use App\Models\BankCard;
use App\Http\Requests\StoreBankCardRequest;
use App\Http\Requests\UpdateBankCardRequest;

class BankCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankCardRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BankCard $bankCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankCard $bankCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBankCardRequest $request, BankCard $bankCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankCard $bankCard)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TransferRequest;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function transfer(TransferRequest $request)
    {
        try {
            $transaction = $this->transactionService->transfer(
                $request->user()->account->id,
                $request->to_account_id,
                $request->amount,
                $request->currency
            );
            return response()->json(['message' => 'Transfer successful', 'transaction' => $transaction]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}

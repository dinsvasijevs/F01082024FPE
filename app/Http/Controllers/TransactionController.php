<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function transfer(TransferRequest $request): JsonResponse
    {
        $result = $this->transactionService->transfer(
            $request->user()->account->id,
            $request->to_account_id,
            $request->amount,
            $request->currency
        );

        return response()->json(['message' => 'Transfer successful', 'transaction' => $result]);
    }
}

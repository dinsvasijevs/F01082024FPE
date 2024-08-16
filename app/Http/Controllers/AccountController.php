<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function manageAccount(Request $request): RedirectResponse
    {
        // Get the authenticated user's account information
        $account = User::where('id', auth()->user()->id)->first();

        // Handle user request to update their account information
        if ($request->hasFile('document')) {
            // Upload document (e.g., ID card, passport) to cloud storage
            $file = $request->document;
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($fileName, file_get_contents($file));
        }

        // Update the account information in the database
        if ($account) {
            $account->update($request->all());
        } else {
            // Create a new account for the user if it doesn't exist
            Account::create([
                'user_id' => auth()->user()->id,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
            ]);
        }

        // Redirect the user to their account dashboard
        return redirect()->route('account.dashboard');
    }
    public function transactionHistory(): View|Factory|Application
    {
        // Get the authenticated user's account information
        $account = User::where('id', auth()->user()->id)->first();

        // Get the transactions for the current month
        $transactions = Transaction::whereMonth('date', now()->month)
            ->where('user_id', auth()->user()->id)
            ->get();

        // Return a view with the transaction history data
        return view('account.transaction-history', ['transactions' => $transactions]);
    }
    public function dashboard(): View|Factory|Application
    {
        // Get the authenticated user's account information
        $account = User::where('id', auth()->user()->id)->first();

        // Get the transactions for the current month
        $transactions = Transaction::whereMonth('date', now()->month)
            ->where('user_id', auth()->user()->id)
            ->get();

        // Return a view with the account dashboard data
        return view('account.dashboard', ['account' => $account, 'transactions' => $transactions]);
    }
    public function update(Request $request): JsonResponse
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()]);
        }

        // Get the authenticated user's ID
        $userId = auth()->user()->id;

        // Update the account information in the database
        User::where('id', $userId)->update($request->all());

        // Upload a document to S3 if provided
        if ($request->hasFile('document')) {
            $fileUploader = new FileUploadController();
            $result = $fileUploader->upload($request);
            if ($result) {
                // Update the user's document in the database (optional)
                User::where('id', $userId)->update(['document' => $result['Key']]);
            }
        }

        return response()->json(['message' => 'Account updated successfully!']);
    }
    public function getTransactions(Request $request): JsonResponse
    {
        // Get the transactions for the current month
        $transactions = Transaction::whereMonth('date', now()->month)
            ->where('user_id', auth()->user()->id)
            ->get();

        return response()->json(['transactions' => $transactions]);
    }

    public function updateDocument(Request $request): JsonResponse
    {
        // Validate the request data
        Validator::make($request->all(), [
            'document' => 'required|file',
        ])->validate();

        // Get the authenticated user's ID
        $userId = auth()->user()->id;

        // Upload a document to S3
        $fileUploader = new FileUploadController();
        $result = $fileUploader->upload($request);

        if ($result) {
            // Update the user's document in the database (optional)
            User::where('id', $userId)->update(['document' => $result['Key']]);
        }

        return response()->json(['message' => 'Document updated successfully!']);
    }
}

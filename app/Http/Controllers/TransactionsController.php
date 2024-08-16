<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionsController extends Controller
{
    public function index(): View|Factory|Application
    {
        $transactions = auth()->user()->transactions()->latest()->paginate(20);
        return view('transactions.index', compact('transactions'));
    }
}

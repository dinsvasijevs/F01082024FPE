<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CryptocurrencyController extends Controller
{
    public function index(): View|Factory|Application
    {
        // In a real application, you'd use a proper API to fetch cryptocurrency data
        $cryptocurrencies = [
            ['name' => 'Bitcoin', 'symbol' => 'BTC', 'price' => 50000],
            ['name' => 'Ethereum', 'symbol' => 'ETH', 'price' => 3000],
            ['name' => 'Cardano', 'symbol' => 'ADA', 'price' => 2],
        ];

        return view('cryptocurrencies.index', compact('cryptocurrencies'));
    }
}

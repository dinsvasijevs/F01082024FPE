<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function index(): View|Factory|Application
    {
        // In a real application, you'd fetch actual investment data
        $investments = [
            ['name' => 'Stock Fund A', 'value' => 1000, 'return' => 5.2],
            ['name' => 'Bond Fund B', 'value' => 2000, 'return' => 3.1],
            ['name' => 'Real Estate Fund C', 'value' => 1500, 'return' => 4.5],
        ];

        return view('investments.index', compact('investments'));
    }
}

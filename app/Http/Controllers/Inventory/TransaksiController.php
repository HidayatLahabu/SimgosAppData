<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        // Return Inertia view with paginated data
        return inertia("Inventory/Transaksi/Index");
    }
}
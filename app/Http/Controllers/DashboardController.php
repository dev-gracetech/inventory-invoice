<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Key Metrics
        $totalInvoices = Invoice::count();
        $totalRevenue = Invoice::sum('total_amount');
        $pendingInvoices = Invoice::where('status', 'pending')->count();
        $totalProducts = Product::count();
        $totalPOs = PurchaseOrder::count();
        $pendingPOs = PurchaseOrder::where('status', 'sent')->count();
        $totalPOAmount = PurchaseOrder::sum('total_amount');

        // Recent Activity
        $recentInvoices = Invoice::latest()->take(5)->get();
        $recentQuotations = Quotation::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalInvoices',
            'totalRevenue',
            'pendingInvoices',
            'totalProducts',
            'recentInvoices',
            'recentQuotations',
            'totalPOs',
            'pendingPOs',
            'totalPOAmount'
        ));
    }
}
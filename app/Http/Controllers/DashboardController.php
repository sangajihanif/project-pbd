<?php

namespace App\Http\Controllers;


use App\Models\VBarang;
use App\Models\VMarginPenjualan;
use App\Models\VRole;
use App\Models\VUser;
use App\Models\VVendor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $totalBarang = VBarang::where('status_text', 'Aktif')->count();
        $totalVendor = VVendor::where('status_text', 'Aktif')->count();
        $totalUser = VUser::count();
        $totalRole = VRole::count();
        $totalMarginPenjualan = VMarginPenjualan::count();

        $latestBarang = VBarang::orderBy('idbarang', 'desc')->take(10)->get();
        $latestVendor = VVendor::orderBy('idvendor', 'desc')->take(10)->get();
        $latestUser = VUser::orderBy('iduser', 'desc')->take(10)->get();
        $latestMarginPenjualan = VMarginPenjualan::orderBy('idmargin_penjualan', 'desc')->take(10)->get();

        return view('dashboard', compact('totalBarang', 'totalVendor', 'totalUser', 'totalRole', 'totalMarginPenjualan', 'latestBarang', 'latestVendor', 'latestUser', 'latestMarginPenjualan'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DataPJU;
use App\Models\DataPanel;
use App\Models\RiwayatPanel;
use App\Models\RiwayatPJU;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{

    public function getDashboardData()
    {
        $totalPJU = DataPJU::count();
        $totalPanel = DataPanel::count();
        $totalRiwayatPanel = RiwayatPanel::count();
        $totalRiwayatPJU = RiwayatPJU::count();
        return response()->json([
            'total_pju' => $totalPJU,
            'total_panel' => $totalPanel,
            'total_riwayat_panel' => $totalRiwayatPanel,
            'total_riwayat_pju' => $totalRiwayatPJU,
        ]);
    }
}
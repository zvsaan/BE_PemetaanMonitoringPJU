<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataPJU;
use App\Models\DataPanel;
use App\Models\RiwayatPanel;
use App\Models\RiwayatPJU;
use App\Models\DetailPengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{

    public function getDashboardData()
    {
        // Total Data
        $totalPJU = DataPJU::count();
        $totalPanel = DataPanel::count();

        // Total Riwayat
        $totalRiwayatPJU = RiwayatPJU::count() + DetailPengaduan::whereNotNull('pju_id')->count();
        $totalRiwayatPanel = RiwayatPanel::count() + DetailPengaduan::whereNotNull('panel_id')->count();

        // Riwayat by Status (Per ID)
        $riwayatPendingPJU = RiwayatPJU::select('pju_id')
            ->where('status', 'Pending')
            ->distinct()
            ->count() +
            DetailPengaduan::whereNotNull('pju_id')
                ->whereHas('pengaduan', function ($query) {
                    $query->where('status', 'Pending');
                })
                ->distinct('pju_id')
                ->count();

        $riwayatPendingPanel = RiwayatPanel::select('panel_id')
            ->where('status', 'Pending')
            ->distinct()
            ->count() +
            DetailPengaduan::whereNotNull('panel_id')
                ->whereHas('pengaduan', function ($query) {
                    $query->where('status', 'Pending');
                })
                ->distinct('panel_id')
                ->count();

        $riwayatProsesPJU = RiwayatPJU::select('pju_id')
            ->where('status', 'Proses')
            ->distinct()
            ->count() +
            DetailPengaduan::whereNotNull('pju_id')
                ->whereHas('pengaduan', function ($query) {
                    $query->where('status', 'Proses');
                })
                ->distinct('pju_id')
                ->count();

        $riwayatProsesPanel = RiwayatPanel::select('panel_id')
            ->where('status', 'Proses')
            ->distinct()
            ->count() +
            DetailPengaduan::whereNotNull('panel_id')
                ->whereHas('pengaduan', function ($query) {
                    $query->where('status', 'Proses');
                })
                ->distinct('panel_id')
                ->count();

        // Rata-Rata Riwayat
        $avgRiwayatPerPJU = $totalRiwayatPJU > 0 ? $totalRiwayatPJU / max(1, $totalPJU) : 0;
        $avgRiwayatPerPanel = $totalRiwayatPanel > 0 ? $totalRiwayatPanel / max(1, $totalPanel) : 0;

        return response()->json([
            'total_pju' => $totalPJU,
            'total_panel' => $totalPanel,
            'total_riwayat_pju' => $totalRiwayatPJU,
            'total_riwayat_panel' => $totalRiwayatPanel,
            'riwayat_pending_pju' => $riwayatPendingPJU,
            'riwayat_proses_pju' => $riwayatProsesPJU,
            'riwayat_pending_panel' => $riwayatPendingPanel,
            'riwayat_proses_panel' => $riwayatProsesPanel,
            'avg_riwayat_pju' => round($avgRiwayatPerPJU, 2),
            'avg_riwayat_panel' => round($avgRiwayatPerPanel, 2),
        ]);
    }

    public function dashboardUserData()
    {
        // $superAdminCount = User::where('role', 'superadmin')->count();
        $adminCount = User::where('role', 'admin')->count();
        $dishubCount = User::where('role', 'dishub')->count();
        $visitorCount = User::where('role', 'visitor')->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'userdata_count' => $adminCount + $dishubCount + $visitorCount,
                'admin_count' => $adminCount,
                'dishub_count' => $dishubCount,
                'visitor_count' => $visitorCount
            ]
        ], 200);
    }
}
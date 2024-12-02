<?php

namespace App\Http\Controllers;

use App\Exports\DataPJUExport;
use App\Exports\DataPanelExport;
use App\Exports\RiwayatPanelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\RiwayatPanel;

class ExportController extends Controller
{
    public function exportDataPJU()
    {
        return Excel::download(new DataPJUExport, 'data_pju.xlsx');
    }

    public function exportDataPanel()
    {
        return Excel::download(new DataPanelExport, 'data_panel.xlsx');
    }

    public function exportRiwayatPanel($panelId)
    {
        $riwayatData = RiwayatPanel::where('panel_id', $panelId)->get();

        if ($riwayatData->isEmpty()) {
            return response()->json([
                'message' => 'Data tidak ditemukan untuk Panel ID ' . $panelId
            ], 404);
        }

        return Excel::download(new RiwayatPanelExport($riwayatData), 'riwayat_panel_' . $panelId . '.xlsx');
    }
}
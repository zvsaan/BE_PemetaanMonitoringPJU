<?php

namespace App\Http\Controllers;

use App\Exports\DataPJUExport;
use App\Exports\DataPanelExport;
use App\Exports\DataKonstruksiExport;
use App\Exports\RiwayatPanelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\RiwayatPanel;
use App\Exports\AllRiwayatPjuExport;
use App\Exports\PengaduanExport;
use App\Exports\RiwayatPjuExport;
use App\Exports\RiwayatPjuSpecificExport;

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

    public function exportDataKonstruksi()
    {
        return Excel::download(new DataKonstruksiExport, 'data_konstruksi.xlsx');
    }



    //Riwayat PJU
    public function exportAll()
    {
        return Excel::download(new AllRiwayatPjuExport, 'Riwayat Semua APJ.xlsx');
    }

    public function exportPengaduan()
    {
        return Excel::download(new PengaduanExport, 'Riwayat APJ by Pengaduan.xlsx');
    }

    public function exportRiwayat()
    {
        return Excel::download(new RiwayatPjuExport, 'Riwayat APJ.xlsx');
    }

    public function exportByPJU($pjuId)
    {
        $noTiang = DB::table('data_pjus')
            ->where('id_pju', $pjuId)
            ->value('no_tiang_baru') ?? 'Unknown';

        $filename = 'Riwayat APJ No Tiang ' . $noTiang . '.xlsx';

        // Export data
        return Excel::download(new RiwayatPjuSpecificExport($pjuId), $filename);
    }
}
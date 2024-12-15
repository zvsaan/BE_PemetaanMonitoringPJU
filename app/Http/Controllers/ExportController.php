<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\DataPJUExport;
use App\Exports\DataPanelExport;
use App\Exports\DataKonstruksiExport;

use App\Exports\AllRiwayatPjuExport;
use App\Exports\AllRiwayatPanelExport;

use App\Exports\PengaduanExport;
use App\Exports\PengaduanPanelExport;

use App\Exports\RiwayatPjuExport;
use App\Exports\RiwayatPanelExport;

use App\Exports\RiwayatPjuSpecificExport;
use App\Exports\RiwayatPanelSpecificExport;

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

    public function exportDataKonstruksi()
    {
        return Excel::download(new DataKonstruksiExport, 'data_konstruksi.xlsx');
    }






    //PJU BY ID
    public function exportByPJU($pjuId)
    {
        $noTiang = DB::table('data_pjus')
            ->where('id_pju', $pjuId)
            ->value('no_tiang_baru') ?? 'Unknown';

        $filename = 'Riwayat APJ No Tiang ' . $noTiang . '.xlsx';

        // Export data
        return Excel::download(new RiwayatPjuSpecificExport($pjuId), $filename);
    }

    //Panel BY ID
    public function exportByPanel($panelId)
    {
        $noApp = DB::table('data_panels')
            ->where('id_panel', $panelId)
            ->value('no_app') ?? 'Unknown';

        $filename = 'Riwayat Panel No Panel ' . $noApp . '.xlsx';

        // Export data
        return Excel::download(new RiwayatPanelSpecificExport($panelId), $filename);
    }










    //Riwayat PJU
    public function exportAllPJU()
    {
        return Excel::download(new AllRiwayatPjuExport, 'Riwayat Semua APJ.xlsx');
    }

    public function exportPengaduanPJU()
    {
        return Excel::download(new PengaduanExport, 'Riwayat APJ by Pengaduan.xlsx');
    }

    public function exportRiwayatPJU()
    {
        return Excel::download(new RiwayatPjuExport, 'Riwayat APJ.xlsx');
    }







    //Riwayat Panel
    public function exportAllPanel()
    {
        return Excel::download(new AllRiwayatPanelExport, 'Riwayat Semua Panel.xlsx');
    }

    public function exportPengaduanPanel()
    {
        return Excel::download(new PengaduanPanelExport, 'Riwayat Panel by Pengaduan.xlsx');
    }

    public function exportRiwayatPanel()
    {
        return Excel::download(new RiwayatPanelExport, 'Riwayat Panel.xlsx');
    }
}
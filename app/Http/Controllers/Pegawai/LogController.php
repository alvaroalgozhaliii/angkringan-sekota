<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends PegawaiController
{
    public function index(Request $request)
    {
        $query = ActivityLog::query()->with('user');

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('aksi', 'like', "%{$keyword}%")
                    ->orWhere('modul', 'like', "%{$keyword}%")
                    ->orWhere('user_nama', 'like', "%{$keyword}%")
                    ->orWhere('data_sebelum', 'like', "%{$keyword}%")
                    ->orWhere('data_sesudah', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        $bulan = (int) $request->bulan;
        if ($bulan >= 1 && $bulan <= 12) {
            $query->whereMonth('created_at', $bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', (int) $request->tahun);
        }

        $logs = $query->latest('created_at')->paginate(20)->withQueryString();
        $modul = ActivityLog::distinct()->orderBy('modul')->pluck('modul');
        $driver = DB::connection()->getDriverName();
        $tahun = ActivityLog::whereNotNull('created_at')
            ->when($driver === 'sqlite', function ($q) {
                $q->selectRaw("DISTINCT CAST(strftime('%Y', created_at) AS INTEGER) as tahun");
            }, function ($q) {
                $q->selectRaw('DISTINCT YEAR(created_at) as tahun');
            })
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('pegawai.log.index', compact('logs', 'modul', 'tahun'));
    }
}

<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends PegawaiController
{
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('aksi', 'like', "%{$keyword}%")
                    ->orWhere('modul', 'like', "%{$keyword}%")
                    ->orWhere('user_nama', 'like', "%{$keyword}%");
            });
        }
        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        $logs = $query->latest('created_at')->paginate(20)->withQueryString();
        $modul = ActivityLog::distinct()->orderBy('modul')->pluck('modul');

        return view('pegawai.log.index', compact('logs', 'modul'));
    }
}

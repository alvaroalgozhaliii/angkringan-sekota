<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesan;

class PesanController extends Controller
{
    public function index()
    {
        $pesan = Pesan::orderBy('created_at', 'desc')->paginate(15);
        $belumDibaca = Pesan::where('status', 'belum_dibaca')->count();
        return view('admin.pesan.index', compact('pesan', 'belumDibaca'));
    }

    public function show(Pesan $pesan)
    {
        $pesan->markAsRead();
        return view('admin.pesan.show', compact('pesan'));
    }

    public function destroy(Pesan $pesan)
    {
        $pesan->delete();
        return back()->with('success', 'Pesan berhasil dihapus!');
    }

    public function markAllRead()
    {
        Pesan::where('status', 'belum_dibaca')->update(['status' => 'sudah_dibaca']);
        return back()->with('success', 'Semua pesan ditandai sudah dibaca!');
    }
}

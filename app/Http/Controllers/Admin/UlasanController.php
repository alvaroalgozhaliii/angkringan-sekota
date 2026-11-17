<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');

        $query = Testimonial::orderBy('id', 'desc');

        if ($filter === 'aktif') {
            $query->where('is_active', 1);
        } elseif ($filter === 'pending') {
            $query->where('is_active', 0);
        }

        $ulasan   = $query->paginate(15)->withQueryString();
        $totalAll    = Testimonial::count();
        $totalAktif  = Testimonial::where('is_active', 1)->count();
        $totalPending = Testimonial::where('is_active', 0)->count();
        $avgRating   = $totalAll > 0
            ? number_format(Testimonial::where('is_active', 1)->avg('rating'), 1)
            : '0.0';

        return view('admin.ulasan.index', compact(
            'ulasan', 'filter', 'totalAll', 'totalAktif', 'totalPending', 'avgRating'
        ));
    }

    public function toggleAktif(Testimonial $ulasan)
    {
        $ulasan->update(['is_active' => !$ulasan->is_active]);

        $status = $ulasan->is_active ? 'ditampilkan' : 'disembunyikan';

        return back()->with('success', "Ulasan dari \"{$ulasan->customer_name}\" berhasil {$status}.");
    }

    public function destroy(Testimonial $ulasan)
    {
        $nama = $ulasan->customer_name;
        $ulasan->delete();

        return back()->with('success', "Ulasan dari \"{$nama}\" berhasil dihapus.");
    }
}

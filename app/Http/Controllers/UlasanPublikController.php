<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class UlasanPublikController extends Controller
{
    /**
     * Simpan ulasan pengunjung dari web profil
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|min:2|max:100',
            'rating'        => 'required|integer|min:1|max:5',
            'review'        => 'required|string|min:3|max:1000',
        ], [
            'customer_name.required' => 'Mohon cantumkan nama Anda.',
            'customer_name.min'      => 'Nama minimal 2 karakter.',
            'rating.required'         => 'Mohon berikan penilaian bintang (1-5).',
            'rating.min'              => 'Rating minimal 1 bintang.',
            'rating.max'              => 'Rating maksimal 5 bintang.',
            'review.required'         => 'Mohon tuliskan ulasan atau keterangan Anda.',
            'review.min'              => 'Ulasan minimal 3 karakter.',
        ]);

        $ulasan = Testimonial::create([
            'customer_name' => strip_tags(trim($validated['customer_name'])),
            'rating'        => (int) $validated['rating'],
            'review'        => strip_tags(trim($validated['review'])),
            'source'        => 'Web',
            'is_active'     => 1,
            'is_featured'   => 0,
            'sort_order'    => 0,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Matur nuwun! Ulasan Anda berhasil dikirim dan ditampilkan.',
                'data'    => [
                    'id'            => $ulasan->id,
                    'customer_name' => $ulasan->customer_name,
                    'rating'        => $ulasan->rating,
                    'review'        => $ulasan->review,
                    'inisial'       => $ulasan->inisial,
                    'waktu'         => 'Baru saja',
                ]
            ]);
        }

        return back()->with('success', 'Matur nuwun! Ulasan Anda berhasil dikirim.');
    }
}

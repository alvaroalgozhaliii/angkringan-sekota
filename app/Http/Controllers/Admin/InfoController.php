<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyInfo;

class InfoController extends Controller
{
    public function index()
    {
        $info = DailyInfo::first();
        return view('admin.info', compact('info'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'opening_hours' => 'required|string',
            'promo' => 'nullable|string',
            'announcement' => 'nullable|string',
        ]);
        $info = DailyInfo::firstOrNew();
        $info->fill($data);
        $info->save();
        return redirect()->back()->with('status', 'Informasi harian diperbarui');
    }
}

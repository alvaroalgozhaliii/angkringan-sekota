<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;

abstract class PegawaiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin', 'role:pegawai,super_admin']);
    }
}

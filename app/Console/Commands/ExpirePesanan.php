<?php

namespace App\Console\Commands;

use App\Services\PesananService;
use Illuminate\Console\Command;

class ExpirePesanan extends Command
{
    protected $signature = 'pesanan:expire';

    protected $description = 'Menandai pesanan yang melewati batas waktu bayar sebagai kedaluwarsa';

    public function handle(PesananService $service): int
    {
        $jumlah = $service->jalankanKedaluwarsa();
        $this->info($jumlah.' pesanan ditandai kedaluwarsa.');

        return self::SUCCESS;
    }
}

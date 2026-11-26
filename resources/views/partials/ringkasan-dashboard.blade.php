<div class="panel panel-ringkasan">
    <div class="ringkasan-head">
        <div>
            <div class="ringkasan-eyebrow">Ringkasan hari ini</div>
            <h2 class="ringkasan-judul">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</h2>
        </div>
        <span class="ringkasan-sumber">Angka diambil dari transaksi yang tersimpan</span>
    </div>

    <div class="ringkasan-utama">
        <div class="ringkasan-item">
            <span class="ringkasan-label">Uang masuk hari ini</span>
            <strong class="ringkasan-nilai">Rp{{ number_format($ringkasan['uangMasukHariIni'], 0, ',', '.') }}</strong>
        </div>
        <div class="ringkasan-item">
            <span class="ringkasan-label">Pengunjung hari ini</span>
            <strong class="ringkasan-nilai">{{ $ringkasan['pengunjungHariIni'] }}</strong>
            <span class="ringkasan-catatan">Sudah dikonfirmasi atau pesanan sudah diambil</span>
        </div>
    </div>

    <div class="ringkasan-antrian">
        <div class="ringkasan-antrian-judul">Antrian saat ini</div>
        <div class="ringkasan-antrian-grid">
            @foreach(\App\Support\OrderStatus::grupAntrean() as $kunci => $statuses)
                <a href="{{ route($ruteAntrian, ['grup' => $kunci]) }}" class="antrian-item">
                    <strong class="antrian-nilai">{{ $ringkasan['antrian'][$kunci] }}</strong>
                    <span class="antrian-label">{{\App\Support\OrderStatus::labelGrupAntrean($kunci)}}</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="ringkasan-total">
        <div>
            <span>Pengunjung total</span>
            <strong>{{ $ringkasan['pengunjungTotal'] }}</strong>
        </div>
        <div>
            <span>Pemasukan total</span>
            <strong>Rp{{ number_format($ringkasan['pemasukanTotal'], 0, ',', '.') }}</strong>
        </div>
    </div>
</div>

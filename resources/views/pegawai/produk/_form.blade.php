<div class="two-col">
    <div class="form-group">
        <label class="form-label" for="nama">Nama produk</label>
        <input id="nama" name="nama" type="text" class="form-control" value="{{ old('nama', $produk?->nama) }}" required maxlength="255">
        @error('nama') <div class="field-error">{{ $message }}</div> @enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="kategori">Kategori</label>
        @php
            $kategoriPilihan = ['Makanan', 'Minuman', 'Camilan'];
            $kategoriSaatIni = old('kategori', $produk?->kategori ?? 'Makanan');
        @endphp
        <select id="kategori" name="kategori" class="form-control" required>
            @foreach($kategoriPilihan as $kat)
                <option value="{{ $kat }}" {{ strcasecmp((string)$kategoriSaatIni, $kat) === 0 ? 'selected' : '' }}>{{ $kat }}</option>
            @endforeach
            @if($kategoriSaatIni && !in_array(strtolower($kategoriSaatIni), ['makanan', 'minuman', 'camilan'], true))
                <option value="{{ $kategoriSaatIni }}" selected>{{ $kategoriSaatIni }}</option>
            @endif
        </select>
        <div class="hint">Pilih jenis menu: Makanan, Minuman, atau Camilan.</div>
        @error('kategori') <div class="field-error">{{ $message }}</div> @enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="harga">Harga (Rp)</label>
        <input id="harga" name="harga" type="number" min="0" step="1" class="form-control" value="{{ old('harga', $produk?->harga) }}" required>
        @error('harga') <div class="field-error">{{ $message }}</div> @enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="stok">Stok awal / stok saat ini</label>
        <input id="stok" name="stok" type="number" min="0" class="form-control" value="{{ old('stok', $produk?->stok ?? 0) }}" required>
        @error('stok') <div class="field-error">{{ $message }}</div> @enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="status">Status</label>
        <select id="status" name="status" class="form-control">
            @foreach(['tayang' => 'Tayang di website', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $value => $label)
                <option value="{{ $value }}" {{ old('status', $produk?->status ?? 'tayang') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <div class="field-error">{{ $message }}</div> @enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="urutan">Urutan tampil</label>
        <input id="urutan" name="urutan" type="number" min="0" class="form-control" value="{{ old('urutan', $produk?->urutan ?? 0) }}">
    </div>
    <div class="form-group" style="grid-column:1/-1">
        <label class="form-label" for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $produk?->deskripsi) }}</textarea>
    </div>
    <div class="form-group" style="grid-column:1/-1">
        <label class="form-label" for="gambar">Gambar produk {{ $produk ? '(kosongkan jika tidak diganti)' : '' }}</label>
        @if($produk)<img src="{{ $produk->gambar_url }}" alt="Gambar produk" class="thumb" style="margin-bottom:.5rem">@endif
        <input id="gambar" name="gambar" type="file" accept="image/jpeg,image/png,image/webp" class="form-control">
        <div class="hint">JPG, PNG, atau WebP. Maksimal 2 MB.</div>
        @error('gambar') <div class="field-error">{{ $message }}</div> @enderror
    </div>
</div>

{{-- ==============================================================
     HALAMAN: LOKASI & KONTAK — ANGKRINGAN SEKOTA
     Desain Minimalis, Rapi, Responsif & Sesuai Tema
     Bebas AI-Slop (Antislop Compliant)
     ============================================================== --}}
<div id="page-kontak" class="page">

    {{-- Banner Header Minimalis --}}
    <div class="relative bg-gradient-to-b from-[#C8102E] to-[#9E0C23] text-white py-12 px-4 sm:px-6 lg:px-8 text-center shadow-inner">
        <div class="max-w-3xl mx-auto">
            <span class="inline-block text-xs uppercase tracking-widest font-semibold text-rose-200 mb-2">
                Pusat Kota Tulungagung
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white mb-3">
                Lokasi &amp; Kontak
            </h1>
            <p class="text-sm sm:text-base text-rose-100 max-w-2xl mx-auto leading-relaxed">
                Mampir langsung untuk menikmati suasana santai malam khas angkringan, atau hubungi kami untuk info menu dan reservasi meja.
            </p>
            <div class="mt-5 flex flex-wrap items-center justify-center gap-3 text-xs sm:text-sm font-medium">
                <span class="inline-flex items-center gap-1.5 bg-black/20 text-white px-3 py-1.5 rounded-full border border-white/20">
                    <svg class="w-4 h-4 text-rose-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Buka Setiap Hari: {{ $profil->jam_buka ?? '17.00 - 23.00 WIB' }}
                </span>
                <span class="inline-flex items-center gap-1.5 bg-black/20 text-white px-3 py-1.5 rounded-full border border-white/20">
                    <svg class="w-4 h-4 text-rose-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Tulungagung, Jawa Timur
                </span>
            </div>
        </div>
    </div>

    {{-- Konten Utama Halaman --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-12">

        {{-- Grid Baris 1: Informasi Kontak, Alamat, dan Peta --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-stretch">
            
            {{-- Kolom Kiri: WhatsApp & Detail Info --}}
            <div class="lg:col-span-5 flex flex-col gap-6">

                {{-- Kartu WhatsApp & Pemesanan Cepat --}}
                <div class="bg-white rounded-2xl border border-stone-200/90 p-6 shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2.5 text-[#C8102E] font-semibold text-xs uppercase tracking-wider mb-2">
                            <span class="w-2 h-2 rounded-full bg-[#C8102E]"></span>
                            Layanan Cepat
                        </div>
                        <h2 class="text-xl font-bold text-stone-900 tracking-tight mb-2">
                            Pemesanan &amp; Reservasi
                        </h2>
                        <p class="text-xs sm:text-sm text-stone-600 leading-relaxed mb-5">
                            Ingin memesan meja untuk rombongan, memesan menu sebelum datang, atau menanyakan info terkini? Hubungi kami langsung melalui WhatsApp.
                        </p>

                        @php
                            $cleanWa = preg_replace('/[^0-9]/', '', $profil->whatsapp ?? '6281234567890');
                            if (str_starts_with($cleanWa, '0')) {
                                $cleanWa = '62' . substr($cleanWa, 1);
                            }
                        @endphp

                        <div class="bg-stone-50 border border-stone-200 rounded-xl p-4 mb-4 flex items-center justify-between gap-3">
                            <div>
                                <span class="text-xs text-stone-500 block">Nomor Resmi:</span>
                                <span class="font-mono font-bold text-stone-900 text-base sm:text-lg tracking-wide">
                                    {{ $profil->whatsapp ?? '0812-3456-7890' }}
                                </span>
                            </div>
                            <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-800">
                                Aktif Setiap Hari
                            </span>
                        </div>
                    </div>

                    <a href="https://wa.me/{{ $cleanWa }}?text=Halo%20Angkringan%20Sekota%2C%20mau%20tanya%20info%2Freservasi..." 
                       target="_blank" 
                       rel="noopener" 
                       class="w-full inline-flex items-center justify-center gap-2.5 bg-[#25D366] hover:bg-[#20ba59] active:bg-[#1caa51] text-white font-bold text-sm sm:text-base py-3.5 px-6 rounded-xl shadow-sm hover:shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#25D366] focus:ring-offset-2"
                       aria-label="Kirim pesan ke WhatsApp Angkringan Sekota">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.586 1.761.88 2.79.88 3.182 0 5.768-2.587 5.768-5.766.001-3.181-2.586-5.766-5.767-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.824 0-3.535-.49-5.018-1.344l-5.982 1.568 1.597-5.836c-.927-1.536-1.464-3.329-1.464-5.255 0-5.514 4.486-10 10-10 5.514 0 10 4.486 10 10z"/>
                        </svg>
                        <span>Chat WhatsApp Sekarang</span>
                    </a>
                </div>

                {{-- Kartu Alamat & Fasilitas --}}
                <div class="bg-white rounded-2xl border border-stone-200/90 p-6 shadow-sm hover:shadow-md transition-shadow duration-200 space-y-5">
                    <div>
                        <h2 class="text-base font-bold text-stone-900 tracking-tight mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Informasi Warung
                        </h2>

                        <div class="space-y-4">
                            {{-- Baris Alamat --}}
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-rose-50 text-[#C8102E] flex items-center justify-center shrink-0 mt-0.5" aria-hidden="true">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Alamat Lengkap</span>
                                    <p class="text-sm font-medium text-stone-800 leading-snug mt-0.5">
                                        {{ $profil->alamat ?? 'Tulungagung, Jawa Timur' }}
                                    </p>
                                    <button type="button" 
                                            id="btnCopyAddress"
                                            onclick="salinAlamat('{{ addslashes($profil->alamat ?? 'Tulungagung, Jawa Timur') }}')" 
                                            class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-[#C8102E] hover:text-[#9E0C23] bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-[#C8102E]"
                                            aria-label="Salin teks alamat lengkap ke clipboard">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="copyIconSvg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                        </svg>
                                        <span id="copyBtnLabel">Salin Alamat</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Baris Jam Buka --}}
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-rose-50 text-[#C8102E] flex items-center justify-center shrink-0 mt-0.5" aria-hidden="true">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Jam Operasional</span>
                                    <p class="text-sm font-medium text-stone-800 leading-snug mt-0.5">
                                        Buka Setiap Hari, {{ $profil->jam_buka ?? '17.00 - 23.00 WIB' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Baris Sosial Media (jika ada) --}}
                            @if(!empty($profil->instagram) || !empty($profil->facebook))
                            <div class="pt-2 border-t border-stone-100 flex items-center gap-3">
                                <span class="text-xs text-stone-500">Media Sosial:</span>
                                <div class="flex items-center gap-2">
                                    @if(!empty($profil->instagram))
                                    <a href="https://instagram.com/{{ ltrim($profil->instagram, '@') }}" 
                                       target="_blank" 
                                       rel="noopener" 
                                       class="text-xs font-medium text-stone-700 hover:text-[#C8102E] bg-stone-100 hover:bg-stone-200 px-2.5 py-1 rounded transition-colors"
                                       title="Buka Instagram Angkringan Sekota">
                                        Instagram: {{ $profil->instagram }}
                                    </a>
                                    @endif
                                    @if(!empty($profil->facebook))
                                    <a href="#" 
                                       class="text-xs font-medium text-stone-700 hover:text-[#C8102E] bg-stone-100 hover:bg-stone-200 px-2.5 py-1 rounded transition-colors"
                                       title="Buka Facebook Angkringan Sekota">
                                        Facebook
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Fasilitas Tersedia --}}
                    <div class="pt-4 border-t border-stone-100">
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block mb-2.5">
                            Fasilitas Tersedia
                        </span>
                        <div class="flex flex-wrap gap-2 text-xs text-stone-700 font-medium">
                            <span class="bg-stone-100 text-stone-700 px-2.5 py-1.5 rounded-lg border border-stone-200">
                                Lesehan &amp; Meja Kursi
                            </span>
                            <span class="bg-stone-100 text-stone-700 px-2.5 py-1.5 rounded-lg border border-stone-200">
                                Free WiFi
                            </span>
                            <span class="bg-stone-100 text-stone-700 px-2.5 py-1.5 rounded-lg border border-stone-200">
                                Stopkontak Charger
                            </span>
                            <span class="bg-stone-100 text-stone-700 px-2.5 py-1.5 rounded-lg border border-stone-200">
                                Area Parkir Luas
                            </span>
                            <span class="bg-stone-100 text-stone-700 px-2.5 py-1.5 rounded-lg border border-stone-200">
                                Ruang Bersih &amp; Nyaman
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan: Peta Interaktif & Petunjuk Arah --}}
            <div class="lg:col-span-7 flex flex-col">
                <div class="bg-white rounded-2xl border border-stone-200/90 p-6 shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col h-full">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-stone-900 tracking-tight">
                                Peta Lokasi
                            </h2>
                            <p class="text-xs sm:text-sm text-stone-600">
                                Temukan rute tercepat menuju lokasi Angkringan Sekota di Google Maps.
                            </p>
                        </div>
                        <a href="https://maps.app.goo.gl/NyuN7yDHJ21HJt4L7" 
                           target="_blank" 
                           rel="noopener" 
                           class="hidden sm:inline-flex items-center gap-1.5 bg-[#C8102E] hover:bg-[#9E0C23] text-white text-xs font-bold px-3.5 py-2 rounded-lg shadow-sm transition-colors shrink-0"
                           title="Buka rute langsung di aplikasi Google Maps">
                            <span>Petunjuk Arah</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>

                    {{-- Kontainer Iframe Peta --}}
                    <div class="relative w-full flex-1 min-h-[340px] sm:min-h-[400px] rounded-xl overflow-hidden border border-stone-200 bg-stone-100 shadow-inner">
                        <iframe 
                            src="https://maps.google.com/maps?q=-8.0593353,111.9106113&hl=id&z=17&output=embed"
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Peta Lokasi Angkringan Sekota Tulungagung"
                            class="absolute inset-0 w-full h-full">
                        </iframe>
                    </div>

                    {{-- Footer Info Peta --}}
                    <div class="mt-4 pt-3 border-t border-stone-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-stone-600">
                        <div>
                            <strong class="text-stone-800 font-semibold">Angkringan Sekota Tulungagung</strong>
                            <p class="text-stone-500">Akses jalan mulus, mudah dicapai kendaraan roda 2 dan roda 4.</p>
                        </div>
                        <a href="https://maps.app.goo.gl/NyuN7yDHJ21HJt4L7" 
                           target="_blank" 
                           rel="noopener" 
                           class="sm:hidden w-full inline-flex items-center justify-center gap-1.5 bg-[#C8102E] hover:bg-[#9E0C23] text-white font-bold py-2.5 px-4 rounded-lg shadow-sm transition-colors text-center"
                           title="Buka rute langsung di aplikasi Google Maps">
                            <span>Buka di Google Maps</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- ==============================================================
             BAGIAN: ULASAN & PENGALAMAN PENGUNJUNG
             ============================================================== --}}
        @php
            $daftarUlasan = $ulasan ?? collect();
            $totalCount = $daftarUlasan->count();
            $avgScore = $totalCount > 0 ? number_format($daftarUlasan->avg('rating'), 1) : '5.0';
        @endphp

        <div class="bg-white rounded-2xl border border-stone-200/90 p-6 sm:p-8 shadow-sm space-y-8">
            
            {{-- Header Bagian Ulasan --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-stone-100">
                <div>
                    <div class="flex items-center gap-2 text-[#C8102E] font-semibold text-xs uppercase tracking-wider mb-1">
                        <span>Suara Pelanggan</span>
                    </div>
                    <h2 class="text-2xl font-extrabold text-stone-900 tracking-tight">
                        Ulasan &amp; Pengalaman Pengunjung
                    </h2>
                    <p class="text-xs sm:text-sm text-stone-600 mt-1 max-w-xl">
                        Kesan jujur dari para sedulur dan pelanggan setia yang pernah mampir menikmati hidangan kami.
                    </p>
                </div>

                {{-- Kotak Rangkuman Nilai Asli --}}
                <div class="bg-amber-50/80 border border-amber-200 rounded-xl p-3.5 sm:p-4 flex items-center gap-3.5 self-start sm:self-auto shrink-0">
                    <div class="text-3xl font-extrabold text-amber-700 leading-none">
                        {{ $avgScore }}
                    </div>
                    <div>
                        <div class="text-amber-500 font-bold text-sm tracking-wider">
                            @for($i = 1; $i <= 5; $i++)
                                ★
                            @endfor
                        </div>
                        <span class="text-xs font-semibold text-amber-900 block mt-0.5" id="summaryReviewCountText">
                            Berdasarkan {{ $totalCount }} ulasan pengunjung
                        </span>
                    </div>
                </div>
            </div>

            {{-- Grid Sub-bagian: Form di Kiri, Feed di Kanan --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- Kolom Kiri: Form Kirim Ulasan --}}
                <div class="lg:col-span-5 bg-stone-50 border border-stone-200 rounded-xl p-5 sm:p-6 space-y-4">
                    <div>
                        <h3 class="text-base font-bold text-stone-900">
                            Tulis Ulasan Anda
                        </h3>
                        <p class="text-xs text-stone-600 mt-0.5">
                            Ceritakan kesan Anda seputar cita rasa nasi kucing, aneka sate, wedangan hangat, dan keramahan pelayanan kami.
                        </p>
                    </div>

                    <form id="formReviewVisitor" onsubmit="handleKirimUlasan(event)" class="space-y-4">
                        <div>
                            <label for="reviewCustomerName" class="block text-xs font-bold text-stone-800 uppercase tracking-wider mb-1.5">
                                Nama Anda <span class="text-[#C8102E]">*</span>
                            </label>
                            <input type="text" 
                                   id="reviewCustomerName" 
                                   name="customer_name" 
                                   class="w-full bg-white border border-stone-300 rounded-lg px-3.5 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-[#C8102E] transition-all"
                                   placeholder="Contoh: Mas Danang atau Mbak Rani" 
                                   required 
                                   maxlength="100" 
                                   autocomplete="name">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-800 uppercase tracking-wider mb-1.5">
                                Penilaian Bintang <span class="text-[#C8102E]">*</span>
                            </label>
                            <div class="bg-white border border-stone-300 rounded-lg p-3 flex flex-col gap-2">
                                <div class="star-picker flex items-center gap-1.5" id="starPicker">
                                    <button type="button" class="star-btn active text-2xl text-amber-400 hover:scale-110 transition-transform p-0.5 cursor-pointer leading-none" data-val="1" onclick="setRating(1)" onmouseenter="previewRating(1)" onmouseleave="resetStarPreview()" aria-label="1 Bintang" title="1 Bintang">★</button>
                                    <button type="button" class="star-btn active text-2xl text-amber-400 hover:scale-110 transition-transform p-0.5 cursor-pointer leading-none" data-val="2" onclick="setRating(2)" onmouseenter="previewRating(2)" onmouseleave="resetStarPreview()" aria-label="2 Bintang" title="2 Bintang">★</button>
                                    <button type="button" class="star-btn active text-2xl text-amber-400 hover:scale-110 transition-transform p-0.5 cursor-pointer leading-none" data-val="3" onclick="setRating(3)" onmouseenter="previewRating(3)" onmouseleave="resetStarPreview()" aria-label="3 Bintang" title="3 Bintang">★</button>
                                    <button type="button" class="star-btn active text-2xl text-amber-400 hover:scale-110 transition-transform p-0.5 cursor-pointer leading-none" data-val="4" onclick="setRating(4)" onmouseenter="previewRating(4)" onmouseleave="resetStarPreview()" aria-label="4 Bintang" title="4 Bintang">★</button>
                                    <button type="button" class="star-btn active text-2xl text-amber-400 hover:scale-110 transition-transform p-0.5 cursor-pointer leading-none" data-val="5" onclick="setRating(5)" onmouseenter="previewRating(5)" onmouseleave="resetStarPreview()" aria-label="5 Bintang" title="5 Bintang">★</button>
                                </div>
                                <span class="text-xs font-semibold text-amber-800" id="starRatingLabel">
                                    5 Bintang: Sangat Puas!
                                </span>
                                <input type="hidden" name="rating" id="reviewRatingInput" value="5">
                            </div>
                        </div>

                        <div>
                            <label for="reviewText" class="block text-xs font-bold text-stone-800 uppercase tracking-wider mb-1.5">
                                Isi Ulasan <span class="text-[#C8102E]">*</span>
                            </label>
                            <textarea id="reviewText" 
                                      name="review" 
                                      rows="3" 
                                      class="w-full bg-white border border-stone-300 rounded-lg px-3.5 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-[#C8102E] transition-all resize-y"
                                      placeholder="Tulis ulasan Anda di sini..." 
                                      required 
                                      maxlength="1000"></textarea>
                        </div>

                        <button type="submit" 
                                id="btnSubmitReview" 
                                class="w-full inline-flex items-center justify-center gap-2 bg-[#C8102E] hover:bg-[#9E0C23] active:bg-[#850a1d] text-white font-bold text-sm py-3 px-5 rounded-lg shadow-sm hover:shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:ring-offset-2">
                            <span>Kirim Ulasan Saya</span>
                        </button>
                    </form>
                </div>

                {{-- Kolom Kanan: Feed Ulasan Pengunjung --}}
                <div class="lg:col-span-7 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                        <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">
                            Daftar Ulasan (<span id="feedCountNum">{{ $totalCount }}</span>)
                        </span>
                        <span class="text-xs text-stone-500">Terbaru lebih dulu</span>
                    </div>

                    {{-- Wadah Scrollable Ulasan --}}
                    <div class="reviews-feed-scroll max-h-[500px] overflow-y-auto space-y-3 pr-1" id="reviewsFeedList">
                        @forelse($daftarUlasan as $itemUlasan)
                        <div class="review-item-card bg-stone-50 border border-stone-200/90 rounded-xl p-4 transition-all duration-150 hover:bg-stone-100/70">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-[#C8102E] text-white font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ $itemUlasan->inisial ?? 'AS' }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm text-stone-900 leading-tight">
                                            {{ $itemUlasan->customer_name }}
                                        </div>
                                        <span class="text-[11px] text-stone-500">
                                            Pengunjung Angkringan Sekota
                                        </span>
                                    </div>
                                </div>
                                <span class="text-xs text-stone-400 shrink-0">
                                    {{ $itemUlasan->waktu_format ?? ($itemUlasan->created_at ? $itemUlasan->created_at->diffForHumans() : 'Baru saja') }}
                                </span>
                            </div>

                            <div class="text-amber-500 text-xs tracking-wider mb-1.5" aria-label="{{ $itemUlasan->rating }} dari 5 bintang">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $itemUlasan->rating ? '★' : '☆' }}
                                @endfor
                            </div>

                            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed">
                                &ldquo;{{ $itemUlasan->review }}&rdquo;
                            </p>
                        </div>
                        @empty
                        <div class="bg-stone-50 border border-stone-200 rounded-xl p-8 text-center text-stone-500 text-xs sm:text-sm" id="emptyReviewNotice">
                            Belum ada ulasan yang tersimpan. Jadilah yang pertama membagikan cerita dan pengalaman Anda di Angkringan Sekota!
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Footer --}}
    @include('partials._footer')
</div>

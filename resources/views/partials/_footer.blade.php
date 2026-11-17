@php
    $namaFooter = $profil->nama_usaha ?? 'Angkringan Sekota';
    $igHandle = ltrim((string) ($profil->instagram ?? 'angkringan.sekota'), '@');
    $fbRaw = trim((string) ($profil->facebook ?? 'Angkringan Sekota'));
    $ttHandle = ltrim((string) ($profil->tiktok ?? $igHandle), '@');

    if ($fbRaw !== '' && preg_match('#^https?://#i', $fbRaw)) {
        $fbUrl = $fbRaw;
        $fbLabel = 'Facebook';
    } else {
        $fbSlug = preg_replace('/\s+/', '', $fbRaw) ?: 'AngkringanSekota';
        $fbUrl = 'https://www.facebook.com/' . rawurlencode($fbSlug);
        $fbLabel = $fbRaw !== '' ? $fbRaw : 'Facebook';
    }

    $igUrl = 'https://www.instagram.com/' . rawurlencode($igHandle);
    $ttUrl = 'https://www.tiktok.com/@' . rawurlencode($ttHandle);
@endphp

<footer class="footer">
    <div class="footer-glow" aria-hidden="true"></div>
    <div class="footer-container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">
                    <div class="footer-logo-circle">
                        <img src="{{ $profil->logo_url ?? asset('images/logo.png') }}" alt="{{ $namaFooter }}" width="48" height="48">
                    </div>
                    <div>
                        <div class="footer-brand-text">{{ $namaFooter }}</div>
                        <div class="footer-brand-sub">{{ $profil->alamat ?? 'Tulungagung, Jawa Timur' }}</div>
                    </div>
                </div>
                <p class="footer-desc">
                    {{ $profil->teks_tentang_kami ?? 'Angkringan Sekota hadir dari kecintaan kami pada suasana sederhana, rasa yang jujur, dan obrolan yang tak ada habisnya.' }}
                </p>
            </div>

            <div class="footer-col">
                <h5>Social Media</h5>
                <ul class="footer-social-list">
                    <li>
                        <a class="footer-social-link" href="{{ $fbUrl }}" target="_blank" rel="noopener noreferrer">
                            <span class="footer-social-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M14 13.5h2.5l.5-3H14V8.75c0-.83.17-1.25 1.34-1.25H17V5.07C16.67 5.03 15.77 5 14.71 5 12.45 5 11 6.29 11 8.54V10.5H8.5v3H11V19h3v-5.5z"/></svg>
                            </span>
                            <span class="footer-social-text">
                                <strong>Facebook</strong>
                                <span>{{ $fbLabel }}</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="footer-social-link" href="{{ $ttUrl }}" target="_blank" rel="noopener noreferrer">
                            <span class="footer-social-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.08v13.67a2.89 2.89 0 01-2.88 2.74 2.9 2.9 0 01-2.89-2.9 2.89 2.89 0 012.89-2.89c.28 0 .55.04.81.1v-3.15a6.03 6.03 0 00-.81-.05A6.01 6.01 0 003 15.66a6.01 6.01 0 006.01 6.01 6.01 6.01 0 006.01-6.01V8.68a7.85 7.85 0 004.57 1.46V7.05a4.85 4.85 0 01-1.99-.36z"/></svg>
                            </span>
                            <span class="footer-social-text">
                                <strong>TikTok</strong>
                                <span>{{ '@' . $ttHandle }}</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="footer-social-link" href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer">
                            <span class="footer-social-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 01-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 017.8 2zm-.2 2A3.6 3.6 0 004 7.6v8.8A3.6 3.6 0 007.6 20h8.8a3.6 3.6 0 003.6-3.6V7.6A3.6 3.6 0 0016.4 4H7.6zm9.65 1.5a1.25 1.25 0 110 2.5 1.25 1.25 0 010-2.5zM12 7a5 5 0 110 10 5 5 0 010-10zm0 2a3 3 0 100 6 3 3 0 000-6z"/></svg>
                            </span>
                            <span class="footer-social-text">
                                <strong>Instagram</strong>
                                <span>{{ '@' . $igHandle }}</span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Kontak &amp; Info</h5>
                <div class="footer-contact-item">
                    <span class="footer-contact-label">Alamat</span>
                    <span>{{ $profil->alamat ?? 'Tulungagung, Jawa Timur' }}</span>
                </div>
                <div class="footer-contact-item">
                    <span class="footer-contact-label">Jam buka</span>
                    <span>{{ $profil->jam_buka ?? '17.00 - 23.00 WIB' }}</span>
                </div>
                @if(!empty($profil->whatsapp))
                <div class="footer-contact-item">
                    <span class="footer-contact-label">WhatsApp</span>
                    <span>{{ $profil->whatsapp }}</span>
                </div>
                @endif
                @if(!empty($profil->email))
                <div class="footer-contact-item">
                    <span class="footer-contact-label">Email</span>
                    <span>{{ $profil->email }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ $namaFooter }}. Semua hak cipta dilindungi.</p>
        </div>
    </div>
</footer>

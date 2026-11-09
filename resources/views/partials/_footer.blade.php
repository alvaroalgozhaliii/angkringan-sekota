<footer class="footer">
    <div class="footer-container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">
                    <div class="footer-logo-circle">
                        <img src="{{ $profil->logo_url ?? asset('images/logo.png') }}" alt="{{ $profil->nama_usaha ?? 'Angkringan Sekota' }}">
                    </div>
                    <div>
                        <div class="footer-brand-text">{{ $profil->nama_usaha ?? 'Angkringan Sekota' }}</div>
                        <div class="footer-brand-sub">{{ $profil->alamat ?? 'Tulungagung, Jawa Timur' }}</div>
                    </div>
                </div>
                <p class="footer-desc">
                    {{ $profil->teks_tentang_kami ?? 'Angkringan Sekota hadir dari kecintaan kami pada suasana sederhana, rasa yang jujur, dan obrolan yang tak ada habisnya.' }}
                </p>
            </div>

            <div class="footer-col">
                <h5>Navigasi</h5>
                <ul class="footer-links">
                    <li><a onclick="goTo('beranda')"><span>→</span> Beranda</a></li>
                    <li><a onclick="goTo('menu')"><span>→</span> Menu Kami</a></li>
                    <li><a onclick="goTo('tentang')"><span>→</span> Tentang Kami</a></li>
                    <li><a onclick="goTo('kontak')"><span>→</span> Lokasi &amp; Kontak</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Kontak &amp; Info</h5>
                <div class="footer-contact-item">
                    <span class="ico">📍</span>
                    <span>{{ $profil->alamat ?? 'Tulungagung, Jawa Timur' }}</span>
                </div>
                <div class="footer-contact-item">
                    <span class="ico">🕐</span>
                    <span>{{ $profil->jam_buka ?? '17.00 - 23.00 WIB' }}</span>
                </div>
                @if(!empty($profil->whatsapp))
                <div class="footer-contact-item">
                    <span class="ico">💬</span>
                    <span>{{ $profil->whatsapp }}</span>
                </div>
                @endif
                @if(!empty($profil->email))
                <div class="footer-contact-item">
                    <span class="ico">✉️</span>
                    <span>{{ $profil->email }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ $profil->nama_usaha ?? 'Angkringan Sekota' }}. Semua hak cipta dilindungi.</p>
            <div class="footer-socials">
                @if(!empty($profil->instagram))
                <a href="https://instagram.com/{{ ltrim($profil->instagram, '@') }}" target="_blank" class="social-btn" title="Instagram">📸</a>
                @endif
                @if(!empty($profil->facebook))
                <a href="#" class="social-btn" title="Facebook">📘</a>
                @endif
                @if(!empty($profil->whatsapp))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->whatsapp) }}" target="_blank" class="social-btn" title="WhatsApp">💬</a>
                @endif
            </div>
        </div>
    </div>
</footer>

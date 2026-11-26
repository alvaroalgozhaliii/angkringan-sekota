const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');
const backdrop = document.getElementById('sidebarBackdrop');
const kurangiGerak = window.matchMedia('(prefers-reduced-motion: reduce)');

const setSidebar = (open) => {
  if (!sidebar) return;
  sidebar.classList.toggle('open', open);
  backdrop?.classList.toggle('show', open);
  document.body.classList.toggle('sidebar-open', open);
  hamburger?.setAttribute('aria-expanded', String(open));
  if (open && window.matchMedia('(max-width: 900px)').matches) {
    sidebar.querySelector('a')?.focus();
  }
};

hamburger?.addEventListener('click', (e) => {
  e.stopPropagation();
  setSidebar(!sidebar.classList.contains('open'));
});
backdrop?.addEventListener('click', () => setSidebar(false));
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && sidebar?.classList.contains('open')) {
    setSidebar(false);
    hamburger?.focus();
  }
});
document.addEventListener('click', (e) => {
  if (sidebar?.classList.contains('open') && !sidebar.contains(e.target) && !hamburger?.contains(e.target)) {
    setSidebar(false);
  }
});

function updateClock() {
  const timeEl = document.getElementById('clockTime');
  const dateEl = document.getElementById('clockDate');
  if (!timeEl || !dateEl) return;

  const sekarang = new Date();
  timeEl.textContent = new Intl.DateTimeFormat('id-ID', {
    timeZone: 'Asia/Jakarta',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  }).format(sekarang) + ' WIB';

  dateEl.textContent = new Intl.DateTimeFormat('id-ID', {
    timeZone: 'Asia/Jakarta',
    weekday: 'short',
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  }).format(sekarang);
}
updateClock();
setInterval(updateClock, 1000);

(function () {
  const nav = document.getElementById('sidebarNav');
  const indicator = document.getElementById('sidebarIndicator');
  if (!nav || !indicator) return;

  const links = Array.from(nav.querySelectorAll('a'));
  let activeLink = nav.querySelector('a.active');

  function positionIndicator(target, animate = true) {
    if (!target) {
      indicator.style.opacity = '0';
      return;
    }
    const top = target.offsetTop;
    const height = target.offsetHeight;
    const gerak = animate && !kurangiGerak.matches;

    indicator.style.transition = gerak
      ? 'transform .35s cubic-bezier(.22,1,.36,1), height .25s cubic-bezier(.22,1,.36,1), opacity .18s ease'
      : 'none';
    indicator.style.transform = `translateY(${top}px)`;
    indicator.style.height = `${height}px`;
    indicator.style.opacity = '1';
  }

  const prevTop = sessionStorage.getItem('sekota_nav_prev_top');
  const prevHeight = sessionStorage.getItem('sekota_nav_prev_height');
  sessionStorage.removeItem('sekota_nav_prev_top');
  sessionStorage.removeItem('sekota_nav_prev_height');

  if (activeLink) {
    if (!kurangiGerak.matches && prevTop !== null && prevHeight !== null && Math.abs(Number(prevTop) - activeLink.offsetTop) > 4) {
      indicator.style.transition = 'none';
      indicator.style.transform = `translateY(${Number(prevTop)}px)`;
      indicator.style.height = `${Number(prevHeight)}px`;
      indicator.style.opacity = '1';
      requestAnimationFrame(() => {
        requestAnimationFrame(() => positionIndicator(activeLink, true));
      });
    } else {
      positionIndicator(activeLink, false);
    }
  }

  links.forEach((link) => {
    link.addEventListener('click', (e) => {
      if (e.metaKey || e.ctrlKey || e.button !== 0 || link.target === '_blank' || link.classList.contains('active')) {
        return;
      }
      const targetUrl = link.href;
      if (!targetUrl || targetUrl.startsWith('#')) return;

      e.preventDefault();
      const currentOriginTop = activeLink ? activeLink.offsetTop : link.offsetTop;
      const currentOriginHeight = activeLink ? activeLink.offsetHeight : link.offsetHeight;
      sessionStorage.setItem('sekota_nav_prev_top', String(currentOriginTop));
      sessionStorage.setItem('sekota_nav_prev_height', String(currentOriginHeight));
      positionIndicator(link, true);

      if (activeLink) activeLink.classList.remove('active');
      link.classList.add('active');
      activeLink = link;

      if (kurangiGerak.matches) {
        window.location.href = targetUrl;
        return;
      }

      const content = document.querySelector('.content');
      if (content) {
        content.style.transition = 'opacity .2s ease, transform .2s ease';
        content.style.opacity = '0.35';
        content.style.transform = 'translateY(-4px)';
      }
      setTimeout(() => {
        window.location.href = targetUrl;
      }, 200);
    });
  });

  window.addEventListener('resize', () => {
    if (activeLink) positionIndicator(activeLink, false);
  });
})();

if (!kurangiGerak.matches) {
  document.querySelectorAll('.btn-row a, .page-header a').forEach((link) => {
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      if (!href || href.startsWith('#') || link.target === '_blank' || e.ctrlKey || e.metaKey || link.classList.contains('active')) {
        return;
      }
      const content = document.querySelector('.content');
      if (content) {
        content.style.transition = 'opacity .18s ease, transform .18s ease';
        content.style.opacity = '0.35';
        content.style.transform = 'translateY(-4px)';
      }
    });
  });
}

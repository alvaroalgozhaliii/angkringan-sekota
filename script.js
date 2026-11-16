// script.js – Employee Dashboard functionality
// Uses localStorage for mock data persistence.

(() => {
  // ---------- Data Layer ----------
  const STORAGE_KEY = {
    products: 'dashboard_products',
    categories: 'dashboard_categories',
    dailyInfo: 'dashboard_dailyInfo',
    messages: 'dashboard_messages',
    profile: 'dashboard_profile',
  };

  // Initialize data if not present
  function initData() {
    if (!localStorage.getItem(STORAGE_KEY.products)) {
      const sampleProducts = [];
      localStorage.setItem(STORAGE_KEY.products, JSON.stringify(sampleProducts));
    }
    if (!localStorage.getItem(STORAGE_KEY.categories)) {
      const sampleCategories = [{ id: generateId(), name: 'Makanan' }];
      localStorage.setItem(STORAGE_KEY.categories, JSON.stringify(sampleCategories));
    }
    if (!localStorage.getItem(STORAGE_KEY.dailyInfo)) {
      const dailyInfo = { hours: '', promo: '' };
      localStorage.setItem(STORAGE_KEY.dailyInfo, JSON.stringify(dailyInfo));
    }
    if (!localStorage.getItem(STORAGE_KEY.messages)) {
      const messages = [];
      localStorage.setItem(STORAGE_KEY.messages, JSON.stringify(messages));
    }
    if (!localStorage.getItem(STORAGE_KEY.profile)) {
      const profile = { name: 'Pegawai', email: 'pegawai@example.com', password: '' };
      localStorage.setItem(STORAGE_KEY.profile, JSON.stringify(profile));
    }
  }

  // Utility helpers
  function generateId() {
    return '_' + Math.random().toString(36).substr(2, 9);
  }
  function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
  }

  // ---------- Storage Access ----------
  const store = {
    getProducts() { return JSON.parse(localStorage.getItem(STORAGE_KEY.products) || '[]'); },
    setProducts(arr) { localStorage.setItem(STORAGE_KEY.products, JSON.stringify(arr)); },
    getCategories() { return JSON.parse(localStorage.getItem(STORAGE_KEY.categories) || '[]'); },
    setCategories(arr) { localStorage.setItem(STORAGE_KEY.categories, JSON.stringify(arr)); },
    getDailyInfo() { return JSON.parse(localStorage.getItem(STORAGE_KEY.dailyInfo) || '{}'); },
    setDailyInfo(obj) { localStorage.setItem(STORAGE_KEY.dailyInfo, JSON.stringify(obj)); },
    getMessages() { return JSON.parse(localStorage.getItem(STORAGE_KEY.messages) || '[]'); },
    setMessages(arr) { localStorage.setItem(STORAGE_KEY.messages, JSON.stringify(arr)); },
    getProfile() { return JSON.parse(localStorage.getItem(STORAGE_KEY.profile) || '{}'); },
    setProfile(obj) { localStorage.setItem(STORAGE_KEY.profile, JSON.stringify(obj)); },
  };

  // ---------- UI Rendering ----------
  function renderDashboard() {
    const tasks = 0; // Placeholder, can be extended later
    document.getElementById('task-count').textContent = tasks;
    document.getElementById('product-count').textContent = store.getProducts().length;
    document.getElementById('message-count').textContent = store.getMessages().length;
  }

  function renderProducts() {
    const tbody = document.querySelector('#product-table tbody');
    tbody.innerHTML = '';
    const products = store.getProducts();
    const categories = store.getCategories();
    products.forEach(p => {
      const tr = document.createElement('tr');
      const imgTd = document.createElement('td');
      const img = document.createElement('img');
      img.src = p.photo || 'https://via.placeholder.com/60';
      img.style.width = '60px';
      img.style.height = '60px';
      img.style.objectFit = 'cover';
      imgTd.appendChild(img);

      const nameTd = document.createElement('td');
      nameTd.textContent = p.name;
      const catTd = document.createElement('td');
      const cat = categories.find(c => c.id === p.categoryId);
      catTd.textContent = cat ? cat.name : '-';
      const priceTd = document.createElement('td');
      priceTd.textContent = formatCurrency(p.price);
      const bestTd = document.createElement('td');
      const bestSwitch = document.createElement('input');
      bestSwitch.type = 'checkbox';
      bestSwitch.checked = p.bestSeller;
      bestSwitch.onchange = () => { p.bestSeller = bestSwitch.checked; store.setProducts(products); };
      bestTd.appendChild(bestSwitch);
      const statusTd = document.createElement('td');
      const statusSelect = document.createElement('select');
      ['Tersedia', 'Habis'].forEach(opt => {
        const o = document.createElement('option');
        o.value = opt;
        o.textContent = opt;
        if (p.status === opt) o.selected = true;
        statusSelect.appendChild(o);
      });
      statusSelect.onchange = () => { p.status = statusSelect.value; store.setProducts(products); };
      statusTd.appendChild(statusSelect);
      const actionTd = document.createElement('td');
      const editBtn = document.createElement('button');
      editBtn.textContent = 'Edit';
      editBtn.className = 'btn secondary';
      editBtn.onclick = () => openProductModal(p);
      const delBtn = document.createElement('button');
      delBtn.textContent = 'Hapus';
      delBtn.className = 'btn secondary';
      delBtn.style.marginLeft = '0.5rem';
      delBtn.onclick = () => {
        if (confirm('Yakin hapus produk ini?')) {
          const idx = products.findIndex(prod => prod.id === p.id);
          products.splice(idx, 1);
          store.setProducts(products);
          renderProducts();
          renderDashboard();
        }
      };
      actionTd.appendChild(editBtn);
      actionTd.appendChild(delBtn);

      tr.appendChild(imgTd);
      tr.appendChild(nameTd);
      tr.appendChild(catTd);
      tr.appendChild(priceTd);
      tr.appendChild(bestTd);
      tr.appendChild(statusTd);
      tr.appendChild(actionTd);
      tbody.appendChild(tr);
    });
  }

  function renderCategories() {
    const tbody = document.querySelector('#category-table tbody');
    tbody.innerHTML = '';
    const categories = store.getCategories();
    categories.forEach(c => {
      const tr = document.createElement('tr');
      const nameTd = document.createElement('td');
      nameTd.textContent = c.name;
      const actionTd = document.createElement('td');
      const editBtn = document.createElement('button');
      editBtn.textContent = 'Edit';
      editBtn.className = 'btn secondary';
      editBtn.onclick = () => openCategoryModal(c);
      actionTd.appendChild(editBtn);
      tr.appendChild(nameTd);
      tr.appendChild(actionTd);
      tbody.appendChild(tr);
    });
  }

  function renderDailyInfo() {
    const info = store.getDailyInfo();
    document.getElementById('operational-hours').value = info.hours || '';
    document.getElementById('promo-text').value = info.promo || '';
  }

  function renderMessages() {
    const list = document.getElementById('message-list');
    list.innerHTML = '';
    const msgs = store.getMessages();
    msgs.forEach(m => {
      const li = document.createElement('li');
      li.className = 'message-item';
      const title = document.createElement('h4');
      title.textContent = m.subject || 'Pesan';
      const body = document.createElement('p');
      body.textContent = m.body;
      const reply = document.createElement('textarea');
      reply.placeholder = 'Balas (opsional)';
      reply.value = m.reply || '';
      reply.style.width = '100%';
      reply.onblur = () => {
        m.reply = reply.value;
        store.setMessages(msgs);
      };
      li.appendChild(title);
      li.appendChild(body);
      li.appendChild(reply);
      list.appendChild(li);
    });
  }

  function renderProfile() {
    const profile = store.getProfile();
    document.getElementById('profile-name').value = profile.name || '';
    document.getElementById('profile-email').value = profile.email || '';
    document.getElementById('profile-password').value = '';
  }

  // ---------- Modals (simple prompt based) ----------
  function openProductModal(product = null) {
    const isEdit = !!product;
    const name = prompt(isEdit ? 'Ubah nama produk:' : 'Nama produk baru:', product?.name || '');
    if (!name) return;
    const priceStr = prompt('Harga (angka):', product?.price || '0');
    const price = parseFloat(priceStr.replace(/[^0-9.-]+/g, '')) || 0;
    const categories = store.getCategories();
    const catOptions = categories.map(c => `${c.id}:${c.name}`).join('\n');
    const catId = prompt(`Pilih kategori (format id)\n${catOptions}`, product?.categoryId || (categories[0]?.id || ''));
    const desc = prompt('Deskripsi (opsional):', product?.description || '');
    const photo = prompt('URL foto (opsional):', product?.photo || '');
    const status = prompt('Status (Tersedia/Habis):', product?.status || 'Tersedia');
    const best = confirm('Jadikan Best Seller?');

    const prodData = {
      id: product?.id || generateId(),
      name,
      price,
      categoryId: catId,
      description: desc,
      photo,
      status: status === 'Habis' ? 'Habis' : 'Tersedia',
      bestSeller: best,
    };

    const products = store.getProducts();
    if (isEdit) {
      const idx = products.findIndex(p => p.id === product.id);
      products[idx] = prodData;
    } else {
      products.push(prodData);
    }
    store.setProducts(products);
    renderProducts();
    renderDashboard();
  }

  function openCategoryModal(cat = null) {
    const isEdit = !!cat;
    const name = prompt(isEdit ? 'Ubah nama kategori:' : 'Nama kategori baru:', cat?.name || '');
    if (!name) return;
    const categories = store.getCategories();
    if (isEdit) {
      const idx = categories.findIndex(c => c.id === cat.id);
      categories[idx].name = name;
    } else {
      categories.push({ id: generateId(), name });
    }
    store.setCategories(categories);
    renderCategories();
    renderProducts(); // refresh product category names
  }

  // ---------- Routing ----------
  function activateSection(sectionId) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');
    // Update nav active state
    document.querySelectorAll('.nav-link').forEach(a => a.classList.toggle('active', a.dataset.section === sectionId));
    // Section‑specific rendering
    switch (sectionId) {
      case 'dashboard': renderDashboard(); break;
      case 'products': renderProducts(); break;
      case 'categories': renderCategories(); break;
      case 'dailyInfo': renderDailyInfo(); break;
      case 'messages': renderMessages(); break;
      case 'profile': renderProfile(); break;
    }
  }

  function initRouting() {
    window.addEventListener('hashchange', () => {
      const hash = location.hash.replace('#', '').split('/')[0] || 'dashboard';
      activateSection(hash);
    });
    // Initial load
    const initial = location.hash.replace('#', '').split('/')[0] || 'dashboard';
    activateSection(initial);
  }

  // ---------- Event Listeners ----------
  function bindEvents() {
    document.getElementById('add-product-btn').addEventListener('click', () => openProductModal());
    document.getElementById('add-category-btn').addEventListener('click', () => openCategoryModal());
    document.getElementById('daily-form').addEventListener('submit', e => {
      e.preventDefault();
      const hours = document.getElementById('operational-hours').value.trim();
      const promo = document.getElementById('promo-text').value.trim();
      store.setDailyInfo({ hours, promo });
      alert('Info harian disimpan');
    });
    document.getElementById('profile-form').addEventListener('submit', e => {
      e.preventDefault();
      const name = document.getElementById('profile-name').value.trim();
      const email = document.getElementById('profile-email').value.trim();
      const password = document.getElementById('profile-password').value;
      const profile = store.getProfile();
      profile.name = name;
      profile.email = email;
      if (password) profile.password = password; // mock; not hashed
      store.setProfile(profile);
      alert('Profil diperbarui');
    });
    document.getElementById('logout-btn').addEventListener('click', () => {
      // Simple mock logout – clear a flag if you had one
      alert('Logout berhasil (mock)');
    });
  }

  // ---------- Init ----------
  function init() {
    initData();
    initRouting();
    bindEvents();
  }

  // Run when DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();

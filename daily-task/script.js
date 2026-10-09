/* ============================================================
   Daily Task - script.js
   Tanpa framework & tanpa backend. Data disimpan di localStorage
   dengan key "daily_tasks". Aman XSS: judul tugas selalu
   dirender dengan textContent, bukan innerHTML.

   Bagian:
   1) KONSTANTA & STATE
   2) HELPERS (tanggal lokal, validasi, ID unik)
   3) STORAGE (load / sanitasi / simpan)
   4) FITUR WAJIB (tambah, edit, hapus, checkbox, filter,
      navigasi tanggal, urutan, progres, render, empty state)
   5) FITUR TAMBAHAN (tema, ekspor/impor, pencarian, notifikasi)
   6) INIT & EVENT BINDING
   ============================================================ */

'use strict';

/* ============ 1) KONSTANTA & STATE ============ */

const STORAGE_KEY = 'daily_tasks';
const THEME_KEY = 'daily_task_theme';
const REMINDER_KEY = 'daily_task_reminder';

const CATEGORIES = ['Kerja', 'Pribadi', 'Belajar'];
const PRIORITIES = ['low', 'medium', 'high'];
const PRIORITY_RANK = { high: 0, medium: 1, low: 2 };
const PRIORITY_LABEL = { high: 'Tinggi', medium: 'Sedang', low: 'Rendah' };

/** State aplikasi (sumber kebenaran = localStorage, state hanya cache). */
let tasks = [];
let currentDate = '';      // YYYY-MM-DD (lokal)
let currentFilter = 'semua'; // semua | aktif | selesai
let searchQuery = '';
const notifiedIds = new Set(); // id yang sudah dinotifikasi sesi ini (cegah spam)
let reminderTimer = null;

/* ============ 2) HELPERS ============ */

/** Tanggal lokal YYYY-MM-DD — TIDAK pakai toISOString (aman lintas zona waktu). */
function toLocalISO(date) {
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, '0');
  const d = String(date.getDate()).padStart(2, '0');
  return `${y}-${m}-${d}`;
}

function todayStr() {
  return toLocalISO(new Date());
}

function shiftDate(iso, days) {
  // Parse manual agar tidak terkena bug TZ pada new Date('YYYY-MM-DD')
  const [y, m, d] = iso.split('-').map(Number);
  const dt = new Date(y, m - 1, d);
  dt.setDate(dt.getDate() + days);
  return toLocalISO(dt);
}

/** Format tanggal Indonesia: "Senin, 12 Oktober 2026". */
function formatDateID(iso) {
  const [y, m, d] = iso.split('-').map(Number);
  const dt = new Date(y, m - 1, d);
  return dt.toLocaleDateString('id-ID', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
  });
}

/** ID unik sederhana (tanpa library). */
function genId() {
  return 't_' + Date.now().toString(36) + '_' + Math.random().toString(36).slice(2, 8);
}

function isValidISODate(s) {
  if (typeof s !== 'string' || !/^\d{4}-\d{2}-\d{2}$/.test(s)) return false;
  const [y, m, d] = s.split('-').map(Number);
  const dt = new Date(y, m - 1, d);
  return dt.getFullYear() === y && dt.getMonth() === m - 1 && dt.getDate() === d;
}

function isValidTime(s) {
  return typeof s === 'string' && /^([01]\d|2[0-3]):[0-5]\d$/.test(s);
}

/* ============ 3) STORAGE ============ */

/**
 * Baca & sanitasi data. Menangani kasus tepi:
 * localStorage kosong, rusak, atau struktur tidak sesuai.
 * Item tidak valid dibuang (bukan error fatal).
 */
function loadTasks() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (raw === null || raw === '') return [];
    const parsed = JSON.parse(raw);
    if (!Array.isArray(parsed)) return [];
    return parsed
      .map(sanitizeTask)
      .filter((t) => t !== null);
  } catch (err) {
    // JSON rusak / storage tidak terbaca → mulai dengan daftar kosong.
    console.warn('Gagal membaca localStorage, memakai daftar kosong.', err);
    return [];
  }
}

/** Validasi & rapikan satu objek tugas → null jika tidak valid. */
function sanitizeTask(item) {
  if (typeof item !== 'object' || item === null) return null;
  if (typeof item.title !== 'string' || item.title.trim() === '') return null;
  if (!isValidISODate(item.date)) return null;
  return {
    id: typeof item.id === 'string' && item.id ? item.id : genId(),
    title: item.title.trim().slice(0, 200),
    date: item.date,
    time: isValidTime(item.time) ? item.time : '',
    priority: PRIORITIES.includes(item.priority) ? item.priority : 'medium',
    category: CATEGORIES.includes(item.category) ? item.category : 'Pribadi',
    done: item.done === true,
  };
}

/** Simpan ke localStorage. Kegagalan (kuota penuh) ditangani tanpa crash. */
function saveTasks() {
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(tasks));
    return true;
  } catch (err) {
    console.error('Gagal menyimpan ke localStorage.', err);
    showToast('Gagal menyimpan data (storage penuh?)', true);
    return false;
  }
}

/* ============ 4) FITUR WAJIB ============ */

/** Ambil tugas sesuai tanggal, filter status, pencarian, lalu urutkan. */
function getVisibleTasks() {
  let list = tasks.filter((t) => t.date === currentDate);

  if (currentFilter === 'aktif') list = list.filter((t) => !t.done);
  if (currentFilter === 'selesai') list = list.filter((t) => t.done);

  if (searchQuery) {
    const q = searchQuery.toLowerCase();
    list = list.filter((t) => t.title.toLowerCase().includes(q));
  }

  // Urut: berjam dulu menaik; tanpa jam di bawah; tie-break prioritas.
  list.sort((a, b) => {
    if (a.time && !b.time) return -1;
    if (!a.time && b.time) return 1;
    if (a.time !== b.time) return a.time.localeCompare(b.time);
    return PRIORITY_RANK[a.priority] - PRIORITY_RANK[b.priority];
  });

  return list;
}

/** Render seluruh UI: judul tanggal, daftar, progres, empty state. */
function render() {
  // Tanggal
  const dateLabel = document.getElementById('date-label');
  dateLabel.textContent = formatDateID(currentDate);
  const dateInput = document.getElementById('date-input');
  if (dateInput.value !== currentDate) dateInput.value = currentDate;

  // Daftar tugas
  const listEl = document.getElementById('task-list');
  listEl.textContent = ''; // bersihkan tanpa innerHTML

  const dayTasks = tasks.filter((t) => t.date === currentDate);
  const visible = getVisibleTasks();

  visible.forEach((task) => listEl.appendChild(buildTaskItem(task)));

  // Progres harian (dihitung dari seluruh tugas hari ini, bukan hasil filter)
  const total = dayTasks.length;
  const done = dayTasks.filter((t) => t.done).length;
  const pct = total === 0 ? 0 : Math.round((done / total) * 100);
  document.getElementById('progress-fill').style.width = pct + '%';
  document.getElementById('progress-text').textContent = `${done} dari ${total} tugas selesai`;
  document.querySelector('.progress-bar').setAttribute('aria-valuenow', String(pct));

  // Empty state (ramah & kontekstual)
  const emptyEl = document.getElementById('empty-state');
  const emptyTitle = document.getElementById('empty-title');
  const emptyDesc = document.getElementById('empty-desc');
  if (visible.length === 0) {
    emptyEl.hidden = false;
    if (searchQuery) {
      emptyTitle.textContent = 'Tidak ada hasil';
      emptyDesc.textContent = `Tidak ada tugas yang cocok dengan kata kunci "${searchQuery}".`;
    } else if (total > 0) {
      emptyTitle.textContent = 'Tidak ada tugas di filter ini';
      emptyDesc.textContent = 'Coba ganti filter status untuk melihat tugas lain.';
    } else if (currentDate === todayStr()) {
      emptyTitle.textContent = 'Belum ada tugas hari ini';
      emptyDesc.textContent = 'Tambahkan tugas pertama Anda dengan tombol “＋ Tambah Tugas”.';
    } else {
      emptyTitle.textContent = 'Tidak ada tugas pada tanggal ini';
      emptyDesc.textContent = 'Navigasi ke tanggal lain atau tambah tugas baru.';
    }
  } else {
    emptyEl.hidden = true;
  }

  // Sinkronkan tombol filter aktif
  document.querySelectorAll('.filter-btn').forEach((btn) => {
    btn.classList.toggle('active', btn.dataset.filter === currentFilter);
  });
}

/** Bangun satu elemen tugas. Judul via textContent → aman dari XSS. */
function buildTaskItem(task) {
  const li = document.createElement('li');
  li.className = 'task-item' + (task.done ? ' done' : '');

  // Checkbox selesai/belum
  const cb = document.createElement('input');
  cb.type = 'checkbox';
  cb.checked = task.done;
  cb.setAttribute('aria-label', 'Tandai selesai');
  cb.addEventListener('change', () => toggleDone(task.id));

  const body = document.createElement('div');
  body.className = 'task-body';

  // Judul tugas — textContent, bukan innerHTML (perlindungan XSS)
  const title = document.createElement('span');
  title.className = 'task-title';
  title.textContent = task.title;
  body.appendChild(title);

  // Meta: jam, prioritas, kategori
  const meta = document.createElement('div');
  meta.className = 'task-meta';

  if (task.time) {
    meta.appendChild(makeChip('🕒 ' + task.time, 'chip chip-time'));
  }
  meta.appendChild(
    makeChip(PRIORITY_LABEL[task.priority], 'chip chip-priority p-' + task.priority)
  );
  meta.appendChild(makeChip(task.category, 'chip'));
  if (task.done) meta.appendChild(makeChip('Selesai', 'chip'));

  body.appendChild(meta);

  // Tombol edit & hapus
  const actions = document.createElement('div');
  actions.className = 'task-actions';

  const btnEdit = document.createElement('button');
  btnEdit.type = 'button';
  btnEdit.className = 'icon-btn';
  btnEdit.title = 'Edit tugas';
  btnEdit.setAttribute('aria-label', 'Edit tugas');
  btnEdit.textContent = '✏️';
  btnEdit.addEventListener('click', () => openForm(task.id));

  const btnDel = document.createElement('button');
  btnDel.type = 'button';
  btnDel.className = 'icon-btn danger';
  btnDel.title = 'Hapus tugas';
  btnDel.setAttribute('aria-label', 'Hapus tugas');
  btnDel.textContent = '🗑️';
  btnDel.addEventListener('click', () => deleteTask(task.id));

  actions.appendChild(btnEdit);
  actions.appendChild(btnDel);

  li.appendChild(cb);
  li.appendChild(body);
  li.appendChild(actions);
  return li;
}

function makeChip(text, className) {
  const span = document.createElement('span');
  span.className = className;
  span.textContent = text; // aman dari XSS
  return span;
}

/** Tambah atau perbarui tugas dari form. Validasi: judul wajib. */
function handleSubmit(event) {
  event.preventDefault();

  const id = document.getElementById('in-id').value;
  const title = document.getElementById('in-title').value.trim();
  const date = document.getElementById('in-date').value;
  const time = document.getElementById('in-time').value;
  const priority = document.getElementById('in-priority').value;
  const category = document.getElementById('in-category').value;

  // Kasus tepi: input kosong
  if (!title) {
    showFormError('Judul tugas wajib diisi.');
    return;
  }
  if (!isValidISODate(date)) {
    showFormError('Tanggal tidak valid.');
    return;
  }

  if (id) {
    // Mode edit: pertahankan status done
    const task = tasks.find((t) => t.id === id);
    if (task) {
      task.title = title;
      task.date = date;
      task.time = time;
      task.priority = priority;
      task.category = category;
    }
    showToast('Tugas diperbarui.');
  } else {
    // Mode tambah
    tasks.push({
      id: genId(),
      title,
      date,
      time,
      priority,
      category,
      done: false,
    });
    showToast('Tugas ditambahkan.');
  }

  saveTasks();
  closeForm();
  currentDate = date; // pindah ke tanggal tugas agar langsung terlihat
  render();
}

/** Hapus tugas dengan konfirmasi. */
function deleteTask(id) {
  const task = tasks.find((t) => t.id === id);
  if (!task) return;
  const ok = confirm(`Hapus tugas "${task.title}"? Tindakan ini tidak bisa dibatalkan.`);
  if (!ok) return;
  tasks = tasks.filter((t) => t.id !== id);
  saveTasks();
  render();
  showToast('Tugas dihapus.');
}

/** Toggle selesai/belum selesai. */
function toggleDone(id) {
  const task = tasks.find((t) => t.id === id);
  if (!task) return;
  task.done = !task.done;
  if (task.done) notifiedIds.delete(id); // izinkan notifikasi lagi jika diulang
  saveTasks();
  render();
}

/* ============ 5) FITUR TAMBAHAN ============ */

/* ---- Form (buka/tutup) ---- */
function openForm(id) {
  const form = document.getElementById('task-form');
  form.reset();
  hideFormError();

  const titleEl = document.getElementById('form-title');
  const idEl = document.getElementById('in-id');

  if (id) {
    const task = tasks.find((t) => t.id === id);
    if (!task) return;
    titleEl.textContent = 'Edit Tugas';
    idEl.value = task.id;
    document.getElementById('in-title').value = task.title;
    document.getElementById('in-date').value = task.date;
    document.getElementById('in-time').value = task.time;
    document.getElementById('in-priority').value = task.priority;
    document.getElementById('in-category').value = task.category;
  } else {
    titleEl.textContent = 'Tambah Tugas';
    idEl.value = '';
    document.getElementById('in-date').value = currentDate || todayStr();
    document.getElementById('in-priority').value = 'medium';
    document.getElementById('in-category').value = 'Pribadi';
  }

  document.getElementById('modal').hidden = false;
  document.getElementById('in-title').focus();
}

function closeForm() {
  document.getElementById('modal').hidden = true;
}

function showFormError(msg) {
  const el = document.getElementById('form-error');
  el.textContent = msg;
  el.hidden = false;
}

function hideFormError() {
  const el = document.getElementById('form-error');
  el.textContent = '';
  el.hidden = true;
}

/* ---- Mode gelap/terang (preferensi disimpan) ---- */
function applyTheme(theme) {
  document.documentElement.classList.toggle('dark', theme === 'dark');
  document.getElementById('btn-theme').textContent = theme === 'dark' ? '☀️' : '🌙';
}

function toggleTheme() {
  const isDark = document.documentElement.classList.contains('dark');
  const next = isDark ? 'light' : 'dark';
  applyTheme(next);
  try {
    localStorage.setItem(THEME_KEY, next);
  } catch (err) {
    console.warn('Gagal menyimpan tema.', err);
  }
}

/* ---- Ekspor ke file JSON ---- */
function exportData() {
  try {
    const blob = new Blob([JSON.stringify(tasks, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `daily_tasks_${todayStr()}.json`;
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
    showToast('Data diekspor ke JSON.');
  } catch (err) {
    console.error(err);
    showToast('Gagal mengekspor data.', true);
  }
}

/* ---- Impor dari file JSON (dengan validasi format) ---- */
function importFromFile(file) {
  const reader = new FileReader();
  reader.onload = () => {
    let parsed;
    try {
      parsed = JSON.parse(reader.result);
    } catch (err) {
      showToast('Gagal: file bukan JSON yang valid.', true);
      return;
    }
    if (!Array.isArray(parsed)) {
      showToast('Gagal: format harus berupa daftar tugas (array).', true);
      return;
    }

    // Validasi & sanitasi tiap item; satu saja tidak valid → tolak seluruh file.
    const imported = [];
    for (let i = 0; i < parsed.length; i++) {
      const clean = sanitizeTask(parsed[i]);
      if (!clean) {
        showToast(`Gagal: tugas ke-${i + 1} tidak valid (judul/tanggal salah).`, true);
        return;
      }
      imported.push(clean);
    }

    const ok = confirm(
      `Impor ${imported.length} tugas? Data saat ini (${tasks.length} tugas) akan DIGANTI.`
    );
    if (!ok) return;

    tasks = imported;
    saveTasks();
    render();
    showToast(`Berhasil mengimpor ${imported.length} tugas.`);
  };
  reader.onerror = () => showToast('Gagal membaca file.', true);
  reader.readAsText(file);
}

/* ---- Pengingat dengan Notification API ---- */
/** Pengingat aktif = izin granted + preferensi pengguna tersimpan ('1'). */
function isReminderOn() {
  if (!('Notification' in window) || Notification.permission !== 'granted') return false;
  try {
    return localStorage.getItem(REMINDER_KEY) === '1';
  } catch (err) {
    return false;
  }
}

function updateReminderUI() {
  const supported = 'Notification' in window;
  const toggle = document.getElementById('toggle-reminder');
  toggle.disabled = !supported;
  toggle.checked = isReminderOn(); // hormati preferensi, bukan sekadar izin
}

async function toggleReminder(checked) {
  if (!('Notification' in window)) {
    showToast('Browser ini tidak mendukung notifikasi.', true);
    updateReminderUI();
    return;
  }

  if (checked) {
    // Minta izin HANYA saat pengguna mengaktifkannya.
    const permission = await Notification.requestPermission();
    if (permission === 'granted') {
      try {
        localStorage.setItem(REMINDER_KEY, '1');
      } catch (err) { /* abaikan */ }
      startReminderTimer();
      showToast('Pengingat aktif.');
    } else {
      showToast('Izin notifikasi ditolak.', true);
    }
  } else {
    stopReminderTimer();
    try {
      localStorage.setItem(REMINDER_KEY, '0');
    } catch (err) { /* abaikan */ }
    showToast('Pengingat dimatikan.');
  }
  updateReminderUI();
}

/** Cek tugas hari ini yang jamnya sudah tiba (maksimal 5 menit lalu). */
function checkReminders() {
  if (!('Notification' in window) || Notification.permission !== 'granted') return;

  const now = new Date();
  const nowHM = now.getHours() * 60 + now.getMinutes();
  const today = todayStr();

  tasks.forEach((t) => {
    if (t.done || t.date !== today || !t.time || notifiedIds.has(t.id)) return;
    const [h, m] = t.time.split(':').map(Number);
    const due = h * 60 + m;
    // Notifikasi saat jam tiba, toleransi 5 menit; tugas lama tidak dibombardir.
    if (nowHM >= due && nowHM - due <= 5) {
      notifiedIds.add(t.id);
      try {
        new Notification('Pengingat Tugas 🕒', {
          body: `${t.time} - ${t.title} (${t.category})`,
        });
      } catch (err) {
        console.warn('Gagal menampilkan notifikasi.', err);
      }
    }
  });
}

function startReminderTimer() {
  stopReminderTimer();
  checkReminders();
  reminderTimer = setInterval(checkReminders, 30000); // tiap 30 detik
}

function stopReminderTimer() {
  if (reminderTimer !== null) {
    clearInterval(reminderTimer);
    reminderTimer = null;
  }
}

/* ---- Toast (pesan singkat) ---- */
let toastTimer = null;
function showToast(message, isError = false) {
  const el = document.getElementById('toast');
  el.textContent = message;
  el.classList.toggle('error', isError);
  el.hidden = false;
  if (toastTimer) clearTimeout(toastTimer);
  toastTimer = setTimeout(() => {
    el.hidden = true;
  }, 3000);
}

/* ============ 6) INIT & EVENT BINDING ============ */

function init() {
  // --- Tema tersimpan (sebelum render pertama) ---
  let savedTheme = 'light';
  try {
    savedTheme = localStorage.getItem(THEME_KEY) || 'light';
  } catch (err) { /* storage bermasalah → pakai default */ }
  applyTheme(savedTheme);

  // --- Muat data (aman terhadap localStorage kosong/rusak) ---
  tasks = loadTasks();
  currentDate = todayStr();

  // --- Tombol tambah & form ---
  document.getElementById('btn-add').addEventListener('click', () => openForm(''));
  document.getElementById('btn-cancel').addEventListener('click', closeForm);
  document.getElementById('task-form').addEventListener('submit', handleSubmit);
  document.querySelector('.modal-backdrop').addEventListener('click', closeForm);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeForm();
  });

  // --- Navigasi tanggal ---
  document.getElementById('btn-prev').addEventListener('click', () => {
    currentDate = shiftDate(currentDate, -1);
    render();
  });
  document.getElementById('btn-next').addEventListener('click', () => {
    currentDate = shiftDate(currentDate, 1);
    render();
  });
  document.getElementById('btn-today').addEventListener('click', () => {
    currentDate = todayStr();
    render();
  });
  document.getElementById('date-input').addEventListener('change', (e) => {
    if (isValidISODate(e.target.value)) {
      currentDate = e.target.value;
      render();
    }
  });

  // --- Filter status ---
  document.querySelectorAll('.filter-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      currentFilter = btn.dataset.filter;
      render();
    });
  });

  // --- Pencarian ---
  document.getElementById('search-input').addEventListener('input', (e) => {
    searchQuery = e.target.value.trim();
    render();
  });

  // --- Tema ---
  document.getElementById('btn-theme').addEventListener('click', toggleTheme);

  // --- Ekspor / Impor ---
  document.getElementById('btn-export').addEventListener('click', exportData);
  const importInput = document.getElementById('import-file');
  document.getElementById('btn-import').addEventListener('click', () => importInput.click());
  importInput.addEventListener('change', (e) => {
    const file = e.target.files && e.target.files[0];
    if (file) importFromFile(file);
    importInput.value = ''; // reset agar file sama bisa dipilih lagi
  });

  // --- Pengingat ---
  updateReminderUI();
  document.getElementById('toggle-reminder').addEventListener('change', (e) => {
    toggleReminder(e.target.checked);
  });
  // Lanjutkan timer jika pengingat memang sedang aktif (izin + preferensi)
  if (isReminderOn()) {
    startReminderTimer();
  }

  // Render pertama
  render();
}

// Mulai setelah DOM siap
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}

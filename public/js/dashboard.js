document.addEventListener('DOMContentLoaded', function () {
    // Render ikon Lucide (jika CDN berhasil dimuat)
    if (window.lucide) {
        window.lucide.createIcons();
    }

    var body = document.body;
    var toggleBtn = document.getElementById('sidebarToggle');
    var closeBtn = document.getElementById('sidebarClose');
    var overlay = document.getElementById('overlay');
    var profileBtn = document.getElementById('profileBtn');
    var profileMenu = document.getElementById('profileMenu');
    var profile = document.getElementById('profile');

    /* ===== Sidebar drawer (mobile) ===== */
    function openSidebar() {
        body.classList.add('sidebar-open');
        toggleBtn.setAttribute('aria-expanded', 'true');
    }

    function closeSidebar() {
        body.classList.remove('sidebar-open');
        toggleBtn.setAttribute('aria-expanded', 'false');
    }

    toggleBtn.addEventListener('click', openSidebar);
    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

    window.addEventListener('resize', function () {
        if (window.innerWidth > 1024) {
            closeSidebar();
        }
    });

    /* ===== Menu bertingkat (accordion) ===== */
    document.querySelectorAll('.nav-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var submenu = document.getElementById(btn.getAttribute('aria-controls'));
            var willOpen = submenu.hidden;
            submenu.hidden = !willOpen;
            btn.setAttribute('aria-expanded', String(willOpen));
        });
    });

    /* ===== Dropdown profil ===== */
    function closeProfile() {
        profileMenu.hidden = true;
        profileBtn.setAttribute('aria-expanded', 'false');
    }

    profileBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        var willOpen = profileMenu.hidden;
        profileMenu.hidden = !willOpen;
        profileBtn.setAttribute('aria-expanded', String(willOpen));
    });

    document.addEventListener('click', function (e) {
        if (!profile.contains(e.target)) {
            closeProfile();
        }
    });

    /* ===== Escape menutup drawer & dropdown ===== */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeSidebar();
            closeProfile();
        }
    });

    /* ===== Link menu =====
       Hanya link kosong (href="#") yang diblokir.
       Link ke halaman sungguhan tetap berjalan normal. */
    document.querySelectorAll('a.nav-link, a.nav-sublink, a.dropdown__item').forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (link.getAttribute('href') === '#') {
                e.preventDefault();
                if (window.innerWidth <= 1024) {
                    closeSidebar();
                }
                closeProfile();
            }
        });
    });

    /* ===== Production Plan: filter tab + pencarian ===== */
    var planTable = document.getElementById('planTable');

    if (planTable) {
        var rows = planTable.querySelectorAll('tbody tr');
        var tabs = document.querySelectorAll('.tabs__btn');
        var searchInput = document.getElementById('planSearch');
        var emptyState = document.getElementById('planEmpty');
        var activeFilter = 'all';

        function applyFilter() {
            var keyword = searchInput.value.trim().toLowerCase();
            var visible = 0;

            rows.forEach(function (row) {
                var matchStatus = activeFilter === 'all' || row.dataset.status === activeFilter;
                var matchSearch = row.dataset.search.indexOf(keyword) !== -1;
                var show = matchStatus && matchSearch;
                row.hidden = !show;
                if (show) { visible++; }
            });

            emptyState.hidden = visible !== 0;
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) { t.classList.remove('is-active'); });
                tab.classList.add('is-active');
                activeFilter = tab.dataset.filter;
                applyFilter();
            });
        });

        searchInput.addEventListener('input', applyFilter);
    }
});

/* =====================================================
   FORM BUAT PRODUCTION PLAN
   ===================================================== */
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('planForm');
    if (!form) { return; }

    var products = JSON.parse(form.dataset.products || '{}');

    function $(id) { return document.getElementById(id); }

    var productEl = $('product');
    var qtyEl = $('qty');
    var lineEl = $('line');
    var startEl = $('startDate');
    var endEl = $('endDate');
    var matBody = $('matBody');
    var matTable = $('matTable');
    var matEmpty = $('matEmpty');
    var matAlert = $('matAlert');

    function fmt(n) {
        return n.toLocaleString('id-ID', { maximumFractionDigits: 2 });
    }

    function fmtDate(value) {
        if (!value) { return ''; }
        var d = new Date(value + 'T00:00:00');
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function cell(text, className) {
        var td = document.createElement('td');
        td.textContent = text;
        if (className) { td.className = className; }
        return td;
    }

    /* ===== Kebutuhan material ===== */
    function renderMaterials() {
        var product = products[productEl.value];
        var qty = parseFloat(qtyEl.value) || 0;

        matBody.innerHTML = '';

        if (!product) {
            matTable.hidden = true;
            matEmpty.hidden = false;
            matAlert.hidden = true;
            $('sumMaterial').textContent = '-';
            return;
        }

        var shortage = 0;

        product.materials.forEach(function (m) {
            var need = m.per_unit * qty;
            var enough = m.stock >= need;
            if (qty > 0 && !enough) { shortage++; }

            var tr = document.createElement('tr');
            tr.appendChild(cell(m.code, 'table__code'));
            tr.appendChild(cell(m.name));
            tr.appendChild(cell(fmt(m.per_unit) + ' ' + m.uom, 'table__right'));
            tr.appendChild(cell(qty > 0 ? fmt(need) + ' ' + m.uom : '-', 'table__right'));
            tr.appendChild(cell(fmt(m.stock) + ' ' + m.uom, 'table__right'));
            tr.appendChild(cell(m.warehouse));

            var statusTd = document.createElement('td');
            var badge = document.createElement('span');
            if (qty === 0) {
                badge.textContent = '-';
            } else {
                badge.className = 'badge ' + (enough ? 'badge--ok' : 'badge--fail');
                badge.textContent = enough ? 'Cukup' : 'Kurang ' + fmt(need - m.stock) + ' ' + m.uom;
            }
            statusTd.appendChild(badge);
            tr.appendChild(statusTd);

            matBody.appendChild(tr);
        });

        matTable.hidden = false;
        matEmpty.hidden = true;

        if (qty === 0) {
            matAlert.hidden = true;
            $('sumMaterial').textContent = '-';
        } else if (shortage > 0) {
            matAlert.hidden = false;
            $('matAlertText').textContent = shortage + ' dari ' + product.materials.length +
                ' material stoknya kurang. Siapkan Stock Transfer atau permintaan pembelian sebelum trial dimulai.';
            $('sumMaterial').textContent = shortage + ' material kurang';
        } else {
            matAlert.hidden = true;
            $('sumMaterial').textContent = 'Semua cukup';
        }
    }

    /* ===== Produk ===== */
    function updateProduct() {
        var product = products[productEl.value];
        $('productCode').value = product ? product.code : '-';
        $('bom').value = product ? product.bom : '-';
        $('uom').textContent = product ? product.uom : 'pcs';
        $('sumProduct').textContent = product ? product.name : '-';
        renderMaterials();
        updateSummary();
    }

    /* ===== Jadwal ===== */
    function updateSchedule() {
        endEl.min = startEl.value;

        if (startEl.value && endEl.value && endEl.value >= startEl.value) {
            var days = Math.round((new Date(endEl.value) - new Date(startEl.value)) / 86400000) + 1;
            $('duration').value = days + ' hari';
        } else {
            $('duration').value = '-';
        }
        updateSummary();
    }

    /* ===== Ringkasan ===== */
    function updateSummary() {
        var qty = parseFloat(qtyEl.value) || 0;
        var product = products[productEl.value];

        $('sumQty').textContent = qty > 0 ? fmt(qty) + ' ' + (product ? product.uom : 'pcs') : '-';
        $('sumLine').textContent = lineEl.value || '-';

        if (startEl.value && endEl.value) {
            $('sumPeriod').textContent = fmtDate(startEl.value) + ' - ' + fmtDate(endEl.value);
        } else if (startEl.value) {
            $('sumPeriod').textContent = 'Mulai ' + fmtDate(startEl.value);
        } else {
            $('sumPeriod').textContent = '-';
        }
    }

    productEl.addEventListener('change', updateProduct);
    qtyEl.addEventListener('input', function () { renderMaterials(); updateSummary(); });
    lineEl.addEventListener('change', updateSummary);
    startEl.addEventListener('change', updateSchedule);
    endEl.addEventListener('change', updateSchedule);

    /* ===== Validasi ===== */
    function setError(input, message) {
        var field = input.closest('.field');
        var msg = field.querySelector('.field__error');
        if (!msg) {
            msg = document.createElement('small');
            msg.className = 'field__error';
            field.appendChild(msg);
        }
        msg.textContent = message;
        field.classList.add('is-invalid');
    }

    function clearError(input) {
        var field = input.closest('.field');
        var msg = field.querySelector('.field__error');
        if (msg) { msg.remove(); }
        field.classList.remove('is-invalid');
    }

    function validate(onlyProduct) {
        var firstInvalid = null;
        var fields = onlyProduct ? [productEl] : form.querySelectorAll('[required]');

        fields.forEach(function (input) {
            clearError(input);
            if (!input.value.trim()) {
                setError(input, 'Wajib diisi.');
                if (!firstInvalid) { firstInvalid = input; }
            }
        });

        if (!onlyProduct) {
            if (parseFloat(qtyEl.value) < 1) {
                setError(qtyEl, 'Qty harus lebih dari 0.');
                if (!firstInvalid) { firstInvalid = qtyEl; }
            }
            if (startEl.value && endEl.value && endEl.value < startEl.value) {
                setError(endEl, 'Tanggal selesai tidak boleh sebelum tanggal mulai.');
                if (!firstInvalid) { firstInvalid = endEl; }
            }
        }

        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus({ preventScroll: true });
            return false;
        }
        return true;
    }

    form.addEventListener('input', function (e) {
        if (e.target.closest('.field')) { clearError(e.target); }
    });
    form.addEventListener('change', function (e) {
        if (e.target.closest('.field')) { clearError(e.target); }
    });

    /* ===== Toast ===== */
    var toastTimer = null;
    function showToast(message, type) {
        var old = document.querySelector('.toast');
        if (old) { old.remove(); }

        var toast = document.createElement('div');
        toast.className = 'toast' + (type ? ' toast--' + type : '');
        toast.setAttribute('role', 'status');
        toast.textContent = message;
        document.body.appendChild(toast);

        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { toast.remove(); }, 4500);
    }

    /* ===== Tombol simpan (belum ke database) ===== */
    form.querySelectorAll('[data-action]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var isDraft = btn.dataset.action === 'draft';

            if (!validate(isDraft)) {
                showToast('Lengkapi data yang ditandai merah terlebih dahulu.', 'error');
                return;
            }

            showToast(
                isDraft
                    ? 'Draft valid. Penyimpanan ke database dibuat pada tahap berikutnya.'
                    : 'Data lengkap dan siap diajukan. Pengiriman approval dibuat pada tahap berikutnya.',
                'success'
            );
        });
    });
});
/* =====================================================
   PRODUCTION TRIAL - DETAIL
   ===================================================== */
document.addEventListener('DOMContentLoaded', function () {
    var page = document.getElementById('trialPage');
    if (!page) { return; }

    function fmt(n, d) {
        return n.toLocaleString('id-ID', { maximumFractionDigits: d === undefined ? 2 : d });
    }

    function badge(text, cls) {
        var s = document.createElement('span');
        s.className = 'badge badge--' + cls;
        s.textContent = text;
        return s;
    }

    /* ===== Parameter: cek terhadap batas min/max ===== */
    var paramInputs = page.querySelectorAll('.js-param');

    function checkParams() {
        var deviations = 0;

        paramInputs.forEach(function (input) {
            var cell = input.closest('tr').querySelector('.js-param-status');
            cell.innerHTML = '';
            input.classList.remove('is-out');

            if (input.value === '') {
                cell.appendChild(badge('Belum diisi', 'draft'));
                return;
            }

            var v = parseFloat(input.value);
            var out = v < parseFloat(input.dataset.min) || v > parseFloat(input.dataset.max);

            if (out) {
                deviations++;
                input.classList.add('is-out');
                cell.appendChild(badge('Deviasi', 'fail'));
            } else {
                cell.appendChild(badge('Sesuai', 'ok'));
            }
        });

        var alertBox = document.getElementById('devAlert');
        alertBox.hidden = deviations === 0;
        document.getElementById('devAlertText').textContent =
            deviations + ' parameter di luar batas. Isi Catatan Deviasi dan laporkan ke QA sebelum trial dilanjutkan.';
        document.getElementById('sumDev').textContent = deviations === 0 ? 'Tidak ada' : deviations + ' parameter';
        return deviations;
    }

    /* ===== Material: selisih rencana vs aktual ===== */
    var matInputs = page.querySelectorAll('.js-mat');

    function checkMaterials() {
        matInputs.forEach(function (input) {
            var cell = input.closest('tr').querySelector('.js-mat-var');
            var plan = parseFloat(input.dataset.plan);

            if (input.value === '') { cell.textContent = '-'; cell.className = 'table__right js-mat-var'; return; }

            var diff = parseFloat(input.value) - plan;
            var pct = plan ? (diff / plan) * 100 : 0;
            cell.textContent = (diff > 0 ? '+' : '') + fmt(diff, 3) + ' (' + (pct > 0 ? '+' : '') + fmt(pct, 1) + '%)';
            cell.className = 'table__right js-mat-var' + (Math.abs(pct) > 5 ? (diff > 0 ? ' is-over' : ' is-under') : '');
        });
    }

    /* ===== Output & yield ===== */
    var inputQty = document.getElementById('inputQty');
    var goodQty = document.getElementById('goodQty');
    var rejectQty = document.getElementById('rejectQty');

    function checkYield() {
        var total = parseFloat(inputQty.value) || 0;
        var good = parseFloat(goodQty.value) || 0;
        var reject = parseFloat(rejectQty.value) || 0;

        if (total > 0) {
            var y = (good / total) * 100;
            var r = (reject / total) * 100;
            document.getElementById('yieldVal').value = fmt(y, 1) + '%';
            document.getElementById('rejectRate').value = fmt(r, 1) + '%';
            document.getElementById('sumYield').textContent = fmt(y, 1) + '%';
        } else {
            document.getElementById('yieldVal').value = '-';
            document.getElementById('rejectRate').value = '-';
            document.getElementById('sumYield').textContent = '-';
        }
    }

    paramInputs.forEach(function (i) { i.addEventListener('input', checkParams); });
    matInputs.forEach(function (i) { i.addEventListener('input', checkMaterials); });
    [inputQty, goodQty, rejectQty].forEach(function (i) { i.addEventListener('input', checkYield); });

    checkParams();
    checkMaterials();
    checkYield();

    /* ===== Toast ===== */
    var toastTimer = null;
    function showToast(message, type) {
        var old = document.querySelector('.toast');
        if (old) { old.remove(); }
        var t = document.createElement('div');
        t.className = 'toast' + (type ? ' toast--' + type : '');
        t.setAttribute('role', 'status');
        t.textContent = message;
        document.body.appendChild(t);
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { t.remove(); }, 4500);
    }

    /* ===== Tombol aksi (belum ke database) ===== */
    document.querySelectorAll('[data-trial-action]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (btn.dataset.trialAction === 'hold') {
                showToast('Trial ditahan. Penyimpanan status dibuat pada tahap database.');
                return;
            }

            var deviations = checkParams();
            var note = document.getElementById('devNote');

            if (deviations > 0 && !note.value.trim()) {
                note.closest('.field').classList.add('is-invalid');
                note.scrollIntoView({ behavior: 'smooth', block: 'center' });
                note.focus({ preventScroll: true });
                showToast('Ada parameter deviasi. Catatan deviasi wajib diisi sebelum trial diselesaikan.', 'error');
                return;
            }

            showToast('Data lengkap. Pengiriman ke Trial Result dibuat pada tahap berikutnya.', 'success');
        });
    });

    document.getElementById('devNote').addEventListener('input', function (e) {
        e.target.closest('.field').classList.remove('is-invalid');
    });
});
/* =====================================================
   TRIAL RESULT - DETAIL
   ===================================================== */
document.addEventListener('DOMContentLoaded', function () {
    var page = document.getElementById('resultPage');
    if (!page) { return; }

    var failCount = parseInt(page.dataset.failCount, 10) || 0;
    var decision = document.getElementById('decision');
    var note = document.getElementById('decisionNote');
    var followUp = document.getElementById('followUp');
    var warn = document.getElementById('decisionWarn');
    var followReq = document.getElementById('followUpReq');

    var labels = {
        pass: 'Lulus',
        cond: 'Lulus Bersyarat',
        retry: 'Gagal - ulangi trial',
        stop: 'Gagal - hentikan'
    };

    /* ===== Toast ===== */
    var toastTimer = null;
    function showToast(message, type) {
        var old = document.querySelector('.toast');
        if (old) { old.remove(); }
        var t = document.createElement('div');
        t.className = 'toast' + (type ? ' toast--' + type : '');
        t.setAttribute('role', 'status');
        t.textContent = message;
        document.body.appendChild(t);
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { t.remove(); }, 4500);
    }

    /* ===== Error helper ===== */
    function setError(input, message) {
        var field = input.closest('.field');
        var msg = field.querySelector('.field__error');
        if (!msg) {
            msg = document.createElement('small');
            msg.className = 'field__error';
            field.appendChild(msg);
        }
        msg.textContent = message;
        field.classList.add('is-invalid');
    }

    function clearError(input) {
        var field = input.closest('.field');
        var msg = field.querySelector('.field__error');
        if (msg) { msg.remove(); }
        field.classList.remove('is-invalid');
    }

    /* ===== Perubahan keputusan ===== */
    function onDecisionChange() {
        var v = decision.value;
        document.getElementById('sumDecision').textContent = labels[v] || '-';
        warn.hidden = !(v === 'pass' && failCount > 0);
        followReq.hidden = !(v && v !== 'pass');
    }

    decision.addEventListener('change', onDecisionChange);

    [decision, note, followUp].forEach(function (el) {
        el.addEventListener('input', function () { clearError(el); });
        el.addEventListener('change', function () { clearError(el); });
    });

    /* ===== Validasi ===== */
    function validate(isDraft) {
        var first = null;

        [decision, note, followUp].forEach(clearError);

        if (isDraft) { return true; }

        if (!decision.value) {
            setError(decision, 'Pilih keputusan.');
            first = first || decision;
        }
        if (!note.value.trim()) {
            setError(note, 'Catatan keputusan wajib diisi.');
            first = first || note;
        }
        if (decision.value && decision.value !== 'pass' && !followUp.value.trim()) {
            setError(followUp, 'Tindak lanjut wajib diisi jika keputusan bukan Lulus.');
            first = first || followUp;
        }

        if (first) {
            first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            first.focus({ preventScroll: true });
            return false;
        }
        return true;
    }

    /* ===== Tombol (belum ke database) ===== */
    document.querySelectorAll('[data-result-action]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var isDraft = btn.dataset.resultAction === 'draft';

            if (!validate(isDraft)) {
                showToast('Lengkapi data yang ditandai merah terlebih dahulu.', 'error');
                return;
            }

            showToast(
                isDraft
                    ? 'Draft valid. Penyimpanan ke database dibuat pada tahap berikutnya.'
                    : 'Keputusan lengkap dan siap dikirim. Proses approval dibuat pada tahap berikutnya.',
                'success'
            );
        });
    });

    onDecisionChange();
});
/* =====================================================
   TRIAL REPORT - filter + export PDF tabel trial
   ===================================================== */
document.addEventListener('DOMContentLoaded', function () {
    var table = document.getElementById('trialReportTable');
    if (!table) { return; }   // bukan halaman Trial Report

    var rows = table.querySelectorAll('tbody tr');
    var tabs = document.querySelectorAll('[data-tr-filter]');
    var searchIn = document.getElementById('trialReportSearch');
    var empty = document.getElementById('trialReportEmpty');
    var exportBtn = document.getElementById('trialReportExportPdf');

    var activeFilter = 'all';

    /* ===== Filter + pencarian ===== */
    function applyFilter() {
        var keyword = (searchIn && searchIn.value.trim().toLowerCase()) || '';
        var visible = 0;

        rows.forEach(function (row) {
            var matchStatus = activeFilter === 'all' || row.dataset.trStatus === activeFilter;
            var matchSearch = row.dataset.trSearch.indexOf(keyword) !== -1;
            var show = matchStatus && matchSearch;
            row.hidden = !show;
            if (show) { visible++; }
        });

        if (empty) { empty.hidden = visible !== 0; }
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('is-active'); });
            tab.classList.add('is-active');
            activeFilter = tab.dataset.trFilter;
            applyFilter();
        });
    });

    if (searchIn) {
        searchIn.addEventListener('input', applyFilter);
    }

    /* ===== Toast ===== */
    var toastTimer = null;
    function showToast(message, type) {
        var old = document.querySelector('.toast');
        if (old) { old.remove(); }
        var t = document.createElement('div');
        t.className = 'toast' + (type ? ' toast--' + type : '');
        t.setAttribute('role', 'status');
        t.textContent = message;
        document.body.appendChild(t);
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { t.remove(); }, 4500);
    }

    /* ===== Export PDF (cetak) ===== */
    if (exportBtn) {
        exportBtn.addEventListener('click', function () {
            showToast('Menyiapkan tabel trial untuk dicetak...', 'success');
            setTimeout(function () { window.print(); }, 400);
        });
    }
});

/* =====================================================
   MASTER BARANG - FORM TAMBAH BARANG
   (baris dinamis spesifikasi & supplier)
   ===================================================== */
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('itemForm');
    if (!form) { return; }   // bukan halaman Master Barang Create

    /* ===== Helper: bikin baris spesifikasi baru ===== */
    function specRow() {
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td><input class="input input--sm" type="text" placeholder="Contoh: Kadar air"></td>' +
            '<td><input class="input input--sm" type="text" placeholder="Contoh: ≤ 12%"></td>' +
            '<td><input class="input input--sm" type="text" placeholder="Contoh: Oven method"></td>' +
            '<td><input class="input input--sm" type="text" placeholder="Contoh: SNI 01-2891"></td>' +
            '<td class="table__right">' +
            '<button type="button" class="btn--danger-ghost js-remove-row" aria-label="Hapus baris">' +
            '<i data-lucide="trash-2"></i>' +
            '</button>' +
            '</td>';
        return tr;
    }

    /* ===== Helper: bikin baris supplier baru ===== */
    function supplierRow() {
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td><input class="input input--sm" type="text" placeholder="Nama supplier"></td>' +
            '<td><input class="input input--sm" type="number" min="0" placeholder="0"></td>' +
            '<td><input class="input input--sm" type="number" min="0" placeholder="0"></td>' +
            '<td><select class="select"><option>Aktif</option><option>Nonaktif</option></select></td>' +
            '<td class="table__right">' +
            '<button type="button" class="btn--danger-ghost js-remove-row" aria-label="Hapus baris">' +
            '<i data-lucide="trash-2"></i>' +
            '</button>' +
            '</td>';
        return tr;
    }

    /* ===== Tombol tambah baris ===== */
    var addSpecBtn = document.getElementById('addSpec');
    var addSupplierBtn = document.getElementById('addSupplier');
    var specBody = document.getElementById('specBody');
    var supplierBody = document.getElementById('supplierBody');

    function afterDomChange() {
        if (window.lucide) { window.lucide.createIcons(); }
        updateSummary();
    }

    if (addSpecBtn && specBody) {
        addSpecBtn.addEventListener('click', function () {
            specBody.appendChild(specRow());
            afterDomChange();
        });
    }

    if (addSupplierBtn && supplierBody) {
        addSupplierBtn.addEventListener('click', function () {
            supplierBody.appendChild(supplierRow());
            afterDomChange();
        });
    }

    /* ===== Hapus baris (event delegation) ===== */
    form.addEventListener('click', function (e) {
        var btn = e.target.closest('.js-remove-row');
        if (!btn) { return; }

        var tr = btn.closest('tr');
        var tbody = tr.parentNode;

        // Minimal 1 baris harus tetap ada
        if (tbody.querySelectorAll('tr').length <= 1) {
            showToast('Minimal harus ada 1 baris.', 'error');
            return;
        }

        tr.remove();
        afterDomChange();
    });

    /* ===== Ringkasan kanan ===== */
    var nameEl = document.getElementById('itemName');
    var catEl = document.getElementById('itemCategory');
    var uomEl = document.getElementById('itemUom');
    var whEl = document.getElementById('itemWarehouse');
    var uomAddon = document.getElementById('uomAddon');

    function updateSummary() {
        document.getElementById('sumName').textContent = (nameEl && nameEl.value.trim()) || '-';
        document.getElementById('sumCategory').textContent = (catEl && catEl.value) || '-';
        document.getElementById('sumUom').textContent = (uomEl && uomEl.value) || '-';
        document.getElementById('sumWarehouse').textContent = (whEl && whEl.value) || '-';
        document.getElementById('sumSpecs').textContent = specBody ? specBody.querySelectorAll('tr').length : 0;
        document.getElementById('sumSuppliers').textContent = supplierBody ? supplierBody.querySelectorAll('tr').length : 0;

        if (uomAddon) {
            uomAddon.textContent = (uomEl && uomEl.value) || '-';
        }
    }

    [nameEl, catEl, uomEl, whEl].forEach(function (el) {
        if (!el) { return; }
        el.addEventListener('input', updateSummary);
        el.addEventListener('change', updateSummary);
    });

    /* ===== Toast ===== */
    var toastTimer = null;
    function showToast(message, type) {
        var old = document.querySelector('.toast');
        if (old) { old.remove(); }
        var t = document.createElement('div');
        t.className = 'toast' + (type ? ' toast--' + type : '');
        t.setAttribute('role', 'status');
        t.textContent = message;
        document.body.appendChild(t);
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { t.remove(); }, 4500);
    }

    /* ===== Validasi minimal ===== */
    function validate() {
        var missing = [];
        if (!nameEl.value.trim()) { missing.push('Nama Barang'); }
        if (!catEl.value) { missing.push('Kategori'); }
        if (!uomEl.value) { missing.push('Satuan Dasar'); }
        if (!whEl.value) { missing.push('Gudang Default'); }

        if (missing.length) {
            showToast('Lengkapi dulu: ' + missing.join(', ') + '.', 'error');
            return false;
        }
        return true;
    }

    /* ===== Tombol Simpan Draft / Simpan & Aktifkan ===== */
    form.querySelectorAll('[data-item-action]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var isDraft = btn.dataset.itemAction === 'draft';

            if (!isDraft && !validate()) { return; }

            showToast(
                isDraft
                    ? 'Draft barang disimpan (belum ke database).'
                    : 'Barang siap diaktifkan (penyimpanan ke database dibuat pada tahap berikutnya).',
                'success'
            );
        });
    });

    /* Inisialisasi pertama */
    afterDomChange();
});
/* =====================================================
   PRODUCTION TRIAL - MODAL "MULAI TRIAL DARI PLAN"
   ===================================================== */
document.addEventListener('DOMContentLoaded', function () {
    var trigger = document.getElementById('btnStartTrial');
    if (!trigger) { return; }   // bukan halaman Production Trial

    var modal = document.getElementById('startTrialModal');
    var nextBtn = document.getElementById('modalNext');
    var backBtn = document.getElementById('modalBack');
    var startBtn = document.getElementById('modalStart');

    var steps = modal.querySelectorAll('.modal__step');
    var panels = modal.querySelectorAll('[data-step-panel]');

    var currentStep = 1;

    /* ===== Buka modal ===== */
    function openModal() {
        modal.hidden = false;
        document.body.style.overflow = 'hidden';
        setStep(1);
        resetChoice();
    }

    /* ===== Tutup modal ===== */
    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
    }

    /* ===== Reset pilihan ===== */
    function resetChoice() {
        modal.querySelectorAll('input[name="planChoice"]').forEach(function (r) { r.checked = false; });
    }

    /* ===== Ambil pilihan plan ===== */
    function getChoice() {
        var checked = modal.querySelector('input[name="planChoice"]:checked');
        return checked;
    }

    /* ===== Set step (1 atau 2) ===== */
    function setStep(step) {
        currentStep = step;

        steps.forEach(function (s) {
            var n = parseInt(s.dataset.step, 10);
            s.classList.toggle('is-active', n === step);
            s.classList.toggle('is-done', n < step);
        });

        panels.forEach(function (p) {
            var n = parseInt(p.dataset.stepPanel, 10);
            p.hidden = n !== step;
        });

        if (step === 1) {
            backBtn.hidden = true;
            nextBtn.hidden = false;
            startBtn.hidden = true;
        } else {
            backBtn.hidden = false;
            nextBtn.hidden = true;
            startBtn.hidden = false;
        }

        if (window.lucide) { window.lucide.createIcons(); }
    }

    /* ===== Toast ===== */
    var toastTimer = null;
    function showToast(message, type) {
        var old = document.querySelector('.toast');
        if (old) { old.remove(); }
        var t = document.createElement('div');
        t.className = 'toast' + (type ? ' toast--' + type : '');
        t.setAttribute('role', 'status');
        t.textContent = message;
        document.body.appendChild(t);
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { t.remove(); }, 4500);
    }

    /* ===== Tombol Lanjut ===== */
    nextBtn.addEventListener('click', function () {
        var chosen = getChoice();
        if (!chosen) {
            showToast('Pilih salah satu Production Plan terlebih dahulu.', 'error');
            return;
        }

        // Isi ringkasan step 2
        document.getElementById('confirmPlan').textContent = chosen.value;
        document.getElementById('confirmProduct').textContent = chosen.dataset.product;
        document.getElementById('confirmTarget').textContent = chosen.dataset.target;
        document.getElementById('confirmLine').textContent = chosen.dataset.line;

        setStep(2);
    });

    /* ===== Tombol Kembali ===== */
    backBtn.addEventListener('click', function () {
        setStep(1);
    });

    /* ===== Tombol Mulai Trial ===== */
    startBtn.addEventListener('click', function () {
        var chosen = getChoice();
        if (!chosen) {
            showToast('Pilih plan terlebih dahulu.', 'error');
            setStep(1);
            return;
        }

        // Simulasi: nanti tinggal redirect ke route production-trial.show dengan nomor trial baru
        // Sekarang kita arahkan ke trial contoh (dummy) supaya user bisa lihat alurnya.
        var dummyTrial = 'TRL-2026-0048';

        showToast('Trial baru dibuat dari ' + chosen.value + '. Mengarahkan ke halaman eksekusi...', 'success');
        closeModal();

        setTimeout(function () {
            window.location.href = '/production-trial/' + dummyTrial;
        }, 900);
    });

    /* ===== Trigger buka modal ===== */
    trigger.addEventListener('click', openModal);

    /* ===== Tutup lewat [data-modal-close] ===== */
    modal.querySelectorAll('[data-modal-close]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });

    /* ===== Escape menutup modal ===== */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });
});

/* =====================================================
   MASTER GUDANG - FORM
   ===================================================== */
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('gudangForm');
    if (!form) { return; }

    function $(id) { return document.getElementById(id); }

    var isEdit = form.dataset.edit === '1';
    var hasStock = parseFloat(form.dataset.filled) > 0;
    var zones = JSON.parse(form.dataset.zones || '[]');

    var kode = $('gKode'), nama = $('gNama'), tipe = $('gTipe'), pic = $('gPic'), status = $('gStatus');
    var kondisi = $('gKondisi'), tmin = $('gTmin'), tmax = $('gTmax'), kap = $('gKap'), unit = $('gUnit');
    var zoneList = $('zoneList');

    /* ===== Toast ===== */
    var toastTimer = null;
    function showToast(message, type) {
        var old = document.querySelector('.toast');
        if (old) { old.remove(); }
        var t = document.createElement('div');
        t.className = 'toast' + (type ? ' toast--' + type : '');
        t.setAttribute('role', 'status');
        t.textContent = message;
        document.body.appendChild(t);
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { t.remove(); }, 4500);
    }

    /* ===== Error helper ===== */
    function setError(input, message) {
        var field = input.closest('.field');
        var msg = field.querySelector('.field__error');
        if (!msg) {
            msg = document.createElement('small');
            msg.className = 'field__error';
            field.appendChild(msg);
        }
        msg.textContent = message;
        field.classList.add('is-invalid');
    }

    function clearError(input) {
        var field = input.closest('.field');
        if (!field) { return; }
        var msg = field.querySelector('.field__error');
        if (msg) { msg.remove(); }
        field.classList.remove('is-invalid');
    }

    /* ===== Zona / rak ===== */
    function addZone(code, name) {
        var row = document.createElement('div');
        row.className = 'zone-row';
        row.innerHTML =
            '<div class="field"><input class="input js-zcode" type="text" maxlength="10" placeholder="Kode zona" aria-label="Kode zona"></div>' +
            '<div class="field"><input class="input js-zname" type="text" maxlength="60" placeholder="Nama zona / rak" aria-label="Nama zona"></div>' +
            '<button type="button" class="icon-btn icon-btn--sm icon-btn--danger js-zdel" aria-label="Hapus zona"><i data-lucide="trash-2" aria-hidden="true"></i></button>';
        row.querySelector('.js-zcode').value = code || '';
        row.querySelector('.js-zname').value = name || '';
        zoneList.appendChild(row);
        if (window.lucide) { window.lucide.createIcons(); }
    }

    function toggleZoneEmpty() {
        var empty = zoneList.querySelector('.zone-empty');
        var hasRows = zoneList.querySelectorAll('.zone-row').length > 0;
        if (!hasRows && !empty) {
            var p = document.createElement('p');
            p.className = 'zone-empty';
            p.textContent = 'Belum ada zona. Klik Tambah Zona jika gudang dibagi per rak atau area.';
            zoneList.appendChild(p);
        } else if (hasRows && empty) {
            empty.remove();
        }
    }

    /* ===== Kondisi penyimpanan: isi suhu otomatis ===== */
    var presets = {
        frozen: { min: -22, max: -18 },
        chiller: { min: 0, max: 4 },
        ambient: { min: '', max: 30 }
    };

    function onConditionChange(applyPreset) {
        var p = presets[kondisi.value];
        if (p && applyPreset) {
            tmin.value = p.min;
            tmax.value = p.max;
        }
        $('gTreq').hidden = !(kondisi.value === 'frozen' || kondisi.value === 'chiller');
        summary();
    }

    /* ===== Ringkasan ===== */
    function summary() {
        var kondisiText = kondisi.value ? kondisi.options[kondisi.selectedIndex].text : '-';
        if (kondisi.value && tmax.value !== '') {
            kondisiText += ' (' + (tmin.value !== '' ? tmin.value + ' s/d ' : '≤ ') + tmax.value + ' °C)';
        }

        $('sumKode').textContent = kode.value.trim() || '-';
        $('sumNama').textContent = nama.value.trim() || '-';
        $('sumTipe').textContent = tipe.value || '-';
        $('sumKondisi').textContent = kondisiText;
        $('sumKap').textContent = parseFloat(kap.value) > 0
            ? parseFloat(kap.value).toLocaleString('id-ID') + ' ' + unit.value : '-';
        $('sumZona').textContent = zoneList.querySelectorAll('.zone-row').length;

        var st = $('sumStatus');
        st.innerHTML = '';
        var b = document.createElement('span');
        b.className = 'badge badge--' + (status.value === 'aktif' ? 'ok' : 'obsolete');
        b.textContent = status.value === 'aktif' ? 'Aktif' : 'Nonaktif';
        st.appendChild(b);

        $('gAlert').hidden = !(isEdit && hasStock && status.value === 'nonaktif');
    }

    /* ===== Event ===== */
    kondisi.addEventListener('change', function () { onConditionChange(true); });

    kode.addEventListener('input', function () {
        kode.value = kode.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
    });

    form.addEventListener('input', function (e) { clearError(e.target); summary(); });
    form.addEventListener('change', function (e) { clearError(e.target); summary(); });

    $('addZone').addEventListener('click', function () {
        addZone();
        toggleZoneEmpty();
        summary();
        zoneList.lastElementChild.querySelector('.js-zcode').focus();
    });

    zoneList.addEventListener('click', function (e) {
        var btn = e.target.closest('.js-zdel');
        if (!btn) { return; }
        btn.closest('.zone-row').remove();
        toggleZoneEmpty();
        summary();
    });

    /* ===== Validasi ===== */
    function validate() {
        var first = null;
        var all = [kode, nama, tipe, pic, kondisi, tmin, tmax, kap];
        all.forEach(clearError);
        zoneList.querySelectorAll('.is-invalid').forEach(function (f) { clearError(f.querySelector('.input')); });

        function need(input, message) {
            setError(input, message);
            first = first || input;
        }

        if (!kode.value.trim()) { need(kode, 'Kode gudang wajib diisi.'); }
        else if (!/^[A-Z0-9]+(-[A-Z0-9]+)*$/.test(kode.value.trim())) { need(kode, 'Gunakan huruf besar, angka, dan strip. Contoh: GD-CS02.'); }

        if (!nama.value.trim()) { need(nama, 'Nama gudang wajib diisi.'); }
        if (!tipe.value) { need(tipe, 'Pilih tipe gudang.'); }
        if (!pic.value) { need(pic, 'Pilih PIC gudang.'); }
        if (!kondisi.value) { need(kondisi, 'Pilih kondisi penyimpanan.'); }

        var needTemp = kondisi.value === 'frozen' || kondisi.value === 'chiller';
        if (needTemp && tmin.value === '') { need(tmin, 'Suhu minimum wajib untuk gudang beku/dingin.'); }
        if (tmax.value === '') { need(tmax, 'Suhu maksimum wajib diisi.'); }
        if (tmin.value !== '' && tmax.value !== '' && parseFloat(tmin.value) > parseFloat(tmax.value)) {
            need(tmin, 'Suhu minimum tidak boleh lebih besar dari maksimum.');
        }
        if (!(parseFloat(kap.value) > 0)) { need(kap, 'Kapasitas harus lebih dari 0.'); }

        if (isEdit && hasStock && status.value === 'nonaktif') {
            need(status, 'Gudang masih berisi stok, tidak dapat dinonaktifkan.');
        }

        var seen = {};
        zoneList.querySelectorAll('.zone-row').forEach(function (row) {
            var c = row.querySelector('.js-zcode');
            var n = row.querySelector('.js-zname');
            var val = c.value.trim().toUpperCase();

            if (!val) { setError(c, 'Wajib.'); first = first || c; }
            else if (seen[val]) { setError(c, 'Kode ganda.'); first = first || c; }
            else { seen[val] = true; }

            if (!n.value.trim()) { setError(n, 'Wajib.'); first = first || n; }
        });

        if (first) {
            first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            first.focus({ preventScroll: true });
            return false;
        }
        return true;
    }

    $('saveGudang').addEventListener('click', function () {
        if (!validate()) {
            showToast('Lengkapi data yang ditandai merah terlebih dahulu.', 'error');
            return;
        }
        showToast('Data valid. Penyimpanan ke database dibuat pada tahap berikutnya.', 'success');
    });

    /* ===== Awal ===== */
    zones.forEach(function (z) { addZone(z[0], z[1]); });
    toggleZoneEmpty();
    onConditionChange(false);
});
/* =====================================================
   AUDIT LOG
   ===================================================== */
document.addEventListener('DOMContentLoaded', function () {
    var table = document.getElementById('auditTable');
    if (!table) { return; }

    function $(id) { return document.getElementById(id); }

    var body = $('auditBody');
    var rows = body.querySelectorAll('.log-row');
    var period = $('fPeriod'), range = $('fRange'), from = $('fFrom'), to = $('fTo');
    var mod = $('fModule'), act = $('fAction'), usr = $('fUser'), search = $('fSearch');
    var today = new Date(table.dataset.today + 'T00:00:00');

    function iso(d) {
        var m = String(d.getMonth() + 1).padStart(2, '0');
        var day = String(d.getDate()).padStart(2, '0');
        return d.getFullYear() + '-' + m + '-' + day;
    }

    function daysAgo(n) {
        var d = new Date(today);
        d.setDate(d.getDate() - n);
        return iso(d);
    }

    /* Batas tanggal sesuai pilihan periode */
    function dateBounds() {
        switch (period.value) {
            case 'today': return [iso(today), iso(today)];
            case '7': return [daysAgo(6), iso(today)];
            case '30': return [daysAgo(29), iso(today)];
            case 'custom': return [from.value || '0000-00-00', to.value || '9999-99-99'];
            default: return ['0000-00-00', '9999-99-99'];
        }
    }

    function applyFilter() {
        var bounds = dateBounds();
        var keyword = search.value.trim().toLowerCase();
        var visible = 0;

        rows.forEach(function (row) {
            var d = row.dataset;
            var show =
                d.date >= bounds[0] && d.date <= bounds[1] &&
                (!mod.value || d.module === mod.value) &&
                (!act.value || d.action === act.value) &&
                (!usr.value || d.user === usr.value) &&
                d.search.indexOf(keyword) !== -1;

            var detail = row.nextElementSibling;
            var open = row.querySelector('.log-toggle').getAttribute('aria-expanded') === 'true';

            row.hidden = !show;
            detail.hidden = !(show && open);
            if (show) { visible++; }
        });

        $('auditEmpty').hidden = visible !== 0;
        $('auditCount').textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' catatan';
    }

    period.addEventListener('change', function () {
        range.hidden = period.value !== 'custom';
        applyFilter();
    });
    [from, to, mod, act, usr].forEach(function (el) { el.addEventListener('change', applyFilter); });
    search.addEventListener('input', applyFilter);

    $('fReset').addEventListener('click', function () {
        period.value = 'all';
        range.hidden = true;
        from.value = '';
        to.value = '';
        mod.value = '';
        act.value = '';
        usr.value = '';
        search.value = '';
        applyFilter();
    });

    /* Buka / tutup detail perubahan */
    body.addEventListener('click', function (e) {
        var btn = e.target.closest('.log-toggle');
        if (!btn) { return; }

        var willOpen = btn.getAttribute('aria-expanded') !== 'true';
        var row = btn.closest('.log-row');

        btn.setAttribute('aria-expanded', String(willOpen));
        row.classList.toggle('is-open', willOpen);
        row.nextElementSibling.hidden = !willOpen;
    });

    applyFilter();
});
/* =====================================================
   ROLES & PERMISSION
   ===================================================== */
document.addEventListener('DOMContentLoaded', function () {
    var page = document.getElementById('rolesPage');
    if (!page) { return; }

    function $(id) { return document.getElementById(id); }
    function clone(o) { return JSON.parse(JSON.stringify(o)); }

    var roles = JSON.parse(page.dataset.roles || '{}');
    var modules = JSON.parse(page.dataset.modules || '[]');
    var original = clone(roles);
    var checks = page.querySelectorAll('.js-perm');
    var current = null;
    var locked = false;
    var dirty = false;

    var totalPerms = 0;
    modules.forEach(function (m) { totalPerms += m.allowed.length; });

    /* ===== Toast ===== */
    var toastTimer = null;
    function showToast(message, type) {
        var old = document.querySelector('.toast');
        if (old) { old.remove(); }
        var t = document.createElement('div');
        t.className = 'toast' + (type ? ' toast--' + type : '');
        t.setAttribute('role', 'status');
        t.textContent = message;
        document.body.appendChild(t);
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { t.remove(); }, 4500);
    }

    /* ===== Helper checkbox ===== */
    function box(module, action) {
        return page.querySelector('.js-perm[data-module="' + module + '"][data-action="' + action + '"]');
    }
    function moduleBoxes(module) {
        return page.querySelectorAll('.js-perm[data-module="' + module + '"]');
    }

    /* ===== Daftar peran ===== */
    function renderList() {
        var list = $('roleList');
        list.innerHTML = '';
        Object.keys(roles).forEach(function (name) {
            var r = roles[name];
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'role-item' + (name === current ? ' is-active' : '');
            b.dataset.role = name;

            var s = document.createElement('strong');
            s.textContent = name;
            var sm = document.createElement('small');
            sm.textContent = r.users + ' pengguna' + (r.system ? ' \u00b7 Peran sistem' : '');

            b.appendChild(s);
            b.appendChild(sm);
            list.appendChild(b);
        });

        var copy = $('nrCopy');
        copy.innerHTML = '<option value="">Mulai dari kosong</option>';
        Object.keys(roles).forEach(function (name) {
            var o = document.createElement('option');
            o.value = name;
            o.textContent = name;
            copy.appendChild(o);
        });
    }

    /* ===== Ambil izin dari tampilan ===== */
    function collect() {
        var perms = {};
        checks.forEach(function (c) {
            if (c.checked) {
                (perms[c.dataset.module] = perms[c.dataset.module] || []).push(c.dataset.action);
            }
        });
        roles[current].perms = perms;
    }

    /* ===== Checkbox "Semua" per baris & kolom ===== */
    function syncToggles() {
        modules.forEach(function (m) {
            var row = page.querySelector('.js-row[data-module="' + m.key + '"]');
            var all = true;
            moduleBoxes(m.key).forEach(function (c) { if (!c.checked) { all = false; } });
            row.checked = all;
            row.disabled = locked;
        });

        page.querySelectorAll('.js-col').forEach(function (col) {
            var all = true;
            page.querySelectorAll('.js-perm[data-action="' + col.dataset.action + '"]').forEach(function (c) {
                if (!c.checked) { all = false; }
            });
            col.checked = all;
            col.disabled = locked;
        });
    }

    /* ===== Ringkasan & peringatan pemisahan tugas ===== */
    function summary() {
        var perms = roles[current].perms;
        var granted = 0;
        var sod = [];

        modules.forEach(function (m) {
            var p = perms[m.key] || [];
            granted += p.length;
            if (p.indexOf('create') !== -1 && p.indexOf('approve') !== -1) { sod.push(m.label); }
        });

        $('roleSummary').textContent = granted + ' dari ' + totalPerms + ' izin diberikan' + (dirty ? ' \u00b7 ada perubahan belum disimpan' : '');
        $('roleCount').textContent = granted + ' izin';

        var showSod = !locked && sod.length > 0;
        $('sodNote').hidden = !showSod;
        $('sodText').textContent = showSod
            ? 'Pemisahan tugas: peran ini dapat membuat sekaligus meng-approve di ' + sod.join(', ') + '. Pastikan sesuai kebijakan perusahaan.'
            : '';

        $('saveRole').disabled = locked;
        $('resetRole').disabled = locked;
    }

    /* ===== Pilih peran ===== */
    function select(name) {
        current = name;
        var r = roles[name];
        locked = !!r.system;
        dirty = false;

        $('roleName').textContent = name;
        $('roleDesc').textContent = r.desc || 'Tanpa deskripsi';
        $('lockNote').hidden = !locked;

        checks.forEach(function (c) {
            var p = r.perms[c.dataset.module] || [];
            c.checked = p.indexOf(c.dataset.action) !== -1;
            c.disabled = locked;
        });

        syncToggles();
        summary();
        renderList();
    }

    $('roleList').addEventListener('click', function (e) {
        var btn = e.target.closest('.role-item');
        if (!btn || btn.dataset.role === current) { return; }

        if (dirty) {
            if (!window.confirm('Ada perubahan izin yang belum disimpan. Tinggalkan peran ini?')) { return; }
            roles[current] = clone(original[current]);
        }
        select(btn.dataset.role);
    });

    /* ===== Perubahan checkbox ===== */
    page.addEventListener('change', function (e) {
        var t = e.target;
        if (locked) { return; }

        if (t.classList.contains('js-perm')) {
            var mod = t.dataset.module;
            if (t.dataset.action === 'view' && !t.checked) {
                moduleBoxes(mod).forEach(function (c) { c.checked = false; });
            } else if (t.dataset.action !== 'view' && t.checked) {
                box(mod, 'view').checked = true;
            }
        } else if (t.classList.contains('js-row')) {
            moduleBoxes(t.dataset.module).forEach(function (c) { c.checked = t.checked; });
        } else if (t.classList.contains('js-col')) {
            var action = t.dataset.action;
            page.querySelectorAll('.js-perm[data-action="' + action + '"]').forEach(function (c) {
                c.checked = t.checked;
                if (action === 'view' && !t.checked) {
                    moduleBoxes(c.dataset.module).forEach(function (o) { o.checked = false; });
                } else if (action !== 'view' && t.checked) {
                    box(c.dataset.module, 'view').checked = true;
                }
            });
        } else {
            return;
        }

        dirty = true;
        collect();
        syncToggles();
        summary();
    });

    /* ===== Simpan & batal ===== */
    $('saveRole').addEventListener('click', function () {
        if (locked) {
            showToast('Peran sistem tidak dapat diubah.', 'error');
            return;
        }
        var granted = 0;
        Object.keys(roles[current].perms).forEach(function (k) { granted += roles[current].perms[k].length; });

        if (granted === 0) {
            showToast('Peran harus memiliki minimal satu izin.', 'error');
            return;
        }

        original[current] = clone(roles[current]);
        dirty = false;
        summary();
        showToast('Izin valid. Penyimpanan ke database dan pencatatan Audit Log dibuat pada tahap berikutnya.', 'success');
    });

    $('resetRole').addEventListener('click', function () {
        roles[current] = clone(original[current]);
        select(current);
        showToast('Perubahan dibatalkan.');
    });

    /* ===== Peran baru ===== */
    var panel = $('newRolePanel');

    $('btnNewRole').addEventListener('click', function () {
        panel.hidden = !panel.hidden;
        if (!panel.hidden) { $('nrName').focus(); }
    });
    $('nrCancel').addEventListener('click', function () { panel.hidden = true; });

    $('nrCreate').addEventListener('click', function () {
        var name = $('nrName').value.trim();
        var field = $('nrName').closest('.field');
        var old = field.querySelector('.field__error');
        if (old) { old.remove(); }
        field.classList.remove('is-invalid');

        var message = '';
        if (!name) { message = 'Nama peran wajib diisi.'; }
        else if (Object.keys(roles).some(function (n) { return n.toLowerCase() === name.toLowerCase(); })) {
            message = 'Nama peran sudah dipakai.';
        }

        if (message) {
            var err = document.createElement('small');
            err.className = 'field__error';
            err.textContent = message;
            field.appendChild(err);
            field.classList.add('is-invalid');
            return;
        }

        var src = $('nrCopy').value;
        roles[name] = {
            desc: $('nrDesc').value.trim(),
            users: 0,
            system: false,
            perms: src ? clone(roles[src].perms) : {}
        };
        original[name] = clone(roles[name]);

        $('nrName').value = '';
        $('nrDesc').value = '';
        panel.hidden = true;

        if (dirty) { roles[current] = clone(original[current]); }
        select(name);
        showToast('Peran dibuat. Atur izinnya lalu klik Simpan Izin.', 'success');
    });

    renderList();
    select(Object.keys(roles)[0]);
});
(function () {
  var $ = function (s) { return document.querySelector(s); };
  var D = window.FOODAI || {};
  var esc = function (s) { return String(s).replace(/[&<>"']/g, function (c) { return ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" })[c]; }); };
  var pct = function (x) { return Math.round(x * 100) + "%"; };
  var dtk = function (ms) { return (ms / 1000).toLocaleString("id-ID", { minimumFractionDigits: 2, maximumFractionDigits: 2 }); };

  /* ---------- Grafik waktu inferensi (halaman Ringkasan) ---------- */
  function drawChart() {
    var el = $("#chart-infer"); if (!el) return;
    var data = D.series || []; var w = el.clientWidth; if (!w) return;
    if (!data.length) { el.innerHTML = '<p class="sub">Belum ada data untuk digambar.</p>'; return; }
    var target = D.targetMs || 2000, h = 210, L = 40, R = 14, T = 16, B = 28, yMax = 2.5;
    var x = function (i) { return L + (data.length === 1 ? 0 : i * (w - L - R) / (data.length - 1)); };
    var y = function (v) { return T + (1 - v / yMax) * (h - T - B); };
    var out = '<svg width="' + w + '" height="' + h + '" viewBox="0 0 ' + w + ' ' + h + '" role="img" aria-label="Grafik waktu inferensi dalam detik untuk ' + data.length + ' pemindaian terakhir">';
    [0, .5, 1, 1.5, 2, 2.5].forEach(function (t) {
      out += '<line class="gl" x1="' + L + '" x2="' + (w - R) + '" y1="' + y(t) + '" y2="' + y(t) + '"/><text class="tx" x="' + (L - 8) + '" y="' + (y(t) + 4) + '" text-anchor="end">' + t.toLocaleString("id-ID", { minimumFractionDigits: 1 }) + '</text>';
    });
    var pts = data.map(function (r, i) { return x(i) + "," + y(r.ms / 1000); }).join(" ");
    out += '<polygon class="ar" points="' + x(0) + "," + y(0) + " " + pts + " " + x(data.length - 1) + "," + y(0) + '"/>';
    out += '<line class="tg" x1="' + L + '" x2="' + (w - R) + '" y1="' + y(target / 1000) + '" y2="' + y(target / 1000) + '"/>';
    out += '<text class="tgx" x="' + (L + 6) + '" y="' + (y(target / 1000) - 6) + '">target 2 detik</text>';
    out += '<polyline class="ln" points="' + pts + '"/>';
    data.forEach(function (r, i) {
      var last = i === data.length - 1;
      out += '<circle class="pt' + (r.ms > target ? " over" : "") + (last ? " last" : "") + '" cx="' + x(i) + '" cy="' + y(r.ms / 1000) + '" r="' + (last ? 5 : 3.6) + '"><title>' + esc(r.kode) + ': ' + dtk(r.ms) + ' detik</title></circle>';
    });
    out += '<text class="tx" x="' + x(0) + '" y="' + (h - 8) + '" text-anchor="start">' + esc(data[0].kode) + '</text>';
    out += '<text class="tx" x="' + x(data.length - 1) + '" y="' + (h - 8) + '" text-anchor="end">' + esc(data[data.length - 1].kode) + '</text></svg>';
    el.innerHTML = out;
  }
  if ($("#chart-infer")) {
    drawChart();
    var rt; window.addEventListener("resize", function () { clearTimeout(rt); rt = setTimeout(drawChart, 120); });
  }

  /* ---------- Analisis foto (halaman Analisis Foto) ---------- */
  var drop = $("#drop"); if (!drop) return;
  var file = null, busy = false;
  var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || "";

  function placeholder() {
    $("#res").innerHTML =
      '<div class="empty-h">Hasil analisis akan tampil di sini</div>' +
      '<p class="sub" style="margin-top:4px">Sistem mengenali lima kelas objek. Objek di luar kelas ini tidak ditandai.</p>' +
      '<div class="classes rows" style="margin-top:14px">' +
      (D.classes || []).map(function (k) {
        return '<div class="row"><div class="t">' + esc(k.nama) + '</div><span class="pill ' + (k.halal ? "ok" : "bad") + '">' + (k.halal ? "Kelas halal" : "Kelas non-halal") + '</span></div>';
      }).join("") + '</div>' +
      '<p class="fine">Hasil berupa indikasi visual awal. Kepastian kehalalan tetap membutuhkan sertifikasi resmi.</p>';
  }

  function setFile(f) {
    if (!f) return;
    if (!/^image\/(jpeg|png|webp)$/.test(f.type)) { $("#drop-msg").textContent = "Format tidak didukung. Pilih JPG, PNG, atau WebP."; return; }
    if (f.size > 10 * 1024 * 1024) { $("#drop-msg").textContent = "Ukuran berkas melebihi 10 MB. Pilih foto yang lebih kecil."; return; }
    file = f;
    var rd = new FileReader();
    rd.onload = function () { $("#pimg").src = rd.result; $("#preview").hidden = false; };
    rd.readAsDataURL(f);
    $("#overlay").innerHTML = "";
    $("#drop-msg").textContent = f.name + " (" + Math.max(1, Math.round(f.size / 1024)) + " KB)";
    $("#go").disabled = false; $("#reset").hidden = false;
    placeholder();
  }
  $("#file").addEventListener("change", function (e) { setFile(e.target.files[0]); });
  ["dragenter", "dragover"].forEach(function (ev) { drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add("over"); }); });
  ["dragleave", "drop"].forEach(function (ev) { drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove("over"); }); });
  drop.addEventListener("drop", function (e) { setFile(e.dataTransfer.files[0]); });
  $("#reset").addEventListener("click", function () {
    file = null; $("#file").value = ""; $("#preview").hidden = true; $("#pimg").removeAttribute("src"); $("#overlay").innerHTML = "";
    $("#drop-msg").textContent = "Belum ada foto dipilih"; $("#go").disabled = true; $("#reset").hidden = true; placeholder();
  });

  function finish() { busy = false; $("#go").disabled = false; $("#go-t").textContent = "Analisis ulang"; $("#overlay").innerHTML = $("#overlay").innerHTML.replace(/<div class="scan"><\/div>/, ""); }
  function showError(msg) {
    $("#overlay").innerHTML = "";
    $("#res").innerHTML = '<div class="empty-h">Analisis belum berhasil</div><div class="alert" role="alert">' + esc(msg) + '</div><p class="fine">Foto Anda tidak dicatat di riwayat.</p>';
  }

  $("#go").addEventListener("click", function () {
    if (!file || busy) return;
    busy = true; $("#go").disabled = true; $("#go-t").textContent = "Menganalisis…";
    $("#overlay").innerHTML = '<div class="scan"></div>';
    var fd = new FormData(); fd.append("foto", file);
    var t0 = Date.now();
    fetch(D.storeUrl, { method: "POST", body: fd, headers: { "X-CSRF-TOKEN": csrf, "Accept": "application/json" } })
      .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
      .then(function (res) {
        setTimeout(function () {
          if (res.ok) showResult(res.j);
          else showError((res.j.errors && res.j.errors.foto && res.j.errors.foto[0]) || res.j.message || "Terjadi kesalahan pada server.");
          finish();
        }, Math.max(0, 900 - (Date.now() - t0)));
      })
      .catch(function () { showError("Koneksi terputus. Periksa jaringan lalu coba lagi."); finish(); });
  });

  function showResult(r) {
    var ambang = D.ambang || 0.6, st = r.status_cls;
    var b = r.box;
    $("#overlay").innerHTML = b ? '<div class="box ' + st + '" style="left:' + b.x + '%;top:' + b.y + '%;width:' + b.w + '%;height:' + b.h + '%"><span>' + esc(r.nama_kelas) + " " + pct(r.conf) + '</span></div>' : "";
    var teks = r.status === "nonhalal" ? "Ada objek terindikasi non-halal" : r.status === "aman" ? "Tidak ada objek non-halal yang terlihat" : "Hasil belum meyakinkan";
    var saran = r.status === "nonhalal" ? "Objek pada kotak batas cocok dengan kelas non-halal. Periksa makanan dan bahan aslinya." : r.status === "aman" ? "Objek yang terdeteksi termasuk kelas halal. Bahan tersembunyi tidak ikut diperiksa." : "Keyakinan di bawah " + pct(ambang) + ". Coba foto yang lebih terang dan dekat, atau periksa langsung.";
    var catatan = r.simulasi
      ? '<b>Mode simulasi.</b> Kelas dan skor dihitung dari nama berkas, bukan dari isi gambar, karena layanan FastAPI belum dihubungkan. '
      : "";
    $("#res").innerHTML =
      '<div class="result-top"><h2>Hasil analisis</h2><span class="pill ' + st + '">' + esc(r.status_label) + '</span></div>' +
      '<div class="verdict ' + st + '">' + teks + '</div>' +
      '<p class="sub" style="margin-top:6px;font-size:13px">' + saran + '</p>' +
      '<div class="meter"><div class="top"><span>Skor keyakinan</span><b>' + pct(r.conf) + '</b></div><div class="tr"><div class="fl" id="mfl" style="width:0"></div></div></div>' +
      '<div class="meta"><div><small>Objek</small><b>' + esc(r.nama_kelas) + '</b></div><div><small>Waktu inferensi</small><b>' + dtk(r.ms) + ' detik</b></div><div><small>Dicatat sebagai</small><b class="mono">' + esc(r.kode) + '</b></div><div><small>Target 2 detik</small><b>' + (r.lewat_target ? "Terlewati" : "Tercapai") + '</b></div></div>' +
      '<p class="fine">' + catatan + 'Hasil ini penilaian visual tingkat pertama dan bukan pengganti sertifikasi LPPOM MUI. <a href="/riwayat" style="color:var(--brand);font-weight:600">Lihat di riwayat</a></p>';
    requestAnimationFrame(function () { var m = $("#mfl"); if (m) m.style.width = (r.conf * 100) + "%"; });
  }

  placeholder();
})();

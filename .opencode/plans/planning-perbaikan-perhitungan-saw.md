# Planning Perbaikan Fitur Perhitungan SAW

## 1. Tujuan Perubahan

Berdasarkan ketentuan yang diinginkan:

| # | Ketentuan | Status |
|---|-----------|--------|
| 1 | Perhitungan SAW hanya berjalan saat tombol Generate Penilaian diklik | Selesai (dari task sebelumnya) |
| 2 | Data Alternatif (C1, C2, C3, C4) muncul ketika menu SAW dibuka (tanpa generate) | Perlu diubah |
| 3 | Tabel Normalisasi, Nilai Preferensi, Ranking tidak muncul selama perhitungan belum dijalankan | Perlu diubah |
| 4 | Analisis codebase fitur SAW telah berjalan dengan baik | Perlu dicek |

---

## 2. Analisis Kondisi Saat Ini

### 2.1. Data Alternatif Tidak Muncul Sebelum Generate

Di PenilaianSawController@index() baris 35-66, saat transform criteria_summary:

Ketika penilaianSaw = null (belum generate), maka numericValue = null, sehingga Data Alternatif menampilkan "-" untuk semua nilai C1-C4.

### 2.2. Tabel Normalisasi/Preferensi/Ranking Masih Muncul (Meskipun Kosong)

View index.blade.php menggunakan @forelse + @empty, sehingga card/tabel tetap dirender dengan pesan "Tidak ada data". Ketentuan baru: seluruh card harus disembunyikan jika belum ada data.

### 2.3. Eager Loading Kurang profilePelanggan

Query di index() hanya load user.cabang dan penilaianSaw, tapi untuk komputasi C3 (calculateC3) dibutuhkan user.profilePelanggan.

### 2.4. Findings Lain

- hasGlobalAccess() tidak include role admin di PenilaianSawController (PengaduanController sudah include)
- clearSawScoreIfStatusIsNotValid() hapus SAW setelah assign-teknisi (mungkin intentional)

---

## 3. Rencana Implementasi

### 3.1. Controller: Compute C1-C4 On-The-Fly

File: app/Http/Controllers/PenilaianSawController.php

#### a) Tambah profilePelanggan di eager load (baris 25)

Sebelum:
  Pengaduan::with(['user.cabang', 'penilaianSaw'])

Sesudah:
  Pengaduan::with(['user.cabang', 'user.profilePelanggan', 'penilaianSaw'])

#### b) Ubah transform closure untuk compute on-the-fly (baris 35-66)

Jika nilaiSaw === null, hitung C1-C4 menggunakan method yang sudah ada (calculateC1, calculateC2, dll) tanpa simpan ke DB.

Logika:
- computedValues = [] jika ada nilaiSaw
- Jika tidak ada nilaiSaw, hitung: C1 dari calculateC1(item), C2 dari calculateC2(hours), C3 dari calculateC3(pelanggan), C4 dari calculateC4(distance)
- numericValue pakai dari DB jika ada, fallback ke computedValues

### 3.2. View: Sembunyikan Card Normalisasi, Preferensi, Ranking

File: resources/views/penilaian-saw/index.blade.php

#### a) Card Normalisasi (baris 102-153)
Bungkus dengan @if(->isNotEmpty())

#### b) Card Nilai Preferensi (baris 155-206)
Bungkus dengan @if(->isNotEmpty())

#### c) Card Ranking (baris 208-274)
Bungkus dengan @if(->isNotEmpty())

normalisasiRows dan rankedRows sudah difilter di controller hanya untuk item yang memiliki penilaianSaw. Sebelum generate, keduanya kosong, sehingga card-card tidak tampil.

### 3.3. Tidak Ada Perubahan Lain

- generate(), processAll(), recalculateScores() tetap seperti sekarang
- show.blade.php dan PengaduanController tidak perlu diubah
- Notifikasi di show.blade.php tetap muncul sebelum generate

---

## 4. Hasil Akhir yang Diharapkan

Sebelum Generate:
- Data Alternatif: menampilkan C1, C2, C3, C4 hasil komputasi on-the-fly
- Normalisasi: card tidak tampil
- Nilai Preferensi: card tidak tampil
- Ranking: card tidak tampil

Setelah Generate:
- Data Alternatif: tetap tampil (nilai dari DB)
- Normalisasi: card tampil dengan data
- Nilai Preferensi: card tampil dengan data
- Ranking: card tampil dengan data

---

## 5. Daftar Perubahan

| # | File | Perubahan | Risiko |
|---|------|-----------|--------|
| 1 | app/Http/Controllers/PenilaianSawController.php | Tambah user.profilePelanggan di eager load | Rendah |
| 2 | app/Http/Controllers/PenilaianSawController.php | Ubah transform: compute on-the-fly saat penilaianSaw null | Rendah |
| 3 | resources/views/penilaian-saw/index.blade.php | Bungkus 3 card dengan @if | Rendah |
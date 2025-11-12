# User Manual - Sistem SPK Rekomendasi Jurusan

## Daftar Isi
1. [Pengenalan Sistem](#pengenalan-sistem)
2. [Akses Sistem](#akses-sistem)
3. [Dashboard](#dashboard)
4. [Manajemen Data Siswa](#manajemen-data-siswa)
5. [Input Data Akademik](#input-data-akademik)
6. [Angket Minat](#angket-minat)
7. [Hasil Wawancara](#hasil-wawancara)
8. [Perhitungan SPK](#perhitungan-spk)
9. [Hasil dan Laporan](#hasil-dan-laporan)
10. [Troubleshooting](#troubleshooting)

## Pengenalan Sistem

Sistem SPK (Sistem Pendukung Keputusan) adalah aplikasi web yang membantu sekolah dalam memberikan rekomendasi jurusan (IPA/IPS) kepada siswa kelas 9. Sistem menggunakan metode hybrid SAW-VIKOR untuk menghasilkan rekomendasi yang objektif dan transparan.

### Fitur Utama:
- **Manajemen Data Siswa**: Input dan kelola data siswa
- **Input Nilai Akademik**: Masukkan nilai mata pelajaran
- **Angket Minat**: Survey minat dan kecenderungan siswa
- **Hasil Wawancara**: Input penilaian dari guru/konselor
- **Perhitungan SPK**: Algoritma SAW-VIKOR untuk rekomendasi
- **Visualisasi Hasil**: Grafik dan ranking hasil
- **Laporan Detail**: Detail perhitungan untuk setiap siswa

## Akses Sistem

### URL Aplikasi
```
http://localhost:8000
```

### Browser yang Didukung
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### Resolusi Layar
- **Desktop**: 1920x1080 atau lebih
- **Laptop**: 1366x768 atau lebih
- **Tablet**: 768x1024 atau lebih
- **Mobile**: 375x667 atau lebih

## Dashboard

### Halaman Utama
Dashboard menampilkan:
- **Statistik**: Total siswa, perhitungan selesai, tingkat kelengkapan
- **Aksi SPK**: Tombol untuk menghitung rekomendasi
- **Daftar Siswa**: 10 siswa terbaru dengan status data

### Navigasi
- **Dashboard**: Halaman utama
- **Data Siswa**: Kelola data siswa
- **Hasil SPK**: Lihat hasil perhitungan

### Status Data Siswa
- **Lengkap**: Semua data tersedia (Akademik + Angket + Wawancara)
- **2/3**: Sebagian data tersedia
- **1/3**: Sedikit data tersedia
- **Belum Ada**: Tidak ada data

## Manajemen Data Siswa

### 1. Menambah Siswa Baru

#### Langkah-langkah:
1. Klik tombol **"Tambah Siswa"** di dashboard
2. Isi form dengan data:
   - **Nama Lengkap**: Nama siswa
   - **NISN**: Nomor Induk Siswa Nasional
   - **Kelas**: Kelas siswa (contoh: IX-A)
   - **Jenis Kelamin**: Laki-laki atau Perempuan
3. Klik **"Simpan"**

#### Validasi:
- Nama wajib diisi
- NISN wajib diisi dan unik
- Kelas wajib diisi
- Jenis kelamin wajib dipilih

### 2. Melihat Detail Siswa

#### Langkah-langkah:
1. Dari daftar siswa, klik tombol **"Lihat Detail"** (ikon mata)
2. Halaman detail menampilkan:
   - Informasi pribadi siswa
   - Status data (Akademik, Angket, Wawancara)
   - Tombol untuk input data yang belum ada

### 3. Mengedit Data Siswa

#### Langkah-langkah:
1. Dari daftar siswa, klik tombol **"Edit"** (ikon pensil)
2. Ubah data yang diperlukan
3. Klik **"Simpan"**

### 4. Menghapus Data Siswa

#### Langkah-langkah:
1. Dari daftar siswa, klik tombol **"Hapus"** (ikon trash)
2. Konfirmasi penghapusan
3. Data siswa akan dihapus permanen

## Input Data Akademik

### Akses Form
1. Dari detail siswa, klik **"Tambah"** atau **"Edit"** di bagian Nilai Akademik
2. Atau dari daftar siswa, klik tombol **"Nilai Akademik"**

### Data yang Diinput
- **Matematika**: Skala 0-100
- **Bahasa Indonesia**: Skala 0-100
- **Bahasa Inggris**: Skala 0-100
- **IPA**: Skala 0-100
- **IPS**: Skala 0-100

### Tips Input
- Gunakan nilai rata-rata semester
- Pastikan nilai sesuai dengan skala 0-100
- Input nilai yang akurat dan objektif

### Validasi
- Semua field wajib diisi
- Nilai harus antara 0-100
- Format desimal diperbolehkan (contoh: 85.5)

## Angket Minat

### Akses Form
1. Dari detail siswa, klik **"Tambah"** atau **"Edit"** di bagian Angket Minat
2. Atau dari daftar siswa, klik tombol **"Angket Minat"**

### Pertanyaan Angket
Sistem menyediakan 10 pertanyaan dengan skala 1-5:

#### Pertanyaan 1-5 (Kecenderungan IPA):
1. Saya senang memecahkan masalah matematika yang rumit
2. Saya tertarik dengan eksperimen dan penelitian ilmiah
3. Saya suka menganalisis data dan statistik
4. Saya menikmati membaca buku sejarah dan geografi
5. Saya tertarik dengan perkembangan teknologi dan inovasi

#### Pertanyaan 6-10 (Kecenderungan IPS):
6. Saya suka berdiskusi tentang masalah sosial dan politik
7. Saya menikmati kegiatan laboratorium dan praktikum
8. Saya tertarik dengan bahasa dan sastra
9. Saya suka mempelajari fenomena alam dan lingkungan
10. Saya menikmati kegiatan yang melibatkan kreativitas dan seni

### Skala Penilaian
- **1**: Sangat Tidak Setuju
- **2**: Tidak Setuju
- **3**: Netral
- **4**: Setuju
- **5**: Sangat Setuju

### Tips Pengisian
- Jawab dengan jujur sesuai minat
- Tidak ada jawaban benar atau salah
- Pertimbangkan kecenderungan jangka panjang

## Hasil Wawancara

### Akses Form
1. Dari detail siswa, klik **"Tambah"** atau **"Edit"** di bagian Hasil Wawancara
2. Atau dari daftar siswa, klik tombol **"Wawancara"**

### Aspek Penilaian
Guru/konselor menilai 5 aspek dengan skala 1-5:

#### 1. Kemampuan Komunikasi
- Kemampuan siswa dalam berkomunikasi
- Keterampilan menyampaikan pendapat
- Kemampuan mendengarkan dan merespons

#### 2. Motivasi Belajar
- Tingkat semangat belajar
- Konsistensi dalam belajar
- Keinginan untuk berkembang

#### 3. Kepribadian
- Sikap dan karakter siswa
- Kemampuan bekerja sama
- Kedisiplinan dan tanggung jawab

#### 4. Potensi Akademik
- Kemampuan akademik siswa
- Potensi untuk berkembang
- Kemampuan analisis dan logika

#### 5. Orientasi Karir
- Pemahaman tentang karir
- Minat terhadap bidang tertentu
- Rencana masa depan

### Skala Penilaian
- **1**: Sangat Kurang
- **2**: Kurang
- **3**: Cukup
- **4**: Baik
- **5**: Sangat Baik

### Tips Penilaian
- Berikan penilaian yang objektif
- Pertimbangkan aspek keseluruhan
- Gunakan observasi selama wawancara
- Hindari bias personal

## Perhitungan SPK

### Prasyarat
Sebelum menghitung SPK, pastikan:
- Semua siswa memiliki data lengkap
- Data akademik, angket, dan wawancara sudah diinput
- Tidak ada data yang kosong

### Langkah Perhitungan
1. Dari dashboard, klik **"Hitung Rekomendasi SPK"**
2. Konfirmasi perhitungan
3. Tunggu proses selesai
4. Sistem akan redirect ke halaman hasil

### Proses Perhitungan
1. **Normalisasi Data (SAW)**:
   - Nilai akademik: dibagi 100
   - Angket minat: dibagi 50
   - Wawancara: dibagi 25

2. **Pembobotan**:
   - Akademik: 40%
   - Minat: 30%
   - Wawancara: 30%

3. **Perhitungan VIKOR**:
   - S (Utility Measure)
   - R (Regret Measure)
   - Q (Compromise Measure)

4. **Rekomendasi**:
   - Berdasarkan Q terendah
   - IPA atau IPS

## Hasil dan Laporan

### Halaman Hasil SPK
Menampilkan:
- **Statistik**: Jumlah rekomendasi IPA dan IPS
- **Grafik Distribusi**: Pie chart distribusi jurusan
- **Grafik Ranking**: Bar chart top 10 siswa
- **Tabel Hasil**: Ranking lengkap semua siswa

### Informasi dalam Tabel
- **Peringkat**: Urutan ranking
- **Nama Siswa**: Nama lengkap siswa
- **NISN**: Nomor Induk Siswa Nasional
- **Kelas**: Kelas siswa
- **Rekomendasi**: IPA atau IPS
- **Skor SAW**: Skor dari metode SAW
- **Skor VIKOR**: Skor dari metode VIKOR
- **Skor Akhir**: Skor final untuk ranking

### Detail Perhitungan
1. Klik tombol **"Detail"** pada siswa tertentu
2. Halaman detail menampilkan:
   - Informasi siswa
   - Skor SAW, VIKOR, dan Final
   - Detail perhitungan
   - Penjelasan metode

### Informasi Detail
- **Skor untuk Setiap Jurusan**: IPA dan IPS
- **Nilai VIKOR**: S, R, Q untuk setiap jurusan
- **Penjelasan Metode**: Algoritma SAW dan VIKOR
- **Rekomendasi**: Jurusan yang direkomendasikan

### Export dan Print
- **Print**: Klik tombol "Cetak" untuk print hasil
- **Export**: Data dapat diekspor ke Excel/PDF (fitur tambahan)

## Troubleshooting

### Masalah Umum

#### 1. Halaman Tidak Dapat Diakses
**Penyebab**: Server tidak berjalan
**Solusi**: 
- Pastikan server Laravel berjalan
- Cek URL yang benar
- Restart server jika perlu

#### 2. Data Tidak Tersimpan
**Penyebab**: Validasi gagal atau database error
**Solusi**:
- Cek semua field wajib diisi
- Pastikan format data benar
- Cek koneksi database

#### 3. Perhitungan SPK Gagal
**Penyebab**: Data siswa tidak lengkap
**Solusi**:
- Pastikan semua siswa memiliki data lengkap
- Cek data akademik, angket, dan wawancara
- Input data yang hilang

#### 4. Grafik Tidak Muncul
**Penyebab**: JavaScript error atau data kosong
**Solusi**:
- Refresh halaman
- Cek browser console untuk error
- Pastikan data sudah ada

#### 5. Form Tidak Responsif
**Penyebab**: JavaScript error atau CSS tidak load
**Solusi**:
- Clear browser cache
- Disable browser extensions
- Cek koneksi internet

### Error Messages

#### "Data siswa tidak lengkap"
- **Penyebab**: Ada siswa yang belum memiliki data lengkap
- **Solusi**: Lengkapi data akademik, angket, dan wawancara

#### "Terjadi kesalahan dalam perhitungan"
- **Penyebab**: Error dalam algoritma atau data
- **Solusi**: Cek data input dan coba lagi

#### "Halaman tidak ditemukan"
- **Penyebab**: URL salah atau halaman tidak ada
- **Solusi**: Gunakan navigasi yang benar

### Kontak Support

Jika mengalami masalah yang tidak dapat diselesaikan:
- **Email**: support@yourdomain.com
- **Telepon**: (021) 1234-5678
- **Jam Kerja**: Senin-Jumat, 08:00-17:00

### Tips Penggunaan

#### 1. Backup Data
- Lakukan backup data secara berkala
- Export data penting ke file Excel
- Simpan backup di tempat aman

#### 2. Validasi Data
- Cek data sebelum perhitungan SPK
- Pastikan semua field terisi dengan benar
- Verifikasi hasil perhitungan

#### 3. Keamanan
- Jangan share login dengan orang lain
- Logout setelah selesai menggunakan
- Gunakan password yang kuat

#### 4. Performance
- Tutup tab browser yang tidak perlu
- Clear cache browser secara berkala
- Gunakan browser yang update

### FAQ (Frequently Asked Questions)

#### Q: Apakah data siswa aman?
A: Ya, data disimpan dengan enkripsi dan backup berkala.

#### Q: Bisakah mengubah bobot kriteria?
A: Ya, dapat diubah di file konfigurasi sistem.

#### Q: Apakah hasil dapat diekspor?
A: Ya, hasil dapat diekspor ke Excel atau PDF.

#### Q: Berapa lama proses perhitungan?
A: Tergantung jumlah siswa, biasanya 1-5 detik.

#### Q: Apakah sistem dapat digunakan offline?
A: Tidak, sistem memerlukan koneksi internet.

#### Q: Bisakah menambah jurusan lain?
A: Ya, dapat dikonfigurasi untuk jurusan tambahan.

## Kesimpulan

Sistem SPK ini dirancang untuk memudahkan proses rekomendasi jurusan siswa dengan metode yang objektif dan transparan. Dengan mengikuti panduan ini, pengguna dapat memanfaatkan semua fitur sistem dengan optimal.

Untuk pertanyaan lebih lanjut atau bantuan teknis, silakan hubungi tim support.













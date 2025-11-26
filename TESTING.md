# Panduan Testing Sistem SPK

## Persiapan Testing

### 1. Setup Environment
```bash
# Pastikan server berjalan
php artisan serve

# Akses aplikasi di browser
http://localhost:8000
```

### 2. Data Testing
Aplikasi sudah dilengkapi dengan data contoh dari seeder:
- 5 siswa dengan data lengkap
- Nilai akademik, angket minat, dan wawancara
- Siap untuk perhitungan SPK

## Skenario Testing

### 1. Dashboard SPK
**URL**: `http://localhost:8000/spk`

**Test Cases**:
- [ ] Halaman dashboard dapat diakses
- [ ] Statistik menampilkan data yang benar
- [ ] Tombol "Hitung Rekomendasi SPK" tersedia
- [ ] Daftar siswa terbaru ditampilkan
- [ ] Status data siswa ditampilkan dengan benar

**Expected Results**:
- Total siswa: 5
- Perhitungan selesai: 0 (sebelum dihitung)
- Tingkat kelengkapan: 100%

### 2. Manajemen Data Siswa
**URL**: `http://localhost:8000/students`

**Test Cases**:
- [ ] Daftar siswa ditampilkan
- [ ] Tombol "Tambah Siswa" berfungsi
- [ ] Tombol "Lihat Detail" berfungsi
- [ ] Tombol "Edit" berfungsi
- [ ] Tombol "Hapus" berfungsi

**Test Case: Tambah Siswa Baru**
1. Klik "Tambah Siswa"
2. Isi form:
   - Nama: "Test Student"
   - NISN: "9999999999"
   - Kelas: "IX-D"
   - Jenis Kelamin: "Laki-laki"
3. Klik "Simpan"
4. Verifikasi data tersimpan

### 3. Input Nilai Akademik
**URL**: `http://localhost:8000/students/{id}/academic`

**Test Cases**:
- [ ] Form nilai akademik dapat diakses
- [ ] Input validation berfungsi
- [ ] Skor otomatis terhitung
- [ ] Data tersimpan dengan benar

**Test Case: Input Nilai**
1. Akses form nilai akademik
2. Isi nilai:
   - Matematika: 90
   - Bahasa Indonesia: 85
   - Bahasa Inggris: 88
   - IPA: 92
   - IPS: 80
3. Klik "Simpan Nilai Akademik"
4. Verifikasi data tersimpan

### 4. Angket Minat
**URL**: `http://localhost:8000/students/{id}/interest`

**Test Cases**:
- [ ] Form angket dapat diakses
- [ ] Radio button berfungsi
- [ ] Total skor otomatis terhitung
- [ ] Data tersimpan dengan benar

**Test Case: Isi Angket**
1. Akses form angket minat
2. Pilih jawaban untuk setiap pertanyaan (1-5)
3. Verifikasi total skor terhitung
4. Klik "Simpan Angket Minat"
5. Verifikasi data tersimpan

### 5. Hasil Wawancara
**URL**: `http://localhost:8000/students/{id}/interview`

**Test Cases**:
- [ ] Form wawancara dapat diakses
- [ ] Radio button berfungsi
- [ ] Total skor otomatis terhitung
- [ ] Warna skor berubah sesuai nilai
- [ ] Data tersimpan dengan benar

**Test Case: Input Wawancara**
1. Akses form wawancara
2. Pilih skor untuk setiap aspek (1-5)
3. Verifikasi total skor dan warna
4. Klik "Simpan Hasil Wawancara"
5. Verifikasi data tersimpan

### 6. Perhitungan SPK
**URL**: `http://localhost:8000/spk`

**Test Cases**:
- [ ] Tombol "Hitung Rekomendasi SPK" berfungsi
- [ ] Konfirmasi dialog muncul
- [ ] Perhitungan berhasil
- [ ] Redirect ke halaman hasil

**Test Case: Hitung SPK**
1. Klik "Hitung Rekomendasi SPK"
2. Konfirmasi dialog
3. Tunggu proses selesai
4. Verifikasi redirect ke hasil

### 7. Hasil SPK
**URL**: `http://localhost:8000/spk/results`

**Test Cases**:
- [ ] Statistik distribusi jurusan ditampilkan
- [ ] Grafik distribusi berfungsi
- [ ] Grafik ranking berfungsi
- [ ] Tabel hasil ditampilkan
- [ ] Tombol "Detail" berfungsi

**Expected Results**:
- Distribusi IPA dan IPS
- Ranking siswa berdasarkan skor
- Grafik interaktif

### 8. Detail Perhitungan
**URL**: `http://localhost:8000/spk/detail/{id}`

**Test Cases**:
- [ ] Informasi siswa ditampilkan
- [ ] Skor SAW, VIKOR, dan Final ditampilkan
- [ ] Detail perhitungan ditampilkan
- [ ] Penjelasan metode ditampilkan

**Expected Results**:
- Skor untuk setiap jurusan
- Nilai S, R, Q untuk VIKOR
- Penjelasan algoritma

## Testing API Endpoints

### 1. Chart Data
**URL**: `http://localhost:8000/spk/chart-data`
**Method**: GET

**Expected Response**:
```json
{
  "labels": ["IPA", "IPS"],
  "data": [3, 2]
}
```

### 2. Ranking Data
**URL**: `http://localhost:8000/spk/ranking-data`
**Method**: GET

**Expected Response**:
```json
[
  {
    "rank": 1,
    "name": "Ahmad Rizki",
    "major": "IPA",
    "score": 0.8234
  }
]
```

## Testing Responsiveness

### 1. Desktop (1920x1080)
- [ ] Layout responsif
- [ ] Sidebar dan konten seimbang
- [ ] Grafik tampil dengan baik

### 2. Tablet (768x1024)
- [ ] Layout menyesuaikan
- [ ] Sidebar dapat di-collapse
- [ ] Tabel dapat di-scroll horizontal

### 3. Mobile (375x667)
- [ ] Layout mobile-friendly
- [ ] Sidebar menjadi hamburger menu
- [ ] Form input mudah digunakan

## Testing Performance

### 1. Load Time
- [ ] Halaman utama < 2 detik
- [ ] Form input < 1 detik
- [ ] Perhitungan SPK < 5 detik

### 2. Memory Usage
- [ ] Tidak ada memory leak
- [ ] Database query optimal
- [ ] Asset loading efisien

## Testing Error Handling

### 1. Database Error
- [ ] Koneksi database gagal
- [ ] Tabel tidak ada
- [ ] Data corrupt

### 2. Validation Error
- [ ] Input tidak valid
- [ ] Data tidak lengkap
- [ ] Format salah

### 3. System Error
- [ ] Server error 500
- [ ] Not found 404
- [ ] Unauthorized 403

## Testing Security

### 1. Input Validation
- [ ] SQL injection protection
- [ ] XSS protection
- [ ] CSRF protection

### 2. Data Integrity
- [ ] Data tidak dapat diubah sembarangan
- [ ] Backup data tersedia
- [ ] Log aktivitas tersimpan

## Checklist Testing

### Pre-Testing
- [ ] Environment setup selesai
- [ ] Database terhubung
- [ ] Assets ter-compile
- [ ] Server berjalan

### Functional Testing
- [ ] Semua fitur berfungsi
- [ ] Data tersimpan dengan benar
- [ ] Perhitungan akurat
- [ ] UI/UX responsif

### Integration Testing
- [ ] Database integration
- [ ] Frontend-Backend integration
- [ ] API integration
- [ ] Third-party integration

### User Acceptance Testing
- [ ] User dapat menggunakan aplikasi
- [ ] Hasil sesuai ekspektasi
- [ ] Performance memuaskan
- [ ] Error handling baik

## Reporting Bug

### Format Laporan Bug
```
**Bug ID**: BUG-001
**Title**: [Judul bug]
**Severity**: [Critical/High/Medium/Low]
**Priority**: [P1/P2/P3/P4]
**Environment**: [Browser/OS/Device]
**Steps to Reproduce**:
1. [Langkah 1]
2. [Langkah 2]
3. [Langkah 3]

**Expected Result**: [Hasil yang diharapkan]
**Actual Result**: [Hasil yang terjadi]
**Screenshot**: [Jika ada]
**Additional Info**: [Info tambahan]
```

### Testing Tools
- **Browser**: Chrome, Firefox, Safari, Edge
- **Mobile**: Chrome Mobile, Safari Mobile
- **Testing Tools**: Postman, Browser DevTools
- **Performance**: Lighthouse, GTmetrix

## Conclusion

Testing ini memastikan bahwa sistem SPK berfungsi dengan baik dan memberikan hasil yang akurat untuk rekomendasi jurusan siswa. Semua skenario testing harus dilakukan sebelum aplikasi digunakan dalam produksi.
















# Sistem SPK - Rekomendasi Jurusan dengan Metode Hybrid SAW-VIKOR

Sistem Pendukung Keputusan (SPK) untuk rekomendasi jurusan siswa menggunakan metode hybrid SAW (Simple Additive Weighting) dan VIKOR (VlseKriterijumska Optimizacija I Kompromisno Resenje).

## Fitur Utama

- **Input Data Siswa**: Manajemen data siswa dengan informasi lengkap
- **Nilai Akademik**: Input nilai mata pelajaran (Matematika, Bahasa Indonesia, Bahasa Inggris, IPA, IPS)
- **Angket Minat**: Survey minat siswa dengan 10 pertanyaan
- **Hasil Wawancara**: Penilaian 5 aspek (Komunikasi, Motivasi, Kepribadian, Potensi Akademik, Orientasi Karir)
- **Perhitungan SPK**: Implementasi algoritma SAW untuk normalisasi dan VIKOR untuk perangkingan
- **Visualisasi Hasil**: Grafik dan ranking hasil rekomendasi
- **Laporan Detail**: Detail perhitungan untuk setiap siswa

## Alur Kerja

1. **Input Data Siswa**
   - Data pribadi siswa (nama, NISN, kelas, jenis kelamin)
   - Nilai akademik (skala 0-100)
   - Hasil angket minat (skala 1-5)
   - Hasil wawancara (skala 1-5)

2. **Normalisasi (SAW)**
   - Semua nilai diubah ke skala 0-1
   - Pembobotan berdasarkan kriteria jurusan

3. **Perangkingan (VIKOR)**
   - Perhitungan nilai kedekatan ideal
   - Rekomendasi jurusan terbaik

4. **Output**
   - Rekomendasi jurusan (IPA/IPS)
   - Skor pendukung
   - Grafik distribusi
   - Ranking siswa

## Metode Perhitungan

### SAW (Simple Additive Weighting)
- **Normalisasi**: `nilai / nilai_maksimal`
- **Bobot Kriteria**:
  - Akademik: 40%
  - Minat: 30%
  - Wawancara: 30%

### VIKOR (VlseKriterijumska Optimizacija I Kompromisno Resenje)
- **S (Utility Measure)**: `(max - score) / (max - min)`
- **R (Regret Measure)**: Nilai maksimum dari semua kriteria
- **Q (Compromise Measure)**: `v * S + (1-v) * R` (v = 0.5)
- **Rekomendasi**: Berdasarkan Q terendah

### Kriteria Jurusan

#### IPA (Ilmu Pengetahuan Alam)
- Matematika: 30%
- IPA: 30%
- Bahasa Inggris: 20%
- Bahasa Indonesia: 10%
- IPS: 10%

#### IPS (Ilmu Pengetahuan Sosial)
- IPS: 30%
- Bahasa Indonesia: 30%
- Bahasa Inggris: 20%
- Matematika: 10%
- IPA: 10%

## Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd spk-skripsi
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup Database**
   - Buat database MySQL
   - Update konfigurasi database di `.env`
   ```bash
   php artisan migrate
   php artisan db:seed --class=StudentSeeder
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Jalankan Server**
   ```bash
   php artisan serve
   ```

## Struktur Database

### Tabel `students`
- `id`: Primary key
- `name`: Nama siswa
- `nisn`: Nomor Induk Siswa Nasional
- `class`: Kelas
- `gender`: Jenis kelamin (L/P)

### Tabel `academic_scores`
- `id`: Primary key
- `student_id`: Foreign key ke students
- `mathematics`: Nilai matematika
- `indonesian`: Nilai bahasa Indonesia
- `english`: Nilai bahasa Inggris
- `science`: Nilai IPA
- `social_studies`: Nilai IPS

### Tabel `interest_surveys`
- `id`: Primary key
- `student_id`: Foreign key ke students
- `answers`: JSON array jawaban angket
- `total_score`: Total skor angket

### Tabel `interview_scores`
- `id`: Primary key
- `student_id`: Foreign key ke students
- `communication_skill`: Skor komunikasi (1-5)
- `motivation`: Skor motivasi (1-5)
- `personality`: Skor kepribadian (1-5)
- `academic_potential`: Skor potensi akademik (1-5)
- `career_orientation`: Skor orientasi karir (1-5)
- `total_score`: Total skor wawancara

### Tabel `spk_results`
- `id`: Primary key
- `student_id`: Foreign key ke students
- `recommended_major`: Jurusan yang direkomendasikan
- `saw_score`: Skor SAW
- `vikor_score`: Skor VIKOR
- `final_score`: Skor akhir
- `rank`: Peringkat
- `calculation_details`: JSON detail perhitungan

## API Endpoints

- `GET /spk` - Dashboard SPK
- `POST /spk/calculate` - Hitung rekomendasi SPK
- `GET /spk/results` - Hasil SPK
- `GET /spk/detail/{id}` - Detail perhitungan
- `GET /spk/chart-data` - Data grafik
- `GET /spk/ranking-data` - Data ranking

## Teknologi yang Digunakan

- **Backend**: Laravel 11
- **Frontend**: Bootstrap 5, Chart.js
- **Database**: MySQL
- **JavaScript**: Vanilla JS, jQuery
- **CSS**: Custom CSS, Bootstrap

## Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## Kontak

- Nama: [Your Name]
- Email: [your.email@example.com]
- Project Link: [https://github.com/yourusername/spk-skripsi](https://github.com/yourusername/spk-skripsi)
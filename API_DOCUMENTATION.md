# API Documentation - Sistem SPK

## Base URL
```
http://localhost:8000
```

## Authentication
Sistem ini menggunakan session-based authentication. Pastikan user sudah login sebelum mengakses endpoint yang memerlukan autentikasi.

## Endpoints

### 1. Dashboard SPK

#### GET /spk
Menampilkan dashboard SPK dengan statistik dan daftar siswa.

**Response:**
```json
{
  "students": [
    {
      "id": 1,
      "name": "Ahmad Rizki",
      "nisn": "1234567890",
      "class": "IX-A",
      "gender": "L",
      "academic_score": {
        "mathematics": 85,
        "indonesian": 78,
        "english": 82,
        "science": 88,
        "social_studies": 75
      },
      "interest_survey": {
        "answers": [5, 4, 5, 3, 4, 2, 5, 3, 4, 2],
        "total_score": 37
      },
      "interview_score": {
        "communication_skill": 4,
        "motivation": 5,
        "personality": 4,
        "academic_potential": 5,
        "career_orientation": 4,
        "total_score": 22
      }
    }
  ],
  "total_students": 5,
  "completed_calculations": 0
}
```

### 2. Hitung Rekomendasi SPK

#### POST /spk/calculate
Menghitung rekomendasi SPK untuk semua siswa.

**Request Body:**
```json
{
  "_token": "csrf_token"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Perhitungan SPK berhasil dilakukan untuk 5 siswa",
  "redirect": "/spk/results"
}
```

**Error Response:**
```json
{
  "error": true,
  "message": "Terjadi kesalahan: Data siswa tidak lengkap"
}
```

### 3. Hasil SPK

#### GET /spk/results
Menampilkan hasil perhitungan SPK.

**Response:**
```json
{
  "results": [
    {
      "id": 1,
      "student_id": 1,
      "recommended_major": "IPA",
      "saw_score": 0.8234,
      "vikor_score": 0.0000,
      "final_score": 0.8234,
      "rank": 1,
      "student": {
        "id": 1,
        "name": "Ahmad Rizki",
        "nisn": "1234567890",
        "class": "IX-A",
        "gender": "L"
      }
    }
  ],
  "ipa_count": 3,
  "ips_count": 2
}
```

### 4. Detail Perhitungan

#### GET /spk/detail/{studentId}
Menampilkan detail perhitungan untuk siswa tertentu.

**Parameters:**
- `studentId` (integer): ID siswa

**Response:**
```json
{
  "result": {
    "id": 1,
    "student_id": 1,
    "recommended_major": "IPA",
    "saw_score": 0.8234,
    "vikor_score": 0.0000,
    "final_score": 0.8234,
    "rank": 1,
    "calculation_details": {
      "scores": {
        "IPA": 0.8234,
        "IPS": 0.7980
      },
      "s_values": {
        "IPA": 0.0000,
        "IPS": 1.0000
      },
      "r_values": {
        "IPA": 0.0000,
        "IPS": 1.0000
      },
      "q_values": {
        "IPA": 0.0000,
        "IPS": 1.0000
      },
      "max_score": 0.8234,
      "min_score": 0.7980
    },
    "student": {
      "id": 1,
      "name": "Ahmad Rizki",
      "nisn": "1234567890",
      "class": "IX-A",
      "gender": "L"
    }
  }
}
```

### 5. Data Grafik

#### GET /spk/chart-data
Mengembalikan data untuk grafik distribusi jurusan.

**Response:**
```json
{
  "labels": ["IPA", "IPS"],
  "data": [3, 2]
}
```

### 6. Data Ranking

#### GET /spk/ranking-data
Mengembalikan data ranking top 10 siswa.

**Response:**
```json
[
  {
    "rank": 1,
    "name": "Ahmad Rizki",
    "major": "IPA",
    "score": 0.8234
  },
  {
    "rank": 2,
    "name": "Budi Santoso",
    "major": "IPA",
    "score": 0.8156
  }
]
```

## Student Management Endpoints

### 1. Daftar Siswa

#### GET /students
Menampilkan daftar siswa dengan pagination.

**Query Parameters:**
- `page` (integer, optional): Halaman (default: 1)
- `per_page` (integer, optional): Jumlah item per halaman (default: 10)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Ahmad Rizki",
      "nisn": "1234567890",
      "class": "IX-A",
      "gender": "L",
      "academic_score": {...},
      "interest_survey": {...},
      "interview_score": {...}
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 10,
  "total": 5
}
```

### 2. Detail Siswa

#### GET /students/{id}
Menampilkan detail siswa.

**Parameters:**
- `id` (integer): ID siswa

**Response:**
```json
{
  "student": {
    "id": 1,
    "name": "Ahmad Rizki",
    "nisn": "1234567890",
    "class": "IX-A",
    "gender": "L",
    "academic_score": {
      "id": 1,
      "student_id": 1,
      "mathematics": 85,
      "indonesian": 78,
      "english": 82,
      "science": 88,
      "social_studies": 75
    },
    "interest_survey": {
      "id": 1,
      "student_id": 1,
      "answers": [5, 4, 5, 3, 4, 2, 5, 3, 4, 2],
      "total_score": 37
    },
    "interview_score": {
      "id": 1,
      "student_id": 1,
      "communication_skill": 4,
      "motivation": 5,
      "personality": 4,
      "academic_potential": 5,
      "career_orientation": 4,
      "total_score": 22
    }
  }
}
```

### 3. Tambah Siswa

#### POST /students
Menambah siswa baru.

**Request Body:**
```json
{
  "name": "Test Student",
  "nisn": "9999999999",
  "class": "IX-D",
  "gender": "L",
  "_token": "csrf_token"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Data siswa berhasil ditambahkan",
  "redirect": "/students/6"
}
```

**Validation Errors:**
```json
{
  "errors": {
    "name": ["The name field is required."],
    "nisn": ["The nisn field is required."],
    "class": ["The class field is required."],
    "gender": ["The gender field is required."]
  }
}
```

### 4. Update Siswa

#### PUT /students/{id}
Mengupdate data siswa.

**Parameters:**
- `id` (integer): ID siswa

**Request Body:**
```json
{
  "name": "Updated Name",
  "nisn": "1234567890",
  "class": "IX-A",
  "gender": "L",
  "_token": "csrf_token",
  "_method": "PUT"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Data siswa berhasil diperbarui"
}
```

### 5. Hapus Siswa

#### DELETE /students/{id}
Menghapus data siswa.

**Parameters:**
- `id` (integer): ID siswa

**Response:**
```json
{
  "success": true,
  "message": "Data siswa berhasil dihapus"
}
```

## Academic Score Endpoints

### 1. Form Nilai Akademik

#### GET /students/{id}/academic
Menampilkan form input nilai akademik.

**Parameters:**
- `id` (integer): ID siswa

### 2. Simpan Nilai Akademik

#### POST /students/{id}/academic
Menyimpan nilai akademik siswa.

**Parameters:**
- `id` (integer): ID siswa

**Request Body:**
```json
{
  "mathematics": 90,
  "indonesian": 85,
  "english": 88,
  "science": 92,
  "social_studies": 80,
  "_token": "csrf_token"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Nilai akademik berhasil disimpan"
}
```

## Interest Survey Endpoints

### 1. Form Angket Minat

#### GET /students/{id}/interest
Menampilkan form angket minat.

**Parameters:**
- `id` (integer): ID siswa

### 2. Simpan Angket Minat

#### POST /students/{id}/interest
Menyimpan hasil angket minat.

**Parameters:**
- `id` (integer): ID siswa

**Request Body:**
```json
{
  "answers": [5, 4, 5, 3, 4, 2, 5, 3, 4, 2],
  "_token": "csrf_token"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Hasil angket minat berhasil disimpan"
}
```

## Interview Score Endpoints

### 1. Form Wawancara

#### GET /students/{id}/interview
Menampilkan form hasil wawancara.

**Parameters:**
- `id` (integer): ID siswa

### 2. Simpan Hasil Wawancara

#### POST /students/{id}/interview
Menyimpan hasil wawancara.

**Parameters:**
- `id` (integer): ID siswa

**Request Body:**
```json
{
  "communication_skill": 4,
  "motivation": 5,
  "personality": 4,
  "academic_potential": 5,
  "career_orientation": 4,
  "_token": "csrf_token"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Hasil wawancara berhasil disimpan"
}
```

## Error Responses

### 400 Bad Request
```json
{
  "error": true,
  "message": "Invalid request data",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

### 404 Not Found
```json
{
  "error": true,
  "message": "Resource not found"
}
```

### 500 Internal Server Error
```json
{
  "error": true,
  "message": "Internal server error"
}
```

## Rate Limiting

Sistem menggunakan rate limiting untuk mencegah abuse:
- **Default**: 60 requests per minute per IP
- **API endpoints**: 30 requests per minute per IP

## CORS Configuration

Untuk development, CORS diaktifkan untuk:
- `http://localhost:3000`
- `http://localhost:8080`

## Testing API

### Using cURL

#### Test Dashboard
```bash
curl -X GET http://localhost:8000/spk \
  -H "Accept: application/json"
```

#### Test Calculate SPK
```bash
curl -X POST http://localhost:8000/spk/calculate \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"_token": "your_csrf_token"}'
```

#### Test Chart Data
```bash
curl -X GET http://localhost:8000/spk/chart-data \
  -H "Accept: application/json"
```

### Using Postman

1. **Import Collection**: Import file `SPK_API.postman_collection.json`
2. **Set Environment**: 
   - `base_url`: `http://localhost:8000`
   - `csrf_token`: Get from login response
3. **Run Tests**: Execute collection tests

## SDK Examples

### JavaScript (Fetch API)
```javascript
// Get chart data
fetch('/spk/chart-data')
  .then(response => response.json())
  .then(data => {
    console.log('Chart data:', data);
  });

// Calculate SPK
fetch('/spk/calculate', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify({})
})
.then(response => response.json())
.then(data => {
  console.log('Calculation result:', data);
});
```

### PHP (Guzzle)
```php
use GuzzleHttp\Client;

$client = new Client(['base_uri' => 'http://localhost:8000']);

// Get results
$response = $client->get('/spk/results');
$data = json_decode($response->getBody(), true);

// Calculate SPK
$response = $client->post('/spk/calculate', [
    'form_params' => [
        '_token' => $csrfToken
    ]
]);
```

## Webhooks

Sistem tidak menggunakan webhooks, tetapi dapat dikonfigurasi untuk:
- Notifikasi email setelah perhitungan SPK
- Log aktivitas ke sistem eksternal
- Backup otomatis ke cloud storage

## Versioning

API saat ini menggunakan versi 1.0. Untuk perubahan breaking, akan menggunakan versioning:
- `v1`: Current version
- `v2`: Future version (jika ada)

## Support

Untuk pertanyaan atau bantuan:
- **Email**: support@yourdomain.com
- **Documentation**: https://docs.yourdomain.com
- **Issues**: https://github.com/yourusername/spk-skripsi/issues


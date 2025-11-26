# Dokumentasi Algoritma SPK Hybrid SAW-VIKOR

## Pendahuluan

Sistem Pendukung Keputusan (SPK) ini menggunakan metode hybrid SAW-VIKOR untuk rekomendasi jurusan siswa. SAW digunakan untuk normalisasi data, sedangkan VIKOR digunakan untuk perangkingan.

## Metode SAW (Simple Additive Weighting)

### 1. Normalisasi Data

Semua data diubah ke skala 0-1 menggunakan rumus:

```
Rij = Xij / Xmax
```

Dimana:
- `Rij` = Nilai normalisasi
- `Xij` = Nilai asli
- `Xmax` = Nilai maksimum (100 untuk akademik, 50 untuk angket, 25 untuk wawancara)

### 2. Pembobotan Kriteria

#### Bobot Utama:
- **Akademik**: 40%
- **Minat**: 30%
- **Wawancara**: 30%

#### Bobot Sub-kriteria untuk IPA:
- Matematika: 30%
- IPA: 30%
- Bahasa Inggris: 20%
- Bahasa Indonesia: 10%
- IPS: 10%

#### Bobot Sub-kriteria untuk IPS:
- IPS: 30%
- Bahasa Indonesia: 30%
- Bahasa Inggris: 20%
- Matematika: 10%
- IPA: 10%

### 3. Perhitungan Skor SAW

```
Skor SAW = Σ(Wi × Rij)
```

Dimana:
- `Wi` = Bobot kriteria
- `Rij` = Nilai normalisasi

## Metode VIKOR

### 1. Perhitungan S (Utility Measure)

```
S = (f* - fi) / (f* - f-)
```

Dimana:
- `f*` = Nilai maksimum
- `fi` = Nilai alternatif i
- `f-` = Nilai minimum

### 2. Perhitungan R (Regret Measure)

```
R = max[(f* - fi) / (f* - f-)]
```

### 3. Perhitungan Q (Compromise Measure)

```
Q = v × S + (1-v) × R
```

Dimana `v = 0.5` (parameter kompromi)

### 4. Kriteria Penerimaan

1. **Acceptable Advantage**:
   ```
   Q(A(2)) - Q(A(1)) ≥ 1/(m-1)
   ```

2. **Acceptable Stability**:
   Alternatif A(1) harus juga memiliki S atau R terbaik

3. **Rekomendasi**:
   - Jika kedua kondisi terpenuhi → A(1) direkomendasikan
   - Jika tidak → A(1) dan A(2) direkomendasikan

## Implementasi dalam Kode

### 1. Normalisasi Data (SAW)

```php
private function normalizeData($academicScore, $interestSurvey, $interviewScore): array
{
    // Normalisasi nilai akademik (0-100 -> 0-1)
    $normalizedAcademic = [
        'mathematics' => $academicScore->mathematics / 100,
        'indonesian' => $academicScore->indonesian / 100,
        'english' => $academicScore->english / 100,
        'science' => $academicScore->science / 100,
        'social_studies' => $academicScore->social_studies / 100
    ];

    // Normalisasi angket minat (0-50 -> 0-1)
    $normalizedInterest = $interestSurvey->total_score / 50;

    // Normalisasi wawancara (0-25 -> 0-1)
    $normalizedInterview = $interviewScore->total_score / 25;

    return [
        'academic' => $normalizedAcademic,
        'interest' => $normalizedInterest,
        'interview' => $normalizedInterview
    ];
}
```

### 2. Perhitungan Skor Jurusan

```php
private function calculateMajorScore(array $normalizedData, string $major): float
{
    $criteria = $major === 'IPA' ? $this->ipaCriteria : $this->ipsCriteria;
    
    $academicScore = 0;
    foreach ($criteria as $subject => $weight) {
        $academicScore += $normalizedData['academic'][$subject] * $weight;
    }

    // Hitung skor akhir dengan bobot
    $finalScore = (
        $academicScore * $this->weights['academic'] +
        $normalizedData['interest'] * $this->weights['interest'] +
        $normalizedData['interview'] * $this->weights['interview']
    );

    return $finalScore;
}
```

### 3. Implementasi VIKOR

```php
private function calculateVikor(float $ipaScore, float $ipsScore): array
{
    $scores = [
        'IPA' => $ipaScore,
        'IPS' => $ipsScore
    ];

    // Hitung nilai maksimum dan minimum
    $maxScore = max($scores);
    $minScore = min($scores);

    // Hitung S (utility measure) dan R (regret measure)
    $s = [];
    $r = [];
    
    foreach ($scores as $major => $score) {
        // S = (max - score) / (max - min)
        $s[$major] = ($maxScore - $score) / ($maxScore - $minScore);
        
        // R = max dari semua kriteria
        $r[$major] = $s[$major];
    }

    // Hitung Q (compromise measure) dengan v = 0.5
    $v = 0.5;
    $q = [];
    foreach ($scores as $major => $score) {
        $q[$major] = $v * $s[$major] + (1 - $v) * $r[$major];
    }

    // Tentukan rekomendasi berdasarkan Q terendah
    $recommendedMajor = array_keys($q, min($q))[0];
    $vikorScore = min($q);

    return [
        'recommended_major' => $recommendedMajor,
        'saw_score' => $scores[$recommendedMajor],
        'vikor_score' => $vikorScore,
        'final_score' => $scores[$recommendedMajor] * (1 - $vikorScore),
        'details' => [
            'scores' => $scores,
            's_values' => $s,
            'r_values' => $r,
            'q_values' => $q,
            'max_score' => $maxScore,
            'min_score' => $minScore
        ]
    ];
}
```

## Contoh Perhitungan

### Data Input:
- **Nilai Akademik**: Matematika=85, Indonesia=78, Inggris=82, IPA=88, IPS=75
- **Angket Minat**: Total skor = 37
- **Wawancara**: Total skor = 22

### Normalisasi:
- **Akademik**: Math=0.85, Indo=0.78, Eng=0.82, IPA=0.88, IPS=0.75
- **Minat**: 37/50 = 0.74
- **Wawancara**: 22/25 = 0.88

### Skor SAW:
- **IPA**: (0.85×0.3 + 0.88×0.3 + 0.82×0.2 + 0.78×0.1 + 0.75×0.1) × 0.4 + 0.74×0.3 + 0.88×0.3 = 0.823
- **IPS**: (0.75×0.3 + 0.78×0.3 + 0.82×0.2 + 0.85×0.1 + 0.88×0.1) × 0.4 + 0.74×0.3 + 0.88×0.3 = 0.798

### VIKOR:
- **S(IPA)**: (0.823-0.823)/(0.823-0.798) = 0
- **S(IPS)**: (0.823-0.798)/(0.823-0.798) = 1
- **Q(IPA)**: 0.5×0 + 0.5×0 = 0
- **Q(IPS)**: 0.5×1 + 0.5×1 = 1

### Rekomendasi:
**IPA** (Q terendah = 0)

## Keunggulan Metode Hybrid SAW-VIKOR

1. **SAW**: Mudah dipahami dan diimplementasikan
2. **VIKOR**: Mempertimbangkan kompromi dan regret
3. **Hybrid**: Kombinasi keunggulan kedua metode
4. **Transparan**: Detail perhitungan dapat dilihat
5. **Fleksibel**: Bobot dapat disesuaikan

## Kelemahan dan Solusi

### Kelemahan:
1. **Subjektif**: Bobot ditentukan secara subjektif
2. **Linear**: Asumsi hubungan linear
3. **Kompleksitas**: Perhitungan VIKOR lebih kompleks

### Solusi:
1. **AHP**: Gunakan AHP untuk menentukan bobot
2. **Sensitivity Analysis**: Analisis sensitivitas bobot
3. **Validation**: Validasi dengan metode lain
4. **Documentation**: Dokumentasi lengkap perhitungan
















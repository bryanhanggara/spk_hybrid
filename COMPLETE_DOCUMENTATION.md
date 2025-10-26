# Complete Documentation - Sistem SPK Rekomendasi Jurusan

## Table of Contents
1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Algorithm Implementation](#algorithm-implementation)
4. [Database Design](#database-design)
5. [User Interface](#user-interface)
6. [API Documentation](#api-documentation)
7. [Testing Strategy](#testing-strategy)
8. [Deployment Guide](#deployment-guide)
9. [User Manual](#user-manual)
10. [Technical Documentation](#technical-documentation)
11. [Maintenance Guide](#maintenance-guide)
12. [Troubleshooting](#troubleshooting)

## Project Overview

### Purpose
Sistem SPK (Sistem Pendukung Keputusan) untuk rekomendasi jurusan siswa menggunakan metode hybrid SAW-VIKOR. Sistem ini membantu sekolah dalam memberikan rekomendasi jurusan (IPA/IPS) yang objektif dan transparan kepada siswa kelas 9.

### Key Features
- **Manajemen Data Siswa**: CRUD operations untuk data siswa
- **Input Nilai Akademik**: Form input nilai 5 mata pelajaran
- **Angket Minat**: Survey 10 pertanyaan dengan skala 1-5
- **Hasil Wawancara**: Input 5 aspek penilaian dari guru
- **Perhitungan SPK**: Algoritma SAW-VIKOR untuk rekomendasi
- **Visualisasi Hasil**: Grafik distribusi dan ranking
- **Detail Perhitungan**: Transparansi algoritma

### Technology Stack
- **Backend**: Laravel 11, PHP 8.1+
- **Frontend**: Bootstrap 5, Chart.js, Vanilla JavaScript
- **Database**: MySQL 8.0+ / MariaDB 10.3+
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **Build Tools**: Vite, npm

## System Architecture

### High-Level Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │    │   Database      │
│   (Bootstrap)   │◄──►│   (Laravel)     │◄──►│   (MySQL)       │
│   Chart.js      │    │   PHP 8.1+      │    │   MySQL 8.0+    │
│   JavaScript    │    │   MVC Pattern   │    │   InnoDB Engine │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Directory Structure
```
app/
├── Http/Controllers/
│   ├── SpkController.php      # SPK logic
│   └── StudentController.php  # Student management
├── Models/
│   ├── Student.php
│   ├── AcademicScore.php
│   ├── InterestSurvey.php
│   ├── InterviewScore.php
│   └── SpkResult.php
└── Services/
    └── SawVikorService.php    # SAW-VIKOR algorithm

resources/
├── views/
│   ├── layouts/app.blade.php
│   ├── spk/
│   └── students/
├── css/app.css
└── js/app.js

routes/web.php
database/
├── migrations/
└── seeders/
```

## Algorithm Implementation

### SAW (Simple Additive Weighting)

#### Normalization Formula
```php
Rij = Xij / Xmax
```

Where:
- `Rij` = Normalized value
- `Xij` = Original value
- `Xmax` = Maximum value (100 for academic, 50 for interest, 25 for interview)

#### Weight Calculation
```php
// Main criteria weights
$weights = [
    'academic' => 0.4,      // 40%
    'interest' => 0.3,      // 30%
    'interview' => 0.3       // 30%
];

// Academic weights for IPA
$ipaCriteria = [
    'mathematics' => 0.3,
    'science' => 0.3,
    'english' => 0.2,
    'indonesian' => 0.1,
    'social_studies' => 0.1
];

// Academic weights for IPS
$ipsCriteria = [
    'social_studies' => 0.3,
    'indonesian' => 0.3,
    'english' => 0.2,
    'mathematics' => 0.1,
    'science' => 0.1
];
```

### VIKOR (VlseKriterijumska Optimizacija I Kompromisno Resenje)

#### S (Utility Measure)
```php
S = (f* - fi) / (f* - f-)
```

#### R (Regret Measure)
```php
R = max[(f* - fi) / (f* - f-)]
```

#### Q (Compromise Measure)
```php
Q = v × S + (1-v) × R
```

Where `v = 0.5` (compromise parameter)

#### Recommendation Logic
```php
$recommendedMajor = array_keys($q, min($q))[0];
```

## Database Design

### Entity Relationship Diagram
```
Student (1) ──── (1) AcademicScore
    │
    ├── (1) InterestSurvey
    │
    ├── (1) InterviewScore
    │
    └── (1) SpkResult
```

### Table Definitions

#### students
```sql
CREATE TABLE students (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    nisn VARCHAR(255) UNIQUE NOT NULL,
    class VARCHAR(50) NOT NULL,
    gender ENUM('L', 'P') NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### academic_scores
```sql
CREATE TABLE academic_scores (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    mathematics DECIMAL(5,2) NOT NULL,
    indonesian DECIMAL(5,2) NOT NULL,
    english DECIMAL(5,2) NOT NULL,
    science DECIMAL(5,2) NOT NULL,
    social_studies DECIMAL(5,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
```

#### interest_surveys
```sql
CREATE TABLE interest_surveys (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    answers JSON NOT NULL,
    total_score DECIMAL(5,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
```

#### interview_scores
```sql
CREATE TABLE interview_scores (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    communication_skill TINYINT UNSIGNED NOT NULL,
    motivation TINYINT UNSIGNED NOT NULL,
    personality TINYINT UNSIGNED NOT NULL,
    academic_potential TINYINT UNSIGNED NOT NULL,
    career_orientation TINYINT UNSIGNED NOT NULL,
    total_score DECIMAL(5,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
```

#### spk_results
```sql
CREATE TABLE spk_results (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    recommended_major VARCHAR(10) NOT NULL,
    saw_score DECIMAL(5,4) NOT NULL,
    vikor_score DECIMAL(5,4) NOT NULL,
    final_score DECIMAL(5,4) NOT NULL,
    rank INT NOT NULL,
    calculation_details JSON NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
```

## User Interface

### Layout Structure
```
┌─────────────────────────────────────────────────────────┐
│                    Header/Navigation                    │
├─────────────┬───────────────────────────────────────────┤
│   Sidebar   │                Main Content              │
│             │                                           │
│   - Dashboard│   - Dashboard Statistics                 │
│   - Students │   - Student List                         │
│   - Results  │   - Forms                                │
│             │   - Charts                               │
│             │   - Tables                               │
│             │                                           │
└─────────────┴───────────────────────────────────────────┘
```

### Responsive Design
- **Desktop**: Full sidebar navigation
- **Tablet**: Collapsible sidebar
- **Mobile**: Hamburger menu

### Key Components
- **Dashboard**: Statistics and quick actions
- **Student Management**: CRUD operations
- **Forms**: Input validation and auto-calculation
- **Charts**: Interactive data visualization
- **Tables**: Sortable and searchable data

## API Documentation

### RESTful Endpoints

#### SPK Endpoints
```
GET    /spk                    # Dashboard
POST   /spk/calculate          # Calculate SPK
GET    /spk/results            # Results
GET    /spk/detail/{id}        # Detail calculation
GET    /spk/chart-data         # Chart data
GET    /spk/ranking-data       # Ranking data
```

#### Student Endpoints
```
GET    /students               # List students
POST   /students               # Create student
GET    /students/{id}          # Show student
PUT    /students/{id}          # Update student
DELETE /students/{id}          # Delete student
GET    /students/{id}/academic # Academic form
POST   /students/{id}/academic # Store academic
GET    /students/{id}/interest # Interest form
POST   /students/{id}/interest # Store interest
GET    /students/{id}/interview # Interview form
POST   /students/{id}/interview # Store interview
```

### Response Format

#### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {...}
}
```

#### Error Response
```json
{
  "error": true,
  "message": "Error description",
  "errors": {
    "field": ["Validation error"]
  }
}
```

## Testing Strategy

### Unit Testing
- Model relationships
- Service layer logic
- Algorithm calculations
- Data validation

### Feature Testing
- Dashboard access
- Student CRUD operations
- SPK calculation process
- API endpoints
- Form validations

### Integration Testing
- Database operations
- File uploads
- Chart rendering
- Print functionality

### Browser Testing
- Cross-browser compatibility
- Responsive design
- JavaScript functionality
- Performance testing

## Deployment Guide

### Development Environment
```bash
# Clone repository
git clone <repository-url>
cd spk-skripsi

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed --class=StudentSeeder

# Build assets
npm run build

# Run server
php artisan serve
```

### Production Environment
```bash
# Server requirements
- Ubuntu 20.04+ / CentOS 7+
- PHP 8.1+ with extensions
- MySQL 8.0+ / MariaDB 10.3+
- Apache 2.4+ / Nginx 1.18+
- Node.js 16+

# Deployment steps
1. Server setup
2. Database configuration
3. Application deployment
4. Web server configuration
5. SSL certificate setup
6. Firewall configuration
7. Monitoring setup
```

## User Manual

### Getting Started
1. **Access System**: Navigate to application URL
2. **Dashboard**: View statistics and student list
3. **Add Students**: Create student records
4. **Input Data**: Complete academic, interest, and interview data
5. **Calculate SPK**: Run recommendation algorithm
6. **View Results**: Analyze recommendations and rankings

### Data Input Process
1. **Student Information**: Name, NISN, class, gender
2. **Academic Scores**: 5 subjects (0-100 scale)
3. **Interest Survey**: 10 questions (1-5 scale)
4. **Interview Results**: 5 aspects (1-5 scale)

### Results Interpretation
1. **Recommendation**: IPA or IPS based on algorithm
2. **Scores**: SAW, VIKOR, and final scores
3. **Ranking**: Position among all students
4. **Details**: Complete calculation breakdown

## Technical Documentation

### Code Architecture
- **MVC Pattern**: Model-View-Controller separation
- **Service Layer**: Business logic encapsulation
- **Repository Pattern**: Data access abstraction
- **Factory Pattern**: Object creation

### Security Implementation
- **CSRF Protection**: Form token validation
- **Input Validation**: Server-side validation
- **SQL Injection Prevention**: Eloquent ORM
- **XSS Protection**: Blade templating

### Performance Optimization
- **Database Indexing**: Primary and foreign keys
- **Query Optimization**: Eager loading
- **Caching Strategy**: Configuration and route caching
- **Asset Optimization**: Minification and compression

## Maintenance Guide

### Regular Maintenance
- **Weekly**: Check disk space, review logs
- **Monthly**: Security updates, performance review
- **Quarterly**: Database optimization, backup verification

### Backup Procedures
- **Database Backup**: Automated daily backups
- **File Backup**: Application files backup
- **Retention Policy**: 7-day retention for backups

### Monitoring
- **Application Logs**: Laravel log files
- **System Logs**: Server and database logs
- **Performance Metrics**: Response times and resource usage

## Troubleshooting

### Common Issues

#### 1. Application Not Loading
**Symptoms**: Blank page or error message
**Solutions**:
- Check server status
- Verify database connection
- Check file permissions
- Review error logs

#### 2. Database Connection Error
**Symptoms**: Database connection failed
**Solutions**:
- Check database credentials
- Verify MySQL service status
- Test database connectivity
- Review database logs

#### 3. SPK Calculation Fails
**Symptoms**: Calculation process fails
**Solutions**:
- Check data completeness
- Verify algorithm implementation
- Review calculation logs
- Test with sample data

#### 4. Charts Not Displaying
**Symptoms**: Charts don't render
**Solutions**:
- Check JavaScript console
- Verify Chart.js loading
- Check data format
- Test browser compatibility

### Error Codes
- **500**: Internal server error
- **404**: Page not found
- **403**: Access forbidden
- **422**: Validation error

### Support Contacts
- **Technical Support**: support@yourdomain.com
- **Documentation**: https://docs.yourdomain.com
- **Issues**: https://github.com/yourusername/spk-skripsi/issues

## Conclusion

The SPK system provides a comprehensive solution for student major recommendations using proven SAW-VIKOR algorithms. The system is:

- **Functionally Complete**: All requested features implemented
- **Technically Sound**: Robust architecture and security
- **User-Friendly**: Intuitive interface and clear documentation
- **Production Ready**: Complete deployment and maintenance procedures
- **Extensible**: Architecture supports future enhancements

The system successfully demonstrates objective decision-making capabilities while maintaining transparency in the recommendation process. It serves as a valuable tool for educational institutions in making informed decisions about student academic pathways.

## Quick Reference

### Key URLs
- **Dashboard**: `/spk`
- **Students**: `/students`
- **Results**: `/spk/results`
- **API**: `/spk/chart-data`, `/spk/ranking-data`

### Key Commands
- **Serve**: `php artisan serve`
- **Migrate**: `php artisan migrate`
- **Seed**: `php artisan db:seed --class=StudentSeeder`
- **Build**: `npm run build`
- **Test**: `php artisan test`

### Key Files
- **Routes**: `routes/web.php`
- **Controllers**: `app/Http/Controllers/`
- **Models**: `app/Models/`
- **Services**: `app/Services/`
- **Views**: `resources/views/`
- **Assets**: `resources/css/`, `resources/js/`

This documentation provides a complete reference for understanding, using, and maintaining the SPK system.


# Technical Documentation - Sistem SPK

## Architecture Overview

### System Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │    │   Database      │
│   (Bootstrap)   │◄──►│   (Laravel)     │◄──►│   (MySQL)       │
│   Chart.js      │    │   PHP 8.1+      │    │   MySQL 8.0+    │
│   JavaScript    │    │   MVC Pattern   │    │   InnoDB Engine │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Technology Stack
- **Backend**: Laravel 11, PHP 8.1+
- **Frontend**: Bootstrap 5, Chart.js, Vanilla JavaScript
- **Database**: MySQL 8.0+ / MariaDB 10.3+
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **Build Tools**: Vite, npm
- **Version Control**: Git

## Database Schema

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

// Main criteria weights
$weights = [
    'academic' => 0.4,
    'interest' => 0.3,
    'interview' => 0.3
];
```

### VIKOR (VlseKriterijumska Optimizacija I Kompromisno Resenje)

#### S (Utility Measure)
```php
S = (f* - fi) / (f* - f-)
```

Where:
- `f*` = Maximum value
- `fi` = Alternative i value
- `f-` = Minimum value

#### R (Regret Measure)
```php
R = max[(f* - fi) / (f* - f-)]
```

#### Q (Compromise Measure)
```php
Q = v × S + (1-v) × R
```

Where `v = 0.5` (compromise parameter)

## Code Structure

### Directory Structure
```
app/
├── Http/
│   └── Controllers/
│       ├── SpkController.php
│       └── StudentController.php
├── Models/
│   ├── Student.php
│   ├── AcademicScore.php
│   ├── InterestSurvey.php
│   ├── InterviewScore.php
│   └── SpkResult.php
└── Services/
    └── SawVikorService.php

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php
│   ├── spk/
│   │   ├── index.blade.php
│   │   ├── results.blade.php
│   │   └── detail.blade.php
│   └── students/
│       ├── index.blade.php
│       ├── create.blade.php
│       ├── show.blade.php
│       ├── academic.blade.php
│       ├── interest.blade.php
│       └── interview.blade.php
├── css/
│   └── app.css
└── js/
    └── app.js

routes/
└── web.php

database/
├── migrations/
│   ├── create_students_table.php
│   ├── create_academic_scores_table.php
│   ├── create_interest_surveys_table.php
│   ├── create_interview_scores_table.php
│   └── create_spk_results_table.php
└── seeders/
    └── StudentSeeder.php
```

### Service Layer Pattern

#### SawVikorService.php
```php
class SawVikorService
{
    private array $weights;
    private array $ipaCriteria;
    private array $ipsCriteria;

    public function calculateRecommendation(Student $student): array
    public function calculateRankings(): array
    private function normalizeData($academicScore, $interestSurvey, $interviewScore): array
    private function calculateMajorScore(array $normalizedData, string $major): float
    private function calculateVikor(float $ipaScore, float $ipsScore): array
}
```

### Controller Layer

#### SpkController.php
```php
class SpkController extends Controller
{
    protected $sawVikorService;

    public function index()
    public function calculateAll()
    public function results()
    public function showDetail($studentId)
    public function getChartData()
    public function getRankingData()
}
```

#### StudentController.php
```php
class StudentController extends Controller
{
    public function index()
    public function create()
    public function store(Request $request)
    public function show(Student $student)
    public function edit(Student $student)
    public function update(Request $request, Student $student)
    public function destroy(Student $student)
    public function academicForm(Student $student)
    public function storeAcademic(Request $request, Student $student)
    public function interestForm(Student $student)
    public function storeInterest(Request $request, Student $student)
    public function interviewForm(Student $student)
    public function storeInterview(Request $request, Student $student)
}
```

## API Design

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

## Frontend Architecture

### Component Structure
```
layouts/
└── app.blade.php              # Main layout

spk/
├── index.blade.php            # Dashboard
├── results.blade.php          # Results with charts
└── detail.blade.php           # Calculation details

students/
├── index.blade.php            # Student list
├── create.blade.php           # Create form
├── show.blade.php             # Student detail
├── academic.blade.php         # Academic form
├── interest.blade.php         # Interest survey
└── interview.blade.php        # Interview form
```

### JavaScript Architecture

#### app.js
```javascript
// Auto-hide alerts
// Form validation
// Auto-calculate totals
// Chart initialization
// Event handlers
```

#### Chart Integration
```javascript
// Major distribution chart (Doughnut)
// Ranking chart (Bar)
// Responsive design
// Data binding
```

### CSS Architecture

#### app.css
```css
/* Custom styles for SPK application */
.badge-ipa, .badge-ips
.progress bars
.table styling
.card hover effects
.btn-group styling
.chart-container
.print styles
```

## Security Implementation

### CSRF Protection
```php
// All forms include CSRF token
@csrf
```

### Input Validation
```php
// Server-side validation
$request->validate([
    'name' => 'required|string|max:255',
    'nisn' => 'required|string|unique:students,nisn',
    'mathematics' => 'required|numeric|min:0|max:100'
]);
```

### SQL Injection Prevention
```php
// Using Eloquent ORM
Student::create($request->all());

// Using Query Builder
DB::table('students')->where('id', $id)->first();
```

### XSS Protection
```php
// Blade templates auto-escape
{{ $student->name }}

// Manual escaping
{!! $htmlContent !!}
```

## Performance Optimization

### Database Optimization

#### Indexing
```sql
-- Primary keys (auto-indexed)
-- Foreign keys (auto-indexed)
-- Unique constraints
CREATE UNIQUE INDEX idx_students_nisn ON students(nisn);
```

#### Query Optimization
```php
// Eager loading
Student::with(['academicScore', 'interestSurvey', 'interviewScore'])->get();

// Select specific columns
Student::select('id', 'name', 'nisn')->get();

// Pagination
Student::paginate(10);
```

### Caching Strategy
```php
// Configuration caching
php artisan config:cache

// Route caching
php artisan route:cache

// View caching
php artisan view:cache
```

### Asset Optimization
```bash
# Production build
npm run build

# Asset minification
# CSS/JS compression
# Image optimization
```

## Testing Strategy

### Unit Testing
```php
// Test models
class StudentTest extends TestCase
{
    public function test_student_creation()
    public function test_student_relationships()
    public function test_student_validation()
}

// Test services
class SawVikorServiceTest extends TestCase
{
    public function test_normalization()
    public function test_calculation()
    public function test_ranking()
}
```

### Integration Testing
```php
// Test controllers
class SpkControllerTest extends TestCase
{
    public function test_dashboard()
    public function test_calculation()
    public function test_results()
}

// Test API endpoints
class ApiTest extends TestCase
{
    public function test_chart_data()
    public function test_ranking_data()
}
```

### Browser Testing
```javascript
// Selenium WebDriver
// Cypress
// Playwright
```

## Deployment Architecture

### Development Environment
```
Local Development:
├── Laravel Artisan Server
├── MySQL Database
├── Node.js (Vite)
└── Browser Testing
```

### Production Environment
```
Production Server:
├── Nginx/Apache
├── PHP-FPM
├── MySQL
├── Redis (optional)
└── SSL Certificate
```

### CI/CD Pipeline
```
Git Push → GitHub Actions → Build → Test → Deploy
```

## Monitoring and Logging

### Application Logs
```php
// Laravel logging
Log::info('SPK calculation completed', ['student_id' => $id]);
Log::error('Calculation failed', ['error' => $exception->getMessage()]);
```

### Database Logs
```sql
-- Slow query log
-- Error log
-- General log
```

### System Monitoring
```bash
# Server monitoring
htop, iotop, nethogs

# Application monitoring
Laravel Telescope (optional)
```

## Backup Strategy

### Database Backup
```bash
# Automated backup script
mysqldump -u$user -p$pass $database > backup_$(date +%Y%m%d).sql
```

### File Backup
```bash
# Application files
tar -czf app_backup_$(date +%Y%m%d).tar.gz /var/www/spk-skripsi
```

### Backup Retention
```bash
# Keep last 7 days
find /backup -name "*.sql" -mtime +7 -delete
find /backup -name "*.tar.gz" -mtime +7 -delete
```

## Scalability Considerations

### Horizontal Scaling
```
Load Balancer → Multiple App Servers → Database Cluster
```

### Vertical Scaling
```
More CPU/RAM → Better Performance
```

### Database Scaling
```
Read Replicas → Master-Slave Setup
```

## Maintenance Procedures

### Regular Maintenance
```bash
# Weekly tasks
- Check disk space
- Review logs
- Update dependencies
- Backup verification
```

### Monthly Maintenance
```bash
# Monthly tasks
- Security updates
- Performance review
- Database optimization
- Log rotation
```

### Emergency Procedures
```bash
# Emergency response
- Incident response plan
- Rollback procedures
- Data recovery
- Communication plan
```

## Future Enhancements

### Planned Features
- Multi-language support
- Advanced reporting
- API versioning
- Mobile app
- Real-time notifications

### Technical Improvements
- Microservices architecture
- Containerization (Docker)
- Kubernetes orchestration
- Advanced caching (Redis)
- Message queues

### Performance Improvements
- CDN integration
- Image optimization
- Lazy loading
- Progressive Web App (PWA)

## Conclusion

This technical documentation provides a comprehensive overview of the SPK system architecture, implementation details, and maintenance procedures. It serves as a reference for developers, system administrators, and stakeholders involved in the system's development and maintenance.













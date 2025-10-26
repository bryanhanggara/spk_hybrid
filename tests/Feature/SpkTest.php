<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Student;
use App\Models\AcademicScore;
use App\Models\InterestSurvey;
use App\Models\InterviewScore;
use App\Models\SpkResult;
use App\Services\SawVikorService;

class SpkTest extends TestCase
{
    use RefreshDatabase;

    protected $sawVikorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sawVikorService = new SawVikorService();
    }

    /**
     * Test dashboard access
     */
    public function test_dashboard_access(): void
    {
        $response = $this->get('/spk');
        $response->assertStatus(200);
        $response->assertViewIs('spk.index');
    }

    /**
     * Test student creation
     */
    public function test_student_creation(): void
    {
        $studentData = [
            'name' => 'Test Student',
            'nisn' => '1234567890',
            'class' => 'IX-A',
            'gender' => 'L'
        ];

        $response = $this->post('/students', $studentData);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('students', $studentData);
    }

    /**
     * Test academic score input
     */
    public function test_academic_score_input(): void
    {
        $student = Student::factory()->create();
        
        $academicData = [
            'mathematics' => 85,
            'indonesian' => 78,
            'english' => 82,
            'science' => 88,
            'social_studies' => 75
        ];

        $response = $this->post("/students/{$student->id}/academic", $academicData);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('academic_scores', array_merge($academicData, ['student_id' => $student->id]));
    }

    /**
     * Test interest survey input
     */
    public function test_interest_survey_input(): void
    {
        $student = Student::factory()->create();
        
        $interestData = [
            'answers' => [5, 4, 5, 3, 4, 2, 5, 3, 4, 2],
            'total_score' => 37
        ];

        $response = $this->post("/students/{$student->id}/interest", $interestData);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('interest_surveys', array_merge($interestData, ['student_id' => $student->id]));
    }

    /**
     * Test interview score input
     */
    public function test_interview_score_input(): void
    {
        $student = Student::factory()->create();
        
        $interviewData = [
            'communication_skill' => 4,
            'motivation' => 5,
            'personality' => 4,
            'academic_potential' => 5,
            'career_orientation' => 4,
            'total_score' => 22
        ];

        $response = $this->post("/students/{$student->id}/interview", $interviewData);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('interview_scores', array_merge($interviewData, ['student_id' => $student->id]));
    }

    /**
     * Test SPK calculation
     */
    public function test_spk_calculation(): void
    {
        // Create student with complete data
        $student = Student::factory()->create();
        
        AcademicScore::create([
            'student_id' => $student->id,
            'mathematics' => 85,
            'indonesian' => 78,
            'english' => 82,
            'science' => 88,
            'social_studies' => 75
        ]);

        InterestSurvey::create([
            'student_id' => $student->id,
            'answers' => [5, 4, 5, 3, 4, 2, 5, 3, 4, 2],
            'total_score' => 37
        ]);

        InterviewScore::create([
            'student_id' => $student->id,
            'communication_skill' => 4,
            'motivation' => 5,
            'personality' => 4,
            'academic_potential' => 5,
            'career_orientation' => 4,
            'total_score' => 22
        ]);

        $response = $this->post('/spk/calculate');
        $response->assertRedirect('/spk/results');
        
        $this->assertDatabaseHas('spk_results', ['student_id' => $student->id]);
    }

    /**
     * Test SPK results page
     */
    public function test_spk_results_page(): void
    {
        // Create SPK result
        $student = Student::factory()->create();
        SpkResult::create([
            'student_id' => $student->id,
            'recommended_major' => 'IPA',
            'saw_score' => 0.8234,
            'vikor_score' => 0.0000,
            'final_score' => 0.8234,
            'rank' => 1,
            'calculation_details' => []
        ]);

        $response = $this->get('/spk/results');
        $response->assertStatus(200);
        $response->assertViewIs('spk.results');
    }

    /**
     * Test chart data API
     */
    public function test_chart_data_api(): void
    {
        // Create SPK results
        $student1 = Student::factory()->create();
        $student2 = Student::factory()->create();
        
        SpkResult::create([
            'student_id' => $student1->id,
            'recommended_major' => 'IPA',
            'saw_score' => 0.8234,
            'vikor_score' => 0.0000,
            'final_score' => 0.8234,
            'rank' => 1,
            'calculation_details' => []
        ]);

        SpkResult::create([
            'student_id' => $student2->id,
            'recommended_major' => 'IPS',
            'saw_score' => 0.7980,
            'vikor_score' => 0.0000,
            'final_score' => 0.7980,
            'rank' => 2,
            'calculation_details' => []
        ]);

        $response = $this->get('/spk/chart-data');
        $response->assertStatus(200);
        $response->assertJson([
            'labels' => ['IPA', 'IPS'],
            'data' => [1, 1]
        ]);
    }

    /**
     * Test ranking data API
     */
    public function test_ranking_data_api(): void
    {
        $student = Student::factory()->create();
        
        SpkResult::create([
            'student_id' => $student->id,
            'recommended_major' => 'IPA',
            'saw_score' => 0.8234,
            'vikor_score' => 0.0000,
            'final_score' => 0.8234,
            'rank' => 1,
            'calculation_details' => []
        ]);

        $response = $this->get('/spk/ranking-data');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /**
     * Test SAW-VIKOR service
     */
    public function test_saw_vikor_service(): void
    {
        $student = Student::factory()->create();
        
        AcademicScore::create([
            'student_id' => $student->id,
            'mathematics' => 85,
            'indonesian' => 78,
            'english' => 82,
            'science' => 88,
            'social_studies' => 75
        ]);

        InterestSurvey::create([
            'student_id' => $student->id,
            'answers' => [5, 4, 5, 3, 4, 2, 5, 3, 4, 2],
            'total_score' => 37
        ]);

        InterviewScore::create([
            'student_id' => $student->id,
            'communication_skill' => 4,
            'motivation' => 5,
            'personality' => 4,
            'academic_potential' => 5,
            'career_orientation' => 4,
            'total_score' => 22
        ]);

        $result = $this->sawVikorService->calculateRecommendation($student);
        
        $this->assertArrayHasKey('recommended_major', $result);
        $this->assertArrayHasKey('saw_score', $result);
        $this->assertArrayHasKey('vikor_score', $result);
        $this->assertArrayHasKey('final_score', $result);
        $this->assertArrayHasKey('calculation_details', $result);
    }

    /**
     * Test validation errors
     */
    public function test_validation_errors(): void
    {
        // Test student creation validation
        $response = $this->post('/students', []);
        $response->assertSessionHasErrors(['name', 'nisn', 'class', 'gender']);

        // Test academic score validation
        $student = Student::factory()->create();
        $response = $this->post("/students/{$student->id}/academic", []);
        $response->assertSessionHasErrors(['mathematics', 'indonesian', 'english', 'science', 'social_studies']);
    }

    /**
     * Test incomplete data handling
     */
    public function test_incomplete_data_handling(): void
    {
        $student = Student::factory()->create();
        
        // Only create academic score, missing interest and interview
        AcademicScore::create([
            'student_id' => $student->id,
            'mathematics' => 85,
            'indonesian' => 78,
            'english' => 82,
            'science' => 88,
            'social_studies' => 75
        ]);

        $response = $this->post('/spk/calculate');
        $response->assertRedirect();
        
        // Should not create SPK result for incomplete data
        $this->assertDatabaseMissing('spk_results', ['student_id' => $student->id]);
    }
}

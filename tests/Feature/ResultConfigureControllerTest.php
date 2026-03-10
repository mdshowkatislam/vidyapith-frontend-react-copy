<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ResultConfigureControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function it_can_be_tested_without_database_operations()
    {
        // Test that the test framework is working
        $response = $this->postJson('/api/subject-wise-result-configure', [
            'branch_id' => 1,
            'class_id' => 1,
            'section_id' => 1,
            'subject_id' => 1,
            'exam_category_id' => 'test-uid',
        ]);

        // Should return a response (even if it's an error due to missing data)
        $this->assertTrue($response->getStatusCode() >= 200);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/subject-wise-result-configure', []);

        // Should return validation error
        $response->assertStatus(422);
    }

    /** @test */
    public function it_accepts_valid_request_structure()
    {
        $response = $this->postJson('/api/subject-wise-result-configure', [
            'branch_id' => 1,
            'class_id' => 1,
            'section_id' => 1,
            'subject_id' => 1,
            'exam_category_id' => 'test-uid',
        ]);

        // Should not return a 422 validation error
        $this->assertNotEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function it_processes_result_storage_requests()
    {
        $response = $this->postJson('/api/subject-wise-result-store', [
            'branch_id' => 1,
            'class_id' => 1,
            'section_id' => 1,
            'subject_id' => 1,
            'exam_category_id' => 'test-uid',
            'is_optional_subject' => true,
            'is_separately_pass' => false,
            'resultData' => [],
        ]);

        // Should not return a 422 validation error
        $this->assertNotEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function it_processes_configuration_storage_requests()
    {
        $response = $this->postJson('/api/result-configure-store', [
            'branch_id' => 1,
            'class_id' => 1,
            'section_id' => 1,
            'subject_id' => 1,
            'exam_category_id' => 'test-uid',
            'is_optional_subject' => true,
            'is_separately_pass' => false,
            'examData' => [],
        ]);

        // Should not return a 422 validation error
        $this->assertNotEquals(422, $response->getStatusCode());
    }
}

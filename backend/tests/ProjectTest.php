<?php

declare(strict_types=1);

// Simple test for Project class without PHPUnit dependency
require_once __DIR__ . '/../vendor/autoload.php';

use App\Domain\Project\Project;

class ProjectTest
{
    private int $testsRun = 0;
    private int $testsPassed = 0;

    public function runAllTests(): void
    {
        echo "Running Project tests...\n";
        
        $this->testConstructorAndGetters();
        $this->testJsonSerialization();
        $this->testDefaultUrl();
        
        echo "\nTest Results: {$this->testsPassed}/{$this->testsRun} passed\n";
        
        if ($this->testsPassed === $this->testsRun) {
            echo "✅ All tests passed!\n";
        } else {
            echo "❌ Some tests failed!\n";
            exit(1);
        }
    }

    public function testConstructorAndGetters(): void
    {
        $this->testsRun++;
        
        $project = new Project(1, 'Test Project', 'Test Description', 'https://example.com');
        
        $passed = true;
        $errors = [];
        
        if ($project->getId() !== 1) {
            $passed = false;
            $errors[] = "Expected ID 1, got " . $project->getId();
        }
        
        if ($project->getName() !== 'Test Project') {
            $passed = false;
            $errors[] = "Expected name 'Test Project', got '" . $project->getName() . "'";
        }
        
        if ($project->getDescription() !== 'Test Description') {
            $passed = false;
            $errors[] = "Expected description 'Test Description', got '" . $project->getDescription() . "'";
        }
        
        if ($project->getUrl() !== 'https://example.com') {
            $passed = false;
            $errors[] = "Expected URL 'https://example.com', got '" . $project->getUrl() . "'";
        }
        
        if ($passed) {
            $this->testsPassed++;
            echo "✅ testConstructorAndGetters: PASSED\n";
        } else {
            echo "❌ testConstructorAndGetters: FAILED\n";
            foreach ($errors as $error) {
                echo "   - $error\n";
            }
        }
    }

    public function testJsonSerialization(): void
    {
        $this->testsRun++;
        
        $project = new Project(2, 'JSON Test', 'JSON Description', 'https://json.test');
        $json = $project->jsonSerialize();
        
        $expected = [
            'id' => 2,
            'name' => 'JSON Test',
            'description' => 'JSON Description',
            'url' => 'https://json.test'
        ];
        
        if ($json === $expected) {
            $this->testsPassed++;
            echo "✅ testJsonSerialization: PASSED\n";
        } else {
            echo "❌ testJsonSerialization: FAILED\n";
            echo "   Expected: " . json_encode($expected) . "\n";
            echo "   Got: " . json_encode($json) . "\n";
        }
    }

    public function testDefaultUrl(): void
    {
        $this->testsRun++;
        
        $project = new Project(3, 'No URL Project', 'No URL Description');
        
        if ($project->getUrl() === '') {
            $this->testsPassed++;
            echo "✅ testDefaultUrl: PASSED\n";
        } else {
            echo "❌ testDefaultUrl: FAILED\n";
            echo "   Expected empty string, got '" . $project->getUrl() . "'\n";
        }
    }
}

// Run the tests
$test = new ProjectTest();
$test->runAllTests();
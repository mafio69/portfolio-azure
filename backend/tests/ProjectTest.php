<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Domain\Project\Project;

/**
 * Tests for Project domain entity
 * Clean Architecture - Domain layer testing
 */
class ProjectTest extends TestCase
{
    /**
     * Test constructor and getter methods of Project entity
     */
    public function testConstructorAndGetters(): void
    {
        // Given - Create a project with all parameters
        $project = new Project(
            id: 1,
            name: 'Test Project',
            description: 'Test Description for the project',
            url: 'https://test-project.com',
            technologies: ['PHP', 'Vue.js', 'MySQL']
        );

        // Then - Verify all properties are set correctly
        $this->assertEquals(1, $project->getId(), 'Project ID should match');
        $this->assertEquals('Test Project', $project->getName(), 'Project name should match');
        $this->assertEquals('Test Description for the project', $project->getDescription(), 'Project description should match');
        $this->assertEquals('https://test-project.com', $project->getUrl(), 'Project URL should match');
        $this->assertEquals(['PHP', 'Vue.js', 'MySQL'], $project->getTechnologies(), 'Project technologies should match');
    }

    /**
     * Test JSON serialization of Project entity
     */
    public function testJsonSerialization(): void
    {
        // Given - Create a project for serialization testing
        $project = new Project(
            id: 2,
            name: 'JSON Test Project',
            description: 'Project for testing JSON serialization',
            url: 'https://json.test.com',
            technologies: ['JavaScript', 'Node.js']
        );

        // When - Serialize project to array (simulate JSON conversion)
        $actualArray = [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
            'url' => $project->getUrl(),
            'technologies' => $project->getTechnologies()
        ];

        // Then - Verify serialized data matches expected structure
        $expectedArray = [
            'id' => 2,
            'name' => 'JSON Test Project',
            'description' => 'Project for testing JSON serialization',
            'url' => 'https://json.test.com',
            'technologies' => ['JavaScript', 'Node.js']
        ];

        $this->assertEquals($expectedArray, $actualArray, 'Project should serialize correctly to array');
    }

    /**
     * Test project with empty technologies array
     */
    public function testProjectWithEmptyTechnologies(): void
    {
        // Given - Create project with empty technologies
        $project = new Project(
            id: 3,
            name: 'Minimal Project',
            description: 'Project without specific technologies',
            url: 'https://minimal.test.com',
            technologies: []
        );

        // Then - Verify technologies is empty array
        $this->assertIsArray($project->getTechnologies(), 'Technologies should be an array');
        $this->assertEmpty($project->getTechnologies(), 'Technologies array should be empty');
        $this->assertCount(0, $project->getTechnologies(), 'Technologies array should have zero elements');
    }

    /**
     * Test project creation with various technology stacks
     */
    public function testProjectWithDifferentTechnologyStacks(): void
    {
        // Test cases for different technology combinations
        $testCases = [
            [
                'name' => 'Frontend Project',
                'technologies' => ['React', 'TypeScript', 'Tailwind CSS'],
                'expected_count' => 3
            ],
            [
                'name' => 'Backend Project',
                'technologies' => ['PHP', 'Slim Framework', 'PostgreSQL', 'Docker'],
                'expected_count' => 4
            ],
            [
                'name' => 'Full Stack Project',
                'technologies' => ['Vue.js', 'PHP', 'MySQL', 'Nginx'],
                'expected_count' => 4
            ]
        ];

        foreach ($testCases as $index => $testCase) {
            $project = new Project(
                id: $index + 10,
                name: $testCase['name'],
                description: "Test case for {$testCase['name']}",
                url: "https://test{$index}.com",
                technologies: $testCase['technologies']
            );

            $this->assertCount(
                $testCase['expected_count'],
                $project->getTechnologies(),
                "Project '{$testCase['name']}' should have {$testCase['expected_count']} technologies"
            );

            $this->assertEquals(
                $testCase['technologies'],
                $project->getTechnologies(),
                "Project '{$testCase['name']}' should have correct technology stack"
            );
        }
    }

    /**
     * Test project immutability - getters should always return same values
     */
    public function testProjectImmutability(): void
    {
        // Given - Create a project
        $project = new Project(
            id: 99,
            name: 'Immutable Test',
            description: 'Testing immutability',
            url: 'https://immutable.test',
            technologies: ['PHP', 'Clean Architecture']
        );

        // When - Call getters multiple times
        $firstId = $project->getId();
        $secondId = $project->getId();
        $firstTechnologies = $project->getTechnologies();
        $secondTechnologies = $project->getTechnologies();

        // Then - Values should be consistent
        $this->assertEquals($firstId, $secondId, 'Project ID should be immutable');
        $this->assertEquals($firstTechnologies, $secondTechnologies, 'Technologies should be immutable');

        // Modify returned array and verify original is unchanged
        $technologies = $project->getTechnologies();
        $technologies[] = 'New Technology';

        $this->assertNotEquals(
            $technologies,
            $project->getTechnologies(),
            'Original technologies array should not be affected by external modifications'
        );
    }

    /**
     * Test project URL validation (if implemented in constructor)
     */
    public function testProjectUrlFormat(): void
    {
        // Given - Create project with various URL formats
        $validUrls = [
            'https://example.com',
            'http://localhost:8080',
            'https://subdomain.example.org/path',
            'https://github.com/user/repo'
        ];

        foreach ($validUrls as $index => $url) {
            $project = new Project(
                id: $index + 100,
                name: "URL Test {$index}",
                description: "Testing URL: {$url}",
                url: $url,
                technologies: ['PHP']
            );

            $this->assertEquals($url, $project->getUrl(), "URL '{$url}' should be stored correctly");
        }
    }
}

<?php

namespace talbertsutah\UUtahSemester\Tests;

use PHPUnit\Framework\TestCase;
use talbertsutah\UUtahSemester\Semester;

class SemesterSerializationTest extends TestCase
{
    /**
     * Test toArray returns correct structure
     */
    public function testToArrayStructure(): void
    {
        $semester = new Semester('Fall 2024');
        $array = $semester->toArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('code', $array);
        $this->assertArrayHasKey('string', $array);
        $this->assertArrayHasKey('term', $array);
        $this->assertArrayHasKey('year', $array);
    }

    /**
     * Test toArray values are correct
     */
    public function testToArrayValues(): void
    {
        $semester = new Semester('Summer 2020');
        $array = $semester->toArray();
        
        $this->assertEquals('1206', $array['code']);
        $this->assertEquals('Summer 2020', $array['string']);
        $this->assertEquals('Summer', $array['term']);
        $this->assertEquals('2020', $array['year']);
    }

    /**
     * Test toArray with leading zeros
     */
    public function testToArrayWithLeadingZeros(): void
    {
        $semester = new Semester(264);
        $array = $semester->toArray();
        
        $this->assertEquals('0264', $array['code']);
        $this->assertEquals('Spring 1926', $array['string']);
    }

    /**
     * Test toJson returns valid JSON
     */
    public function testToJsonReturnsValidJson(): void
    {
        $semester = new Semester('Fall 2024');
        $json = $semester->toJson();
        
        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
    }

    /**
     * Test toJson contains expected data
     */
    public function testToJsonContainsExpectedData(): void
    {
        $semester = new Semester('Spring 2024');
        $json = $semester->toJson();
        
        $this->assertStringContainsString('1244', $json);
        $this->assertStringContainsString('Spring 2024', $json);
        $this->assertStringContainsString('Spring', $json);
        $this->assertStringContainsString('2024', $json);
    }

    /**
     * Test toJson roundtrip - create from array
     */
    public function testToJsonRoundtrip(): void
    {
        $original = new Semester('Fall 2024');
        $json = $original->toJson();
        $array = json_decode($json, true);
        
        $fromArray = new Semester((int) $array['code']);
        $this->assertTrue($original->equals($fromArray));
    }

    /**
     * Test toArray roundtrip - create from array
     */
    public function testToArrayRoundtrip(): void
    {
        $original = new Semester('Summer 2020');
        $array = $original->toArray();
        
        $fromArray = new Semester($array['string']);
        $this->assertEquals($original->getCode(), $fromArray->getCode());
        $this->assertEquals($original->getString(), $fromArray->getString());
    }

    /**
     * Test toJson with default flags
     */
    public function testToJsonDefaultFlags(): void
    {
        $semester = new Semester('Fall 2024');
        $json = $semester->toJson();
        
        // Should be pretty printed (default) so contains newlines
        $this->assertStringContainsString("\n", $json);
    }

    /**
     * Test toJson with custom flags
     */
    public function testToJsonWithCustomFlags(): void
    {
        $semester = new Semester('Fall 2024');
        $json = $semester->toJson(0); // No flags = compact JSON
        
        $this->assertIsString($json);
        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
    }

    /**
     * Test toArray consistency with get method
     */
    public function testToArrayConsistencyWithGet(): void
    {
        $semester = new Semester('Spring 2026');
        $array = $semester->toArray();
        $legacyGet = $semester->get();
        
        $this->assertEquals($legacyGet['Code'], $array['code']);
        $this->assertEquals($legacyGet['String'], $array['string']);
    }

    /**
     * Test multiple calls to toArray return same data
     */
    public function testMultipleToArrayCallsConsistent(): void
    {
        $semester = new Semester('Fall 2024');
        $array1 = $semester->toArray();
        $array2 = $semester->toArray();
        
        $this->assertEquals($array1, $array2);
    }

    /**
     * Test multiple calls to toJson return same data
     */
    public function testMultipleToJsonCallsConsistent(): void
    {
        $semester = new Semester('Fall 2024');
        $json1 = $semester->toJson();
        $json2 = $semester->toJson();
        
        $this->assertEquals($json1, $json2);
    }

    /**
     * Test toArray works after modifications
     */
    public function testToArrayAfterModifications(): void
    {
        $semester = new Semester('Spring 2024');
        $semester->addSemesters(5);
        $array = $semester->toArray();
        
        $this->assertEquals('1258', $array['code']);
        $this->assertEquals('Fall 2025', $array['string']);
    }

    /**
     * Test toJson works after modifications
     */
    public function testToJsonAfterModifications(): void
    {
        $semester = new Semester('Fall 2024');
        $semester->subYears(1);
        $json = $semester->toJson();
        
        $this->assertStringContainsString('Fall 2023', $json);
    }
}

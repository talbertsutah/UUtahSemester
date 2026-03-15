<?php

namespace talbertsutah\UUtahSemester\Tests;

use PHPUnit\Framework\TestCase;
use talbertsutah\UUtahSemester\Semester;
use talbertsutah\UUtahSemester\Exceptions\SemesterInvalidInput;

class SemesterRangeTest extends TestCase
{
    /**
     * Test getSemestersBetween with two different semesters
     */
    public function testGetSemestersBetweenTwoDifferentSemesters(): void
    {
        $sem1 = new Semester('Spring 2024');
        $sem2 = new Semester('Fall 2024');
        $range = $sem1->getSemestersBetween($sem2);
        
        $this->assertCount(3, $range); // Spring, Summer, Fall 2024
        $this->assertEquals('Spring 2024', $range[0]->getString());
        $this->assertEquals('Summer 2024', $range[1]->getString());
        $this->assertEquals('Fall 2024', $range[2]->getString());
    }

    /**
     * Test getSemestersBetween in reverse order
     */
    public function testGetSemestersBetweenReverseOrder(): void
    {
        $sem1 = new Semester('Fall 2024');
        $sem2 = new Semester('Spring 2024');
        $range = $sem1->getSemestersBetween($sem2);
        
        $this->assertCount(3, $range);
        $this->assertEquals('Spring 2024', $range[0]->getString());
        $this->assertEquals('Summer 2024', $range[1]->getString());
        $this->assertEquals('Fall 2024', $range[2]->getString());
    }

    /**
     * Test getSemestersBetween same semester
     */
    public function testGetSemestersBetweenSameSemester(): void
    {
        $sem1 = new Semester('Spring 2024');
        $sem2 = new Semester('Spring 2024');
        $range = $sem1->getSemestersBetween($sem2);
        
        $this->assertCount(1, $range);
        $this->assertEquals('Spring 2024', $range[0]->getString());
    }

    /**
     * Test getSemestersBetween with multiple years
     */
    public function testGetSemestersBetweenMultipleYears(): void
    {
        $sem1 = new Semester('Fall 2023');
        $sem2 = new Semester('Spring 2025');
        $range = $sem1->getSemestersBetween($sem2);
        
        // Fall 2023, Spring 2024, Summer 2024, Fall 2024, Spring 2025
        $this->assertCount(5, $range);
    }

    /**
     * Test getSemestersBetween with string argument
     */
    public function testGetSemestersBetweenWithString(): void
    {
        $semester = new Semester('Spring 2024');
        $range = $semester->getSemestersBetween('Summer 2024');
        
        $this->assertCount(2, $range);
        $this->assertEquals('Spring 2024', $range[0]->getString());
        $this->assertEquals('Summer 2024', $range[1]->getString());
    }

    /**
     * Test getSemestersBetween with integer code
     */
    public function testGetSemestersBetweenWithIntegerCode(): void
    {
        $semester = new Semester('Spring 2024');
        $range = $semester->getSemestersBetween(1248); // Fall 2024
        
        $this->assertCount(3, $range);
    }

    /**
     * Test getSemestersBetween returns Semester instances
     */
    public function testGetSemestersBetweenReturnsSemesterInstances(): void
    {
        $sem1 = new Semester('Spring 2024');
        $sem2 = new Semester('Summer 2024');
        $range = $sem1->getSemestersBetween($sem2);
        
        foreach ($range as $semester) {
            $this->assertInstanceOf(Semester::class, $semester);
        }
    }

    /**
     * Test getSemestersBetween consecutive semesters
     */
    public function testGetSemestersBetweenConsecutive(): void
    {
        $sem1 = new Semester('Summer 2024');
        $sem2 = new Semester('Fall 2024');
        $range = $sem1->getSemestersBetween($sem2);
        
        $this->assertCount(2, $range);
        $this->assertEquals('Summer 2024', $range[0]->getString());
        $this->assertEquals('Fall 2024', $range[1]->getString());
    }

    /**
     * Test getSemestersBetween throws exception for invalid input
     */
    public function testGetSemestersBetweenThrowsExceptionForInvalidInput(): void
    {
        $semester = new Semester('Spring 2024');
        $this->expectException(SemesterInvalidInput::class);
        $semester->getSemestersBetween('Winter 2024');
    }

    /**
     * Test getSemestersBetween large range
     */
    public function testGetSemestersBetweenLargeRange(): void
    {
        $sem1 = new Semester('Spring 2020');
        $sem2 = new Semester('Fall 2023');
        $range = $sem1->getSemestersBetween($sem2);
        
        // 4 years * 3 semesters = 12 semesters
        $this->assertCount(12, $range);
        $this->assertEquals('Spring 2020', $range[0]->getString());
        $this->assertEquals('Fall 2023', $range[11]->getString());
    }

    /**
     * Test getSemestersBetween can be used to iterate
     */
    public function testGetSemestersBetweenIteration(): void
    {
        $sem1 = new Semester('Spring 2024');
        $sem2 = new Semester('Fall 2024');
        $range = $sem1->getSemestersBetween($sem2);
        
        $strings = array_map(fn($s) => $s->getString(), $range);
        $this->assertEquals(['Spring 2024', 'Summer 2024', 'Fall 2024'], $strings);
    }

    /**
     * Test getSemestersBetween across year boundary
     */
    public function testGetSemestersBetweenAcrossYearBoundary(): void
    {
        $sem1 = new Semester('Fall 2023');
        $sem2 = new Semester('Spring 2024');
        $range = $sem1->getSemestersBetween($sem2);
        
        $this->assertCount(2, $range);
        $this->assertEquals('Fall 2023', $range[0]->getString());
        $this->assertEquals('Spring 2024', $range[1]->getString());
    }

    /**
     * Test getSemestersBetween century boundary
     */
    public function testGetSemestersBetweenCenturyBoundary(): void
    {
        $sem1 = new Semester('Fall 1999');
        $sem2 = new Semester('Spring 2000');
        $range = $sem1->getSemestersBetween($sem2);
        
        $this->assertCount(2, $range);
        $this->assertEquals('Fall 1999', $range[0]->getString());
        $this->assertEquals('Spring 2000', $range[1]->getString());
    }

    /**
     * Test getSemestersBetween all semesters in range are valid
     */
    public function testGetSemestersBetweenAllValid(): void
    {
        $sem1 = new Semester('Spring 2024');
        $sem2 = new Semester('Summer 2025');
        $range = $sem1->getSemestersBetween($sem2);
        
        foreach ($range as $semester) {
            $this->assertTrue(Semester::isValidCode((int)$semester->getCode()));
        }
    }
}

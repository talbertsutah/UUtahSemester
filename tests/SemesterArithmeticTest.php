<?php

namespace talbertsutah\UUtahSemester\Tests;

use PHPUnit\Framework\TestCase;
use talbertsutah\UUtahSemester\Semester;

class SemesterArithmeticTest extends TestCase
{
    /**
     * Test addSemesters moves forward correctly
     */
    public function testAddSemestersForward(): void
    {
        $semester = new Semester('Fall 2024');
        $result = $semester->addSemesters(1);
        $this->assertEquals('1254', $result->getCode());
        $this->assertEquals('Spring 2025', $result->getString());
    }

    /**
     * Test addSemesters with multiple semesters
     */
    public function testAddMultipleSemesters(): void
    {
        $semester = new Semester('Spring 2024');
        $result = $semester->addSemesters(3);
        $this->assertEquals('Spring 2025', $result->getString());
    }

    /**
     * Test addSemesters with negative value (backwards)
     */
    public function testAddSemestersBackward(): void
    {
        $semester = new Semester('Spring 2025');
        $result = $semester->addSemesters(-1);
        $this->assertEquals('1248', $result->getCode());
        $this->assertEquals('Fall 2024', $result->getString());
    }

    /**
     * Test addSemesters pattern: Spring -> Summer -> Fall -> Spring
     */
    public function testAddSemestersPattern(): void
    {
        $spring = new Semester('Spring 2024');
        $summer = (new Semester('Spring 2024'))->addSemesters(1);
        $fall = (new Semester('Spring 2024'))->addSemesters(2);
        $nextSpring = (new Semester('Spring 2024'))->addSemesters(3);

        $this->assertEquals('Spring 2024', $spring->getString());
        $this->assertEquals('Summer 2024', $summer->getString());
        $this->assertEquals('Fall 2024', $fall->getString());
        $this->assertEquals('Spring 2025', $nextSpring->getString());
    }

    /**
     * Test addSemesters returns self for chaining
     */
    public function testAddSemestersReturnsSelf(): void
    {
        $semester = new Semester('Spring 2024');
        $result = $semester->addSemesters(5);
        $this->assertSame($semester, $result);
    }

    /**
     * Test addYears adds correct number of semesters
     */
    public function testAddYears(): void
    {
        $semester = new Semester('Spring 2024');
        $result = $semester->addYears(1);
        $this->assertEquals('Spring 2025', $result->getString());
    }

    /**
     * Test addYears with multiple years
     */
    public function testAddMultipleYears(): void
    {
        $semester = new Semester('Fall 2024');
        $result = $semester->addYears(5);
        $this->assertEquals('Fall 2029', $result->getString());
    }

    /**
     * Test addYears with negative value
     */
    public function testAddYearsBackward(): void
    {
        $semester = new Semester('Summer 2025');
        $result = $semester->addYears(-3);
        $this->assertEquals('Summer 2022', $result->getString());
    }

    /**
     * Test addYears preserves semester
     */
    public function testAddYearsPreservesSemester(): void
    {
        $semester = new Semester('Summer 2024');
        $result = $semester->addYears(10);
        $this->assertEquals('Summer 2034', $result->getString());
    }

    /**
     * Test subSemesters
     */
    public function testSubSemesters(): void
    {
        $semester = new Semester('Fall 2024');
        $result = $semester->subSemesters(1);
        $this->assertEquals('Summer 2024', $result->getString());
    }

    /**
     * Test subSemesters with multiple values
     */
    public function testSubMultipleSemesters(): void
    {
        $semester = new Semester('Spring 2025');
        $result = $semester->subSemesters(3);
        $this->assertEquals('Spring 2024', $result->getString());
    }

    /**
     * Test subSemesters returns self for chaining
     */
    public function testSubSemestersReturnsSelf(): void
    {
        $semester = new Semester('Spring 2024');
        $result = $semester->subSemesters(2);
        $this->assertSame($semester, $result);
    }

    /**
     * Test subYears
     */
    public function testSubYears(): void
    {
        $semester = new Semester('Fall 2025');
        $result = $semester->subYears(1);
        $this->assertEquals('Fall 2024', $result->getString());
    }

    /**
     * Test subYears with multiple years
     */
    public function testSubMultipleYears(): void
    {
        $semester = new Semester('Summer 2030');
        $result = $semester->subYears(5);
        $this->assertEquals('Summer 2025', $result->getString());
    }

    /**
     * Test chaining multiple operations
     */
    public function testChainingOperations(): void
    {
        $semester = new Semester('Spring 2024');
        $result = $semester->addYears(2)->addSemesters(1)->subSemesters(3);
        $this->assertEquals('Summer 2025', $result->getString());
    }

    /**
     * Test adding and subtracting same amount returns original
     */
    public function testAddSubtractCancels(): void
    {
        $original = new Semester('Fall 2024');
        $code1 = $original->getCode();
        $original->addSemesters(5)->subSemesters(5);
        $this->assertEquals($code1, $original->getCode());
    }

    /**
     * Test large additions
     */
    public function testLargeAddition(): void
    {
        $semester = new Semester('Spring 1900');
        $result = $semester->addYears(100);
        $this->assertEquals('Spring 2000', $result->getString());
    }

    /**
     * Test around year boundaries
     */
    public function testYearBoundary(): void
    {
        $semester = new Semester('Summer 1999');
        $result = $semester->addSemesters(1);
        $this->assertEquals('Fall 1999', $result->getString());
        
        $result->addSemesters(1);
        $this->assertEquals('Spring 2000', $result->getString());
    }
}

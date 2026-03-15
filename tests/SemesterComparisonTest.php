<?php

namespace talbertsutah\UUtahSemester\Tests;

use PHPUnit\Framework\TestCase;
use talbertsutah\UUtahSemester\Semester;
use talbertsutah\UUtahSemester\Exceptions\SemesterInvalidInput;

class SemesterComparisonTest extends TestCase
{
    /**
     * Test isBefore with later semester
     */
    public function testIsBeforeWithLaterSemester(): void
    {
        $sem1 = new Semester('Spring 2024');
        $sem2 = new Semester('Fall 2024');
        $this->assertTrue($sem1->isBefore($sem2));
        $this->assertFalse($sem2->isBefore($sem1));
    }

    /**
     * Test isAfter with earlier semester
     */
    public function testIsAfterWithEarlierSemester(): void
    {
        $sem1 = new Semester('Fall 2024');
        $sem2 = new Semester('Spring 2024');
        $this->assertTrue($sem1->isAfter($sem2));
        $this->assertFalse($sem2->isAfter($sem1));
    }

    /**
     * Test equals with same semester
     */
    public function testEqualsWithSameSemester(): void
    {
        $sem1 = new Semester('Fall 2024');
        $sem2 = new Semester('Fall 2024');
        $this->assertTrue($sem1->equals($sem2));
    }

    /**
     * Test equals with different semester
     */
    public function testEqualsWithDifferentSemester(): void
    {
        $sem1 = new Semester('Fall 2024');
        $sem2 = new Semester('Spring 2024');
        $this->assertFalse($sem1->equals($sem2));
    }

    /**
     * Test isBefore with integer code
     */
    public function testIsBeforeWithIntegerCode(): void
    {
        $semester = new Semester('Spring 2024');
        $this->assertTrue($semester->isBefore(1246));
        $this->assertFalse($semester->isBefore(1238));
    }

    /**
     * Test isAfter with string
     */
    public function testIsAfterWithString(): void
    {
        $semester = new Semester('Fall 2024');
        $this->assertTrue($semester->isAfter('Spring 2024'));
        $this->assertFalse($semester->isAfter('Fall 2024'));
    }

    /**
     * Test equals with string representation
     */
    public function testEqualsWithString(): void
    {
        $semester = new Semester(1248);
        $this->assertTrue($semester->equals('Fall 2024'));
    }

    /**
     * Test isBefore throws exception for invalid input
     */
    public function testIsBeforeThrowsExceptionForInvalidInput(): void
    {
        $semester = new Semester('Spring 2024');
        $this->expectException(SemesterInvalidInput::class);
        $semester->isBefore('Winter 2024');
    }

    /**
     * Test isAfter throws exception for invalid input
     */
    public function testIsAfterThrowsExceptionForInvalidInput(): void
    {
        $semester = new Semester('Spring 2024');
        $this->expectException(SemesterInvalidInput::class);
        $semester->isAfter(1245);
    }

    /**
     * Test equals throws exception for invalid input
     */
    public function testEqualsThrowsExceptionForInvalidInput(): void
    {
        $semester = new Semester('Spring 2024');
        $this->expectException(SemesterInvalidInput::class);
        $semester->equals('Invalid 2024');
    }

    /**
     * Test comparisons across years
     */
    public function testComparisonsAcrossYears(): void
    {
        $sem1 = new Semester('Fall 2023');
        $sem2 = new Semester('Spring 2024');
        $this->assertTrue($sem1->isBefore($sem2));
        $this->assertTrue($sem2->isAfter($sem1));
    }

    /**
     * Test comparisons with century boundaries
     */
    public function testComparisonsCenturyBoundary(): void
    {
        $sem1 = new Semester('Fall 1999');
        $sem2 = new Semester('Spring 2000');
        $this->assertTrue($sem1->isBefore($sem2));
    }

    /**
     * Test not before or after means equal
     */
    public function testNotBeforeAndNotAfterMeansEqual(): void
    {
        $sem1 = new Semester('Spring 2024');
        $sem2 = new Semester('Spring 2024');
        $this->assertFalse($sem1->isBefore($sem2));
        $this->assertFalse($sem1->isAfter($sem2));
        $this->assertTrue($sem1->equals($sem2));
    }

    /**
     * Test getDifference in semesters
     */
    public function testGetDifferenceInSemesters(): void
    {
        $sem1 = new Semester('Spring 2024');
        $sem2 = new Semester('Fall 2024');
        $diff = $sem1->getDifference($sem2);
        $this->assertEquals(-2, $diff); // sem2 is 2 semesters after sem1
    }

    /**
     * Test getDifference in years
     */
    public function testGetDifferenceInYears(): void
    {
        $sem1 = new Semester('Spring 2024');
        $sem2 = new Semester('Spring 2025');
        $diff = $sem1->getDifference($sem2, 'year');
        $this->assertEquals(-1, $diff);
    }

    /**
     * Test getDifference with same semester
     */
    public function testGetDifferenceWithSameSemester(): void
    {
        $sem1 = new Semester('Spring 2024');
        $sem2 = new Semester('Spring 2024');
        $this->assertEquals(0, $sem1->getDifference($sem2));
    }

    /**
     * Test getDifference with Semester object
     */
    public function testGetDifferenceWithSemesterObject(): void
    {
        $sem1 = new Semester('Fall 2024');
        $sem2 = new Semester('Fall 2023');
        $diff = $sem1->getDifference($sem2);
        $this->assertEquals(3, $diff); // sem1 is 3 semesters after sem2
    }

    /**
     * Test getDifference with integer code
     */
    public function testGetDifferenceWithIntegerCode(): void
    {
        $semester = new Semester('Spring 2024');
        $diff = $semester->getDifference(1234);
        $this->assertEquals(3, $diff); // Spring 2024 is 3 semesters after Spring 2023
    }

    /**
     * Test getDifference positive and negative are opposite
     */
    public function testGetDifferenceSymmetry(): void
    {
        $sem1 = new Semester('Spring 2024');
        $sem2 = new Semester('Fall 2025');
        $diff1 = $sem1->getDifference($sem2);
        $diff2 = $sem2->getDifference($sem1);
        $this->assertEquals(-$diff1, $diff2);
    }

    /**
     * Test getDifference throws exception for invalid input
     */
    public function testGetDifferenceThrowsExceptionForInvalidInput(): void
    {
        $semester = new Semester('Spring 2024');
        $this->expectException(SemesterInvalidInput::class);
        $semester->getDifference('Winter 2024');
    }
}

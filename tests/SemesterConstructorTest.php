<?php

namespace talbertsutah\UUtahSemester\Tests;

use PHPUnit\Framework\TestCase;
use talbertsutah\UUtahSemester\Semester;
use talbertsutah\UUtahSemester\Exceptions\SemesterInvalidInput;

class SemesterConstructorTest extends TestCase
{
    /**
     * Test creating a semester from a valid integer code
     */
    public function testConstructorWithValidCode(): void
    {
        $semester = new Semester(1244);
        $this->assertInstanceOf(Semester::class, $semester);
        $this->assertEquals('1244', $semester->getCode());
        $this->assertEquals('Spring 2024', $semester->getString());
    }

    /**
     * Test creating a semester from a valid string
     */
    public function testConstructorWithValidString(): void
    {
        $semester = new Semester('Spring 2026');
        $this->assertInstanceOf(Semester::class, $semester);
        $this->assertEquals('1264', $semester->getCode());
        $this->assertEquals('Spring 2026', $semester->getString());
    }

    /**
     * Test creating a semester with leading zeros
     */
    public function testConstructorWithLeadingZeros(): void
    {
        $semester = new Semester(264);
        $this->assertEquals('0264', $semester->getCode());
        $this->assertEquals('Spring 1926', $semester->getString());
    }

    /**
     * Test constructor throws exception for invalid code
     */
    public function testConstructorThrowsExceptionForInvalidCode(): void
    {
        $this->expectException(SemesterInvalidInput::class);
        new Semester(1245); // Invalid last digit
    }

    /**
     * Test constructor throws exception for invalid string
     */
    public function testConstructorThrowsExceptionForInvalidString(): void
    {
        $this->expectException(SemesterInvalidInput::class);
        new Semester('Winter 2024'); // Invalid semester name
    }

    /**
     * Test constructor throws TypeError for invalid type
     */
    public function testConstructorThrowsTypeErrorForInvalidType(): void
    {
        $this->expectException(\TypeError::class);
        new Semester([]); // Array is invalid type
    }

    /**
     * Test constructor with negative code
     */
    public function testConstructorWithNegativeCode(): void
    {
        $this->expectException(SemesterInvalidInput::class);
        new Semester(-1244);
    }

    /**
     * Test case-insensitive string input
     */
    public function testConstructorWithMixedCaseSemesterString(): void
    {
        $semester = new Semester('fall 2024');
        $this->assertEquals('Fall 2024', $semester->getString());
    }

    /**
     * Test set method with valid code
     */
    public function testSetMethodWithValidCode(): void
    {
        $semester = new Semester(1244);
        $semester->set(1178);
        $this->assertEquals('1178', $semester->getCode());
        $this->assertEquals('Fall 2017', $semester->getString());
    }

    /**
     * Test set method with valid string
     */
    public function testSetMethodWithValidString(): void
    {
        $semester = new Semester('Spring 2026');
        $semester->set('Summer 1995');
        $this->assertEquals('0956', $semester->getCode());
        $this->assertEquals('Summer 1995', $semester->getString());
    }

    /**
     * Test set method throws exception for invalid input
     */
    public function testSetMethodThrowsExceptionForInvalidInput(): void
    {
        $semester = new Semester(1244);
        $this->expectException(SemesterInvalidInput::class);
        $semester->set(1235);
    }

    /**
     * Test get method returns array with both representations
     */
    public function testGetMethod(): void
    {
        $semester = new Semester('Fall 2024');
        $result = $semester->get();
        $this->assertIsArray($result);
        $this->assertEquals('1248', $result['Code']);
        $this->assertEquals('Fall 2024', $result['String']);
    }

    /**
     * Test getTermAndYear breaks down the semester correctly
     */
    public function testGetTermAndYear(): void
    {
        $semester = new Semester('Summer 2020');
        $result = $semester->getTermAndYear();
        $this->assertEquals('Summer', $result['term']);
        $this->assertEquals('2020', $result['year']);
    }
}

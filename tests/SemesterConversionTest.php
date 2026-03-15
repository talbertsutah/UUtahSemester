<?php

namespace talbertsutah\UUtahSemester\Tests;

use PHPUnit\Framework\TestCase;
use talbertsutah\UUtahSemester\Semester;
use talbertsutah\UUtahSemester\Exceptions\SemesterInvalidInput;

class SemesterConversionTest extends TestCase
{
    /**
     * Test codeToString conversion
     */
    public function testCodeToStringConversion(): void
    {
        $this->assertEquals('Spring 2026', Semester::codeToString(1264));
        $this->assertEquals('Fall 2024', Semester::codeToString(1248));
        $this->assertEquals('Summer 1995', Semester::codeToString(956));
    }

    /**
     * Test codeToString with leading zeros
     */
    public function testCodeToStringWithLeadingZeros(): void
    {
        $this->assertEquals('Spring 1926', Semester::codeToString(264));
    }

    /**
     * Test codeToString throws exception for invalid code
     */
    public function testCodeToStringThrowsExceptionForInvalidCode(): void
    {
        $this->expectException(SemesterInvalidInput::class);
        Semester::codeToString(1245);
    }

    /**
     * Test stringToCode conversion
     */
    public function testStringToCodeConversion(): void
    {
        $this->assertEquals(1264, Semester::stringToCode('Spring 2026'));
        $this->assertEquals(1248, Semester::stringToCode('Fall 2024'));
        $this->assertEquals(956, Semester::stringToCode('Summer 1995'));
    }

    /**
     * Test stringToCode case-insensitive
     */
    public function testStringToCodeCaseInsensitive(): void
    {
        $this->assertEquals(1248, Semester::stringToCode('fall 2024'));
        $this->assertEquals(1248, Semester::stringToCode('FALL 2024'));
    }

    /**
     * Test stringToCode throws exception for invalid string
     */
    public function testStringToCodeThrowsExceptionForInvalidString(): void
    {
        $this->expectException(SemesterInvalidInput::class);
        Semester::stringToCode('Winter 2024');
    }

    /**
     * Test stringToCode with null input
     */
    public function testStringToCodeWithNull(): void
    {
        $this->assertNull(Semester::stringToCode(null));
    }

    /**
     * Test codeYear extraction
     */
    public function testCodeYearExtraction(): void
    {
        $this->assertEquals(2026, Semester::codeYear(1264));
        $this->assertEquals(2024, Semester::codeYear(1244));
        $this->assertEquals(1995, Semester::codeYear(956));
        $this->assertEquals(1926, Semester::codeYear(264));
    }

    /**
     * Test codeYear with null input
     */
    public function testCodeYearWithNull(): void
    {
        $this->assertNull(Semester::codeYear(null));
    }

    /**
     * Test codeYear throws exception for invalid code
     */
    public function testCodeYearThrowsExceptionForInvalidCode(): void
    {
        $this->expectException(SemesterInvalidInput::class);
        Semester::codeYear(1245);
    }

    /**
     * Test codeSemester extraction
     */
    public function testCodeSemesterExtraction(): void
    {
        $this->assertEquals('Spring', Semester::codeSemester(1264));
        $this->assertEquals('Fall', Semester::codeSemester(1248));
        $this->assertEquals('Summer', Semester::codeSemester(956));
    }

    /**
     * Test codeSemester with null input
     */
    public function testCodeSemesterWithNull(): void
    {
        $this->assertNull(Semester::codeSemester(null));
    }

    /**
     * Test codeSemester throws exception for invalid code
     */
    public function testCodeSemesterThrowsExceptionForInvalidCode(): void
    {
        $this->expectException(SemesterInvalidInput::class);
        Semester::codeSemester(1235);
    }

    /**
     * Test round-trip conversion code -> string -> code
     */
    public function testRoundTripCodeToStringToCode(): void
    {
        $original = 1244;
        $string = Semester::codeToString($original);
        $roundTrip = Semester::stringToCode($string);
        $this->assertEquals($original, $roundTrip);
    }

    /**
     * Test round-trip conversion string -> code -> string
     */
    public function testRoundTripStringToCodeToString(): void
    {
        $original = 'Fall 2024';
        $code = Semester::stringToCode($original);
        $roundTrip = Semester::codeToString($code);
        $this->assertEquals($original, $roundTrip);
    }

    /**
     * Test isValidCode with valid codes
     */
    public function testIsValidCodeWithValidCodes(): void
    {
        $this->assertTrue(Semester::isValidCode(1264)); // Spring 2026
        $this->assertTrue(Semester::isValidCode(1244)); // Fall 2024
        $this->assertTrue(Semester::isValidCode(956));  // Summer 1995
    }

    /**
     * Test isValidCode with invalid codes
     */
    public function testIsValidCodeWithInvalidCodes(): void
    {
        $this->assertFalse(Semester::isValidCode(1245)); // Invalid last digit
        $this->assertFalse(Semester::isValidCode(1242)); // Invalid last digit
        $this->assertFalse(Semester::isValidCode(-1));   // Negative
    }

    /**
     * Test isValidString with valid strings
     */
    public function testIsValidStringWithValidStrings(): void
    {
        $this->assertTrue(Semester::isValidString('Spring 2026'));
        $this->assertTrue(Semester::isValidString('Fall 2024'));
        $this->assertTrue(Semester::isValidString('Summer 1995'));
    }

    /**
     * Test isValidString case insensitive
     */
    public function testIsValidStringCaseInsensitive(): void
    {
        $this->assertTrue(Semester::isValidString('fall 2024'));
        $this->assertTrue(Semester::isValidString('SPRING 2026'));
    }

    /**
     * Test isValidString with invalid strings
     */
    public function testIsValidStringWithInvalidStrings(): void
    {
        $this->assertFalse(Semester::isValidString('Winter 2024'));
        $this->assertFalse(Semester::isValidString('Fall2024')); // Missing space
        $this->assertFalse(Semester::isValidString('Fall 24'));  // Year too short
    }
}

<?php

namespace talbertsutah\UUtahSemester\Tests;

use PHPUnit\Framework\TestCase;
use talbertsutah\UUtahSemester\Semester;

class SemesterStaticMethodsTest extends TestCase
{
    /**
     * Test now returns a valid Semester instance
     */
    public function testNowReturnsValidSemester(): void
    {
        $semester = Semester::now();
        $this->assertInstanceOf(Semester::class, $semester);
    }

    /**
     * Test now returns current year
     */
    public function testNowReturnsCurrentYear(): void
    {
        $semester = Semester::now();
        $termAndYear = $semester->getTermAndYear();
        $currentYear = (int) date('Y');
        $this->assertEquals($currentYear, (int) $termAndYear['year']);
    }

    /**
     * Test now returns valid semester term
     */
    public function testNowReturnsValidTerm(): void
    {
        $semester = Semester::now();
        $termAndYear = $semester->getTermAndYear();
        $this->assertContains($termAndYear['term'], ['Spring', 'Summer', 'Fall']);
    }

    /**
     * Test next returns a later semester
     */
    public function testNextReturnsLaterSemester(): void
    {
        $now = Semester::now();
        $next = Semester::next();
        $this->assertTrue($next->isAfter($now));
    }

    /**
     * Test next returns exactly one semester later
     */
    public function testNextIsOneSemesterLater(): void
    {
        $now = Semester::now();
        $next = Semester::next();
        $diff = $now->getDifference($next);
        $this->assertEquals(-1, $diff);
    }

    /**
     * Test previous returns an earlier semester
     */
    public function testPreviousReturnsEarlierSemester(): void
    {
        $now = Semester::now();
        $prev = Semester::previous();
        $this->assertTrue($prev->isBefore($now));
    }

    /**
     * Test previous returns exactly one semester earlier
     */
    public function testPreviousIsOneSemesterEarlier(): void
    {
        $now = Semester::now();
        $prev = Semester::previous();
        $diff = $now->getDifference($prev);
        $this->assertEquals(1, $diff);
    }

    /**
     * Test random returns a valid Semester instance
     */
    public function testRandomReturnsValidSemester(): void
    {
        $semester = Semester::random();
        $this->assertInstanceOf(Semester::class, $semester);
    }

    /**
     * Test random respects year bounds
     */
    public function testRandomRespectsYearBounds(): void
    {
        $semester = Semester::random(2020, 2025);
        $termAndYear = $semester->getTermAndYear();
        $year = (int) $termAndYear['year'];
        $this->assertGreaterThanOrEqual(2020, $year);
        $this->assertLessThanOrEqual(2025, $year);
    }

    /**
     * Test random with exclude parameter
     */
    public function testRandomWithExclude(): void
    {
        $semester = Semester::random(2020, 2025, ['Summer']);
        $termAndYear = $semester->getTermAndYear();
        $this->assertNotEquals('Summer', $termAndYear['term']);
    }

    /**
     * Test random with multiple excludes
     */
    public function testRandomWithMultipleExcludes(): void
    {
        $semester = Semester::random(2020, 2025, ['Summer', 'Fall']);
        $termAndYear = $semester->getTermAndYear();
        $this->assertEquals('Spring', $termAndYear['term']);
    }

    /**
     * Test multiple calls to random return different values (probabilistically)
     */
    public function testMultipleRandomCallsReturnDifferentValues(): void
    {
        $semesters = [];
        for ($i = 0; $i < 10; $i++) {
            $semesters[] = Semester::random(1900, 2100)->getCode();
        }
        
        // Very unlikely all 10 are the same
        $unique = count(array_unique($semesters));
        $this->assertGreaterThan(1, $unique);
    }

    /**
     * Test isValidCode with valid codes
     */
    public function testIsValidCodeAcceptsValidCodes(): void
    {
        $this->assertTrue(Semester::isValidCode(1264)); // Spring
        $this->assertTrue(Semester::isValidCode(1266)); // Summer
        $this->assertTrue(Semester::isValidCode(1268)); // Fall
    }

    /**
     * Test isValidCode with invalid codes
     */
    public function testIsValidCodeRejectsInvalidCodes(): void
    {
        $this->assertFalse(Semester::isValidCode(1265)); // Invalid digit
        $this->assertFalse(Semester::isValidCode(1267)); // Invalid digit
        $this->assertFalse(Semester::isValidCode(-1));  // Negative
    }

    /**
     * Test isValidCode with edge cases
     */
    public function testIsValidCodeEdgeCases(): void
    {
        $this->assertTrue(Semester::isValidCode(4));    // Spring 1900
        $this->assertTrue(Semester::isValidCode(6));    // Summer 1900
        $this->assertTrue(Semester::isValidCode(8));    // Fall 1900
        $this->assertFalse(Semester::isValidCode(0));   // Invalid
        $this->assertFalse(Semester::isValidCode(1));   // Invalid
    }

    /**
     * Test isValidString with valid strings
     */
    public function testIsValidStringAcceptsValidStrings(): void
    {
        $this->assertTrue(Semester::isValidString('Spring 2024'));
        $this->assertTrue(Semester::isValidString('Summer 2024'));
        $this->assertTrue(Semester::isValidString('Fall 2024'));
    }

    /**
     * Test isValidString case insensitive
     */
    public function testIsValidStringCaseInsensitive(): void
    {
        $this->assertTrue(Semester::isValidString('spring 2024'));
        $this->assertTrue(Semester::isValidString('SUMMER 2024'));
        $this->assertTrue(Semester::isValidString('FaLl 2024'));
    }

    /**
     * Test isValidString with invalid strings
     */
    public function testIsValidStringRejectsInvalidStrings(): void
    {
        $this->assertFalse(Semester::isValidString('Winter 2024'));
        $this->assertFalse(Semester::isValidString('Spring2024'));
        $this->assertFalse(Semester::isValidString('Spring 24'));
        $this->assertFalse(Semester::isValidString('2024 Spring')); // Wrong order
    }

    /**
     * Test static methods don't interfere with instance state
     */
    public function testStaticMethodsDontAffectInstances(): void
    {
        $semester = new Semester('Spring 2024');
        $code1 = $semester->getCode();
        
        Semester::now();
        Semester::next();
        Semester::previous();
        Semester::random();
        
        $code2 = $semester->getCode();
        $this->assertEquals($code1, $code2);
    }

    /**
     * Test now is consistent within a single test
     */
    public function testNowIsConsistent(): void
    {
        $now1 = Semester::now();
        $now2 = Semester::now();
        
        // Should be the same semester (not affected by milliseconds)
        $this->assertEquals($now1->getCode(), $now2->getCode());
    }
}

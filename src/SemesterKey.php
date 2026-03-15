<?php

namespace talbertsutah\UUtahSemester;

/*  Enum that holds information about the scheme for converting semesters to codes
*
*   The scheme from string to integer representation is 'SEMESTER YEAR' -> XXXY, where XXX = YEAR - 1900, and SEMESTER is represented by the digit Y
*   
*   SEMESTER is one of {Spring, Summer, Fall}, which map to Spring->4, Summer->6, Fall->8
*
*   ZeroYear = 1900 stores the fact that 1900 is the minimum year
*/

enum SemesterKey: int
{
    case Spring = 4;
    case Summer = 6;
    case Fall = 8;

    /**
     * Get all valid semester digit values [4, 6, 8]
     * 
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }

    /**
     * Get all semester names ['Spring', 'Summer', 'Fall']
     * 
     * @return array
     */
    public static function names(): array
    {
        return array_map(fn(self $case) => $case->name, self::cases());
    }

    /**
     * Get digit-to-name mapping [4 => 'Spring', 6 => 'Summer', 8 => 'Fall']
     * 
     * @return array
     */
    public static function digitToName(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    /**
     * Get name-to-digit mapping ['Spring' => 4, 'Summer' => 6, 'Fall' => 8]
     * 
     * @return array
     */
    public static function nameToDigit(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }

    /**
     * Return the semester for a given day of the year (1-365)
     * Spring: Days 1-125 (Jan 1 - May 5)
     * Summer: Days 126-225 (May 6 - Aug 13)
     * Fall: Days 226-365 (Aug 14 - Dec 31)
     * 
     * @param int $dayOfYear Day number from 1-365
     * @return self The appropriate semester
     */
    public static function fromDayOfYear(int $dayOfYear): self
    {
        return match (true) {
            $dayOfYear < 126 => self::Spring,
            $dayOfYear < 226 => self::Summer,
            default => self::Fall,
        };
    }
}

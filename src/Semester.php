<?php

namespace talbertsutah\UUtahSemester;

use talbertsutah\UUtahSemester\Exceptions\SemesterInvalidInput;


class Semester {

    private string $stringFormat;
    private int $intFormat;
    const ZEROYEAR = 1900;

    /**
     * Create a new Semester instance
     * 
     * @param int|string $val A valid semester code (int) or string ('Fall 2024', etc.)
     * @throws TypeError If input is not int or string
     * @throws SemesterInvalidInput If the value is not a valid semester
     */
    public function __construct(int|string $val) 
    {    
        $this->validateInput($val);
        $this->set($val);
    }

    /**
     * Return boolean indicating if integer code is a valid representation of a semester
     * 
     * @example Semester::isValidCode(1244)
     * @param int $intCode
     * @return bool
     */
    public static function isValidCode(int $intCode) : bool 
    {
        if ($intCode < 0) return false;
        
        $lastDigit = $intCode % 10; 
        $digitCheck = in_array($lastDigit, SemesterKey::values());

        return $digitCheck;
    }

    /**
     * Return boolean indicating if input string is a valid semester
     * 
     * @example Semester::isValidString('Fall 2024')
     * @param string $semesterString
     * @return bool
     */
    public static function isValidString(string $semesterString) : bool 
    {
        return preg_match('/(Fall|Spring|Summer)\\s\\d{4}/i', $semesterString);
    }

    /**
     * Validate that the input is a valid semester value, throws exception if not
     * Type checking is handled by the constructor parameter type hint
     *
     * @param int|string $val The value to validate
     * @return void
     * @throws SemesterInvalidInput If the value is not a valid semester
     */
    private function validateInput(int|string $val): void
    {
        if (is_int($val)) {
            if (!self::isValidCode($val)) {
                throw new SemesterInvalidInput($val, 'code');
            }
        }
        elseif (is_string($val)) {
            if (!self::isValidString($val)) {
                throw new SemesterInvalidInput($val, 'string');
            }
        }
    }

    /**
     * Return an array representing the code and string representation of the semester instance
     * @return array
     */
    public function get(): array
    {
        return ['Code' => $this->getCode(), 'String' => $this->getString()];
    }

    /**
     * Return the code representation of the semester instance. Returned as a string with front padded zeros to make it 4 digits.
     * @return string
     */
    public function getCode(): string
    {
        return sprintf('%04d', $this->intFormat);
    }

    /**
     * Return the string representation of the semester instance
     * @return string
     */
    public function getString(): string
    {
        return $this->stringFormat;
    }

    /**
     * Return an associative array containing the string version of the term and year
     * 
     * @return array
     */
    public function getTermAndYear(): array
    {
        $splitString = explode(" ", $this->stringFormat);
        return ['term' => $splitString[0], 'year' => $splitString[1]];
    }

    /**
     * Set the internal code and string representation of the semester instance
     * Input can be either a string representation or an integer representation
     * 
     * @param int|string $val A semester code or semester string
     * @return void
     * @throws SemesterInvalidInput If the value is not a valid semester
     */
    public function set(int|string $val): void
    {
        if (is_int($val) || is_numeric($val)) {
            $val = (int) $val;
            if (!self::isValidCode($val)) {
                throw new SemesterInvalidInput($val, 'code');
            }
            $this->intFormat = $val;
            $this->stringFormat = self::codeToString($val);
        }
        elseif (is_string($val)) {
            if (!self::isValidString($val)) {
                throw new SemesterInvalidInput($val, 'string');
            }
            // Normalize the semester name to title case
            $semester = substr($val, 0, strpos($val, ' '));
            $semester = ucfirst(strtolower($semester));
            $year = substr($val, -4);
            $val = $semester . ' ' . $year;
            
            $this->stringFormat = $val;
            $this->intFormat = self::stringToCode($val);
        }
        else {
            throw new SemesterInvalidInput($val);
        }
    }

    /**
     * Convert an integer representation of a semester into the string representation
     * 
     * @example Semester::codeToString(1244) returns 'Fall 2024'
     * @param int $intCode The semester code to convert
     * @return string The string representation ('Fall 2024', etc.)
     * @throws SemesterInvalidInput If the code is invalid
     */
    public static function codeToString(int $intCode) : string 
    {
        if (!self::isValidCode($intCode)) {
            throw new SemesterInvalidInput($intCode, 'code');
        }
        return self::codeSemester($intCode) . ' ' . self::codeYear($intCode);
    }

    /**
     * Return the year part of an integer representation of a semester
     * 
     * @example Semester::codeYear(1244) returns 2024 since it is 1900 + 124
     * @param int $intCode The semester code
     * @return int The year
     * @throws SemesterInvalidInput If the code is invalid
     */
    public static function codeYear(?int $intCode): ?int 
    { 
        if (is_null($intCode)) return null;
        if (!self::isValidCode($intCode)) {
            throw new SemesterInvalidInput($intCode, 'code');
        }
        $lastDigit = $intCode % 10;
        return ($intCode - $lastDigit)/10 + self::ZEROYEAR;
    }

    /**
     * Return the semester part of an integer representation of a semester
     * 
     * @example Semester::codeSemester(1244) returns 'Spring'
     * @param int $intCode The semester code
     * @return string The semester name (Spring, Summer, or Fall)
     * @throws SemesterInvalidInput If the code is invalid
     */
    public static function codeSemester(?int $intCode): ?string 
    {
        if (is_null($intCode)) return null;
        if (!self::isValidCode($intCode)) {
            throw new SemesterInvalidInput($intCode, 'code');
        }
        $lastDigit = $intCode % 10;
        return SemesterKey::digitToName()[$lastDigit];
    }

    /**
     * Convert the string representation of a semester into the integer representation
     * 
     * @example Semester::stringToCode('Fall 2024') returns 1244
     * @param string $semesterString The semester string to convert ('Fall 2024', etc.)
     * @return int The semester code
     * @throws SemesterInvalidInput If the string is invalid
     */
    public static function stringToCode(?string $semesterString): ?int
    {
        if (is_null($semesterString)) return null;
        
        if (!self::isValidString($semesterString)) {
            throw new SemesterInvalidInput($semesterString, 'string');
        }
        
        $year = substr($semesterString, -4);
        $start = ($year - self::ZEROYEAR)*10;
        $semester = substr($semesterString, 0, strpos($semesterString, ' '));
        $semester = ucfirst(strtolower($semester)); // Normalize to title case
        $digit = SemesterKey::nameToDigit()[$semester];

        return $start + $digit;
    }

    /**
     * Return a randomly generated instance of the Semester class
     * 
     * @example Semester:random(1990, 2000, ['Summer'])
     * 
     * @param int $minYear
     * @param int $maxYear
     * @param array $exclude
     * 
     * @return static
     */
    public static function random(int $minYear = 1900, int $maxYear = 2899, $exclude = []): static
    {
        $possibleSemesters = array_diff(SemesterKey::names(), $exclude);
        $randSemester = $possibleSemesters[array_rand($possibleSemesters)];
        $randYear = rand($minYear, $maxYear);
        
        return new static($randSemester . ' ' . $randYear);
    }


    /**
     * Return an instance representing the current semester, based on system time
     * 
     * @example Semester::now()
     * @return static
     */
    public static function now(): static
    {
        $currYear = (int) date('Y');
        $dayOfYear = (int) date('z') + 1; // date('z') is 0-based, so add 1 for 1-based day number
        $currSemester = SemesterKey::fromDayOfYear($dayOfYear)->name;

        return new static($currSemester . ' ' . $currYear);
    }

    /**
     * Return an instance representing the next semester, based on system time
     * 
     * @example Semester::next()
     * @return Semester
     */
    public static function next(): static
    {
        return self::now()->addSemesters(1);
    }

    /**
     * Return an instance representing the previous semester, based on system time
     * 
     * @example Semester::previous()
     * @return Semester
     */
    public static function previous(): static
    {
        return self::now()->addSemesters(-1);
    }

    /**
     * Add input number of semesters to the instance, can be negative
     * 
     * @example $semester->addSemesters(11) shifts the internal value forward in time by 11 semesters
     * @param int $toAdd
     * @return $this
     */
    public function addSemesters(int $toAdd): static 
    {
        $currSemesterCode = $this->intFormat % 10;
        $mod3Digit = ($currSemesterCode - 4)/2; 
        $shiftTo = $mod3Digit + $toAdd;
        
        // Use floor division to properly handle negative numbers
        // This ensures: $shiftTo = 3 * $yearsToAdd + $newSemesterCode
        $yearsToAdd = floor($shiftTo / 3);
        $newSemesterCode = $shiftTo - 3 * $yearsToAdd;
        $newSemesterCode = 2 * $newSemesterCode + 4;

        $this->intFormat += 10 * $yearsToAdd;
        $this->intFormat += $newSemesterCode - $currSemesterCode;
        $this->stringFormat = self::codeToString($this->intFormat);

        return $this;
    }

    /**
     * Add input number of years to the instance
     * 
     * @example $semester->addYears(4) adds 4 years (12 semesters) to the instance
     * @param int $toAdd
     * @return $this
     */
    public function addYears(int $toAdd): static 
    {
        $this->addSemesters(3*$toAdd);
        return $this;
    }

    /**
     * Subtract input number of semesters from the instance
     * 
     * @example $semester->subSemesters(4) subtracts 4 semesters from the instance
     * @param int $toSubtract
     * @return static
     */
    public function subSemesters(int $toSubtract): static
    {
        $this->addSemesters(-$toSubtract);
        return $this;
    }

    /**
     * Subtract input number of years from the instance
     * 
     * @example $semester->subYears(2) subtracts 2 years (4 semesters) from the instance
     * @param int $toSubtract
     * @return Semester
     */
    public function subYears(int $toSubtract): static 
    {
        $this->addYears(-$toSubtract);
        return $this;
    }

    /**
     * Compare this semester with another semester
     * 
     * @example $sem1->isBefore($sem2) returns true if sem1 occurs before sem2
     * @param Semester|int|string $other A Semester instance, semester code, or semester string
     * @return bool True if this semester occurs before the other semester
     * @throws SemesterInvalidInput If the comparison value is invalid
     */
    public function isBefore($other): bool
    {
        $otherCode = $this->normalizeToCode($other);
        return $this->intFormat < $otherCode;
    }

    /**
     * Check if this semester occurs after another semester
     * 
     * @example $sem1->isAfter($sem2) returns true if sem1 occurs after sem2
     * @param Semester|int|string $other A Semester instance, semester code, or semester string
     * @return bool True if this semester occurs after the other semester
     * @throws SemesterInvalidInput If the comparison value is invalid
     */
    public function isAfter($other): bool
    {
        $otherCode = $this->normalizeToCode($other);
        return $this->intFormat > $otherCode;
    }

    /**
     * Check if this semester is the same as another semester
     * 
     * @example $sem1->equals($sem2) returns true if sem1 and sem2 represent the same semester
     * @param Semester|int|string $other A Semester instance, semester code, or semester string
     * @return bool True if both semesters are equal
     * @throws SemesterInvalidInput If the comparison value is invalid
     */
    public function equals($other): bool
    {
        $otherCode = $this->normalizeToCode($other);
        return $this->intFormat === $otherCode;
    }

    /**
     * Get the difference between this semester and another semester
     * 
     * @example $sem1->getDifference($sem2) returns the number of semesters between them
     * Positive value means $other is in the past, negative means $other is in the future
     * @param Semester|int|string $other A Semester instance, semester code, or semester string
     * @param string $unit Either 'semester' (default) or 'year' for units of comparison
     * @return int|float The difference in semesters or years (float if unit is 'year')
     * @throws SemesterInvalidInput If the comparison value is invalid
     */
    public function getDifference($other, string $unit = 'semester')
    {
        $otherCode = $this->normalizeToCode($other);
        $semesterDiff = $this->calculateSemesterDifference($this->intFormat, $otherCode);
        
        if ($unit === 'year' || $unit === 'years') {
            return $semesterDiff / 3;
        }
        return $semesterDiff;
    }

    /**
     * Get all semesters between this semester and another (inclusive)
     * 
     * @example $sem1->getSemestersBetween($sem2) returns an array of Semester objects
     * @param Semester|int|string $other A Semester instance, semester code, or semester string
     * @return array Array of Semester instances in chronological order
     * @throws SemesterInvalidInput If the comparison value is invalid
     */
    public function getSemestersBetween($other): array
    {
        $otherCode = $this->normalizeToCode($other);
        $start = min($this->intFormat, $otherCode);
        $end = max($this->intFormat, $otherCode);
        
        $semesters = [];
        for ($code = $start; $code <= $end; $code++) {
            // Check if the last digit is a valid semester digit (4, 6, or 8)
            $lastDigit = $code % 10;
            if (in_array($lastDigit, SemesterKey::values())) {
                $semesters[] = new static($code);
            }
        }
        return $semesters;
    }

    /**
     * Convert the semester instance to an associative array
     * 
     * @return array An array with 'code', 'string', 'term', and 'year' keys
     */
    public function toArray(): array
    {
        $termAndYear = $this->getTermAndYear();
        return [
            'code' => $this->getCode(),
            'string' => $this->getString(),
            'term' => $termAndYear['term'],
            'year' => $termAndYear['year'],
        ];
    }

    /**
     * Convert the semester instance to JSON
     * 
     * @param int $flags JSON encoding flags (default: JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
     * @return string JSON representation of the semester
     */
    public function toJson(int $flags = 448): string // 448 = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    {
        return json_encode($this->toArray(), $flags);
    }

    /**
     * Helper method to normalize various input types to a semester code
     * 
     * @param Semester|int|string $value
     * @return int The semester code
     * @throws SemesterInvalidInput If the value is invalid
     */
    private function normalizeToCode($value): int
    {
        if ($value instanceof self) {
            return (int) $value->getCode();
        } elseif (is_int($value) || is_numeric($value)) {
            $code = (int) $value;
            if (!self::isValidCode($code)) {
                throw new SemesterInvalidInput($code, 'code');
            }
            return $code;
        } elseif (is_string($value)) {
            if (!self::isValidString($value)) {
                throw new SemesterInvalidInput($value, 'string');
            }
            return self::stringToCode($value);
        } else {
            throw new SemesterInvalidInput($value);
        }
    }

    /**
     * Calculate the difference in semesters between two semester codes
     * Positive result means first code is after second code
     * 
     * @param int $code1 First semester code
     * @param int $code2 Second semester code
     * @return int The difference in number of semesters
     */
    private static function calculateSemesterDifference(int $code1, int $code2): int
    {
        $year1 = ($code1 - $code1 % 10) / 10;
        $sem1 = ($code1 % 10 - 4) / 2;
        
        $year2 = ($code2 - $code2 % 10) / 10;
        $sem2 = ($code2 % 10 - 4) / 2;
        
        $yearDiff = $year1 - $year2;
        $semDiff = $sem1 - $sem2;
        
        return $yearDiff * 3 + $semDiff;
    }

}

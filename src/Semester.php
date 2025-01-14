<?php

namespace App\Helpers;
use App\Exceptions\SemesterInvalidConstructorInput;
use Illuminate\Support\Str;
use Carbon\Carbon;


class Semester {

    private string $stringFormat;
    private string $intFormat;
    const ZEROYEAR = 1900;

    /**
     * Create a new Semester instance
     * @param int|string $val
     */
    function __construct(int|string $val) 
    {    
        try {
            $this->isValidInput($val);
            $this->set($val);
        }
        catch (SemesterInvalidConstructorInput $e) {
            echo $e->errorMessage();
        }

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

    private function isValidInput(int|string $val) : bool
    {
        if (is_int($val)) {
            return self::isValidCode($val);
        }
        elseif (is_string($val)) {
            return self::isValidString($val);
        }
        else {
            throw new SemesterInvalidConstructorInput($val);
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
     * Set the interal code and string representation of the semester instance
     * Input can be either a string representation or an integer representation
     * @param int|string $val
     * @return void
     */
    public function set(int|string $val)
    {
        if (is_int($val) || is_numeric($val)) {
        
            if (!(self::isValidCode(($val)))) {
                $this->intFormat = -1;
            }
            else {
                $this->intFormat = $val;
                $this->stringFormat = self::codeToString($val);
            }
        }
        elseif (is_string($val)) {

            if (!(self::isValidString($val))) {
                /* TODO Throw an error */
                $this->intFormat = -1;
            }
            else {
                $this->stringFormat = $val;
                $this->intFormat = self::stringToCode($val);
            }
        }
        else {
            /* TODO Throw an error */
            $this->intFormat = NULL;
            $this->stringFormat = NULL;
        }
    }

    /**
     * Convert an integer representation of a semester into the spring representation
     * 
     * @example Semester::codeToString(1244) returns 'Fall 2024'
     * @param int $intCode
     * @return string
     */

    /* TODO
       Check if the input code is null, if so return a null
       Check if the input code is valid, if not throw an error
    */     
    public static function codeToString(int $intCode) : string 
    {
        if (!(self::isValidCode($intCode))) return "ERROR";
        return self::codeSemester($intCode) . ' ' . self::codeYear($intCode);
    }

    /**
     * Return the year part of an integer representation of a semester
     * @example Semester::codeYear(1244) returns 2024 since it is 1900 + 124
     * @param int $intCode
     * @return int
     */

    /* TODO
       Check if the input code is valid, if not throw an error
    */
    public static function codeYear(?int $intCode): ?int 
    { 
        if (is_null($intCode)) return null;
        $lastDigit = $intCode % 10;
        return ($intCode - $lastDigit)/10 + self::ZEROYEAR;
    }

    /**
     * Return the semester part of an integer representation of a semester
     * @example Semester::codeSemester(1244) returns 'Spring'
     * @param int $intCode
     * @return string
     */

    /* TODO
       Check if the input code is valid, if not throw an error
    */
    public static function codeSemester(?int $intCode): ?string 
    {
        if (is_null($intCode)) return null;
        $lastDigit = $intCode % 10;
        return SemesterKey::asArrayReversed()[$lastDigit];
    }

    /**
     * Convert the string representation of a semester into the integer representation
     * 
     * @example Semester::stringToCode('Fall 2024') returns 1244
     * @param string $semesterString
     * @return int
     */

     /* TODO
        Check if the input string is valid, if not throw an error
     */
    public static function stringToCode(?string $semesterString): ?int
    {
        if (is_null($semesterString)) return null;
        
        $year = substr($semesterString, -4);
        $start = ($year - self::ZEROYEAR)*10;
        $semester = Str::chopEnd($semesterString, ' ' . $year);
        $digit =  SemesterKey::asArray()[$semester];

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
        $randCode = fake()->randomElement($possibleSemesters) . ' ' . fake()->numberBetween($minYear, $maxYear);
        
        return new static($randCode);
    }


    /**
     * Return an instance representing the current semester, based on system time
     * 
     * @example Semester::now()
     * @return static
     */
    public static function now(): static
    {
        $currDate = Carbon::now();
        $currYear = $currDate->year;
        $currSemester = ($currDate->dayOfYear < 126) ? 'Spring' : ($currDate->dayOfYear < 226 ? 'Summer' : 'Fall');

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
     * @example                 $semester->addSemesters(11) shifts the internal value forward in time by 11 semesters
     * @param int $toAdd
     * @return $this
     */
    public function addSemesters(int $toAdd): static 
    {
        $currSemesterCode = $this->intFormat % 10;
        $mod3Digit = ($currSemesterCode - 4)/2; 
        $shiftTo = $mod3Digit + $toAdd;
        $newSemesterCode = $shiftTo % 3;
        $newSemesterCode += ($newSemesterCode < 0) ? 3 : 0;     // Shift to deal with the fact that for (a mod b) PHP returns the value with the same sign as a
        $newSemesterCode = 2*$newSemesterCode + 4;
        $yearsToAdd = intdiv($shiftTo, 3);                      

        $this->intFormat += 10*$yearsToAdd;
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

}

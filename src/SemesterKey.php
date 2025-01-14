<?php

namespace talbertsutah\UUtahSemester;
use Traits\EnumToArray;

/*  Enum that holds information about the scheme for converting semesters to codes
*
*   The scheme from string to integer representation is 'SEMESTER YEAR' -> XXXY, where XXX = YEAR - 1900, and SEMESTER is represented by the digit Y
*   
*   SEMESTER is one of {Spring, Summer, Fall}, which map to Spring->4, Summer->6, Fall->8
*
*   ZeroYear = 1900 stores the fact that 1900 is the minimum year
*/

// ?? TODO: Put this key directly into the Semester class? Can that be done? Is it advisable?

enum SemesterKey: int 
{
    use EnumToArray;

    case Spring = 4;
    case Summer = 6;
    case Fall = 8;

}

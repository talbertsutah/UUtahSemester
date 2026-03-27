<?php

namespace talbertsutah\UUtahSemester\Casts;

use talbertsutah\UUtahSemester\Semester;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class SemesterCast implements CastsAttributes
{
    /**
     * Convert the raw database value into a Semester value object.
     *
     * A nullable database column remains nullable in PHP.
     * Non-null values are passed directly to Semester so its native
     * validation and exceptions are preserved.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return Semester|null
     * @throws \talbertsutah\UUtahSemester\Exceptions\SemesterInvalidInput If the value is an invalid semester
     * @throws \TypeError If value type cannot be accepted by Semester
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Semester
    {
        if ($value === null) {
            return null;
        }

        return $value instanceof Semester ? $value : new Semester($value);
    }

    /**
     * Convert an incoming value to the storable semester code.
     *
     * Accepts a Semester instance or any constructor-compatible value
     * (for example, integer or string semester code). Invalid values
     * intentionally bubble up Semester's own exception types.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string|null
     * @throws \talbertsutah\UUtahSemester\Exceptions\SemesterInvalidInput If the value is an invalid semester
     * @throws \TypeError If value type cannot be accepted by Semester
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        $semester = $value instanceof Semester ? $value : new Semester($value);

        return $semester->getCode();
    }
}

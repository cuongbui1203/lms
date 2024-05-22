<?php

namespace App\Rules;

use App\Enums\StatusEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StatusesArrayRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = json_decode($value);
        if (!is_array($data)) {
            $fail($attribute . ' must be array.');
            return;
        }
        foreach ($data as $e) {
            if (!in_array($e, StatusEnum::getValues())) {
                $fail('invalid status.');
                return;
            }
        }
    }
}

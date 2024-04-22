<?php

namespace App\Rules;

use Closure;
use DB;
use Illuminate\Contracts\Validation\ValidationRule;

class UsernameRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            if (!DB::table('users')->where('username', '=', $value)->exists()) {
                $fail('The :attribute must be email or username');
            } else {
                request()->merge(['isUsername' => true]);
            }
        } else if (!DB::table('users')->where('email', '=', $value)->exists()) {
            $fail('The email or username doesn\'t exists');
        } else {
            request()->merge(['isUsername' => false]);
        }
    }
}

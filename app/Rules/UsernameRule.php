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
        $patten = '/^[w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/';
        if (!DB::table('users')->where('username', '=', $value)->exists()) {
            if (preg_match($patten, $value)) {
                $fail('The :attribute must be email or username');
            } else if (!DB::table('users')->where('email', '=', $value)->exists()) {
                $fail('The email or username doesn\'t exists');
            }
        }
    }
}

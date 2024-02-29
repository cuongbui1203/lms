<?php

namespace App\Rules;

use App\Models\User;
use Closure;
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
        $patten = "/^[w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/";
        if(!User::where('username', '=', $value)->exists()) {
            if(preg_match($patten, $value)) {
                $fail('The :attribute must be email or username');
            }else if(!User::where('email', '=', $value)->exists()) {
                $fail('The email or username doesn\'t exists');
            }
        }
    }

}

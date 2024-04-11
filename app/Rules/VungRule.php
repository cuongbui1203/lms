<?php

namespace App\Rules;

use Closure;
use DB;
use Illuminate\Contracts\Validation\ValidationRule;

class VungRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $db = DB::connection('sqlite_vn_map');
        $w = $db->table('wards')->where('code', $value)->exists();
        $d = $db->table('districts')->where('code', $value)->exists();
        $p = $db->table('provinces')->where('code', $value)->exists();

        if (!(($d || $w) || $p)) {
            $fail("The selected $attribute is invalid.");
        }
    }
}

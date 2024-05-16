<?php

namespace App\Rules;

use App\Models\Order;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderListIdRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $orderIds = Order::all(['id'])->pluck('id')->toArray();
        $array = json_decode($value);
        $diff = array_diff($array, $orderIds);
        if (count($diff) != 0) {
            $fail('Has order id invalid!');
            foreach ($diff as $v) {
                $fail('order id ' . $v . ' invalid');
            }
        }
    }
}

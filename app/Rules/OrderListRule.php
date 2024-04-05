<?php

namespace App\Rules;

use App\Models\Order;
use Illuminate\Contracts\Validation\Rule;

class OrderListRule implements Rule
{
    protected $errors = [];

    public function __construct()
    {
        //
        // dd($this);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!json_validate($value)) {
            $this->errors[] = 'must be valid json string';

            return false;
        }

        $orderIds = json_decode($value);
        if (!is_array($orderIds)) {
            $this->errors[] = 'must be array';

            return false;
        }

        $all = Order::all(['id'])->pluck('id')->toArray();
        $res = array_diff($orderIds, $all);
        if (count($res) !== 0) {
            foreach ($res as $id) {
                array_push($this->errors, [
                    $id => 'Order id invalid.',
                ]);
            }

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errors;
    }
}

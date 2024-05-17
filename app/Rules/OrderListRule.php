<?php

namespace App\Rules;

use App\Models\Order;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class OrderListRule implements Rule
{
    protected $errors = [];
    protected Collection $all;

    public function __construct()
    {
        $this->all = Order::all(['id']);
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
        collect($value)->map(function ($e, $i) {
            $attribute = 'request-no-' . ($i + 1);
            $err = $this->check((object) $e);
            if (count($err) !== 0) {
                $this->errors[$attribute] = $err;
            }
        });
        return count($this->errors) === 0;
    }

    protected function check($e)
    {
        $errors = [];
        if (!$this->all->contains('id', $e->id)) {
            $errors['id'] = 'Order id invalid.';
        }
        if (isset($e->distance)) {
            if (!is_numeric($e->distance)) {
                $errors['distance'] = 'Distance must be number';
            } else {
                if ($e->distance <= 0) {
                    $errors['distance'] = 'Distance must be greater than 0';
                }
            }
        }
        return $errors;
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

<?php

namespace App\Rules;

use App\Models\Order;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class ListOrdersLeaveRule implements Rule
{

    protected Collection $orderIds;
    protected array $errors;

    public function passes($attribute, $value)
    {
        $this->errors = [];
        $this->orderIds = Order::all(['id']);
        $data = collect($value);
        $data->each(function ($e) {
            if (!$this->orderIds->contains($e)) {
                $this->errors[] = 'order id ' . $e . ' is invalid';
            }
        });
        return count($this->errors) === 0;
    }

    public function message()
    {
        return $this->errors;
    }
}

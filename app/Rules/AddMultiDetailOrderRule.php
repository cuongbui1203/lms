<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AddMultiDetailOrderRule implements Rule
{
    private $errors = [];

    public function passes($attribute, $value)
    {
        // dd($value);
        $data = collect($value);
        $data->map(function ($e, $i) {
            $attribute = 'detail-no-' . ($i + 1);
            $errors = $this->check((object) $e);
            if (count($errors) !== 0) {
                $this->errors[$attribute] = $errors;
            }
        });

        return count($this->errors) === 0;
    }

    public function check($e)
    {
        $errors = [];
        if (!isset($e->name)) {
            $errors['name'] = 'The name field is required';
        }

        if (isset($e->mass)) {
            if (!is_int($e->mass)) {
                $errors['mass'] = 'The mass field must be int';
            }
        } else {
            $errors['mass'] = 'The mass field is required';
        }

        if (!isset($e->desc)) {
            $errors['desc'] = 'The desc field is required';
        }
        if (isset($e->img) && !filter_var($e->img, FILTER_SANITIZE_URL)) {
            $errors['img'] = 'The image link is invalid';
        }

        return $errors;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return $this->errors;
    }
}

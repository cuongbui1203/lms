<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AddMultiDetailOrderRule implements Rule
{
    private $errors = [];

    public function passes($attribute, $value)
    {
        $data = json_decode($value);

        if (!is_array($data)) {
            $this->errors[] = 'must be array';

            return false;
        }

        $data->map(function ($e, $i) {
            $attribute = 'detail-no-' . ($i + 1);
            $errors = $this->check($e);
            if (count($errors) !== 0) {
                $this->errors[$attribute] = $errors;
            }
        });

        return count($this->errors) === 0;
    }

    public function check($e)
    {
        $errors = [];
        if (!$e->name) {
            $errors['name'] = 'The name field is required';
        }

        if ($e->mass) {
            if (!is_int($e->mass)) {
                $errors['mass'] = 'The mass field must be int';
            }
        } else {
            $errors['mass'] = 'The mass field is required';
        }

        if (!$e->desc) {
            $errors['desc'] = 'The desc field is required';
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

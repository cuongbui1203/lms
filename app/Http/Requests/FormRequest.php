<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

abstract class FormRequest extends BaseFormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $this->failedValidationFormat($validator->errors()->getMessages());

        /** @var \Illuminate\Validation\ValidationException $exception */
        $exception = $validator->getException();

        throw (new $exception($validator, response()->json([
            'success' => false,
            'error' => $errors,
            'status_code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)));
    }

    /**
     * format validation message
     *
     * @param array $errors
     * @return Collection
     */
    protected function failedValidationFormat(array $errors)
    {
        $errorsFormatted = new Collection();
        foreach ($errors as $key => $value) {
            if (preg_match('/request-no-/', $key) > 0) {
                $formattedErrors = new Collection();
                foreach ($value[0] as $subKey => $subValue) {
                    $formattedErrors->push([
                        'field' => $subKey,
                        'message' => $subValue,
                    ]);
                }

                $errorsFormatted->push([$key => $formattedErrors]);
            } else {
                $errorsFormatted->push([
                    'field' => $key,
                    'message' => $value,
                ]);
            }
        }

        return $errorsFormatted;
    }
}

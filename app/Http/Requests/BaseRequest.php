<?php

namespace App\Http\Requests;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Validator $validator The validator object.
     *
     * @return void
     * @throws ValidationException If validation fails.
     */
    public function failedValidation(Validator $validator)
    {
        $statusCodeError = Response::HTTP_BAD_REQUEST;
        $responseJson = [
            'code' => $statusCodeError,
        ];
        foreach ($validator->errors()->messages() as $value => $item) {
            $responseJson['message'] = $item[0];
            break;
        }

        $response = new JsonResponse($responseJson, $statusCodeError);
        throw (new ValidationException($validator, $response))
            ->status($statusCodeError);
    }
}

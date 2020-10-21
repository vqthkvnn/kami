<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        foreach ($errors as $key => $error){
            $errors[$key] = $error[0];
        }
        throw new HttpResponseException(response()->json(['errors' => $errors
        ], JsonResponse::HTTP_BAD_REQUEST));
    }

    /**get
     * Keep only fields with rules
     * @return array
     */
    public function getData() {
        return $this->only(array_keys($this->rules()));
    }
}

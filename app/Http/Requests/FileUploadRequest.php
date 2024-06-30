<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FileUploadRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust as needed for your authorization logic
    }

    public function rules()
    {
        return [
            'file' => [
                'required',
                'file',
                'mimetypes:video/mp4,video/mpeg,video', ## Adjust allowed video MIME types
            ],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        $response = array();
        $response['status'] = "error";
        $response['code'] = 422;
        $response['message'] = $errors;
        $response['result'] = array();
        throw new HttpResponseException(response()->json($response, 422));
    }

}

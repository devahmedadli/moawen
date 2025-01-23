<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponse;

abstract class BaseFormRequest extends FormRequest
{
    use ApiResponse;

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            $this->error(
                "فشل التحقق من البيانات",
                422,
                $validator->errors()
            )
        );
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            $this->forbidden(
                'برجاء التأكد من صلاحيات الحساب',
                ['reason' => 'insufficient_permissions'],
                403,
            )
        );
    }


    /**
     * Remove empty values from the request
     * @return void
     */
    public function removeNullFields()
    {
        // map the validated array to remove empty values
        $this->replace(array_filter($this->all(), function ($value) {
            return !is_null($value) && $value !== '';
        }));
        
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BranchRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch (strtolower($this->getMethod())) {
            case 'post':
                return [
                    'name' => 'required|string|max:255',
                    'branch_for' => 'sometimes|nullable|string',
                    //'manager_id' => 'sometimes|nullable',
                    'contact_number' => 'sometimes|nullable|string|unique:branches,contact_number',
                    'contact_email' => 'sometimes|nullable|string|unique:branches,contact_email',
                    'address.address_line_1' => 'sometimes|nullable|string',
                    'address.address_line_2' => 'sometimes|nullable|string',
                    'address.city' => 'sometimes|nullable|string',
                    'address.state' => 'sometimes|nullable|string',
                    'address.country' => 'sometimes|nullable|string',
                    'address.postal_code' => 'sometimes|nullable|string',
                    'payment_method' => 'sometimes|nullable',
                    'status' => 'sometimes|boolean',
                ];
                break;
            case 'put':
            case 'patch':
                $branchId = $this->route('branch');

                return [
                    'name' => 'required|string',
                    'branch_for' => 'sometimes|nullable|string',
                    //'manager_id' => 'sometimes|nullable',
                    'contact_number' => 'sometimes|nullable|string',
                    'contact_email' => 'sometimes|nullable|string',
                    'address.address_line_1' => 'sometimes|nullable|string',
                    'address.address_line_2' => 'sometimes|nullable|string',
                    'address.city' => 'sometimes|nullable|string',
                    'address.state' => 'sometimes|nullable|string',
                    'address.country' => 'sometimes|nullable|string',
                    'address.postal_code' => 'sometimes|nullable|string',
                    'payment_method' => 'sometimes|nullable',
                    'status' => 'sometimes|boolean',
                ];
                break;

            default:
                // code...
                break;
        }
    }

    protected function failedValidation(Validator $validator)
    {
        $data = [
            'status' => false,
            'message' => $validator->errors()->first(),
            'all_message' => $validator->errors(),
        ];

        if (request()->wantsJson() || request()->is('api/*')) {
            throw new HttpResponseException(response()->json($data, 422));
        }

        throw new HttpResponseException(redirect()->back()->withInput()->with('errors', $validator->errors()));
    }
}

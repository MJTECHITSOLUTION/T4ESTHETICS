<?php

namespace Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class customerRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $normalizeToArray = function ($value) {
            if (is_array($value)) return $value;
            if (is_string($value)) {
                // Try JSON first
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
                // Fallback: comma-separated
                return array_values(array_filter(array_map('trim', explode(',', $value)), function ($v) {
                    return $v !== '';
                }));
            }
            return $value;
        };

        $this->merge([
            'langue_parlee' => $normalizeToArray($this->input('langue_parlee')),
            'motif_consultation' => $normalizeToArray($this->input('motif_consultation')),
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (strtolower($this->getMethod())) {
            case 'post':
                return [
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string',
                    'email' => 'required|string',
                    'mobile' => 'required|string',
                    'gender' => 'string',
                    // optional new fields
                    'profession' => 'nullable|string|max:255',
                    'adresse' => 'nullable|string',
                    'langue_parlee' => 'nullable|array',
                    'langue_parlee.*' => 'string',
                    'adressage' => 'nullable|string|max:255',
                    'motif_consultation' => 'nullable|array',
                    'motif_consultation.*' => 'string',
                    'origine_patient' => 'nullable|string|max:255',
                    'remarque_interne' => 'nullable|string',
                ];
                break;
            case 'put':
            case 'patch':
                return [
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string',
                    'email' => ['required', 'string', Rule::unique('users', 'email')->ignore($this->id)->whereNull('deleted_at')],
                    'mobile' => 'required|string',
                    'gender' => 'string',
                    // optional new fields
                    'profession' => 'nullable|string|max:255',
                    'adresse' => 'nullable|string',
                    'langue_parlee' => 'nullable|array',
                    'langue_parlee.*' => 'string',
                    'adressage' => 'nullable|string|max:255',
                    'motif_consultation' => 'nullable|array',
                    'motif_consultation.*' => 'string',
                    'origine_patient' => 'nullable|string|max:255',
                    'remarque_interne' => 'nullable|string',
                ];
                break;
            default:
                return [];
                break;
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}

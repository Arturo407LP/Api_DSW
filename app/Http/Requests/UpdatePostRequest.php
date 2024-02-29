<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'posts_types_id' => 'numeric',
            'fecha_publicacion' => 'after:today',
            'fecha_inicio' => 'after:today',
            'fecha_fin' => 'after:fecha_inicio',
            'activo' => 'boolean'
        ];
    }
}

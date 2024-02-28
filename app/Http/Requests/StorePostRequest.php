<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'titulo' => 'required',
            'descripcion' => 'required',
            'posts_types_id' => 'required|numeric',
            'fecha_publicacion' => 'required|after:today',
            'fecha_inicio' => 'required|after:today',
            'fecha_fin' => 'required|after:fecha_inicio',
            'activo' => 'required|boolean'
        ];
    }

}

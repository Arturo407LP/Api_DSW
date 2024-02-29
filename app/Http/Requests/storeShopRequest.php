<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeShopRequest extends FormRequest
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
            "name" => "required",
            "email" => "required",
            "password" => "required",
            "municipality_id" => "required|numeric",
            "phone" => "required|numeric",
            "nombre" => "required",
            "categories_id" => "required|numeric",
            "direccion" => "required",
            "descripcion" => "required",
            "tokens_id" => "required"
        ];
    }
}

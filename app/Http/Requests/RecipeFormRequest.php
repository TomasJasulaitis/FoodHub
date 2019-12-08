<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        return auth()->check();
    }

    /**
     *
     * TODO Not working.
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'name' => 'required',
            'description' => 'required',
            'ingredients' => 'required',
            'preparation_time' => 'required',
            'nutrients' => 'required',
            'calories' => 'required',
            'number_of_servings' => 'required',
            'image_url' => 'required',
            'directions' => 'required',
        ];
    }
}

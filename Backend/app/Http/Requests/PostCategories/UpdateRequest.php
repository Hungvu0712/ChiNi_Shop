<?php

namespace App\Http\Requests\PostCategories;

use App\Models\PostCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
        $slug = $this->route('post_category');
        $pc = PostCategory::where('slug', $slug)->first();

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('post_categories', 'name')->ignore($pc?->id),
            ],

        ];
    }
}

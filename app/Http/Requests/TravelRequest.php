<?php

namespace App\Http\Requests;

use App\Models\Travel;
use Illuminate\Foundation\Http\FormRequest;



/** 

 */

class TravelRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     * 
     * @bodyParam is_public bool required is thiss travel is public or not. Example: true
     * @bodyParam name string required The name of the travel. Example: travel-1
     * @bodyParam desctiption string required the description of the travel. Example: description of the travel 
     * @bodyParam number_of_days number required the number of days of the travel. Example:10
     */
    public function rules(): array
    {
        return [
            'is_public' => ['required', 'boolean'],
            'name' => ['required', 'unique:' . Travel::class],
            'description' => ['required'],
            'number_of_days' => ['required', 'integer'],
        ];
    }
}

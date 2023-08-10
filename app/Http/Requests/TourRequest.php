<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



/** 
 * @bodyParam name string required The name of the travel.
 * @bodyParam starting_date date required the starting date of the tour 
 * @bodyParam ending_date date required the ending date of the tour 
 * @bodyParam price number required the price of the tour 
 */
class TourRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'starting_date' => ['required', 'date'],
            'ending_date' => ['required', 'date', 'after:starting_date'],
            'price' => ['required', 'numeric'],
        ];
    }
}

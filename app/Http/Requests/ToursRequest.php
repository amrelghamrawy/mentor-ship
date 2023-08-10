<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


/**
 * @queryParam sortBy Field to sort by. Example: price
 * @queryParam sortOrder the type of order you want. Example: asc
 * @queryParam dateFrom Filter by date starting_date. Example: 2023-02-01
 * @queryParam dateTo Filter by date enging_date. Example: 2023-02-30
 * @queryParam priceFrom Filter by price. Example: 100.00 
 * @queryParam priceTo Filter by price. Example: 999.99
*/
class ToursRequest extends FormRequest
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
            'dateFrom' => 'date',
            'dateTo' => 'date',
            'priceFrom' => 'numeric',
            'priceTo' => 'numeric',
            'sortBy' => Rule::in(['price']),
            'sortOrder' => Rule::in(['asc', 'desc']),
        ];
    }

    public function bodyParameters()
    {
        return [] ; 
    }
    public function messages(): array
    {
        return [
            'sortBy' => "the 'sortBy' parameter by should be 'price'",
            'sortOrder' => "the 'sortorder' parameter by should be 'asc' or 'desc'",
        ];

    }
}

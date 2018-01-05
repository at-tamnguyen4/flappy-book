<?php

namespace App\Http\Requests\backend;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'required|min:6',
            'category'    => 'required|exists:category,name',
            'price'       => 'required|numeric',
            'unit'        => 'in:[0,1,2,3]',
            'iddonator'   => 'required',
            'description' => 'required',
            'year'        => 'date_format:"Y"',
            'author'      => 'required',
            'picture'     => 'image|mimes:png,jpg,jpeg|dimensions:min_width=100,min_height=200',
        ];
    }
}
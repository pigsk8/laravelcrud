<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required|unique:books,title|max:200'
                ];
                break;

            case 'PATCH':
            case 'PUT':
                return [
                    'title' => 'required|unique:books,title,' . $this->book->id,
                ];
                break;
            default:
                return [];
        }
    }
}

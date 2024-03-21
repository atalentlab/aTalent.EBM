<?php

namespace App\Http\Requests\Main;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsletterSubscriptionRequest extends FormRequest
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
            'email' => 'required|max:255|email|unique:newsletter_subscribers,email',
        ];
    }

    public function wantsJson()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $input = $this->all();
        $input['email'] = strtolower($input['email']);

        $this->replace($input);
    }
}

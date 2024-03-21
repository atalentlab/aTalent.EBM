<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StorePostDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->user()->hasPermissionTo('manage post data');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'like_count'       => 'nullable|integer|min:0',
             'comment_count'    => 'nullable|integer|min:0',
             'view_count'       => 'nullable|integer|min:0',
             'share_count'      => 'nullable|integer|min:0',
             'period_id' => [
                 'required',
                 'integer',
                 'exists:periods,id',
                 $this->getUniqueRule(),
             ],
         ];
    }

    /**
     * Make sure the combination of channel, period and organization is unique
     *
     * @return \Illuminate\Validation\Rules\Unique
     */
    private function getUniqueRule()
    {
        $postId = $this->route('post');

        $rule = Rule::unique('post_data', 'period_id')->where(function ($query) use ($postId) {
            return $query->where('post_id', $postId);
        });

        if ($id = $this->route('id')) {
            $rule->ignore($id);
        }

        return $rule;
    }

    public function messages()
    {
        return [
            'post_id.unique' => 'Data for this combination of post and period already exists.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'There were errors while saving the data.');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRejectReason;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StoreRejectNewUserAdminNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->user()->hasPermissionTo('manage notifications');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rejection_reason' => 'required|string|in:' . implode(',', UserRejectReason::getKeys()),
            'rejection_message' => 'required|string|max:800',
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->all();

        $this->replace($input);
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'There were errors while saving the notification.');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}

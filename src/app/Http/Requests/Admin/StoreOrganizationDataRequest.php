<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreOrganizationDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->user()->hasPermissionTo('manage organization data');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'period_id'             => 'required|integer|exists:periods,id',
             'follower_count'        => 'required|integer|min:0',
             'channel_id' => [
                 'required',
                 'integer',
                 'exists:channels,id',
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
        $organizationId = $this->route('organization');
        $periodId = $this->input('period_id');

        $rule = Rule::unique('organization_data', 'channel_id')->where(function ($query) use ($periodId, $organizationId) {
            return $query->where(function ($query) use ($periodId, $organizationId) {
                $query->where('period_id', $periodId)
                    ->where('organization_id', $organizationId);
            });
        });

        if ($id = $this->route('id')) {
            $rule->ignore($id);
        }

        return $rule;
    }

    public function messages()
    {
        return [
            'channel_id.unique' => 'Data for this combination of organization, channel and period already exists.',
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

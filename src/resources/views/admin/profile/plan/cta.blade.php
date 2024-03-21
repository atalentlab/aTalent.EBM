@if($entity->hasRole($role))
    <strong class="text-primary">{{__('admin.misc.your-current-plan')}}</strong>
    @if($role != 'Basic User')
    @if($activeMembership = $entity->getActiveMembershipByRole($role)->first())
        <div>
            {{__('admin.misc.you-have')}} <strong>{{ $activeMembership->getTimeLeftPretty() }}</strong> {{__('admin.misc.left-on-this')}} {{ $activeMembership->is_trial ? 'trial' : 'plan' }}.
        </div>
        <a href="mailto:{{ config('settings.support-email') }}?subject=Upgrade to {{ $role }} plan" class="btn mt-5 w-100{{ $isPopular ? ' btn-success' : ' btn-outline-secondary' }}">
            @if($activeMembership->is_trial)
                {{__('admin.misc.upgrade-to-full-plan')}}
            @else
                {{__('admin.misc.extend-plan')}}
            @endif
        </a>
    @endif
    @endif
@else
    @if($role != 'Basic User')
    <a href="mailto:{{ config('settings.support-email') }}?subject=Upgrade to {{ $role }} plan" class="btn w-100{{ $isPopular ? ' btn-success' : ' btn-outline-secondary' }}">{{__('admin.misc.choose-plan')}}</a>
    @endif
@endif

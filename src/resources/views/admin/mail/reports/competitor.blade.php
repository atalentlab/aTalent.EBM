@component('mail::message')
{{ __('email.reports.competitor.greeting', ['name' => $user->name]) }}

@if($competitor)
{{ __('email.reports.competitor.line-1', ['organization' =>  $organization->name, 'competitor' => $competitor->name]) }}
@else
{{ __('email.reports.competitor.line-1-alt', ['organization' =>  $organization->name]) }}

{!! __('email.reports.competitor.line-2', ['link' =>  route('admin.my-organization.index')]) !!}
@endif

@component('mail::table')
| @foreach($headers as $item ) {{ $item }} |@endforeach

| ------------:|@foreach($headers as $item) ----------------:|@endforeach

| @foreach($data['post_count'] as $cell) {{ $cell }} |@endforeach

| @foreach($data['view_count'] as $cell) {{ $cell }} |@endforeach

| @foreach($data['like_count'] as $cell) {{ $cell }} |@endforeach

| @foreach($data['comment_count'] as $cell) {{ $cell }} |@endforeach

| @foreach($data['share_count'] as $cell) {{ $cell }} |@endforeach

| @foreach($data['follower_count'] as $cell) {{ $cell }} |@endforeach
@endcomponent
@component('mail::button', ['url' => route('admin.my-organization.index')])
{{ __('email.reports.competitor.cta') }}
@endcomponent

{{ __('email.reports.competitor.salutation') }}

{{ __('email.general.signature.name') }}<br><br>

If you would like to unsubscribe from receiving further emails you can do so [here]({{ route('admin.profile.show') }}#email-preferences).
@endcomponent

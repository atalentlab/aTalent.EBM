@component('mail::message')
{{ __('email.reports.my-organization.greeting', ['name' => $user->name]) }}

{{ __('email.reports.my-organization.line-1', ['organization' =>  $organization->name]) }}

@component('mail::table')
| Metric       |@foreach($data as $item) {{ $item['period_name'] }} |@endforeach

| ------------:|@foreach($data as $item) ----------------:|@endforeach

| Posts |@foreach($data as $item) {{ number_format($item['post_count_difference']) }} |@endforeach

| Reads |@foreach($data as $item) {{ number_format($item['view_count_difference']) }} |@endforeach

| Likes |@foreach($data as $item) {{ number_format($item['like_count_difference']) }} |@endforeach

| Comments |@foreach($data as $item) {{ number_format($item['comment_count_difference']) }} |@endforeach

| Shares/Wows |@foreach($data as $item) {{ number_format($item['share_count_difference']) }} |@endforeach

| Est. Total Fan base |@foreach($data as $item) {{ number_format($item['total_follower_count']) }} |@endforeach
@endcomponent
@component('mail::button', ['url' => route('admin.my-organization.index')])
{{ __('email.reports.my-organization.cta') }}
@endcomponent

{{ __('email.reports.my-organization.salutation') }}

{{ __('email.general.signature.name') }}<br><br>

If you would like to unsubscribe from receiving further emails you can do so [here]({{ route('admin.profile.show') }}#email-preferences).
@endcomponent

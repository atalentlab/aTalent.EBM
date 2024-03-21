@component('mail::message')
# Crawler report for {{ $period->name }}
@component('mail::table')
| Channel      | Complete %       | Complete count  | Error count     |
| ------------ |:----------------:|----------------:|----------------:|
@foreach($channelsData as $data)
| {{ $data['name'] }} | {{ $data['crawled_completed_percentage'] }}% | {{ $data['success_crawled_count'] }} of {{ $data['total_count'] }} | {{ $data['error_count'] }} |
@endforeach
@endcomponent
@component('mail::button', ['url' => route('admin.crawler.index')])
View crawler dashboard
@endcomponent
@if($hasAttachment)
An excel sheet with crawler errors is attached to this email<br><br>
@endif
Regards,<br>
{{ config('app.name') }}
@endcomponent

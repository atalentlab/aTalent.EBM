<form class="card js-parsley js-form" method="POST" action="{{ route('admin.notification.update.new-user.accept', $entity->id) }}">
    @csrf

    @if($user = $entity->getNewlyRegisteredUser())
    <div class="">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <tbody>
            <tr>
                <td>{{ __('admin.crawler.times-stamp') }}</td>
                <td>{{ $entity->created_at }}</td>
            </tr>
            <tr>
                <td>{{ __('admin.misc.status') }}</td>
                <td>
                    {!! $entity->getStatusColored()  !!}
                </td>
            </tr>
            @if($entity->status == 'rejected')
                <tr>
                    <td>Rejected reason</td>
                    <td>
                        {{ \App\Enums\UserRejectReason::getValue($entity->content['rejection_reason'])['name'] }}
                    </td>
                </tr>
                <tr>
                    <td>Rejected message</td>
                    <td>
                        {{ $entity->content['rejection_message']  }}
                    </td>
                </tr>
            @endif
            <tr>
                <td>{{ __('admin.misc.name') }}</td>
                <td>
                    {{ $user->name }}
                    @can('view users')
                        <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}" class="btn btn-outline-primary btn-sm ml-2" title="Edit user" target="_blank"><i class="fe fe-edit"></i></a>
                    @endcan
                </td>
            </tr>
            <tr>
                <td>{{ __('admin.misc.email') }}</td>
                <td><a href="mailto:{{ $user->email }}" target="_blank">{{ $user->email }}</a></td>
            </tr>
            <tr>
                <td>{{ __('admin.misc.phone') }}</td>
                <td>{{ $user->phone }}</td>
            </tr>
            <tr>
                <td>{{ __('admin.misc.user-role') }}</td>
                <td>{{ $user->roles->count() ? $user->roles->implode('name', ', ') : 'None' }}</td>
            </tr>
            <tr>
                <td>{{ __('admin.misc.organization') }}</td>
                <td>
                    @if(isset($user->additional_data['organization']))
                        <div class="d-flex align-items-center">
                            <div class="flex-column">
                                <label class="mb-0 mr-2">Create new organization:</label>
                            </div>
                            <div class="flex-column flex-grow-1">
                                <input type="text" class="form-control js-new-organization" name="organization" placeholder="Enter organization name" value="{{ old('organization', $user->additional_data['organization']) }}">
                            </div>
                        </div>
                        <div class="hr-text">Or</div>
                        <div class="d-flex align-items-center">
                            <div class="flex-column">
                                <label class="mb-0 mr-2">Select existing organization:</label>
                            </div>
                            <div class="flex-column flex-grow-1">
                                @input([
                                    'type' => 'select',
                                    'name' => 'organization_id',
                                    'label' => '',
                                    'placeholder' => 'Type an organization name',
                                    'required' => false,
                                    'remote' => true,
                                    'remoteUrl' => route('admin.organizations.list'),
                                    'relation' => 'organization',
                                    'relationField' => 'name',
                                    'entity' => $user,
                                    'groupClass' => 'mb-0',
                                    'inputClass' => 'js-toggle-field',
                                    'inputAttributes' => [
                                        'data-target' => '.js-new-organization',
                                    ],
                                ])
                            </div>
                        </div>
                    @else
                        @input([
                            'type' => 'select',
                            'name' => 'organization_id',
                            'label' => '',
                            'placeholder' => 'Type an organization name',
                            'required' => false,
                            'remote' => true,
                            'remoteUrl' => route('admin.organizations.list'),
                            'relation' => 'organization',
                            'relationField' => 'name',
                            'entity' => $user,
                            'groupClass' => 'mb-0',
                        ])
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    @else
        <div class="card-body">
            User not found
        </div>
    @endif


    <div class="card-footer text-right">
        <div class="d-flex">
            <a href="{{ route('admin.notification.index') }}" class="btn btn-secondary mr-1"><i class="fe fe-arrow-left mr-2"></i> {{ __('admin.misc.back') }}</a>
            @can('manage notifications')
                <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete-modal">
                    <i class="fe fe-trash mr-2"></i> {{ __('admin.misc.delete') }} {{ __('admin.header.notification') }}
                </button>

                <div class="ml-auto">
                    @if($entity->status == 'to_review')

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject-user-modal">
                        <i class="fe fe-x mr-2"></i> Reject
                    </button>

                    <button type="submit" class="btn btn-success js-submit" data-loading='<i class="fe fe-loader mr-2"></i> Saving...'>
                        <i class="fe fe-check mr-2"></i> Accept
                    </button>
                    @endif
                </div>
            @endcan
        </div>
    </div>
</form>

<div class="modal fade" id="reject-user-modal" tabindex="-1" role="dialog" aria-labelledby="reject-user-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="js-form js-parsley" method="POST" action="{{ route('admin.notification.update.new-user.reject', $entity->id) }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Reject user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Please specify a reason for rejecting this user.</p>

                    <div class="form-group">
                        <select id="rejection_reason" name="rejection_reason" class="form-control js-toggle-content" data-target="#rejection_message" required>
                            <option value="" data-content="">Select a reason for rejection</option>
                            @foreach(\App\Enums\UserRejectReason::getContentList() as $key => $item)
                                <option value="{{ $key }}" data-content="{{ $item['message'] }}">{{ $item['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    @input([
                        'type' => 'textarea',
                        'name' => 'rejection_message',
                        'maxLength' => 800,
                        'groupClass' => 'd-none',
                        'required' => true,
                    ])

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary js-submit" data-loading='<i class="fe fe-loader mr-2"></i> Saving...'>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

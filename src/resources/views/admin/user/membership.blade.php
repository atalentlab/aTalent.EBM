<div class="card h-100">
    <div class="card-header">
        <h3 class="card-title">{{ Str::ucfirst(Str::plural($name)) }}</h3>
        <div class="card-options">
            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <h4 class="h4">Active {{ $name }}</h4>
            </div>
        </div>
        <div class="row align-items-center" id="js-active-{{ $name }}">
            <div class="col">
                @input([
                    'type' => 'select',
                    'name' => $name . '_role',
                    'items' => $membershipRoles,
                    'label' => 'Role',
                    'placeholder' => 'Select role',
                    'required' => false,
                    'relationField' => 'name',
                    'isNativeSelect' => true,
                    'value' => $membership ? $membership->role_id : null,
                ])
            </div>
            <div class="col">
                @input([
                    'type' => 'date',
                    'label' => 'Expires at',
                    'name' => $name . '_expires_at',
                    'required' => false,
                    'addTimeButtons' => true,
                    'minDate' => date('Y-m-d'),
                    'value' => $membership ? $membership->expires_at : null,
                ])
            </div>
            @if($membership)
                <div class="col col-auto">
                    <div class="form-control-plaintext">
                        <button type="button" class="btn btn-danger btn-sm js-clear" data-target="#js-active-{{ $name }}"><i class="fe fe-x"></i></button>
                    </div>
                </div>
            @endif
        </div>

        @if($inactiveMemberships->count())

            <div class="row">
                <div class="col-12">
                    <h4 class="h4 mt-3">Expired {{ Str::plural($name) }}</h4>
                </div>
            </div>

            @foreach($inactiveMemberships as $membership)
            <div class="row align-items-center border-top">
                <div class="col">
                    <div class="form-control-plaintext">{{ $membership->role->name }}</div>
                </div>
                <div class="col">
                    <div class="form-control-plaintext">{{ $membership->created_at->format('Y-m-d') }} to {{ $membership->expires_at->format('Y-m-d') }}</div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>

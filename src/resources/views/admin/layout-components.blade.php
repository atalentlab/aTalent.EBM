@extends('admin.layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">
                        Layout components
                    </h1>
                </div>

                {{-- Form example --}}
                <form class="card js-parsley js-form" method="POST" action="#">
                    <div class="card-header">
                        <h2 class="card-title">Form components</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h4>Checkbox toggle</h4>
                                <div class="form-group">
                                    <label class="custom-switch">
                                        <input type="checkbox" name="published" class="custom-switch-input" checked value="1">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Toggle checkbox</span>
                                    </label>
                                </div>

                                <h4>Radio toggle</h4>
                                <div class="form-group">
                                    <div class="custom-switches-stacked">
                                        <label class="custom-switch">
                                            <input type="radio" name="option" value="1" class="custom-switch-input" checked="">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Option 1</span>
                                        </label>
                                        <label class="custom-switch">
                                            <input type="radio" name="option" value="2" class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Option 2</span>
                                        </label>
                                        <label class="custom-switch">
                                            <input type="radio" name="option" value="3" class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Option 3</span>
                                        </label>
                                    </div>
                                </div>

                                <h4>Checkbox pills</h4>
                                <div class="form-group">
                                    <label class="form-label">Your skills</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="value" value="HTML" class="selectgroup-input" checked="">
                                            <span class="selectgroup-button">HTML</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="value" value="CSS" class="selectgroup-input">
                                            <span class="selectgroup-button">CSS</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="value" value="PHP" class="selectgroup-input">
                                            <span class="selectgroup-button">PHP</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="value" value="JavaScript" class="selectgroup-input">
                                            <span class="selectgroup-button">JavaScript</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="value" value="Ruby" class="selectgroup-input">
                                            <span class="selectgroup-button">Ruby</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="value" value="Ruby" class="selectgroup-input">
                                            <span class="selectgroup-button">Ruby</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="value" value="C++" class="selectgroup-input">
                                            <span class="selectgroup-button">C++</span>
                                        </label>
                                    </div>
                                </div>

                                <h4>Textarea</h4>
                                <div class="form-group">
                                    <label class="form-label" for="textarea">Basic text area<span class="form-required">*</span></label>
                                    <textarea rows="5" class="form-control" placeholder="Here can be your description" maxlength="5000" required id="textarea"></textarea>
                                </div>

                            </div>

                            <div class="col-6">
                                <h4>Input fields</h4>
                                <div class="form-group">
                                    <label class="form-label" for="text">Text input<span class="form-required">*</span></label>
                                    <input id="text" type="text" class="form-control" name="text" maxlength="255" required placeholder="Enter text">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="email">Email input<span class="form-required">*</span></label>
                                    <input id="email" type="email" class="form-control" name="email" maxlength="255" required placeholder="Enter email">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="number">Number input<span class="form-required">*</span></label>
                                    <input id="number" type="number" class="form-control" name="number" min="0" required placeholder="Enter number">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="normal-select">Normal select<span class="form-required">*</span></label>
                                    <select id="normal-select" class="form-control" name="normal-select">
                                        <option value="" selected>Select a user...</option>
                                        @foreach($namesList as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="single-select">Single select<span class="form-required">*</span></label>
                                    <select id="single-select" class="form-control selectize" name="single-select">
                                        <option value="" selected>Select a user...</option>
                                        @foreach($namesList as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="multi-select">Multi select<span class="form-required">*</span></label>
                                    <select id="multi-select" class="form-control selectize" name="multi-select" multiple>
                                        <option value="" selected>Select a user...</option>
                                        @foreach($namesList as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="addon-input">Input with URL addon</label>
                                    <div class="input-group">
                                          <span class="input-group-prepend" id="basic-addon">
                                            <span class="input-group-text">https://example.com/users/</span>
                                          </span>
                                        <input type="text" id="addon-input" class="form-control" aria-describedby="basic-addon">
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </form>


                {{-- DataTable example --}}
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Normal DataTable</h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                            <tr>
                                <th>Order</th>
                                <th>Name</th>
                                <th>Published</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>



            </div>
        </div>
    </div>


@endsection


@push('scripts')
    <script>
        $(function() {
            // DT
            $('.datatable').DataTable({
                processing: true,
                serverSide: false,
                pageLength: 10,
                lengthChange: false,
                order: [ [0, 'asc'] ],
                columns: [
                    { data: 'order', name: 'order' },
                    { data: 'name', name: 'name' },
                    { data: 'published', name: 'published', searchable: false }
                ],
                data: {!! json_encode( $dataTablesData ) !!}
            });
        });
    </script>
@endpush

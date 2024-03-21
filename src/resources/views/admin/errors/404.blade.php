@extends('admin.layouts.app')

@section('page-title', '404 | Not found')

@section('body')


    <div class="page">
        <div class="flex-fill d-flex align-items-center justify-content-center">

                <div class="text-center">
                    <div class="p-2 bd-highlight d-flex align-items-center"> <div class="code">404 </div>  <div class="message">
                        @if($exception) {{ __($exception->getMessage() ?: 'Not found') }} @endif
                        </div> </div>

                    @if(auth()->user())
                    <a href="/app" class="btn btn-secondary mt-5 mr-1 pr-5">
                        <i class="fe fe-arrow-left mr-2"></i>
                        Back
                    </a>
                    @else
                        <a href="/" class="btn btn-secondary mt-5 mr-1 pr-5">
                            <i class="fe fe-arrow-left mr-2"></i>
                            Back
                        </a>
                    @endif
                </div>


        </div>
    </div>

@endsection

@push('stylesheets')
    <style>

        .code {
            border-right: 2px solid;
            font-size: 26px;
            padding: 0 15px 0 15px;
            text-align: center;
        }

        .message {
            padding-left: 15px;
            font-size: 18px;
            text-align: center;
        }
    </style>

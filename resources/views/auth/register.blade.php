@extends('layouts.layout-blank')

@section('styles')
    <!-- Page -->
    <link rel="stylesheet" href="{{ mix('/vendor/css/pages/authentication.css') }}">
@endsection

@section('content')
    <div class="authentication-wrapper authentication-2 ui-bg-cover ui-bg-overlay-container px-4" style="background-image: url('/img/bg/1.jpg');">
        <div class="ui-bg-overlay bg-dark opacity-25"></div>

        <div class="authentication-inner py-5">

            <div class="card">
                <div class="p-4 px-sm-5 pt-sm-5">
                    <!-- Logo -->
                    <div class="d-flex justify-content-center align-items-center pb-2 mb-4">
                        <div class="ui-w-120">
                            <div class="w-100 position-relative pb-2">
                                @include('layouts.includes.sub.logo')
                            </div>
                        </div>
                    </div>
                    <!-- / Logo -->

                    <h5 class="text-center text-muted font-weight-normal mb-4">{{ __('Create an Account') }}</h5>

                    <!-- Form -->
                    <form>
                        <div class="form-group">
                            <label class="form-label">Your name</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Your email</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control">
                        </div>
                        <button type="button" class="btn btn-primary btn-block mt-4">Sign Up</button>
                        <div class="text-light small mt-4">
                            By clicking "Sign Up", you agree to our
                            <a href="javascript:void(0)">terms of service and privacy policy</a>.
                            We’ll occasionally send you account related emails.
                        </div>
                    </form>
                    <!-- / Form -->

                </div>
                <div class="card-footer py-3 px-4 px-sm-5">
                    <div class="text-center text-muted">
                        Already have an account? <a href="javascript:void(0)">Sign In</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

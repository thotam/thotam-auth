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

                    <h5 class="text-center text-muted font-weight-normal mb-4">Đặt lại mật khẩu</h5>

                    <!-- Form -->
                    <form role="form" id="login-register-form" method="POST" action="{{route('password.update', ['urlback' => request("urlback")]) }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $request->email) }}"  autocomplete="email" >
                            @error('email')
                                <label class="error jquery-validation-error small form-text invalid-feedback" for="email" style="display: inline-block;">{{ $message }}</label>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">Mật khẩu</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                            @error('password')
                                <label class="error jquery-validation-error small form-text invalid-feedback" for="password" style="display: inline-block;">{{ $message }}</label>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Nhập lại mật khẩu</label>
                            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="new-password">
                            @error('password_confirmation')
                                <label class="error jquery-validation-error small form-text invalid-feedback" for="password_confirmation" style="display: inline-block;">{{ $message }}</label>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-4">Xác nhận</button>

                    </form>
                    <!-- / Form -->

                </div>

                <div class="card-footer py-3 px-4 px-sm-5">
                    <div class="text-center text-muted">
                        Bạn đã nhớ lại mật khẩu? <a href="{{URL::route('login', ['urlback' => request("urlback")]) }}">Đăng nhập</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

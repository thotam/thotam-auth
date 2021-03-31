@extends('layouts.layout-blank')

@section('styles')
    <!-- Page -->
    <link rel="stylesheet" href="{{ mix('/vendor/css/pages/authentication.css') }}">
@endsection

@section('content')
    <div class="authentication-wrapper authentication-2 ui-bg-cover ui-bg-overlay-container px-4" style="background-image: url('/img/bg/1.jpg');">
        <div class="authentication-inner py-5">

            @if (session('status'))
                <div class="card mb-1">
                    <div class="p-3 border-left border-left-3 border-success">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="card mb-1">
                    <div class="p-3 border-left border-left-3 border-danger">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card">
                <!-- Form -->
                <form role="form" id="login-register-form" method="POST" action="{{ route('password.email', ['urlback' => request("urlback")]) }}">
                    @csrf
                    <div class="p-4 p-sm-5">

                        <!-- Logo -->
                        <div class="d-flex justify-content-center align-items-center pb-2 mb-4">
                            <div class="ui-w-120">
                                <div class="w-100 position-relative pb-2">
                                    @include('layouts.includes.sub.logo')
                                </div>
                            </div>
                        </div>
                        <!-- / Logo -->

                        <h5 class="text-center text-muted font-weight-normal mb-4">Lấy lại mật khẩu</h5>

                        <hr class="mt-0 mb-4">

                        <p>
                            Bạn quên mật khẩu đăng nhập, rất đơn giản chỉ cần điền email mà bạn đã đăng ký, chúng tôi sẽ hỗ trợ bạn.
                        </p>

                        <div class="form-group">
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" placeholder="Địa chỉ email của bạn">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Gửi tôi link reset mật khẩu</button>

                    </div>
                </form>
                <!-- / Form -->

                <div class="card-footer py-3 px-4 px-sm-5">
                    <div class="text-center text-muted">
                        Bạn đã nhớ lại mật khẩu? <a href="{{URL::route('login', ['urlback' => request("urlback")]) }}">Đăng nhập</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

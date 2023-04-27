@php
	header('Location: https://member.cpc1hn.com.vn/');
	exit();
@endphp
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

					<h5 class="text-center text-muted font-weight-normal mb-4">
						Đăng nhập bằng tài khoản của bạn
						<br>
						<span class="text-info mt-2 d-block">Có thể dùng tài khoản: <b class="text-danger">Sổ tay nhân viên</b></span>
					</h5>

					<!-- Form -->
					<form role="form" id="login-register-form" method="POST" action="{{ route('login_stnv', ['urlback' => request('urlback')]) }}">
						@csrf

						<div class="form-group">
							<label class="form-label" for="email">Email/Số điện thoại</label>
							<input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email-phone">
							@error('email')
								<label class="error jquery-validation-error small form-text invalid-feedback" for="email" style="display: inline-block;">{{ $message }}</label>
							@enderror
						</div>

						<div class="form-group">
							<label class="form-label d-flex justify-content-between align-items-end" for="password">
								<div>Mật khẩu</div>

								@if (Route::has('password.request'))
									<a href="{{ URL::route('password.request', ['urlback' => request('urlback')]) }}" class="d-block small">Quên mật khẩu?</a>
								@endif
							</label>
							<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">
							@error('password')
								<label class="error jquery-validation-error small form-text invalid-feedback" for="password" style="display: inline-block;">{{ $message }}</label>
							@enderror
						</div>

						<div class="d-flex justify-content-between align-items-center m-0">
							<label class="custom-control custom-checkbox m-0">
								<input type="checkbox" class="custom-control-input" name="remember" value="1" id="remember" {{ old('remember') ? 'checked' : '' }}>
								<span class="custom-control-label" for="remember">Tự động đăng nhập</span>
							</label>
							<button type="sumbit" class="btn btn-primary">Đăng nhập</button>
						</div>

					</form>
					<!-- / Form -->

				</div>
				<div class="card-footer py-3 px-4 px-sm-5">
					<div class="text-center text-muted">
						{{-- Bạn chưa có tài khoản? <a href="{{ URL::route('register', ['urlback' => request('urlback')]) }}">Đăng ký</a> --}}
						Bạn chưa có tài khoản? Tải ngay app <span class="text-danger">Sổ tay nhân viên</span> để đăng ký tài khoản
					</div>
					<div class="text-center pt-2">
						<a href="https://play.google.com/store/apps/details?id=com.bk.cpc1hn" target="_blank" rel="noopener noreferrer">
							<button type="button" class="mx-2 btn icon-btn btn-lg btn-outline-black"><span class="fa-brands fa-google-play"></span></button>
						</a>

						<a href="https://apps.apple.com/vn/app/cpc1hn/id1393555624" target="_blank" rel="noopener noreferrer">
							<button type="button" class="mx-2 btn icon-btn btn-lg btn-outline-black"><span class="fa-brands fa-app-store-ios"></span></button>
						</a>
					</div>
				</div>
			</div>

		</div>
	</div>
@endsection

<?php

namespace Thotam\ThotamAuth\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Thotam\ThotamHr\Models\HR;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Thotam\ThotamAuth\Models\iCPC1HN_Group;
use Thotam\ThotamIcpc1hnApi\Models\iCPC1HN_Account;
use Thotam\ThotamAuth\DataTables\AdminUserDataTable;
use Thotam\ThotamIcpc1hnApi\Traits\Login\LoginTrait;

class UserController extends Controller
{
	use LoginTrait;

	/**
	 * index
	 *
	 * @return void
	 */
	public function index(AdminUserDataTable $dataTable)
	{
		if (Auth::user()->hr->hasAnyPermission(["view-user", "add-user", "edit-user", "link-user", "delete-user"])) {
			return $dataTable->render('thotam-auth::auth', ['title' => 'Quản lý Tài khoản']);
		} else {
			return view('errors.dynamic', [
				'error_code' => '403',
				'error_description' => 'Không có quyền truy cập',
				'title' => 'Quản lý Tài khoản',
			]);
		}
	}

	/**
	 * select_hr
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function select_hr(Request $request)
	{
		$hrs = HR::whereNull('deleted_by');

		if ((bool)$request->search) {
			$hrs->where(function ($query) use ($request) {
				$query->where('key', 'like', "%" . $request->search . "%")
					->orWhere('hoten', 'like', "%" . $request->search . "%");
			})->select('key', 'hoten');
		}

		$response['total_count'] = $hrs->count();

		if ((bool)$request->perPage) {
			$hrs->limit($request->perPage);

			if ((bool)$request->page) {
				$hrs->offset(($request->page - 1) * $request->perPage);
			}
		}

		$response_data = [];

		foreach ($hrs->get() as $hr) {
			if ((bool)$request->mail_tag && (bool)$hr->getMail("tuyendung")) {
				$text = '[' . $hr->key . '] ' . $hr->hoten . " (" . $hr->getMail("tuyendung") . ")";
			} else {
				$text = '[' . $hr->key . '] ' . $hr->hoten;
			}

			$response_data[] = [
				"id" => $hr->key,
				"text" => $text,
			];
		}

		$response['data'] = $response_data;

		return collect($response)->toJson(JSON_PRETTY_PRINT);
	}

	/**
	 * login_stnv
	 *
	 * @return void
	 */
	public function login_stnv()
	{
		return view('thotam-auth::auth.login-stnv', ['urlback' => request("urlback")]);
	}

	/**
	 * login_stnv_action
	 *
	 * @return void
	 */
	public function login_stnv_action(Request $request)
	{
		//Validat Dữ liệu Input
		$rules = [
			'email' => 'required|string',
			'password' => ['required', 'string'],
			'remember' => 'nullable|boolean',
		];

		$messages = [];

		$attributes = [
			'email' => 'email/số điện thoại',
			'password' => 'mật khẩu',
			'remember' => 'ghi nhớ',
		];

		$validator = Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {
			return back()
				->withErrors($validator)
				->withInput();
		}

		$validated = $validator->validated();

		\Thotam\ThotamAuth\Jobs\iCPC1HN_Group_Sync_Job::dispatch();

		//Đăng nhập qua API sổ tay
		$response = $this->iCPC1HN_Login($validated['email'], $validated['password']);

		if ($response->status() != 200) {
			return back()
				->withErrors(['email' => "Đã có sự cố sảy ra, vui lòng thử lại sau"])
				->withInput();
		}

		//Xử lý nếu như đăng nhập thành công
		$json_array = $response->json();
		if ($json_array["ResCode"] == 0) {
			$data = collect(collect($json_array)->get('Data'));

			$iCPC1HN_Account = iCPC1HN_Account::updateOrCreate([
				'account' => $data->get('idEmployee'),
			], [
				'password' => $validated['password'],
				'token' => $json_array["token"],
				'json_array' => $json_array,
				'hr_key' => (bool)$data->get('employeeCode') ? $data->get('employeeCode') : null,
				'active' => true,
			]);

			if ($iCPC1HN_Account->user_id != null) {
				$User = User::find($iCPC1HN_Account->user_id);
			} else {
				$User = User::firstOrCreate([
					'phone' => $data->get('idEmployee'),
				], [
					'name' => $data->get('nameEmployee'),
					'password' => Hash::make($validated['password']),
					'active' => true,
					'hr_key' => (bool)$data->get('employeeCode') ? $data->get('employeeCode') : null,
				]);
			}

			if ($User->hr_key === null) {
				$User->update([
					'hr_key' => (bool)$data->get('employeeCode') ? $data->get('employeeCode') : null,
				]);
			}

			//Update Email
			if ($User->email === null) {
				$EmployeeInfo = $this->iCPC1HN_GetEmployeeInforByToken($json_array["token"]);

				if ($EmployeeInfo->status() == 200) {
					$EmployeeInfoJson = $EmployeeInfo->json();
					if ($EmployeeInfoJson["ResCode"] == 0) {
						$email = collect(collect(collect($EmployeeInfoJson)->get('Data'))->first())->get('Emasil');
						if (empty($email)) {
							$email = collect(collect(collect($EmployeeInfoJson)->get('Data'))->first())->get('EmailCompany');
						}
						if (!empty($email)) {
							$User->update([
								'email' => $email,
							]);
						}
					}
				}
			}

			Auth::login($User, $remember = (bool)collect($validated)->get('remember'));

			//update nhóm
			$nhom_array = array_filter(iCPC1HN_Group::whereIn('icpc1hn_group_id', explode(';', $data->get('idGroup')))->pluck('nhom_id')->toArray());
			if (count($nhom_array) > 0 && $User->hr) {
				$User->hr->thanhvien_of_nhoms()->syncWithoutDetaching($nhom_array);
			}

			//Update user_id
			if ($iCPC1HN_Account->user_id === null) {
				$iCPC1HN_Account->update([
					'user_id' => $User->id,
				]);
			}

			if ($request->wantsJson()) {
				return response()->json(['two_factor' => false]);
			} elseif (!!$request->get('urlback')) {
				return redirect($request->get('urlback'));
			} else {
				return redirect()->intended(config('fortify.home'));
			}
		}

		//Đăng nhập bằng tài khoản member
		$phone = User::select('phone')->where('email', $validated['email'])->orWhere('phone', $validated['email'])->first();

		if (empty($phone)) {
			return back()
				->withErrors(['email' => "Không tìm thấy tài khoản nào với Email/Số điện thoại này"])
				->withInput();
		}

		if (Auth::attempt(['phone' => $phone, 'password' => $validated['password']], (bool)collect($validated)->get('remember'))) {
			// Authentication passed...
			if ($request->wantsJson()) {
				return response()->json(['two_factor' => false]);
			} elseif (!!$request->get('urlback')) {
				return redirect($request->get('urlback'));
			} else {
				return redirect()->intended(config('fortify.home'));
			}
		} else {
			return back()
				->withErrors(['password' => "Đăng nhập thất bại. Mật khẩu không đúng"])
				->withInput();
		}

		//Trả lại thông tin đăng nhập thất bại
		if (Str::contains($json_array["ResMes"], 'đăng nhập') && Str::contains($json_array["ResMes"], 'mật khẩu') && Str::contains($json_array["ResMes"], 'không hợp lệ')) {
			return back()
				->withErrors(['email' => $json_array["ResMes"]])
				->withInput();
		}
	}
}

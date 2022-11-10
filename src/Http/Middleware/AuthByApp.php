<?php

namespace Thotam\ThotamAuth\Http\Middleware;

use Auth;
use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Thotam\ThotamIcpc1hnApi\Models\iCPC1HN_Account;
use Thotam\ThotamIcpc1hnApi\Traits\Login\LoginTrait;

class AuthByApp
{
    use LoginTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
        }

        //Check Phone and Token
        if (!(bool)$request->route('phone') || !(bool)$request->route('token')) {
            abort(403, 'Missing Account or Token');
        }

        //Check token
        $EmployeeInfo = $this->iCPC1HN_GetEmployeeInforByToken($request->route('token'));
        if ($EmployeeInfo->status() != 200) {
            abort(403, 'Đã sảy ra lỗi kết nối');
        }

        $EmployeeInfoJson = $EmployeeInfo->json();
        if ($EmployeeInfoJson["ResCode"] != 0) {
            abort(403, 'Đã sảy ra lỗi khi check thông tin tài khoản');
        }

        if (collect(collect(collect($EmployeeInfoJson)->get('Data'))->first())->get('EmployeeID') != $request->route('phone')) {
            abort(403, 'Số điện thoại và Token không hợp lệ');
        }

        //Lấy thông tin tài khoản
        $iCPC1HN_Account = iCPC1HN_Account::where('account', $request->route('phone'))->latest()->first();
        if (!(bool)$iCPC1HN_Account?->user_id) {
            abort(403, 'Tài khoản chưa được liên kết, vui lòng truy cập: ' . route('home') . ' để thực hiện liên kết tài khoản lần đầu');
        }

        //Login
        $User = User::find($iCPC1HN_Account->user_id);
        Auth::login($User, $remember = true);

        return $next($request);
    }
}

<?php

namespace Thotam\ThotamAuth\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->active === 0) {
            return response()->view('errors.dynamic',[
                'title' => 'Tài khoản đã bị vô hiệu hóa',
                'error_code' => '403',
                'error_description' => 'Không có quyền truy cập',
                'text_xlarge' => 'Vui lòng liên hệ phòng truyền thông để được trợ giúp',
                ]);
        } elseif (!!!Auth::user()->active) {
            return response()->view('errors.dynamic',[
                'title' => 'Tài khoản chưa được kích hoạt',
                'error_code' => '403',
                'error_description' => 'Không có quyền truy cập',
                'text_xlarge' => 'Vui lòng liên hệ phòng truyền thông để được trợ giúp',
            ]);
        } else {
            return $next($request);
        }
    }
}

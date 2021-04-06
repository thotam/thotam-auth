<?php

namespace Thotam\ThotamAuth\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Thotam\ThotamAuth\DataTables\AdminUserDataTable;

class UserController extends Controller
{
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
}

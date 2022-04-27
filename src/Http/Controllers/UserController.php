<?php

namespace Thotam\ThotamAuth\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Thotam\ThotamHr\Models\HR;
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
}

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
        $hrs = HR::where('active', true);

        if (!!$request->search) {
            $hrs->where(function($query) use ($request) {
                $query->where('key', 'like', "%" . $request->search . "%")
                      ->orWhere('hoten', 'like', "%" . $request->search . "%");
            })->select('key', 'hoten');
        } else {
            //$hrs->where('active', 999);
        }

        $response['hr_count'] = $hrs->count();

        if (!!$request->perPage) {
            $hrs->limit($request->perPage);

            if (!!$request->page) {
                return 2;
                $hrs->offset($request->page * $request->perPage);
            }
        }

        $response_hrs = [];

        foreach ($hrs->get() as $hr) {
            $response_hrs['id'] = $hr->key;
            $response_hrs['text'] = $hr->hoten;
        }

        $response['hrs'] = $response_hrs;

        return collect($response)->toJson(JSON_PRETTY_PRINT);
    }

    // $("#hr_key").select2({
    //     ajax: {
    //       url: "http://127.0.0.1:8000/admin/member/select_hr",
    //       dataType: 'json',
    //       "method": "POST",
    //       delay: 250,
    //       data: function (params) {
    //         return {
    //           search: params.term, // search term
    //           page: params.page,
    //           perPage: 20
    //         };
    //       },
    //       processResults: function (data, params) {
    //         // parse the results into the format expected by Select2
    //         // since we are using custom formatting functions we do not need to
    //         // alter the remote JSON data, except to indicate that infinite
    //         // scrolling can be used
    //         params.page = params.page || 1;

    //         return {
    //           results: data.hrs,
    //           pagination: {
    //             more: (params.page * 20) < data.hr_count
    //           }
    //         };
    //       },
    //       cache: true
    //     },
    //     placeholder: 'Search for a repository',
    //     minimumInputLength: 1
    //   });
}

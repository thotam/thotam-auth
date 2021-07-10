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


    //Livewire with select2
window.thotam_ajax_select2 = function(thotam_el, thotam_livewire_id, url, perPage, token) {
    $(thotam_el).select2({
        placeholder: $(thotam_el).attr("thotam-placeholder"),
        minimumResultsForSearch: $(thotam_el).attr("thotam-search"),
        allowClear: !!$(thotam_el).attr("thotam-allow-clear"),
        dropdownParent: $("#" + $(thotam_el).attr("id") + "_div"),
        ajax: {
            url: url,
            dataType: "json",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": token,
            },
            delay: 1000,
            data: function(params) {
                return {
                    search: params.term, // search term
                    page: params.page || 1,
                    perPage: perPage,
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;

                return {
                    results: data.hrs,
                    pagination: {
                        more: params.page * perPage < data.hr_count,
                    },
                };
            },
            cache: true,
        },
    });

    if (!!$(thotam_el).attr("multiple")) {
        $(thotam_el).on("select2:close", function(e) {
            thotam_livewire_id.set(
                $(thotam_el).attr("wire:model"),
                $(thotam_el).val()
            );
        });
    } else {
        $(thotam_el).on("change", function(e) {
            thotam_livewire_id.set(
                $(thotam_el).attr("wire:model"),
                $(thotam_el).val()
            );
        });
    }
};

}

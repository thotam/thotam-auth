<?php

namespace Thotam\ThotamAuth\DataTables;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AdminUserDataTable extends DataTable
{
    public $hr, $table_id;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->hr = Auth::user()->hr;
        $this->table_id = "admin-user-table";
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (!!request('id_filter')) {
                    $query->where('users.id', 'like', '%' . request('id_filter') . '%');
                }

                if (!!request('name_filter')) {
                    $query->where('users.name', 'like', '%' . request('name_filter') . '%');
                }

                if (!!request('email_filter')) {
                    $query->where('users.email', 'like', '%' . request('email_filter') . '%');
                }

                if (!!request('phone_filter')) {
                    $query->where('users.phone', 'like', '%' . request('phone_filter') . '%');
                }

                if (!!request('hr_key_filter')) {
                    $query->whereHas('hr', function ($query2) {
                        $query2->where('hrs.hoten', 'like', '%' . request('hr_key_filter') . '%');
                        $query2->orWhere('hrs.key', 'like', '%' . request('hr_key_filter') . '%');
                    });
                }

                if (!!request('update_hr_status_filter') && request('update_hr_status_filter') != -999) {
                    if (request('update_hr_status_filter') == 1) {
                        $query->has('update_hr');
                    } else {
                        $query->doesntHave('update_hr');
                    }
                }

                if (request('active_filter') !== NULL && request('active_filter') != -999) {
                    if (request('active_filter') == 1) {
                        $query->where('users.active', true);
                    } elseif (request('active_filter') == -1) {
                        $query->where('users.active', 0);
                    } else {
                        $query->where('users.active', NULL);
                    }
                }

                if (!!request('created_at_start_filter')) {
                    $time = Carbon::createFromFormat('Y-m-d', request('created_at_start_filter'))->startOfDay();
                    $query->where('users.created_at', ">=", $time);
                }

                if (!!request('created_at_end_filter')) {
                    $time = Carbon::createFromFormat('Y-m-d', request('created_at_end_filter'))->endOfDay();
                    $query->where('users.created_at', "<=", $time);
                }

                if (!!request('link_at_start_filter')) {
                    $time = Carbon::createFromFormat('Y-m-d', request('link_at_start_filter'))->startOfDay();
                    $query->where('users.link_at', ">=", $time);
                }

                if (!!request('link_at_end_filter')) {
                    $time = Carbon::createFromFormat('Y-m-d', request('link_at_end_filter'))->endOfDay();
                    $query->where('users.link_at', "<=", $time);
                }
            }, true)
            ->addColumn('action', function ($query) {
                $Action_Icon = "<div class='action-div icon-4 px-0 mx-1 d-flex justify-content-around text-center'>";

                if ($this->hr->can("edit-user")) {
                    $Action_Icon .= "<div class='col action-icon-w-50 action-icon' thotam-livewire-method='edit_user' thotam-model-id='$query->id'><i class='text-twitter fas fa-user-edit'></i></div>";
                }

                if ($this->hr->can("link-user")) {
                    $Action_Icon .= "<div class='col action-icon-w-50 action-icon' thotam-livewire-method='link_user' thotam-model-id='$query->id'><i class='text-success fas fa-link'></i></div>";
                }

                if ($this->hr->can("reset-password-user")) {
                    $Action_Icon .= "<div class='col action-icon-w-50 action-icon' thotam-livewire-method='reset_password' thotam-model-id='$query->id'><i class='text-linux fas fa-user-lock'></i></div>";
                }

                $Action_Icon .= "</div>";

                return $Action_Icon;
            })
            ->editColumn('created_at', function ($query) {
                return $query->created_at->format("d-m-Y H:i");
            })
            ->editColumn('updated_at', function ($query) {
                return $query->updated_at->format("d-m-Y H:i");
            })
            ->editColumn('link_at', function ($query) {
                if (!!$query->link_at) {
                    return $query->link_at->format("d-m-Y H:i");
                } else {
                    return NULL;
                }
            })
            ->editColumn('active', function ($query) {
                if ($query->active === 0) {
                    return "???? v?? hi???u h??a";
                } elseif (!!!$query->active) {
                    return "Ch??a k??ch ho???t";
                } else {
                    return "??ang ho???t ?????ng";
                }
            })
            ->addColumn('update_hr_status', function ($query) {
                if (!!$query->update_hr) {
                    return "<span class='badge badge-success'>C??</span>";
                } else {
                    return "<span class='badge badge-danger'>Kh??ng</span>";
                }
            })
            ->addColumn('nhoms', function ($query) {
                if (!!$query->hr && !!$query->hr->thanhvien_of_nhoms->count()) {
                    return $query->hr->thanhvien_of_nhoms->pluck('full_name')->implode(', ');
                } else {
                    return NULL;
                }
            })
            ->editColumn('hr.key', function ($query) {
                if (!!optional($query->hr)->key) {
                    return "[" . optional($query->hr)->key . "] " . optional($query->hr)->hoten;
                } else {
                    return NULL;
                }
            })
            ->rawColumns(['action', 'update_hr_status']);;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $query = $model->newQuery();

        if (!request()->has('order')) {
            $query->orderBy('id', 'desc');
        };

        $query->with("hr:key,hoten", 'update_hr', 'hr.thanhvien_of_nhoms:id,full_name');

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId($this->table_id)
            ->columns($this->getColumns())
            ->minifiedAjax("", NULL, [
                "name_filter" => '$("#' . $this->table_id . '-name-filter").val()',
                "email_filter" => '$("#' . $this->table_id . '-email-filter").val()',
                "phone_filter" => '$("#' . $this->table_id . '-phone-filter").val()',
                "update_hr_status_filter" => '$("#' . $this->table_id . '-update_hr_status-filter").val()',
                "hr_key_filter" => '$("#' . $this->table_id . '-hr_key-filter").val()',
                "created_at_start_filter" => '$("#' . $this->table_id . '-created_at-start-filter").val()',
                "created_at_end_filter" => '$("#' . $this->table_id . '-created_at-end-filter").val()',
                "link_at_start_filter" => '$("#' . $this->table_id . '-link_at-start-filter").val()',
                "link_at_end_filter" => '$("#' . $this->table_id . '-link_at-end-filter").val()',
                "active_filter" => '$("#' . $this->table_id . '-active-filter").val()',
                //"id_filter" => '$("#' . $this->table_id . '-id-filter").val()',
            ])
            ->dom("<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'row'<'col-sm-12 table-responsive't>><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>><'d-none'B>")
            ->buttons(
                Button::make('excel')->addClass("btn btn-success waves-effect")->text('<span class="fas fa-file-excel mx-2"></span> Export'),
                Button::make('reload')->addClass("btn btn-info waves-effect")->text('<span class="fas fa-filter mx-2"></span> Filter'),
            )
            ->parameters([
                "autoWidth" => false,
                "lengthMenu" => [
                    [10, 25, 50, -1],
                    [10, 25, 50, "T???t c???"]
                ],
                "order" => [],
                'initComplete' => 'function(settings, json) {
                            var api = this.api();

                            $(document).on("click", "#filter_submit", function(e) {
                                api.draw(false);
                                e.preventDefault();
                            });

                            window.addEventListener("dt_draw", function(e) {
                                api.draw(false);
                                e.preventDefault();
                            })

                            $("thead#' . $this->table_id . '-thead").insertAfter(api.table().header());

                            api.buttons()
                                .container()
                                .removeClass("btn-group")
                                .appendTo($("#datatable-buttons"));

                            $("#datatable-buttons").removeClass("d-none")
                        }',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->title("")
                ->footer(""),
            Column::make('id')
                ->title("ID")
                ->addClass('text-center')
                ->width(5)
                ->searchable(true)
                ->orderable(false)
                ->footer("ID"),
            //->filterView(view('thotam-laravel-datatables-filter::input', ['c_placeholder' => "ID"])->with("colum_filter_id")),
            Column::make("name")
                ->title("H??? t??n")
                ->width(200)
                ->searchable(false)
                ->orderable(false)
                ->footer("H??? t??n")
                ->filterView(view('thotam-laravel-datatables-filter::input', ['c_placeholder' => "H??? t??n"])->with("colum_filter_id")),
            Column::make("email")
                ->title("?????a ch???")
                ->width(150)
                ->searchable(true)
                ->orderable(false)
                ->footer("?????a ch???"),
            Column::make("phone")
                ->title("S??? ??i???n tho???i")
                ->width(50)
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false)
                ->footer("S??? ??i???n tho???i")
                ->filterView(view('thotam-laravel-datatables-filter::input', ['c_placeholder' => "S??T"])->with("colum_filter_id")),
            Column::make("update_hr_status")
                ->title("Y??u c???u")
                ->width(10)
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false)
                ->footer("Y??u c???u")
                ->filterView(view('thotam-laravel-datatables-filter::select-single', ['selects' => $this->getYeucausProperty(), 'c_placeholder' => "Y??u c???u"])->with("colum_filter_id")),
            Column::make("hr.key")
                ->title("Li??n k???t v???i nh??n vi??n")
                ->width(200)
                ->searchable(false)
                ->orderable(false)
                ->render("function() {
                        if (!!data) {
                            return data;
                        } else {
                            return null;
                        }
                    }")
                ->footer("Li??n k???t v???i nh??n vi??n")
                ->filterView(view('thotam-laravel-datatables-filter::input', ['c_placeholder' => "Nh??n vi??n"])->with("colum_filter_id")),
            Column::computed("nhoms")
                ->title("Nh??m")
                ->searchable(false)
                ->width(50)
                ->orderable(false)
                ->footer("Nh??m"),
            Column::computed("active")
                ->title("Tr???ng th??i")
                ->searchable(false)
                ->width(20)
                ->orderable(false)
                ->footer("Tr???ng th??i")
                ->filterView(view('thotam-laravel-datatables-filter::select-single', ['selects' => $this->getTrangThaisProperty(), 'c_placeholder' => "Tr???ng th??i"])->with("colum_filter_id")),
            Column::computed('created_at')
                ->title("Th???i gian ????ng k??")
                ->width(20)
                ->footer("Th???i gian ????ng k??")
                ->filterView(view('thotam-laravel-datatables-filter::date-range')->with("colum_filter_id")),
            Column::computed('link_at')
                ->title("Th???i gian c???p quy???n")
                ->footer("Th???i gian c???p quy???n")
                ->filterView(view('thotam-laravel-datatables-filter::date-range')->with("colum_filter_id"))
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'User_' . date('YmdHis');
    }

    public function getYeucausProperty()
    {
        return [
            "1" => "C??",
            "-1" => "Kh??ng",
        ];
    }

    public function getTrangThaisProperty()
    {
        return [
            "1" => "??ang ho???t ?????ng",
            "0" => "Ch??a k??ch ho???t",
            "-1" => "???? v?? hi???u h??a",
        ];
    }
}

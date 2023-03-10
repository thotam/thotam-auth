<?php

namespace Thotam\ThotamAuth\DataTables;

use Auth;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Thotam\ThotamAuth\Models\iCPC1HN_Group;

class iCPC1HN_Group_DataTable extends DataTable
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
		$this->table_id = "admin-nhom-icpc1hn-table";
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
				if (!!request('icpc1hn_group_id_filter')) {
					$query->where('icpc1hn_groups.icpc1hn_group_id', 'like', '%' . request('icpc1hn_group_id_filter') . '%');
				}

				if (!!request('nhom_full_name_filter')) {
					$query->whereHas('nhom', function ($query2) {
						$query2->where('nhoms.full_name', 'like', '%' . request('nhom_full_name_filter') . '%');
					});
				}

				if (!!request('nhom_status_filter') && request('nhom_status_filter') != -999) {
					if (request('nhom_status_filter') == 1) {
						$query->has('nhom');
					} else {
						$query->doesntHave('nhom');
					}
				}

				if (request('active_filter') !== NULL && request('active_filter') != -999) {
					if (request('active_filter') == 1) {
						$query->where('icpc1hn_groups.active', true);
					} elseif (request('active_filter') == -1) {
						$query->where('icpc1hn_groups.active', 0);
					} else {
						$query->where('icpc1hn_groups.active', NULL);
					}
				}

				if (!!request('created_at_start_filter')) {
					$time = Carbon::createFromFormat('Y-m-d', request('created_at_start_filter'))->startOfDay();
					$query->where('icpc1hn_groups.created_at', ">=", $time);
				}

				if (!!request('created_at_end_filter')) {
					$time = Carbon::createFromFormat('Y-m-d', request('created_at_end_filter'))->endOfDay();
					$query->where('icpc1hn_groups.created_at', "<=", $time);
				}
			}, true)
			->addColumn('action', function ($query) {
				$Action_Icon = "<div class='action-div icon-4 px-0 mx-1 d-flex justify-content-around text-center'>";

				if ($this->hr->can("edit-team")) {
					$Action_Icon .= "<div class='col action-icon-w-50 action-icon' thotam-livewire-method='sync_team' thotam-model-id='$query->id'><i class='text-indigo fas fa-edit'></i></div>";
				}

				$Action_Icon .= "</div>";

				return $Action_Icon;
			})
			->editColumn('created_at', function ($query) {
				return $query->created_at->format("d-m-Y H:i");
			})
			->editColumn('active', function ($query) {
				if ($query->active === 0) {
					return "Đã vô hiệu hóa";
				} elseif (!!!$query->active) {
					return "Chưa kích hoạt";
				} else {
					return "Đang hoạt động";
				}
			})
			->addColumn('nhom_status', function ($query) {
				if (!!$query->nhom) {
					return "<span class='badge badge-success'>Có</span>";
				} else {
					return "<span class='badge badge-danger'>Không</span>";
				}
			})
			->rawColumns(['action', 'nhom_status']);;
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \Thotam\ThotamAuth\Models\iCPC1HN_Group $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query(iCPC1HN_Group $model)
	{
		$query = $model->newQuery();

		if (!request()->has('order')) {
			$query->orderBy('icpc1hn_groups.id', 'desc');
		};

		$query->with("nhom");

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
				"icpc1hn_group_id_filter" => '$("#' . $this->table_id . '-icpc1hn_group_id-filter").val()',
				"nhom_full_name_filter" => '$("#' . $this->table_id . '-nhom_full_name-filter").val()',
				"phone_filter" => '$("#' . $this->table_id . '-phone-filter").val()',
				"nhom_status_filter" => '$("#' . $this->table_id . '-nhom_status-filter").val()',
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
					[10, 25, 50, "Tất cả"]
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
			Column::make('icpc1hn_group_id')
				->title("iCPC1HN Nhóm")
				->width(5)
				->searchable(true)
				->orderable(false)
				->footer("iCPC1HN Nhóm")
				->filterView(view('thotam-laravel-datatables-filter::input', ['c_placeholder' => "iCPC1HN Nhóm"])->with("colum_filter_id")),
			Column::make("nhom.full_name")
				->title("Nhóm Member")
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
				->footer("Nhóm Member")
				->filterView(view('thotam-laravel-datatables-filter::input', ['c_placeholder' => "Nhóm Member"])->with("colum_filter_id")),
			Column::make("nhom_status")
				->title("Đồng bộ")
				->width(10)
				->addClass('text-center')
				->searchable(false)
				->orderable(false)
				->footer("Đồng bộ")
				->filterView(view('thotam-laravel-datatables-filter::select-single', ['selects' => $this->getYeucausProperty(), 'c_placeholder' => "Đồng bộ"])->with("colum_filter_id")),
			Column::computed("active")
				->title("Trạng thái")
				->searchable(false)
				->width(20)
				->orderable(false)
				->footer("Trạng thái")
				->filterView(view('thotam-laravel-datatables-filter::select-single', ['selects' => $this->getTrangThaisProperty(), 'c_placeholder' => "Trạng thái"])->with("colum_filter_id")),
			Column::computed('created_at')
				->title("Thời gian đồng bộ")
				->footer("Thời gian đồng bộ"),
		];
	}

	/**
	 * Get filename for export.
	 *
	 * @return string
	 */
	protected function filename(): string
	{
		return 'User_' . date('YmdHis');
	}

	public function getYeucausProperty()
	{
		return [
			"1" => "Có",
			"-1" => "Không",
		];
	}

	public function getTrangThaisProperty()
	{
		return [
			"1" => "Đang hoạt động",
			"0" => "Chưa kích hoạt",
			"-1" => "Đã vô hiệu hóa",
		];
	}
}

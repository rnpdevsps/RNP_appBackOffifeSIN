<?php

namespace App\DataTables;

use App\Facades\UtilityFacades;
use App\Models\Form;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Rcm;

class FilterRcmDataTable extends DataTable
{
    public function dataTable($query)
    {
        $data = datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('user', function (Rcm $rcm) {
                $tu = '';
                if ($rcm->User) {
                    $tu = $rcm->User->name;
                }
                return $tu;
            })
            ->editColumn('status', function (FormValue $formValue) {
                if ($formValue->status == "free") {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-primary">' . __('Free') . '</span>';
                    return $out;
                } else if ($formValue->status == "pending") {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-warning">' . __('Pending') . '</span>';
                    return $out;
                } else if ($formValue->status == "successfull") {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-success">' . __('Successfull') . '</span>';
                    return $out;
                } else {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-danger">' . __('Failed') . '</span>';
                    return $out;
                }
            })

            ->editColumn('created_at', function (Rcm $rcm) {
                return UtilityFacades::date_time_format($rcm->created_at);
            })
            ->editColumn('user', function (Rcm $rcm) {
                $user = $rcm->User->name;
                return $user;
            })
            ->addColumn('action', function (Rcm $rcm) {
                return view('form-value.action', compact('rcm'));
            });

        $arr = array_merge(['status', 'action', 'user', 'type', 'created_at']);
        $data->rawColumns($arr);
        return $data;
    }

    public function query(FormValue $model, Request $request)
    {
        $usr = \Auth::user();
        $roleId = $usr->roles->first()->id;

        $userId = $usr->id;

        if ($usr->type != 'Admin') {
            if (\Auth::user()->can('access-all-submitted-form')) {
                $formValues = FormValue::select(['form_values.*', 'forms.title'])
                ->join('forms', 'forms.id', '=', 'form_values.form_id')
                ->leftJoin('users', 'users.id', 'form_values.user_id');
            } else {
                $formValues =  $model->newQuery()
                    ->select(['form_values.*', 'forms.title'])
                    ->join('forms', 'forms.id', '=', 'form_values.form_id')
                    ->where(function ($query1) use ($roleId, $userId) {
                        $query1->whereIn('form_values.form_id', function ($query) use ($roleId) {
                            $query->select('form_id')->from('assign_forms_roles')->where('role_id', $roleId);
                        })
                            ->orWhereIn('form_values.form_id', function ($query) use ($userId) {
                                $query->select('form_id')->from('assign_forms_users')->where('user_id', $userId);
                            })
                            ->OrWhere('assign_type', 'public');
                    });
                $formValues->where('form_values.user_id', $userId);
            }
        } else {
            $formValues = FormValue::select(['form_values.*', 'forms.title'])
                ->join('forms', 'forms.id', '=', 'form_values.form_id')
                ->leftJoin('users', 'users.id', 'form_values.user_id');
        }

        if ($request->start_date && $request->end_date) {
            $formValues->whereBetween('form_values.created_at', [$request->start_date, $request->end_date]);
        }
        if ($request->form) {
            $formValues->where('form_values.form_id', '=', $request->form);
        }
        if ($request->customer_status) {
            $formValues->where('form_values.customer_status', $request->customer_status);
        }
        if ($request->user_name) {
            $formValues = FormValue::select(['form_values.*', 'users.name as usr_name'])
                ->join('users', 'users.id', '=', 'form_values.user_id');
            $formValues->where('users.name', 'LIKE', '%' . $request->user_name . '%')->Where('form_values.form_id', '=', $request->form);
        }
        return $formValues;
    }

    public function labels()
    {
        $recordId = $this->form_id;
        $formValue = Form::find($recordId);
        if ($formValue->json != '') {
            $jsonData = $formValue->json;
            $jsonArray = json_decode($jsonData, true);
            $filteredData = [];
            foreach ($jsonArray as $jArray) {
                foreach ($jArray as $item) {
                    if (isset($item['show_datatable']) && $item['show_datatable'] == true) {
                        $filteredData[$item['name']] =  $item['label'];
                    }
                }
            }
            $label = $filteredData;
            return $label;
        }
    }

    public function html()
    {
        $dataTable = $this->builder()
            ->setTableId('forms-table')
            ->addIndex()
            ->columns($this->getColumns($this->labels()))
            ->ajax([
                'data' => 'function(d) {
                            var filter = $(".created_at").val();
                            var spilit = filter.split("to");
                            d.form = $("#form_id").val();
                            d.start_date = spilit[0];
                            d.end_date = spilit[1];

                            var user_filter = $("input[name=user]").val();
                            d.user_name = user_filter;
                        }'
            ])
            ->orderBy(1)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => __("_MENU_") . __('Entries Per Page'),
                "searchPlaceholder" => __('Search...'), "search" => "",
                "info" => __('Showing _START_ to _END_ of _TOTAL_ entries')

            ])->initComplete('function() {
                var table = this;
                $("body").on("click", ".add_filter", function() {
                    $("#forms-table").DataTable().draw();
                });
                $("body").on("click", ".clear_filter", function() {
                    $(".created_at").val("");
                    $("input[name=user]").val("");
                    $("#forms-table").DataTable().draw();
                });
                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                searchInput.removeClass(\'form-control form-control-sm\');
                searchInput.addClass(\'dataTable-input\');
                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
            }');

        $canExportSubmittedForm = \Auth::user()->can('export-submitted-form');

        $exportButtonConfig = [];
        if ($canExportSubmittedForm) {
            $exportButtonConfig = [
                'extend' => 'collection',
                'className' => 'w-inherit btn btn-light-secondary me-1 dropdown-toggle',
                'text' => '<i class="ti ti-download"></i> ' . __('Export'),
                "buttons" => [
                    ["extend" => "print", "text" => '<i class="fas fa-print"></i> ' . __('Print'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                    ["extend" => "csv", "text" => '<i class="fas fa-file-csv"></i> ' . __('CSV'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                    ["extend" => "excel", "text" => '<i class="fas fa-file-excel"></i> ' . __('Excel'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                    //["extend" => "pdf", "text" => '<i class="fas fa-file-pdf"></i> ' . __('PDF'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                    ["extend" => "copy", "text" => '<i class="fas fa-copy"></i> ' . __('Copy'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                ],
            ];
        }


        $buttonsConfig = [
            $exportButtonConfig,
            ['extend' => 'reset', 'className' => 'w-inherit btn btn-light-danger me-1'],
            ['extend' => 'reload', 'className' => 'w-inherit btn btn-light-warning'],
        ];


        $dataTable->parameters([
            "dom" =>  "
            <'dataTable-top row'<'dataTable-dropdown page-dropdown col-lg-2 col-sm-12'l><'dataTable-botton table-btn col-lg-6 col-sm-12'B>>
            <'dataTable-container'<'col-sm-12'tr>>
            <'dataTable-bottom row'<'col-sm-5'i><'col-sm-7'p>>
            ",
            'buttons' => $buttonsConfig,
            "drawCallback" => 'function( settings ) {
                    var tooltipTriggerList = [].slice.call(
                        document.querySelectorAll("[data-bs-toggle=tooltip]")
                      );
                      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                      });
                      var popoverTriggerList = [].slice.call(
                        document.querySelectorAll("[data-bs-toggle=popover]")
                      );
                      var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                        return new bootstrap.Popover(popoverTriggerEl);
                      });
                      var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                      var toastList = toastElList.map(function (toastEl) {
                        return new bootstrap.Toast(toastEl);
                      });
                }'
        ]);


        $dataTable->language([
            'buttons' => [
                'create' => __('Create'),
                'export' => __('Export'),
                'print' => __('Print'),
                'reset' => __('Reset'),
                'reload' => __('Reload'),
                'excel' => __('Excel'),
                'csv' => __('CSV'),
            ]
        ]);
        return $dataTable;
    }

    protected function getColumns($label)
    {
        $columns = [
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('user')->title(__('User')),
            Column::make('amount')->title(__('Amount')),
            // Other fixed columns...
        ];
        if ($label != null) {
            foreach ($label as $key => $value) {
                $columns[] = Column::make($key)->title($value)->searchable(false)->orderable(false);
            }
        }

        $columns[] = Column::make('transaction_id')->title(__('Transaction Id'));
        $columns[] = Column::make('status')->title(__('Payment Status'));
        $columns[] = Column::make('payment_type')->title(__('Payment Type'));
        $columns[] = Column::make('created_at')->title(__('Created At'));
        $columns[] = Column::computed('action')->title(__('Action'))
            ->exportable(false)
            ->printable(false)
            ->addClass('text-end');

        return $columns;
    }


    protected function filename(): string
    {
        return 'FormValues_' . date('YmdHis');
    }
}

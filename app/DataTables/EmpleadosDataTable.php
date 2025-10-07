<?php

namespace App\DataTables;

use App\Facades\UtilityFacades;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;

class EmpleadosDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return UtilityFacades::date_format($request->created_at);
            })
            ->editColumn('status', function (Empleado $data) {
                if ($data->status == 1) {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-info">' . __('Activo') . '</span>';
                    return $out;
                } else {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-warning">' . __('Inactivo') . '</span>';
                    return $out;
                }
            })
            ->addColumn('action', function (Empleado $empleado) {
                return view('empleados.action', compact('empleado'));
            })
            ->editColumn('idDepto', function (Empleado $empleado) {
                return $empleado->nombredepto;
            })
            ->editColumn('idMunicipio', function (Empleado $empleado) {
                return $empleado->nombremunicipio;
            })

            ->editColumn('rcm_id', function (Empleado $empleado) {
                return $empleado->RCM->codigo;
            })
            ->editColumn('id', function (Empleado $empleado) {
                return $empleado->Rcm->name;
            })

            ->rawColumns(['role', 'action', 'status', 'clasificacion']);
    }

    public function query(Empleado $model, Request $request)

    {

        //return $model->newQuery()->orderBy('id', 'ASC');


        $query =  $model->newQuery()->select(['empleados.*', 'deptos.nombredepto as nombredepto', 'municipios.nombremunicipio as nombremunicipio', 'users.name as username'])
            ->join('deptos', 'deptos.id', '=', 'empleados.idDepto')
            ->join('municipios', 'municipios.id', '=', 'empleados.idMunicipio')
            ->join('users', 'empleados.created_by', '=', 'users.id')
            ->orderBy('empleados.id', 'desc');

        if ($request->idDepto) {
            $query->where('empleados.idDepto', '=', $request->idDepto);
        }
        if ($request->idMunicipio) {
            $query->where('empleados.idMunicipio', '=', $request->idMunicipio);
        }

        return $query;

    }



    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
        ->setTableId('empleados-table')
        ->columns($this->getColumns())
        ->minifiedAjax()
        /*->minifiedAjax([
            'data' => 'function(d) {
                        d.idDepto = $("#idDepto").val();
                        d.idMunicipio = $("#idMunicipio").val();
                    }',
        ])  */    
        ->orderBy(1)
        ->language([
            "paginate" => [
                "next" => '<i class="ti ti-chevron-right"></i>',
                "previous" => '<i class="ti ti-chevron-left"></i>'
            ],
            'lengthMenu' => __("_MENU_") . __('Entries Per Page'),
            "searchPlaceholder" => __('Search...'),
            "search" => "",
            "info" => __('Showing _START_ to _END_ of _TOTAL_ entries')
        ])
        ->initComplete('function() {
            var table = this;
            $("body").on("click", ".add_filter", function() {
                $("#empleados-table").DataTable().draw();
            });
            $("body").on("click", ".clear_filter", function() {
                $("#idDepto").val("");
                $("#idMunicipio").val("");
                $("#empleados-table").DataTable().draw();
            });
            var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
            searchInput.removeClass(\'form-control form-control-sm\');
            searchInput.addClass(\'dataTable-input\');
            var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
        }');

    $canCreateUser = \Auth::user()->can('create-empleados');
    $canExportUser = \Auth::user()->can('export-empleados');
    $buttonsConfig = [];


    if ($canCreateUser) {
        $buttonsConfig[] = [
            'extend' => 'create',
            'className' => 'btn btn-light-primary no-corner me-1 add-empleado',
            'action' => " function (e, dt, node, config) { }",
        ];
    }
    $exportButtonConfig = [];

    if ($canExportUser) {
        /*$buttonsConfig[] = [
            'extend' => 'collection',
            'className' => 'btn btn-light-success no-corner me-1 report-empleado',
            'action' => " function (e, dt, node, config) { }",
            'text' => '<i class="fas fa-file-excel"></i> ' . __('Reporte'),
        ];*/

        /*$exportButtonConfig = [
            'extend' => 'collection',
            'className' => 'btn btn-light-secondary me-1 dropdown-toggle',
            'text' => '<i class="ti ti-download"></i> ' . __('Export'),
            'buttons' => [
                [
                    'extend' => 'print',
                    'text' => '<i class="fas fa-print"></i> ' . __('Print'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'csv',
                    'text' => '<i class="fas fa-file-csv"></i> ' . __('CSV'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'excel',
                    'text' => '<i class="fas fa-file-excel"></i> ' . __('Excel'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
            ],
        ];*/
    }


    $buttonsConfig = array_merge($buttonsConfig, [
        $exportButtonConfig,
        [
            'extend' => 'reset',
            'className' => 'btn btn-light-danger me-1',
        ],
        [
            'extend' => 'reload',
            'className' => 'btn btn-light-warning',
        ],
    ]);

    $dataTable->parameters([
        "dom" =>  "
    <'dataTable-top row'<'dataTable-dropdown page-dropdown col-lg-2 col-sm-12'l><'dataTable-botton table-btn col-lg-6 col-sm-12'B><'dataTable-search tb-search col-lg-3 col-sm-12'f>>
    <'dataTable-container'<'col-sm-12'tr>>
    <'dataTable-bottom row'<'col-sm-5'i><'col-sm-7'p>>
    ",
    'pageLength' => 25,
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

    protected function getColumns(): array
    {
        return [
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            
            Column::make('rcm_id')->title(__('RCM')),
            Column::make('id')->title(__('Nombre RCM')),
            Column::make('idDepto')->title(__('Departamento')),
            Column::make('idMunicipio')->title(__('Municipio')),
            Column::make('codigo')->title(__('Codigo')),
            Column::make('dni')->title(__('DNI')),
            Column::make('name')->title(__('Nombre')),
            Column::make('status')->title(__('Estado')),
            Column::make('created_at')->title(__('Fecha')),
            Column::computed('action')->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-end'),
        ];
    }

    protected function filename(): string
    {
        return 'Empleados_' . date('YmdHis');
    }
}

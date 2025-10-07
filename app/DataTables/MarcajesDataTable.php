<?php

namespace App\DataTables;

use App\Facades\UtilityFacades;
use App\Models\Rcm;
use App\Models\Marcaje;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;

class MarcajesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return UtilityFacades::date_format($request->created_at);
            })
            ->editColumn('hora_entrada', function ($request) {
                if ($request->hora_entrada == null) {
                    return '';
                } else {
                    return UtilityFacades::time_format($request->hora_entrada);
                }
            })
            ->editColumn('hora_salida', function ($request) {
                if ($request->hora_salida == null) {
                    return '';
                } else {
                    return UtilityFacades::time_format($request->hora_salida);
                }
            })
            ->editColumn('rcm_id', function (Marcaje $marcaje) {
                return $marcaje->Rcm->codigo;
            })
            ->editColumn('id', function (Marcaje $marcaje) {
                return $marcaje->Rcm->name;
            })

            ->rawColumns(['role']);
    }

    public function query(Marcaje $model, Request $request)
    {
        $query = $model->newQuery()
        ->with(['Empleado:id,name,dni'])  // Eager load related employee fields (only necessary fields)
        ->select('marcajes.id', 'marcajes.empleado_id', 'marcajes.rcm_id', 'marcajes.hora_entrada', 'marcajes.hora_salida', 'marcajes.created_at',  // Add specific columns needed from 'marcajes'
                'empleados.idDepto as idDepto',
                'empleados.idMunicipio as idMunicipio',
                'empleados.name as nameemp', 
                'rcms.name as namercm',
                'empleados.dni as dniemp') // Explicitly select the columns from 'empleados'
        ->join('empleados', 'empleados.id', '=', 'marcajes.empleado_id')
        ->join('rcms', 'rcms.id', '=', 'marcajes.rcm_id')
        ->orderByDesc('marcajes.created_at'); // Simplified orderBy

        if ($request->empleado) {
            $query->where(function ($q) use ($request) {
                $q->where('empleados.name', 'LIKE', '%' . $request->empleado . '%')
                  ->orWhere('empleados.dni', 'LIKE', '%' . $request->empleado . '%')
                  ->orWhere('rcms.name', 'LIKE', '%' . $request->empleado . '%');
            });
        }

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
        ->setTableId('marcajes-table')
        ->columns($this->getColumns())
        //->minifiedAjax()
        /*->minifiedAjax([
            'data' => 'function(d) {
                        d.idDepto = $("#idDepto").val();
                        d.idMunicipio = $("#idMunicipio").val();
                    }',
        ])  */    
        ->ajax([
            'data' => 'function(d) {
                var empleado_filter = $("input[name=empleado]").val();
                d.empleado = empleado_filter;
                d.idDepto = $("select[name=idDepto]").val();
                d.idMunicipio = $("select[name=idMunicipio]").val();

            }'
        ])
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
                $("#marcajes-table").DataTable().draw();
            });
            $("body").on("click", ".clear_filter", function() {
                $("input[name=empleado]").val("");
                $("select[name=idDepto]").val("");
                $("select[name=idMunicipio]").val("");
                $("#marcajes-table").DataTable().draw();
            });
            var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
            searchInput.removeClass(\'form-control form-control-sm\');
            searchInput.addClass(\'dataTable-input\');
            var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
        }');

    $canExportUser = \Auth::user()->can('export-marcajes');
    $buttonsConfig = [];


    $exportButtonConfig = [];

    if ($canExportUser) {
        $buttonsConfig[] = [
            'extend' => 'collection',
            'className' => 'btn btn-light-success no-corner me-1 report-marcajes',
            'action' => " function (e, dt, node, config) { }",
            'text' => '<i class="fas fa-file-excel"></i> ' . __('Reporte'),
        ];

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
            <'dataTable-top row'<'dataTable-dropdown page-dropdown col-lg-2 col-sm-12'l><'dataTable-botton table-btn col-lg-6 col-sm-12'B>>
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
            Column::make('dniemp')->title(__('DNI')),
            Column::make('nameemp')->title(__('Empleado')),
            Column::make('rcm_id')->title(__('Codigo RCM')),
            Column::make('id')->title(__('Nombre RCM')),
            Column::make('hora_entrada')->title(__('Entrada')),
            Column::make('hora_salida')->title(__('Salida')),
            Column::make('created_at')->title(__('Fecha')),
        ];
    }

    protected function filename(): string
    {
        return 'Marcajes_' . date('YmdHis');
    }
}

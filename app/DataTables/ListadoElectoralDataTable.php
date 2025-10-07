<?php

namespace App\DataTables;

use App\Facades\UtilityFacades;
use App\Models\PersonalVotaciones;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Storage;

class ListadoElectoralDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return UtilityFacades::date_time_format($request->created_at);
            })
            ->editColumn('status', function (PersonalVotaciones $listadoelectoral) {
                if ($listadoelectoral->status == 1) {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-info">' . __('Activo') . '</span>';
                    return $out;
                } else {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-warning">' . __('Inactivo') . '</span>';
                    return $out;
                }
            })
            
            ->addColumn('action', function (PersonalVotaciones $listadoelectoral) {
                return view('listadoelectoral.action', compact('listadoelectoral'));
            })
            

            ->editColumn('ubicacion', function (PersonalVotaciones $listadoelectoral) {
                return $listadoelectoral->ubicacion.' - '.$listadoelectoral->municipio;
            })

            

            
            ->rawColumns(['role', 'action', 'status', 'ubicacion']);
    }

    public function query(PersonalVotaciones $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'DESC');
    }



    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
        ->setTableId('listadoelectoral-table')
        ->columns($this->getColumns())
        ->minifiedAjax()
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
    var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
    searchInput.removeClass(\'form-control form-control-sm\');
    searchInput.addClass(\'dataTable-input\');
    var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
}');

    $canCreateUser = \Auth::user()->can('create-listadoelectoral');
    $canExportUser = \Auth::user()->can('export-listadoelectoral');
    $buttonsConfig = [];


    if ($canCreateUser) {
        $buttonsConfig[] = [
            'extend' => 'create',
            'className' => 'btn btn-light-primary no-corner me-1 add-listadoelectoral',
            'action' => " function (e, dt, node, config) { }",
        ];
    }
    $exportButtonConfig = [];

    if ($canExportUser) {
        $buttonsConfig[] = [
            'extend' => 'collection',
            'className' => 'btn btn-light-success no-corner me-1 import-listadoelectoral',
            'action' => " function (e, dt, node, config) { }",
            'text' => '<i class="fas fa-file-excel"></i> ' . __('Importar'),
        ];
        $exportButtonConfig = [
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
        ];
    }


    $buttonsConfig = array_merge($buttonsConfig, [
        $exportButtonConfig,
        /*[
            'extend' => 'reset',
            'className' => 'btn btn-light-danger me-1',
        ],*/
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
            Column::make('dni')->title(__('DNI')),
            Column::make('nombre')->title(__('Nombre')),
            Column::make('puesto')->title(__('Puesto ')),
            Column::make('ubicacion')->title(__('Ubicación')),
            Column::make('periodo')->title(__('Periodo')),
            Column::make('status')->title(__('Estado')),
            Column::computed('action')->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-end'),
        ];
    }

    protected function filename(): string
    {
        return 'ListadoElectoral_' . date('YmdHis');
    }
}

<?php

namespace App\DataTables;

use App\Facades\UtilityFacades;
use App\Models\Rcm;
use App\Models\Clasificacion;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RcmsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return UtilityFacades::date_format($request->created_at);
            })
            ->editColumn('status', function (Rcm $rcm) {
                if ($rcm->status == 1) {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-info">' . __('Activo') . '</span>';
                    return $out;
                } else {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-warning">' . __('Inactivo') . '</span>';
                    return $out;
                }
            })
            ->addColumn('id_clasificacion', function (Rcm $rcm) {
                $out = '<span class="p-2 px-3 badge rounded-pill bg-'.$rcm->Clasificacion->color_name.'">' . $rcm->Clasificacion->name . '</span>';
                return $out;
            })
            ->addColumn('action', function (Rcm $rcm) {
                return view('rcms.action', compact('rcm'));
            })
            ->editColumn('idDepto', function (Rcm $rcm) {
                return $rcm->nombredepto;
            })
            ->editColumn('idMunicipio', function (Rcm $rcm) {
                return $rcm->nombremunicipio;
            })
            ->editColumn('created_by', function (Rcm $rcm) {
                return $rcm->username;
            })
            ->editColumn('foto', function (Rcm $rcm) {
                if ($rcm->foto) {
                    if (Storage::url($rcm->foto)) {
                        $url = Storage::url($rcm->foto);
                        $a = '<a href="'.$url.'" data-lightbox="galeria">
                                <img src="'.$url.'" width="60" height="60" class="img-fluid" style="border:2px solid black; border-radius:6px;">
                            </a>';
                    } else {
                        $url = Storage::url('not-exists-data-images/78x78.png');
                        $a = '<a href="'.$url.'" data-lightbox="galeria">
                                <img src="'.$url.'" style="width:60px; border:2px solid black; border-radius:6px;">
                            </a>';
                    }
                } else {
                    $url = Storage::url('not-exists-data-images/78x78.png');
                    $a = '<a href="'.$url.'" data-lightbox="galeria">
                            <img src="'.$url.'" style="width:60px; border:2px solid black; border-radius:6px;">
                        </a>';
                }

                return $a;
            })

            ->rawColumns(['action', 'status', 'id_clasificacion', 'foto']);
    }

    public function query(Rcm $model, Request $request)

    {
        $query =  $model->newQuery()->select(['rcms.*', 'deptos.nombredepto as nombredepto', 'municipios.nombremunicipio as nombremunicipio', 'users.name as username'])
            ->join('deptos', 'deptos.id', '=', 'rcms.iddepto')
            ->join('municipios', 'municipios.id', '=', 'rcms.idmunicipio')
            ->join('users', 'rcms.created_by', '=', 'users.id')
            ->orderBy('rcms.id', 'desc');

        if ($request->iddepto) {
            $query->where('rcms.iddepto', '=', $request->iddepto);
        }
        if ($request->idmunicipio) {
            $query->where('rcms.idmunicipio', '=', $request->idmunicipio);
        }

        return $query;

    }



    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
        ->setTableId('rcms-table')
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
                $("#rcms-table").DataTable().draw();
            });
            $("body").on("click", ".clear_filter", function() {
                $("#idDepto").val("");
                $("#idMunicipio").val("");
                $("#rcms-table").DataTable().draw();
            });
            var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
            searchInput.removeClass(\'form-control form-control-sm\');
            searchInput.addClass(\'dataTable-input\');
            var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
        }');

    $canCreateUser = \Auth::user()->can('create-rcms');
    $canExportUser = \Auth::user()->can('export-rcms');
    $buttonsConfig = [];


    if ($canCreateUser) {
        $buttonsConfig[] = [
            'extend' => 'create',
            'className' => 'btn btn-light-primary no-corner me-1 add-rcm',
            'action' => " function (e, dt, node, config) { }",
        ];
    }
    $exportButtonConfig = [];

    if ($canExportUser) {
        $buttonsConfig[] = [
            'extend' => 'collection',
            'className' => 'btn btn-light-success no-corner me-1 report-rcm',
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
            Column::make('foto')->title(__('Foto')),
            Column::make('codigo')->title(__('Codigo')),
            Column::make('idDepto')->title(__('Departamento')),
            Column::make('idMunicipio')->title(__('Municipio')),
            Column::make('name')->title(__('Nombre')),
            Column::make('id_clasificacion')->title(__('Tipo')),
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
        return 'RCM_' . date('YmdHis');
    }
}

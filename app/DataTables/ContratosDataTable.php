<?php

namespace App\DataTables;

use App\Models\Contrato;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Facades\UtilityFacades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ContratosDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($request) {
                return UtilityFacades::date_time_format($request->created_at);
            })
            ->editColumn('status_contrato', function (Contrato $contrato) {
                if ($contrato->status_contrato == 0) {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-secondary">' . __('Borrador') . '</span>';
                    return $out;
                }
                if ($contrato->status_contrato == 1) {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-warning">' . __('Local propio') . '</span>';
                    return $out;
                }

                if ($contrato->status_contrato == 2) {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-secondary">' . __('Posible cambio') . '</span>';
                    return $out;
                }

                if ($contrato->status_contrato == 3) {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-danger">' . __('Aumento') . '</span>';
                    return $out;
                }

                if ($contrato->status_contrato == 4) {
                    $out = '<span class="p-2 px-3 badge rounded-pill bg-success">' . __('Firmado') . '</span>';
                    return $out;
                }
            })
            ->addColumn('action', function (Contrato $contratos) {
                $editView = view('contratos.action', compact('contratos'))->render();

                return $editView ;
            })
            ->rawColumns(['action','status_contrato']);
    }



    public function query(Contrato $model, Request $request) // Asegúrate de recibir el Request
    {
        // Crear la consulta base con las relaciones necesarias
        $data = $model->newQuery()->with(['createdBy', 'updatedBy', 'deletedBy']);

        // Aplicar condición para `status_contrato` si está presente en la solicitud
        if ($request->filled('status_contrato')) {
            $data->where('status_contrato', $request->get('status_contrato'));
        }

        // Aplicar condición para ver registros eliminados
        if ($request->get('view') === 'trash') {
            $data->onlyTrashed();
        }

        if (Auth::user()->type != 'Admin') {
            $data->where('created_by', Auth::user()->id);
        }


        return $data->orderBy('id', 'desc');
    }



    /*public function query(Contrato $model, Request $request)
    {

        if (Auth::user()->type == 'Admin') {

            $data = $model->newQuery()->with(['createdBy', 'updatedBy', 'deletedBy'])
            ->when(request()->filled('status_contrato'), function ($query) {
                $query->where('status_contrato', request('status_contrato'));
            });

            if ($request->query->get('view') && $request->query->get('view') == 'trash') {
                $data = $model->newQuery()->with(['createdBy', 'updatedBy', 'deletedBy'])
                ->when(request()->filled('status_contrato'), function ($query) {
                    $query->where('status_contrato', request('status_contrato'));
                })->onlyTrashed();
            }
        } else {


            $data = $model->newQuery()->with(['createdBy', 'updatedBy', 'deletedBy'])
            ->when(request()->filled('status_contrato'), function ($query) {
                $query->where('status_contrato', request('status_contrato'));
            })->where('created_by', Auth::user()->id)->orderBy('id', 'desc');


            if ($request->query->get('view') && $request->query->get('view') == 'trash') {

                $data = $model->newQuery()->with(['createdBy', 'updatedBy', 'deletedBy'])
                ->when(request()->filled('status_contrato'), function ($query) {
                    $query->where('status_contrato', request('status_contrato'));
                })
                ->where('created_by', Auth::user()->id)->orderBy('id', 'desc')
                ->onlyTrashed();

            }

        }

        return $data;
    }*/


    public function html()
    {
        $dataTable =  $this->builder()
            ->setTableId('contratosdatatable-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => __("_MENU_"). __('Entradas Por Página'),
                "searchPlaceholder" => __('Buscar...'), "search" => "",
                "info" => __('Mostrando _START_ a _END_ de _TOTAL_ entradas')

            ])
            ->initComplete('function() {
                var table = this;
                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                searchInput.removeClass(\'form-control form-control-sm\');
                searchInput.addClass(\'dataTable-input\');
                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
            }');
            $canCreateContratos = \Auth::user()->can('create-contratos');
            $buttonCreateConfig = [];


            if ($canCreateContratos) {
                $buttonCreateConfig[] = [
                    'extend' => 'create',
                    'className' => 'btn btn-light-primary no-corner me-1 add-maenotario',
                    'action' => " function (e, dt, node, config) { window.location = '" . route('contratos.create') . "'; }",
                ];
            }
            $exportButtonConfig = [];

            $exportButtonConfig = [
                'extend' => 'collection', 'className' => 'btn btn-light-secondary me-1 dropdown-toggle', 'text' => '<i class="ti ti-download"></i> '. ('Exportar'), "buttons" => [
                    ["extend" => "print", "text" => '<i class="fas fa-print"></i> '. ('Imprimir'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                    ["extend" => "csv", "text" => '<i class="fas fa-file-csv"></i> '. __('CSV'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                    ["extend" => "excel", "text" => '<i class="fas fa-file-excel"></i> '. __('Excel'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                    ["extend" =>"pdf", "text" => '<i class="fas fa-file-pdf"></i> '. __('PDF'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]], ["extend" => "copy", "text" => '<i class="fas fa-copy"></i> '. __('Copiar'), "className" => "btn btn-light text-primary dropdown-item", "exportOptions" => ["columns" => [0, 1, 3]]],
                                    ],
                                                                    ];

                            $buttonsConfig = [
                                $buttonCreateConfig,
                                $exportButtonConfig,
                                ['extend' => 'reset', 'className' => 'w-inherit btn btn-light-danger me-1'],
                                ['extend' => 'reload', 'className' => 'w-inherit btn btn-light-warning'],
                            ];

                            $dataTable->parameters([
                                "dom" =>  "
                                    <'dataTable-top row'<'dataTable-dropdown page-dropdown col-lg-2 col-sm-12'l><'dataTable-botton table-btn col-lg-6 col-sm-12'B><'dataTable-search tb-search col-lg-3 col-sm-12'f>>
                                    <'dataTable-container'<'col-sm-12'tr>>
                                    <'dataTable-bottom row'<'col-sm-5'i><'col-sm-7'p>>
                                    ",
                                'buttons' =>  $buttonsConfig,
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
                                }',
                                'columnDefs' => [
                                    [
                                        'targets' => 0,
                                        'render' => 'function(data, type, row, meta){
                                                data = \'<div><input type="checkbox" data-checkboxes="mygroup" class="form-check-input selected-checkbox dt-checkboxes" id="checkbox-\'+row.id+\'" data-id="\'+row.id+\'" autocomplete="off"></div>\';
                                                  return data;
                                               }',
                                        'checkboxes' => [
                                            'selectAllRender' => '<div><input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" name="checkbox-all" class="form-check-input dt-checkboxes-cell dt-checkboxes-select-all"></div>'
                                        ]
                                    ],
                                    [
                                        'targets' => 2,
                                        'printable' => false,
                                    ]
                                ],
                                'select' => 'multi',
                            ]);

                            $dataTable->language([
                                'buttons' => [
                                    'create' => __('Create'),
                                    'export' => __('Exportar'),
                                    'print' => __('Imprimir'),
                                    'reset' => __('Reiniciar'),
                                    'reload' => __('Recargar'),
                                    'excel' => __('Excel'),
                                    'csv' => __('CSV'),
                                    'pdf' => __('PDF'),
                                    'copy' => __('Copiar'),
                                ]
                            ]);

                            return $dataTable;
                        }

                        protected function getColumns()
                        {
                            return [
                                Column::make('id')->title('<div><input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" name="checkbox-all" class="form-check-input dt-checkboxes-cell dt-checkboxes-select-all p-2"></div>')->exportable(false)->printable(false)->searchable(false)->orderable(false),
                                Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
                                Column::make('codigo_muni')->title(__('Codigo Muni')),
                                Column::make('propietario_inmueble')->title(__('Propietario Inmueble')),
                                Column::make('created_at')->title(__('Fecha')),
                                Column::make('status_contrato')->title(__('Estado')),
                                Column::make('created_by')->title(__('Creado por'))->render('function() { return this.created_by ? this.created_by.name : ""; }'),
                                Column::computed('action')->title(__('Acción'))
                                    ->exportable(false)
                                    ->printable(false)
                                    ->width(60)
                                    ->addClass('text-end'),
                            ];
                        }

                        protected function filename(): string
                        {
                            return 'Contratos_' . date('YmdHis');
                        }
                    }

<?php

namespace App\DataTables;

use App\Models\MaePlantilla;
use Spatie\MailTemplates\Models\MailTemplate;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MaePlantillasDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function (MaePlantilla $maePlantilla) {
                $editView = view('maeplantillas.action', compact('maePlantilla'))->render();
                $deleteRoute = route('maeplantillas.destroy', $maePlantilla->id);
                $deleteView = view('maeplantillas.destroy', compact('maePlantilla', 'deleteRoute'))->render();
                return $editView . $deleteView;
            })
            ->rawColumns(['action']);
    }
    public function query(MaePlantilla $model)
    {
        return $model->newQuery()->with(['createdBy', 'updatedBy', 'deletedBy'])->when(request()->filled('status'), function ($query) {
            return $query->where('status', request('status'));
        });
    }

    public function html()
    {
        $dataTable =  $this->builder()
            ->setTableId('maeplantillasdatatable-table')
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
            $canCreateMAEPlantilla = \Auth::user()->can('create-maeplantillas');
            // $canCreateMAEPlantilla = \Auth::user()->can('create-maeplantillas');
            $buttonCreateConfig = [];


            if ($canCreateMAEPlantilla) {
                $buttonCreateConfig[] = [
                    'extend' => 'create',
                    'className' => 'btn btn-light-primary no-corner me-1 add-maenotario',
                    'action' => " function (e, dt, node, config) { window.location = '" . route('maeplantillas.create') . "'; }",
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
                                $exportButtonConfig,
                                $buttonCreateConfig,
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
                                }'
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
                                Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
                                Column::make('name')->title(__('Nombre')),
                                Column::make('content')->title(__('Contenido')),
                                Column::make('created_by')->title(__('Creado por'))->render('function() { return this.created_by ? this.created_by.name : ""; }'),
                                Column::make('updated_by')->title(__('Actualizado por'))->render('function() { return this.updated_by ? this.updated_by.name : ""; }'),
                                Column::make('deleted_by')->title(__('Eliminado por'))->render('function() { return this.deleted_by ? this.deleted_by.name : ""; }'),
                                Column::computed('action')->title(__('Acción'))
                                    ->exportable(false)
                                    ->printable(false)
                                    ->width(60)
                                    ->addClass('text-end'),
                            ];
                        }

                        protected function filename(): string
                        {
                            return 'MaePlantillas_' . date('YmdHis');
                        }
                    }

<?php

namespace App\DataTables;

use App\Models\ComparecienteTramite;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;

class ComparecientesDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($comparecienteTramite) {
                return $comparecienteTramite->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function (ComparecienteTramite $comparecienteTramite) {
                $editView = view('comparecientes.action', compact('comparecienteTramite'))->render();
                $deleteRoute = route('comparecientes.destroy', $comparecienteTramite->id);
                $deleteView = view('comparecientes.destroy', compact('comparecienteTramite', 'deleteRoute'))->render();
                return $editView . $deleteView;
            })
            ->rawColumns(['action']);
    }

    public function query(ComparecienteTramite $model, Request $request)
    {
        return $model->newQuery()->orderBy('id', 'ASC');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('comparecientesdatatable-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => __("Entries Per Page"),
                "searchPlaceholder" => __('Search...'),
                "search" => "",
                "info" => __('Showing _START_ to _END_ of _TOTAL_ entries')
            ])
            ->parameters([
                "dom" =>  "
                    <'dataTable-top row'<'dataTable-dropdown page-dropdown col-lg-2 col-sm-12'l><'dataTable-botton table-btn col-lg-6 col-sm-12'B><'dataTable-search tb-search col-lg-3 col-sm-12'f>>
                    <'dataTable-container'<'col-sm-12'tr>>
                    <'dataTable-bottom row'<'col-sm-5'i><'col-sm-7'p>>
                ",
                'buttons' => [
                    [
                        'extend' => 'create',
                    'className' => 'btn btn-light-primary no-corner me-1 add-maenotario',
                    'action' => " function (e, dt, node, config) { window.location = '" . route('comparecientes.create') . "'; }",
                    ],
                    [
                        'extend' => 'reset',
                        'className' => 'btn btn-light-danger me-1',
                    ],
                    [
                        'extend' => 'reload',
                        'className' => 'btn btn-light-warning',
                    ],
                ],
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
    }

    protected function getColumns()
    {
        return [
            Column::make('id')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('dni')->title(__('DNI')),
            Column::make('name')->title(__('Nombre')),
            Column::make('estado_autorizacion')->title(__('Estado de Autorización')),
            Column::make('created_at')->title(__('Creado el')),
            Column::make('updated_at')->title(__('Actualizado el')),
            Column::computed('action')->title(__('Acción'))
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-end'),
        ];
    }

    protected function filename(): string
    {
        return 'ComparecienteTramite_' . date('YmdHis');
    }
}

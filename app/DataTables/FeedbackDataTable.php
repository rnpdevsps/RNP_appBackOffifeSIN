<?php

namespace App\DataTables;

use App\Facades\UtilityFacades;
use App\Models\Feedback;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class FeedbackDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function (Feedback $feedback) {
                return view('feedback.action', compact('feedback'));
            })->editColumn('description', function (Feedback $feedback) {
                $maxLength = 50;
                $description = $feedback->description;

                if (strlen($description) > $maxLength) {
                    $truncated = substr($description, 0, $maxLength) . '...';
                    return $truncated;
                }

                return $description;
            })->editColumn('created_at', function (Feedback $feedback) {
                return UtilityFacades::date_time_format($feedback->created_at);
            })->editColumn('rating', function (Feedback $row) {
                $starrating = '<div class="text-left">';
                for ($i = 1; $i <= 5; $i++) {
                    if ($row->rating < $i) {
                        if (is_float($row->rating) && (round($row->rating) == $i)) {
                            $starrating .= '<i class="text-warning fas fa-star-half-alt"></i>';
                        } else {
                            $starrating .= '<i class="fas fa-star"></i>';
                        }
                    } else {
                        $starrating .= '<i class="text-warning fas fa-star"></i>';
                    }
                }
                $starrating .= '<br><span class="theme-text-color">(' . number_format($row->rating, 1) . ')</span></div>';
                return $starrating;
            })

            ->rawColumns(['action', 'description', 'created_at', 'rating']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Feedback $model): QueryBuilder
    {
        return $model->newQuery()->where('form_id', $this->form_id);
    }

    /**
     * Optional method if you want to use the html builder.
     */

    public function html()
    {
        $dataTable = $this->builder()
            ->setTableId('feedback-table')
            ->addIndex()
            ->columns($this->getColumns())
            ->ajax([
                'url' => route('get.feedback', $this->form_id),
                'type' => 'GET',
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

            ])->initComplete('function() {
                var table = this;
                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                searchInput.removeClass(\'form-control form-control-sm\');
                searchInput.addClass(\'dataTable-input\');
                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
            }');

        $canExportSubmittedFeedback = Auth::user()->can('export-feedback');

        $exportButtonConfig = [];
        if ($canExportSubmittedFeedback) {
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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('name'),
            Column::make('description'),
            Column::make('rating')->title(__('Star Rating')),
            Column::make('created_at'),

            Column::computed('action')->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(300)
                ->addClass('text-end'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Feedback_' . date('YmdHis');
    }
}

<?php

namespace App\DataTables;

use App\Models\Task;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TaskDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', 'console.tasks.action')
            ->editColumn('employee_id', fn($data) => $data->employee->user->name)
            ->editColumn('updated_at', fn($data) => $data->updated_at->format('d-m-Y H:i'))
            ->editColumn('description', function ($data) {
                $maxLength = 100;
                if (strlen($data->description) > $maxLength) {
                    return substr($data->description, 0, $maxLength) . '...';
                }
                return $data->description;
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Task $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        // Konfigurasi DOM untuk DataTables
        $dom = '<"row mx-1"' .
            '<"col-sm-12 col-md-3 mt-5 mt-md-0" l>' .
            '<"col-sm-12 col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center flex-wrap me-1"<"me-4"f>B>>' .
            '>t' .
            '<"row mx-2"' .
            '<"col-sm-12 col-md-6"i>' .
            '<"col-sm-12 col-md-6"p>' .
            '>';

        // Konfigurasi bahasa untuk DataTables
        $language = [
            'sLengthMenu' => 'Show _MENU_',
            'search' => '',
            'searchPlaceholder' => 'Search Tasks',
            'paginate' => [
                'next' => '<i class="ri-arrow-right-s-line"></i>',
                'previous' => '<i class="ri-arrow-left-s-line"></i>'
            ]
        ];

        // Konfigurasi tombol
        $buttons = [
            [
                'text' => '<i class="ri-add-line me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Add Task</span>',
                'className' => 'add-new btn btn-primary mb-5 mb-md-0 me-3 waves-effect waves-light',
                'init' => 'function (api, node, config) {
                    $(node).removeClass("btn-secondary");
                }',
                'action' => 'function (e, dt, node, config) {
                    window.location = "' . route('tasks.create') . '";
                }'
            ],
            [
                'text' => '<i class="ri-refresh-line me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Reload</span>',
                'className' => 'btn btn-secondary mb-5 mb-md-0 me-3 waves-effect waves-light',
                'action' => 'function (e, dt, node, config) {
                    dt.ajax.reload();
                    $("#tasks-table_filter input").val("").keyup();
                }'
            ]
        ];

        $columnExport = [0, 1, 2, 3];
        $buttons[] = [
            [
                'extend' => 'collection',
                'text' => '<i class="ri-upload-2-line ri-16px me-2"></i><span class="d-none d-sm-inline-block">Export</span>',
                'buttons' => [
                    [
                        'extend' => 'copy',
                        'exportOptions' => [
                            'columns' => $columnExport
                        ],
                    ],
                    [
                        'extend' => 'excel',
                        'exportOptions' => [
                            'columns' => $columnExport
                        ],
                    ],
                    [
                        'extend' => 'csv',
                        'exportOptions' => [
                            'columns' => $columnExport
                        ],
                    ],
                    [
                        'extend' => 'pdf',
                        'exportOptions' => [
                            'columns' => $columnExport
                        ],
                    ],
                    [
                        'extend' => 'print',
                        'exportOptions' => [
                            'columns' => $columnExport,
                        ],
                    ]
                ],
                'className' => 'btn btn-secondary buttons-collection dropdown-toggle btn-outline-secondary waves-effect waves-light',
                'init' => 'function (api, node, config) {
                    $(node).removeClass("btn-secondary");
                }',
            ],
        ];

        return $this->builder()
            ->setTableId('tasks-table')
            ->columns($this->getColumns())
            ->parameters([
                'order' => [[0, 'desc']], // Urutan default
                'dom' => $dom, // Struktur DOM
                'language' => $language, // Bahasa
                'buttons' => $buttons, // Tombol
                'responsive' => true, // Responsif
                'autoWidth' => false, // AutoWidth
            ])
            ->ajax([
                'url'  => route('tasks.index'),
                'type' => 'GET',
                'data' => "function(d){
                 d.employee_id = $('#employee_id_filter').val();
                }",
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->orderable(false)->searchable(false),
            Column::make('employee_id')->title('Employee Name'),
            Column::make('type')->title('Type'),
            Column::make('title')->title('Title'),
            Column::make('description')->title('Description'),
            Column::make('date')->title('date'),
            Column::make('longlat')->title('Longitude & Latitude'),
            Column::make('updated_at')->title('Last Updated')
                ->searchable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->title('Action'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Tasks_' . date('YmdHis');
    }
}

<?php

namespace App\DataTables;

use App\Enums\TaskReportStatus;
use App\Models\TaskReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TaskReportDataTable extends DataTable
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
            ->addColumn('action', 'console.task-reports.action')
            ->editColumn('task_id', fn($data) => $data->task->title)
            ->editColumn('status', function (TaskReport $taskReport) {
                $status = match ($taskReport->status) {
                    TaskReportStatus::Pending => 'warning',
                    TaskReportStatus::Approved => 'success',
                    TaskReportStatus::Rejected => 'danger',
                    TaskReportStatus::Revision => 'danger',
                    default => 'secondary',
                };

                return '<span class="badge rounded-pill bg-label-' . $status . '">' . ucfirst($taskReport->status) . '</span>';
            })
            ->editColumn('proof_assignment', function (TaskReport $taskReport) {
                return $taskReport->proof_assignment ? '<a download href="' . asset($taskReport->proof_assignment) . '" target="_blank" class="text-decoration-none badge rounded-pill bg-label-primary">Download</a>' : '-';
            })
            ->editColumn('updated_at', fn($data) => Carbon::parse($data->updated_at)->format('d M Y'))
            ->editColumn('pic', fn($data) => $data->task->employee->user->name)
            ->rawColumns(['action', 'status', 'proof_assignment']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TaskReport $model): QueryBuilder
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
            'searchPlaceholder' => 'Search Task Reports',
            'paginate' => [
                'next' => '<i class="ri-arrow-right-s-line"></i>',
                'previous' => '<i class="ri-arrow-left-s-line"></i>'
            ]
        ];

        // Konfigurasi tombol
        $buttons = [
            [
                'text' => '<i class="ri-add-line me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Add Task Report</span>',
                'className' => 'add-new btn btn-primary mb-5 mb-md-0 me-3 waves-effect waves-light',
                'init' => 'function (api, node, config) {
                    $(node).removeClass("btn-secondary");
                }',
                'action' => 'function (e, dt, node, config) {
                    window.location = "' . route('task-reports.create') . '";
                }'
            ],
            [
                'text' => '<i class="ri-refresh-line me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Reload</span>',
                'className' => 'btn btn-secondary mb-5 mb-md-0 me-3 waves-effect waves-light',
                'action' => 'function (e, dt, node, config) {
                    dt.ajax.reload();
                    $("#task-reports-table_filter input").val("").keyup();
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
            ->setTableId('task-reports-table')
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
                'url'  => route('task-reports.index'),
                'type' => 'GET',
                'data' => "function(d){
                    d.employee_id = $('#employee_id_filter').val();
                    d.status = $('#status_filter').val();
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
            Column::make('task_id')->title('Task'),
            Column::make('pic')->title('PIC'),
            Column::make('note')->title('Note'),
            Column::make('proof_assignment')->title('Proof of Assignment'),
            Column::make('status')->title('Status'),
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
        return 'Task-Reports_' . date('YmdHis');
    }
}

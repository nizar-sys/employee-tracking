<?php

namespace App\DataTables;

use App\Enums\LeaveStatus;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LeaveDataTable extends DataTable
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
            ->addColumn('action', 'console.leaves.action')
            ->editColumn('reason', function (Leave $leave) {
                return str($leave->reason)->limit(50);
            })
            ->editColumn('status', function (Leave $leave) {
                $status = match ($leave->status) {
                    LeaveStatus::Pending => 'warning',
                    LeaveStatus::Approved => 'success',
                    LeaveStatus::Rejected => 'danger',
                    default => 'secondary',
                };

                return '<span class="badge rounded-pill bg-label-' . $status . '">' . ucfirst($leave->status) . '</span>';
            })
            ->addColumn('date', function (Leave $leave) {
                return Carbon::parse($leave->start_date)->format('d M Y') . ' - ' . Carbon::parse($leave->end_date)->format('d M Y');
            })
            ->editColumn('document', function (Leave $leave) {
                return $leave->document ? '<a download href="' . asset($leave->document) . '" target="_blank" class="text-decoration-none badge rounded-pill bg-label-primary">Download</a>' : '-';
            })
            ->editColumn('leave_type', function (Leave $leave) {
                return ucfirst($leave->leave_type);
            })
            ->rawColumns(['action', 'status', 'document']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Leave $model): QueryBuilder
    {
        return $model->newQuery()->with(['employee:id,user_id,number', 'employee.user:id,name']);
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
            'searchPlaceholder' => 'Search Leaves',
            'paginate' => [
                'next' => '<i class="ri-arrow-right-s-line"></i>',
                'previous' => '<i class="ri-arrow-left-s-line"></i>'
            ]
        ];

        // Konfigurasi tombol
        $buttons = [
            [
                'text' => '<i class="ri-add-line me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">New Leave</span>',
                'className' => 'add-new btn btn-primary mb-5 mb-md-0 me-3 waves-effect waves-light',
                'init' => 'function (api, node, config) {
                    $(node).removeClass("btn-secondary");
                }',
                'action' => 'function (e, dt, node, config) {
                    window.location = "' . route('leaves.create') . '";
                }'
            ],
            [
                'text' => '<i class="ri-refresh-line me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Reload</span>',
                'className' => 'btn btn-secondary mb-5 mb-md-0 me-3 waves-effect waves-light',
                'action' => 'function (e, dt, node, config) {
                    dt.ajax.reload();
                    $("#leaves-table_filter input").val("").keyup();
                    $("#leave_type_filter").val("").trigger("change");
                    $("#leave_status_filter").val("").trigger("change");
                }'
            ]
        ];

        $columnExport = [0, 1, 2, 3, 4, 5, 7];
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
            ->setTableId('leaves-table')
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
                'url'  => route('leaves.index'),
                'type' => 'GET',
                'data' => "function(d){
                    d.employee_id = $('#employee_id_filter').val();
                    d.leave_type = $('#leave_type_filter').val();
                    d.status = $('#leave_status_filter').val();
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
            Column::make('employee.number')->title('Employee Number'),
            Column::make('employee.user.name')->title('Employee Name'),
            Column::make('leave_type')->title('Type'),
            Column::make('date')->title('Date')
                ->orderable(false)
                ->searchable(false),
            Column::make('reason')->title('Reason'),
            Column::make('document')->title('Document'),
            Column::make('status')->title('Status'),
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
        return 'Leave_' . date('YmdHis');
    }
}

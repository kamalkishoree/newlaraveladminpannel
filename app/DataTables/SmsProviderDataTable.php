<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;
use Auth;
use App\Helpers\Helper;
use App\Models\SmsProvider;

class SmsProviderDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addIndexColumn()
            ->addColumn('provider_name', function ($data) {

                return $data->provider_name;
                
            })
            ->addColumn('sms_from', function ($data) {
                return $data->sms_from;

            })   
              ->addColumn('api_key', function ($data) {
                return $data->api_key;

              })
              ->addColumn('api_secret', function ($data) {
                return $data->api_secret;

              })->addColumn('app_id', function ($data) {
                return $data->app_id;

              })->addColumn('status', function ($data) {
                return $data->provider_name;

              })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ApiKeyDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SmsProvider $model , Request $request): QueryBuilder
    {
        $model = SmsProvider::orderBy('id','DESC');
        return $this->applyScopes($model);
   
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('smsprovider-datatable-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('frtip')
            ->orderBy(1)
            ->searching(true)
            ->responsive(true)
            ->serverSide(true)
            ->processing(true)
            ->scrollY(false)
            ->scrollX(false)
            ->parameters([
               
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            [   
                "data" => "id",
                "name" => "id",
                "title" => "ID",
                "orderable" => true,
                "searchable" => true,
                'exportable' => true,
                'printable' => true,
            ],
            [   
                "data" => "provider_name",
                "name" => "provider_name",
                "title" => "Provider Name",
                "orderable" => true,
                "searchable" => true,
                'exportable' => true,
                'printable' => true,
            ],

            [   
                "data" => "sms_from",
                "name" => "sms_from",
                "title" => "SMS From",
                "orderable" => true,
                "searchable" => true,
                'exportable' => true,
                'printable' => true,
            ],
            [   
                "data" => "api_key",
                "name" => "api_key",
                "title" => "Api key",
                "orderable" => true,
                "searchable" => true,
                'exportable' => true,
                'printable' => true,
            ],

            [   
                "data" => "api_secret",
                "name" => "api_secret",
                "title" => "API Secret",
                "orderable" => true,
                "searchable" => true,
                'exportable' => true,
                'printable' => true,
            ],

            [   
                "data" => "app_id",
                "name" => "app_id",
                "title" => "App ID",
                "orderable" => true,
                "searchable" => true,
                'exportable' => true,
                'printable' => true,
            ],

            [   
                "data" => "status",
                "name" => "status",
                "title" => "Status",
                "orderable" => true,
                "searchable" => true,
                'exportable' => true,
                'printable' => true,
            ],

            [   
                "data" => "action",
                "name" => "action",
                "title" => "Actions",
                "orderable" => true,
                "searchable" => true,
                'exportable' => true,
                'printable' => true,
            ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'smsprovider_' . date('YmdHis');
    }
}

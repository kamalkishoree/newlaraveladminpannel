<?php 
namespace App\DataTables;

use App\Models\AffilateIntegration;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Facades\DataTables;

class SmsProviderDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return DataTables::eloquent($this->query())
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $AffilateIntegration = AffilateIntegration::query();
        return $this->applyScopes($AffilateIntegration);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom' => 'Bfrtip',
                'buttons' => ['export', 'print', 'reset', 'reload'],
                'initComplete' => "function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var input = document.createElement(\"input\");
                        $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    });
                }",
            ]);
    }

    /**
     * @return array
     */
    protected function getColumns()
    {
        return [
            'provider_name',
            'sms_from',
            'api_key',
            'api_secret',
            'app_id',
            'status',
        ];
    }
}

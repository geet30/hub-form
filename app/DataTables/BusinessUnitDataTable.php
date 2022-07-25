<?php

namespace App\DataTables;

use App\Models\Business_unit;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;


class BusinessUnitDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            // ->addColumn('action', 'businessunit.action')
            ->addColumn('location', function (Business_unit $bu) {
                return $bu->locations->vc_name;
            })
            ->addColumn('action', static function (Business_unit $bu) {
                return "<a href='' class='btn btn-success btn-sm w-100'>Detail</a>";
            });
            // ->toJson();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\BusinessUnit $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Business_unit $model)
    {
        return $model->with(['locations', 'business_dept'])->newQuery()->orderBy('created', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('businessunit-table')
            // ->addIndexColumn()
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->dom('Bfrtip')
            ->orderBy(1);
            // ->buttons(
            //     Button::make('create'),
            //     Button::make('export'),
            //     Button::make('print'),
            //     Button::make('reset'),
            //     Button::make('reload')
            // );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        // pr($bu);die;
        return [
            Column::make('id')->title('ID'),
            Column::make('vc_short_name')->title('Name'),
            Column::make('i_status')->title('Status'),
            Column::make('locations.vc_name')->title('Location')->data('locations.vc_name'),
            // Column::make('business_dept.vc_name')->title('Departments')->data('business_dept.vc_name'),
            // Column::make('actions')->title('Actions')->data( function () {
            //     return "<a href='' class='btn btn-success btn-sm w-100'>Detail</a>";
            // }),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'BusinessUnit_' . date('YmdHis');
    }
}

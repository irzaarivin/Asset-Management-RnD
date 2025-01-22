<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\Asset;

/**
 * Class AssetStatusChartChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AssetStatusChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();
        $this->chart->labels(['Tersedia', 'Dipinjam', 'Pemeliharaan']);
        $this->chart->load(backpack_url('charts/asset-status-chart'));
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {
        $statuses = ['available', 'borrowed', 'maintenance'];
        $data = Asset::whereIn('status', $statuses)
            ->get()
            ->groupBy('status')
            ->map(fn($group) => $group->count())
            ->only($statuses)
            ->values();

        $this->chart->dataset('Asset Status', 'pie', $data->toArray())->backgroundColor([
            'rgb(70, 127, 208)',
            'rgb(77, 189, 116)',
            'rgb(96, 92, 168)'
        ]);
    }
}

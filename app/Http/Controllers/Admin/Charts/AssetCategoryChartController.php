<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\Category;
use App\Models\Asset;

/**
 * Class AssetCategoryChartChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AssetCategoryChartController extends ChartController
{
    protected $data;
    protected $colors;

    public function setup()
    {
        $this->chart = new Chart();

        $categories = Category::pluck('name');
        $categoryIds = Category::pluck('id');

        $this->data = $categoryIds->mapWithKeys(function ($category) {
            return [$category => Asset::where('category_id', $category)->count()];
        });

        $this->colors = $categories->map(function () {
            return sprintf('rgb(%d, %d, %d)', rand(0, 255), rand(0, 255), rand(0, 255));
        });

        $this->chart->labels($categories->toArray());
        $this->chart->load(backpack_url('charts/asset-category-chart'));
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {
        $this->chart->dataset('Asset by Category', 'pie', $this->data->values()->toArray())
            ->backgroundColor($this->colors->toArray());
    }
}

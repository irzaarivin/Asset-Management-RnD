<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\Borrowing;
use App\Models\User;

/**
 * Class UserBorrowedChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserBorrowedChartController extends ChartController
{
    protected $data;
    protected $colors;

    public function setup()
    {
        $this->chart = new Chart();

        $users = User::withCount('borrowings')
            ->orderByDesc('borrowings_count')
            ->take(5)
            ->get();

        $this->data = $users->pluck('borrowings_count', 'name');

        // dd($this->data);

        $this->colors = $users->map(function () {
            return sprintf('rgb(%d, %d, %d)', rand(0, 255), rand(0, 255), rand(0, 255));
        });

        $this->chart->labels($this->data->keys()->toArray());

        $this->chart->load(backpack_url('charts/user-borrowing-chart'));
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {
        $this->chart->dataset('Top 5 Borrowers', 'pie', $this->data->values()->toArray())->backgroundColor($this->colors->toArray());
    }
}

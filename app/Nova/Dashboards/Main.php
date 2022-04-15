<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\OrderPerDay;
use App\Nova\Metrics\OrdersPerPlan;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public function label(): string
    {
        return __('main.label');
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new OrderPerDay,
            new OrdersPerPlan,
        ];
    }
}

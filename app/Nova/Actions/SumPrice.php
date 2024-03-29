<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class SumPrice extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '统计价格';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $sumPrice = 0;
        foreach ($models as $model){
            $sumPrice += $model->quantity * $model->price;
        }
        return Action::message("价格总计 : ".$sumPrice);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields($novaRequest)
    {
        return [];
    }
}

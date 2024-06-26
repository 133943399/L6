<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class PaymentStatus extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '付款状态';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return void
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model){
            $model->status = $fields->status;
            $model->save();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields($novaRequest)
    {
        return [
            Select::make(__('order.status'),'status')
                ->options([
                    '0' => '未付款',
                    '1' => '付款',
                ]),
        ];
    }
}

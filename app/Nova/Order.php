<?php

namespace App\Nova;

use App\Nova\Actions\PaymentStatus;
use App\Nova\Actions\SumPrice;
use App\Nova\Lenses\OrderMTotal;
use App\Nova\Metrics\OrderPerDay;
use App\Nova\Metrics\OrdersPerPlan;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Order';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','orderDate'
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('order.label');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('配送->'.__('shop.label'), 'Shop', Shop::class)->searchable(),

            BelongsTo::make(__('product.label'), 'Product', Product::class),

            Number::make(__('order.price'),'price')->step(0.01),

            Number::make('数量','quantity'),

//            Text::make(__('order.old_total'),function (){
//                $total = $this->quantity * $this->product->price;
//                return $total;
//            }),

            Number::make(__('order.now_total'),function (){
                return $this->quantity * $this->price;
            }),

            Boolean::make(__('order.status'),'status'),

            Date::make(__('order.orderDate'),'orderDate')->rules('required'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            new OrderPerDay(),
            new OrdersPerPlan(),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
            new OrderMTotal()
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new PaymentStatus(),
            new SumPrice(),
        ];
    }
}

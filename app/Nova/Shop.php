<?php

namespace App\Nova;

use App\Models\Order;
use App\Models\Payment;
use App\Nova\Lenses\ShopMTotal;
use App\Nova\Lenses\ShopUnpaid;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Laravel\Nova\Http\Requests\NovaRequest;

class Shop extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model =  \App\Models\Shop::class;

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
        'name',
//        'contact_name',
        'telephone',
        'addr',
    ];

    /**
     * 显示名称
     *
     * @return string
     */
    public static function label()
    {
        return __('shop.label');
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->name;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('shop.addr'),'addr')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(__('shop.name'),'name')
                ->sortable()
                ->rules('required', 'max:255'),

//            Text::make(__('shop.contact_name'),'contact_name')
//                ->sortable(),

            Text::make(__('shop.telephone'),'telephone')
                ->sortable()
                ->rules('required', 'max:255'),

            new Panel('统计', $this->totalFields()),

            new Panel('付款信息', $this->paymentFields()),

            new Panel('订单', $this->orderFields()),

            Text::make(__('shop.remark'),'remark')
                ->sortable()
                ->rules('max:255'),
        ];
    }

    public function orderFields(){
        return [
            HasMany::make(__('order.label'), 'Order', \App\Nova\Order::class)
        ];
    }

    public function paymentFields(){
        return [
            HasMany::make(__('payment.label'), 'Payment', \App\Nova\Payment::class)
        ];
    }

    public function totalFields(){
        return [
            Text::make(__('shop.total_price'),function (){
                return DB::table('orders')
                    ->where('shop_id', $this->id)
                    ->whereNull('deleted_at')
                    ->sum(DB::raw('price * quantity'));
            })->displayUsing(function ($value) {
                return $value . ' 元';
            }),

            Text::make(__('shop.total_amount'),function (){
                return DB::table('payments')
                    ->where('shop_id', $this->id)
                    ->whereNull('deleted_at')
                    ->sum('amount');
            })->displayUsing(function ($value) {
                return $value . ' 元';
            }),

            Text::make(__('shop.unpaid'),function (){
                $total = DB::table('orders')
                    ->where('shop_id', $this->id)
                    ->whereNull('deleted_at')
                    ->value(DB::raw("sum(price * quantity)"));

                $payment = DB::table('payments')
                    ->where('shop_id', $this->id)
                    ->whereNull('deleted_at')
                    ->sum('amount');;
                return $total - $payment;
            })->displayUsing(function ($value) {
                return $value . ' 元';
            })
        ];
    }
    /**
     * Get the cards available for the request.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [
            new ShopMTotal(),
            new ShopUnpaid()
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}

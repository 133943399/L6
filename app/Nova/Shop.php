<?php

namespace App\Nova;

use App\Nova\Lenses\ShopMTotal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Shop extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Shop';

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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('shop.name'),'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(__('shop.contact_name'),'contact_name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(__('shop.telephone'),'telephone')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(__('shop.addr'),'addr')
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
            HasMany::make(__('order.label'), 'Order', Order::class)
        ];
    }

    public function paymentFields(){
        return [
            HasMany::make(__('payment.label'), 'Payment', Payment::class)
        ];
    }

    public function totalFields(){
        return [
            Text::make(__('shop.total_price'),function (){
                return DB::table('orders')->select(DB::raw("sum(price * quantity) as total_price"))->where('shop_id',$this->id)->get()->toArray()[0]->total_price;
            }),
            Text::make(__('shop.total_amount'),function (){
                return DB::table('payments')->select(DB::raw("sum(amount) as total_amount"))->where('shop_id',$this->id)->get()->toArray()[0]->total_amount;
            }),
            Text::make(__('shop.unpaid'),function (){
                $total = DB::table('orders')->select(DB::raw("sum(price * quantity) as total_price"))->where('shop_id',$this->id)->get()->toArray()[0]->total_price;
                $payment = DB::table('payments')->select(DB::raw("sum(amount) as total_amount"))->where('shop_id',$this->id)->get()->toArray()[0]->total_amount;
                return $total - $payment;
            })
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
        return [];
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
            new ShopMTotal()
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
        return [];
    }
}

<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;

class ShopMTotal extends Lens
{
    /**
     * Get the displayable name of the lens.
     *
     * @return string
     */
    public function name()
    {
        return __('lens.mTotal');
    }

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->select(self::columns())
                ->join('orders', 'shops.id', '=', 'orders.shop_id')
                ->whereRaw('DATE_FORMAT(orders.orderDate, "%Y%m" ) = DATE_FORMAT(CURDATE(),"%Y%m")')
                ->groupBy('shops.id')
        ));
    }

    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns()
    {
        return [
            'shops.id',
            'shops.addr',
            'shops.name',
            DB::raw('sum(orders.price * orders.quantity) as total_price'),
        ];
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make('ID', 'id')->sortable(),

            Text::make(__('shop.addr'),'addr'),

            Text::make(__('shop.name'),'name'),

            Text::make(__('shop.total_price'),'total_price'),

            Text::make(__('shop.date'),function (){
                return date('Y-m',time());
            }),

            Text::make(__('shop.unpaid'),function (){
                $total = DB::table('orders')->select(DB::raw("sum(price * quantity) as total_price"))->where('shop_id',$this->id)->get()->toArray()[0]->total_price;
                $payment = DB::table('payments')->select(DB::raw("sum(amount) as total_amount"))->where('shop_id',$this->id)->get()->toArray()[0]->total_amount;
                return $total - $payment;
            })
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'shop-m-total';
    }
}

<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;

class ShopUnpaid extends Lens
{

    /**
     * Get the displayable name of the lens.
     *
     * @return string
     */
    public function name()
    {
        return __('lens.unpaid');
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
            ->rightJoinSub('select shop_id,sum(price * quantity) as total from `orders` group by `shop_id` ', 'orders', 'shops.id', '=', 'orders.shop_id')
            ->rightJoinSub('select shop_id,sum(amount) as pay from `payments` group by `shop_id` ', 'payments', 'shops.id', '=', 'payments.shop_id')
            ->whereRaw('orders.total - payments.pay > 0')
            ->orderBy('unpaid','desc')

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
            'orders.total',
            'payments.pay',
            'shops.id',
            'shops.addr',
            'shops.name',
            DB::raw('orders.total - payments.pay as unpaid')
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

//            Text::make(__('lens.total'), 'total'),
//            Text::make(__('lens.pay'), 'pay'),

            Text::make(__('lens.unpaid'), 'unpaid'),
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
        return 'shop-unpaid';
    }
}

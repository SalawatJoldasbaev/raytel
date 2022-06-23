<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreateRequest;
use App\Models\Basket;
use App\Models\Order;
use App\Models\Product;
use App\Src\ApiResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(OrderCreateRequest $request)
    {
        $user = $request->user();
        $basket = Basket::where('user_id', $user->id)->where('active', true)->first();
        $product = Product::find($request->product_id);
        if (!$basket) {
            $basket = Basket::create([
                'user_id'=> $user->id,
                'first_price'=> $product->second_price*($request->count ?? 1),
                'active'=> true,
            ]);
        } else {
            $order = Order::where('basket_id', $basket->id)->where('product_id', $request->product_id)->first();
            $basket->update([
                'first_price'=> $basket->first_price+($product->second_price*($request->count ?? 1))
            ]);
        }
        if (isset($order)) {
            $order->update([
                'count'=> $order->count+($request->count ?? 1),
                'price'=> $product->second_price,
                'color'=> $request->color,
                'size'=> $request->size
            ]);
        } else {
            Order::create([
                'basket_id'=> $basket->id,
                'product_id'=> $request->product_id,
                'count'=> $request->count ?? 1,
                'color'=> $request->color,
                'price'=> $product->second_price,
                'size'=> $request->size
            ]);
        }
        return ApiResponse::success();
    }

    public function single(Request $request)
    {
        $user = $request->user();
        $basket = Basket::where('user_id', $user->id)->where('active', true)->first();
        if (!$basket) {
            return ApiResponse::data([
                'message'=> 'empty basket'
            ]);
        }
        $final = [
            'basket_id'=> $basket->id,
            'price'=> $basket->first_price,
            'ordered_at'=> $basket->created_at,
            'orders'=> []
        ];
        foreach ($basket->orders as $order) {
            $final['orders'][] = [
                'order_id'=> $order->id,
                'product_id'=> $order->product_id,
                'prodcut_name'=> $order->product->name,
                'price'=> $order->price,
                'count'=> $order->count,
                'color'=> $order->color,
                'size'=> $order->size,
                'ordered_at'=> $order->updated_at,
            ];
        }

        return ApiResponse::data($final);
    }

    public function index(Request $request)
    {
        $baskets = Basket::where('active', false)->orderBy('id', 'desc')->paginate(30);
        $final = [
            'last_page'=> $baskets->lastPage(),
            'data'=>[],
        ];
        foreach ($baskets as $basket) {
            $temp = [
                'basket_id'=> $basket->id,
                'price'=> $basket->first_price,
                'ordered_at'=> $basket->updated_at,
                'user_id'=> $basket->user_id,
                'user'=> [
                    'name'=>  $basket->user->name,
                    'email'=>  $basket->user->email,
                ],
                'orders'=> []
            ];
            foreach ($basket->orders as $order) {
                $temp['orders'][] = [
                    'order_id'=> $order->id,
                    'product_id'=> $order->product_id,
                    'prodcut_name'=> $order->product->name,
                    'price'=> $order->price,
                    'count'=> $order->count,
                    'color'=> $order->color,
                    'size'=> $order->size,
                    'ordered_at'=> $order->updated_at,
                ];
            }
            $final['data'][] = $temp;
        }
        return ApiResponse::data($final);
    }
}

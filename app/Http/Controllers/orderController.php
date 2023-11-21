<?php

namespace App\Http\Controllers;

use App\Http\Resources\orderResource;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;

class orderController extends Controller
{

    public function order()
    {

        $exists = Cart::where('users_id', '=', auth()->user()->id)->exists();

        if (!$exists) {
            $response = [
                'content' => [
                    'message' => 'Нет товаров для оформления'
                ]
            ];

            return response($response, 422)->header('status', '422');
        }

        $products = Cart::join('products', 'products.id', '=', 'carts.products_id')->where('users_id', '=', auth()->user()->id)
            ->select('products.price', 'products.id as id')->get();

        $products_str = '';
        foreach ($products as $product) {
            $products_str = $products_str . $product->id . ' ';
        }

        $order_price = 0;
        foreach ($products as $product) {
            $order_price += $product->price;
        }


        $new_order = Order::create(['products' => trim($products_str), 'status' => 'Новый', 'order_price' => $order_price, 'users_id' => auth()->user()->id]);

        Cart::where('users_id', '=', auth()->user()->id)->delete();

        $response = [
            'content' => [
                'order_id' => $new_order->id,
                'message' => 'Заказ оформлен'
            ]
        ];

        return response($response, 201)->header('status', '201');
    }

    public function show()
    {
        $orders = Order::where('users_id', '=', auth()->user()->id)->get();

        foreach ($orders as $order) {
            $order->products = explode(' ', $order->products);
        }
        $price_all = 0;
        foreach ($orders as $order) {
            $price_all += $order->order_price;
        }

        $response = ['content' =>  orderResource::collection($orders), 'price_all' => $price_all];

        return response($response, 200)->header('Status', '200');
    }

    public function change_status(Request $req){

        $product_update = Order::where('id', '=', $req->id)->exists();


        if (!$product_update) {
            $response = [
                'content' => [
                    'message' => 'Товар не существует'
                ]
            ];

            return response($response, 422)->header('status', '422');
        }

        if($req->status == 'yes'){
            Order::where('id', '=', $req->id)->update(['status' => 'Подтверждён']);
        }
        if($req->status == 'no'){
            Order::where('id', '=', $req->id)->update(['status' => 'Отменён']);
        }


    }

    public function allOrders(){
        
        if (auth()->user()->role == 'admin') {
            $orders = Order::get();

            foreach ($orders as $order) {
                $order->products = explode(' ', $order->products);
            }
    
            $response = ['content' =>  orderResource::collection($orders)];
    
            return response($response, 200)->header('Status', '200');
        }
        $response = [
            'content' => [
                'code' => '403',
                'message' => 'Доступ для вашей группы запрещён'
            ]
        ];

        return response($response, 403)->header('status', '403');
    }
}

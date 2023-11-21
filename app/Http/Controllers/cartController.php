<?php

namespace App\Http\Controllers;

use App\Http\Resources\cartResource;
use App\Http\Resources\productRecourse;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class cartController extends Controller
{
    public function add($product_id)
    {

        $exists = Product::where('id', '=', $product_id)->exists();

        if (!$exists) {
            $response = [
                'content' => [
                    'message' => 'Товара не существует'
                ],
            ];

            return response($response, 422)->header('status', '422');
        }

        Cart::create(['users_id' => auth()->user()->id, 'products_id' => $product_id]);

        $response = [
            'content' => [
                'message' => 'Товар в корзине'
            ],
        ];

        return response($response, 201)->header('status', '201');
    }

    public function show()
    {

        $products = Cart::where('users_id', '=', auth()->user()->id)->get();

        $response = ['content' => cartResource::collection($products)];


        return response($response, 200)->header('status', '200');;
    }

    public function delete($id)
    {

        $cart_delete = Cart::where([['id', '=', $id], ['users_id', '=', auth()->user()->id]])->exists();
        Cart::where([['id', '=', $id], ['users_id', '=', auth()->user()->id]])->delete();

        $products = Cart::where('users_id', '=', auth()->user()->id)->get();


        if ($cart_delete) {
            $response = [
                'content' => [
                    'message' => 'Позиция удалена из корзины',
                    'content' => cartResource::collection($products)
                ]
            ];

            return response($response, 200)->header('status', '200');
        }


        $response = [
            'content' => [
                'message' => 'Позиции не существует'
            ]
        ];

        return response($response, 422)->header('status', '422');
    }
}

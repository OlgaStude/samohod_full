<?php

namespace App\Http\Controllers;

use App\Http\Resources\productRecourse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class productsController extends Controller
{

    public function show()
    {

        $products = ['content' =>  productRecourse::collection(Product::all())];

        return response($products, 200)->header('Status', '200');
    }


    public function add(Request $req)
    {

        if (auth()->user()->role == 'admin') {

            try {

                $fields = $req->validate([
                    'name' => 'required|string',
                    'description' => 'required|string',
                    'price' => 'required'
                ]);
            } catch (ValidationException $e) {
                $errors = [];
                foreach ($e->errors() as $err => $messages) {
                    foreach ($messages as $message) {
                        $errors[] = [
                            $err => $message,
                        ];
                    }
                }
                return response()->json([
                    'warning' => [
                        "code" => 422,
                        'message' => 'Несоответствие требованиям',
                        'warnings' => $errors
                    ]


                ], 422)->header('status', '422');
            }

            $product = Product::create(['name' => $req->name, 'description' => $req->description, 'price' => $req->price]);


            $response = [
                'content' => [
                    'id' => $product->id,
                    'message' => 'Товар добавлен'
                ]
            ];

            return response($response, 201)->header('status', '201');
        }

        $response = [
            'content' => [
                'code' => '403',
                'message' => 'Доступ для вашей группы запрещён'
            ]
        ];

        return response($response, 403)->header('status', '403');
    }


    public function delete($id)
    {

        $product_delete = Product::where('id', '=', $id)->exists();



        Product::where('id', '=', $id)->delete();

        if ($product_delete) {
            $response = [
                'content' => [
                    'message' => 'Товар удалён'
                ]
            ];

            return response($response, 200)->header('status', '200');
        }


        $response = [
            'content' => [
                'message' => 'Товар не существует'
            ]
        ];

        return response($response, 422)->header('status', '422');
    }



    public function update($id, Request $req)
    {

        $product_update = Product::where('id', '=', $id)->exists();


        if (!$product_update) {
            $response = [
                'content' => [
                    'message' => 'Товар не существует'
                ]
            ];

            return response($response, 422)->header('status', '422');
        }

        if ($req->name) {
            Product::where('id', '=', $id)->update(['name' => $req->name]);
        }
        if ($req->description) {
            Product::where('id', $id)->update(['name' => $req->description]);
        }
        if ($req->price) {
            Product::where('id', $id)->update(['name' => $req->price]);
        }

        $product = Product::where('id', '=', $id)->get();

        $response = [
            'content' => [
                'id' => $product[0]->id,
                'message' => 'Данные обновлены'
            ]
        ];

        return response($response, 200)->header('status', '200');
    }
}

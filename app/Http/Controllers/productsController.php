<?php

namespace App\Http\Controllers;

use App\Http\Resources\productRecourse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class productsController extends Controller
{

    public function show()
    {

        $products = ['content' =>  productRecourse::collection(Product::orderBy('id', 'desc')->get())];

        return response($products, 200)->header('Status', '200');
    }


    public function add(Request $req)
    {


        if (auth()->user()->role == 'admin') {

            try {

                $fields = $req->validate([
                    'name' => 'required|string',
                    'img' => 'required| mimes:jpeg,png,jpg',
                    'manufacturer' => 'required|string',
                    'category_id' => 'required|numeric',
                    'model' => 'required|string',
                    'year' => 'required|numeric',
                    'price' => 'required|numeric'
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

            $req->file('img')->store('public/printer_imgs');
            $image = $req->file('img')->hashName();
            
            $product = Product::create(['name' => $req->name, 
            'img' => $image,
            'categories_id' => $req->category_id,
            'manufacturer' => $req->manufacturer, 
            'model' => $req->model, 
            'year' => $req->year, 
            'price' => $req->price
        ]);
        

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
        $products = Product::all();

        if ($product_delete) {
            $response = [
                'content' => [
                    'message' => 'Товар удалён',
                    'product' => $products
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

        try {
            if($req->price){
                $fields = $req->validate([
                    'img' => 'mimes:jpeg,png,jpg',
                    'price' => 'numeric'
                ]);
            } else{
                $fields = $req->validate([
                    'img' => 'mimes:jpg,jpeg,png',
                ]);
            }
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

        if ($req->name) {
            Product::where('id', '=', $id)->update(['name' => $req->name]);
        }
        if ($req->img) {
            Storage::delete("public/printer_imgs/".Product::find($id)->img);
            $req->file('img')->store('public/printer_imgs');
            $image = $req->file('img')->hashName();
            Product::where('id', $id)->update(['img' => $image]);
        }
        if ($req->price) {
            Product::where('id', $id)->update(['price' => $req->price]);
        }

        $product = Product::where('id', '=', $id)->get();
        $products = Product::all();

        $response = [
            'content' => [
                'id' => $product[0]->id,
                'message' => 'Данные обновлены',
                'products' => $products
            ]
        ];

        return response($response, 200)->header('status', '200');
    }
}

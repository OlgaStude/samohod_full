<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class categoryController extends Controller
{
    
    public function addCategory(Request $req){


        try {

            $fields = $req->validate([
                'name' => 'required|string'
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

        Category::create(['name' => $req->name]);

        $category = Category::all();

        $response = [
            'content' => [
                'categories' => $category,
                'message' => 'Категория создана'
            ]
        ];

        return response($response, 201)->header('status', '201');

    }


    public function getCategory(){

        $categories = Category::all();

        return $categories;

    }


    public function categoryDelete(Request $req) {
        Product::where('categories_id', '=', $req->id)->delete();
        Category::where('id', '=', $req->id)->delete();

        $category = Category::all();

        $response = [
            'content' => [
                'categories' => $category,
                'message' => 'Категория удалена'
            ]
        ];

        return response($response, 200)->header('status', '200');
    }

}

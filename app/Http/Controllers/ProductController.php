<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Favorite;
use App\Src\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function create(ProductCreateRequest $request)
    {
        Product::create([
            'category_id'=> $request->category_id,
            'name'=> $request->name,
            'images'=> $request->images,
            'first_price'=> $request->first_price,
            'second_price'=> $request->second_price,
            'discount'=> $request->discount,
            'description'=> $request->description,
            'size'=> $request->size,
            'color'=> $request->color,
        ]);

        return ApiResponse::success();
    }

    public function index(Request $request)
    {
        $products = Product::orderBy('id', 'desc')->paginate(30);
        $final = [
            'last_page'=> $products->lastPage(),
            'data'=>[]
        ];
        $user = $request->user();
        foreach ($products as $product) {
            $fav = Favorite::where('user_id', $user?->id)->where('product_id', $product->id)->first();
            $final['data'][] = [
                'id'=> $product->id,
                'name'=> $product->name,
                'first_price'=> $product->first_price,
                'second_price'=> $product->second_price,
                'discount'=> $product->discount,
                'is_favorite'=> isset($fav) ? true:false,
                'description'=> $product->description,
                'category'=>[
                    'id'=> $product->category->id,
                    'parent_id'=> $product->category->parent_id,
                    'name'=> $product->category->name,
                ],
                'size'=> $product->size,
                'color'=> $product->color,
            ];
        }
        return ApiResponse::data($final);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update([
            'category_id'=> $request->category_id,
            'name'=> $request->name,
            'images'=> $request->images,
            'first_price'=> $request->first_price,
            'second_price'=> $request->second_price,
            'discount'=> $request->discount,
            'description'=> $request->description,
            'size'=> $request->size,
            'color'=> $request->color,
        ]);
        return ApiResponse::success();
    }

    public function delete(Request $request, Product $product)
    {
        $product->delete();
        return ApiResponse::success();
    }
}

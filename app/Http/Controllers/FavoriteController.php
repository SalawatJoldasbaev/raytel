<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use App\Src\ApiResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function create(Request $request, Product $product)
    {
        $user = $request->user();
        $fav = Favorite::where('user_id', $user->id)->where('product_id', $product->id)->first();
        if (!$fav) {
            Favorite::create([
                'user_id'=> $user->id,
                'product_id'=> $product->id,
            ]);
        }
        return ApiResponse::success();
    }

    public function show(Request $request)
    {
        $user = $request->user();
        $favs = Favorite::where('user_id', $user->id)->get();
        $final = [];
        foreach ($favs as $fav) {
            $final[] = [
                'product_id'=> $fav->product_id,
                'product_name'=> $fav->product->name,
            ];
        }
        return ApiResponse::data($final);
    }

    public function delete(Request $request, Product $product)
    {
        $user = $request->user();
        $fav = Favorite::where('user_id', $user->id)->where('product_id', $product->id)->first();
        $fav->delete();
        return ApiResponse::success();
    }
}

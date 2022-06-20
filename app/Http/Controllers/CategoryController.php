<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Src\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(CategoryCreateRequest $request)
    {
        $category = Category::create([
            'parent_id'=> $request->parent_id,
            'name'=> $request->name,
        ]);

        return ApiResponse::data([
            'id'=> $category->id,
        ]);
    }

    public function index(Request $request)
    {
        $categories = Category::where('parent_id', 0)->with('children')->get(['id', 'parent_id', 'name']);
        return ApiResponse::data($categories);
    }

    public function delete(Request $request, Category $category)
    {
        $category->products()->delete();
        $category->delete();
        return ApiResponse::success();
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update([
            'parent_id'=> $request->parent_id,
            'name'=> $request->name,
        ]);
        return ApiResponse::success();
    }
}

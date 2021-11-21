<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAllCategories() {
        // TODO: implementare ricerca categorie

        return Category::get();
    }

    public function createCategory(Request $request) {
        $this->validate($request, Category::getValidationRules());

        if ($request->auth->admin === 0) {
            return response([
                'error' => 'Unauthorized',
                'info' => 'Non puoi creare categorie'
            ], 403);
        }

        $category = new Category($request->input());
        $category->save();

        return $category->fresh();
    }

    public function updateCategory(Request $request, $id) {
        $this->validate($request, Category::getValidationRules());

        if ($request->auth->admin === 0) {
            return response([
                'error' => 'Unauthorized',
                'info' => 'Non puoi modificare questa categoria'
            ], 403);
        }

        $category = Category::findOrFail($id);
        $category->fill($request->input());
        $category->save();

        return $category->fresh();
    }

    public function deleteCategory(Request $request, $id) {
        if ($request->auth->admin === 0) {
            return response([
                'error' => 'Unauthorized',
                'info' => 'Non puoi modificare questa categoria'
            ], 403);
        }

        $category = Category::findOrFail($id);
        $category->delete();

        return [];
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category as CategoryModel;

class Category extends Component
{
    public $categories;
    public $category_ids = [];
    public $category_name;

    // 初期化
    public function boot()
    {
        $this->categories = CategoryModel::all();
    }

    // レンダリング
    public function render()
    {
        return view('livewire.category');
    }

    // カテゴリ削除
    public function deleteCategory()
    {
        CategoryModel::whereIn('id', $this->category_ids)->delete();
        $this->categories = CategoryModel::all();
    }

    // カテゴリ追加
    public function addCategory()
    {
        if ($this->category_name) {
            CategoryModel::insert([
                'name' => $this->category_name,
            ]);
            $this->category_name = '';
            $this->categories = CategoryModel::all();
        }
    }
}

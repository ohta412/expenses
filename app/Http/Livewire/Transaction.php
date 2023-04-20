<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Transaction as TransactionModel;
use App\Models\Category as CategoryModel;
use Carbon\Carbon;

class Transaction extends Component
{
    // 初期値
    public $this_month;
    public $last_month;
    public $next_month;
    public $transactions;
    public $sum;
    public $repeat = 1;
    public $count;
    public $word;

    // 固定値
    public $categories;
    public $methods;
    public $types;

    // 入力値
    public $name = [];
    public $category_id = null;
    public $category_ids = [];
    public $method;
    public $type;
    public $amount = [];
    public $date;

    /**
     *
     * バリデーション項目
     */
    protected $rules = [
        'method' => 'required',
        'type' => 'required',
    ];

    /**
     *
     * boot
     */
    public function boot()
    {
        $this->this_month = new Carbon('this month');
        $this->last_month = new Carbon('last month');
        $this->next_month = new Carbon('next month');
        $this->categories = CategoryModel::all();
        $this->methods = get_method();
        $this->types = get_type();

        // 初期化
        $this->init();
    }

    /**
     *
     * 初期化
     */
    public function init()
    {
        // 収支レコード取得
        $query = TransactionModel::query();

        // 年月で絞り込み
        $query->whereYear('date', $this->this_month->format('Y'))->whereMonth('date', $this->this_month->format('m'));

        // カテゴリ絞り込み
        $this->category_ids = array_filter($this->category_ids);
        if (!empty($this->category_ids)) {
            $query->whereIn('category_id', $this->category_ids);
        }

        // ワード検索
        if ($this->word) {
            $query->where('name', 'like', '%'.$this->word.'%');
        }

        // 並び替え
        $query->orderBy('date', 'ASC');
        $this->transactions = $query->get();

        // 合計計算
        $this->get_sum();
    }

    /**
     *
     * レンダリング
     */
    public function render()
    {
        return view('livewire.transaction');
    }

    /**
     *
     * 合計計算
     */
    public function get_sum()
    {
        $this->sum = 0;
        if ($this->transactions->isNotEmpty()) {
            foreach ($this->transactions as $transaction){
                if ($transaction->type === "income") {
                    $this->sum += $transaction->amount;
                }
                if ($transaction->type === "expense") {
                    $this->sum -= $transaction->amount;
                }
            }
        }
    }

    /**
     *
     * 収支登録
     */
    public function addTransaction()
    {
        // バリデーション
        $this->validate();

        // 日付空欄時は今日の日付を入れる
        $date = date('Y-m-d');
        if ($this->date) $date = $this->date;

        // 登録
        if (!empty($this->name)) {
            for ($i = 0; $i < count($this->name); $i++) {
                $name = $this->name[$i] ?? null;
                $amount = $this->amount[$i] ?? null;
                if ($name && $amount) {
                    TransactionModel::insert([
                        'name' => $name,
                        'category_id' => $this->category_id ?? null,
                        'method' => $this->method,
                        'type' => $this->type,
                        'amount' => $amount,
                        'date' => $date,
                    ]);
                }
            }
        }
        $this->repeat = 1;
        $this->name = [];
        $this->category_id = null;
        $this->method = '';
        $this->type = '';
        $this->amount = [];
        $this->date = '';
        $this->transactions = TransactionModel::all();

        // 初期化
        $this->init();
    }

    /**
     *
     * フィールド追加
     */
    public function addField()
    {
        if ($this->count) {
            $this->repeat += $this->count;
        } else {
            $this->repeat++;
        }
    }

    /**
     *
     * 月移動
     */
    public function changeMonth($direction)
    {
        if ($direction === 'back') {
            $this->this_month->subMonth();
            $this->last_month->subMonth();
            $this->next_month->subMonth();
        }
        if ($direction === 'forword') {
            $this->this_month->addMonthNoOverflow();
            $this->last_month->addMonthNoOverflow();
            $this->next_month->addMonthNoOverflow();
        }

        // 初期化
        $this->init();
    }

    /**
     *
     * 検索
     */
    public function search()
    {

        // 初期化
        $this->init();
    }

    /**
     *
     * リセット
     */
    public function resetWord()
    {
        $this->category_ids = [];
        $this->word = '';

        // 初期化
        $this->init();
    }

    /**
     *
     * 削除
     */
    public function deleteTransaction($id)
    {
        TransactionModel::findOrFail($id)->delete();

        // 初期化
        $this->init();
    }
}

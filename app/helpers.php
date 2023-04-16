<?php declare(strict_types=1);

// 支払方法の一覧を返却
if (!function_exists('get_method')) {
    function get_method()
    {
        return [
            'cash' => '現金',
            'bank' => '銀行',
            'credit_card' => 'クレジットカード',
        ];
    }
}

// 収支タイプの一覧を返却
if (!function_exists('get_type')) {
    function get_type()
    {
        return [
            'income' => '収入',
            'expense' => '支出',
        ];
    }
}

// 支払方法のkeyをvalueに変更
if (!function_exists('filter_method')) {
    function filter_method($key)
    {
        $method = get_method();
        return $method[$key];
    }
}

// 収支方法のkeyをvalueに変更
if (!function_exists('filter_type')) {
    function filter_type($key)
    {
        $type = get_type();
        return $type[$key];
    }
}
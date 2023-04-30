<div>
    <div class="f-center pt-2">
        <p class="text-center font-bold mr-8 underline cursor-pointer" wire:click="changeMonth('back')">< {{ $last_month->format('Y年n月') }}</p>
        <p class="font-large text-center font-bold">{{ $this_month->format('Y年n月') }}</p>
        <p class="text-center font-bold ml-8 underline cursor-pointer" wire:click="changeMonth('forword')">{{ $next_month->format('Y年n月') }} ></p>
    </div>
    <div class="f-center -column mt-10">
    @if ($categories)
        <div class="radio-wrap">
            @foreach ($categories as $category)
                <label><input type="checkbox" wire:model.defer="category_ids.{{ $loop->index }}" value="{{ $category->id }}"> {{ $category->name }}</label>
            @endforeach
        </div>
    @endif
    <div class="text-set__item mt-2">
        <div>
            <input class="form-set js-datepicker" type="text" wire:model.defer="start"> ~ 
            <input class="form-set js-datepicker" type="text" wire:model.defer="end">
            <input class="form-set -mini ml-3" type="text" id="full-name" name="full-name" wire:model.blur="word" placeholder="検索ワード">
            <button class="btn -mini -blue ml-2" wire:click="search">検索</button>
            <button class="btn -mini ml-1" wire:click="resetWord">リセット</button>
        </div>
    </div>
</div>
    @if ($transactions->isNotEmpty())
        <table class="table-auto w-full text-left whitespace-no-wrap mt-10 table2">
            <tr>
                <th class="px-4 py-3 bg-gray-100 rounded-tl rounded-bl">商品名</th>
                <th class="px-4 py-3 bg-gray-100 w-40">カテゴリ</th>
                <th class="px-4 py-3 bg-gray-100 w-40">方法</th>
                <th class="px-4 py-3 bg-gray-100 w-24">収支</th>
                <th class="px-4 py-3 bg-gray-100 text-right w-40">金額</th>
                <th class="px-4 py-3 bg-gray-100 w-24 text-right rounded-tr rounded-br">日付</th>
                <th class="px-4 py-3 bg-gray-100 w-24 text-right rounded-tr rounded-br"></th>
            </tr>
            @foreach ($transactions as $transaction)
                @php
                    $class_name = $loop->first ? 'pt-4 pb-1' : 'py-1';
                @endphp
                <tr>
                    <td class="px-4 {{ $class_name }}">{{ $transaction->name }}</td>
                    <td class="px-4 {{ $class_name }}">{{ $transaction->category->name ?? null }}</td>
                    <td class="px-4 {{ $class_name }}">{{ filter_method($transaction->method) }}</td>
                    <td class="px-4 {{ $class_name }}">{{ filter_type($transaction->type) }}</td>
                    <td class="px-4 {{ $class_name }} text-right">
                        @if ($transaction->type === "income")
                            +
                        @endif
                        @if ($transaction->type === "expense")
                            -
                        @endif
                        {{ number_format($transaction->amount) }}
                        円
                    </td>
                    <td class="px-4 {{ $class_name }} text-right">{{ date('n/j', strtotime($transaction->date)) }}</td>
                    <td class="px-4 {{ $class_name }} text-right">
                        <button
                            class="btn -mini"
                            onclick="confirm('削除しますか？') || event.stopImmediatePropagation()"
                            wire:click="deleteTransaction({{ $transaction->id }})"
                        >削除</button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="px-4 py-3 font-bold" colspan="4">合計</td>
                <td class="px-4 py-3 font-bold text-right">{{ number_format($sum) }} 円</td>
            </tr>
        </table>
    @endif
    <div class="f-center boder-top pt-8 mt-4">
        <table class="table">
            @if ($categories)
                <tr>
                    <th>カテゴリ</th>
                    <td>
                        <div class="radio-wrap">
                            @foreach ($categories as $category)
                                <label><input type="radio" wire:model.defer="category_id" name="category_id" value="{{ $category->id }}"> {{ $category->name }}</label>
                            @endforeach
                        </div>
                    </td>
                </tr>
            @endif
            @if ($methods)
                <tr>
                    <th>方法</th>
                    <td>
                        <div class="radio-wrap">
                            @foreach ($methods as $key => $value)
                                <label><input type="radio" wire:model.defer="method" name="method" value="{{ $key }}"> {{ $value }}</label>
                            @endforeach
                        </div>
                        @error('method') <p class="error">{{ $message }}</p> @enderror
                    </td>
                </tr>
            @endif
            @if ($types)
                <tr>
                    <th>収支</th>
                    <td>
                        <div class="radio-wrap">
                            @foreach ($types as $key => $value)
                                <label><input type="radio" wire:model.defer="type" name="type" value="{{ $key }}"> {{ $value }}</label>
                            @endforeach
                        </div>
                        @error('type') <p class="error">{{ $message }}</p> @enderror
                    </td>
                </tr>
            @endif
            <tr>
                <th>項目</th>
                <td>
                    @for ($i = 0; $i < $repeat; $i++)
                        <div class="text-set">
                            <div class="text-set__item">
                                @if ($i === 0)
                                    <p class="text-set__label">商品名</p>
                                @endif
                                <input class="form-set -long" type="text" wire:model.defer="name.{{ $i }}">
                            </div>
                            <div class="text-set__item">
                                @if ($i === 0)
                                    <p class="text-set__label">金額</p>
                                @endif
                                <input class="form-set" type="number" wire:model.defer="amount.{{ $i }}">
                            </div>
                        </div>
                    @endfor
                    <div class="mt-4">
                        <div class="text-set__item">
                            <p class="text-set__label">フィールド追加</p>
                            <div>
                                <input class="form-set -short -mini" type="number" wire:model.defer="count">
                                <button class="btn -mini ml-2" wire:click="addField">追加</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>日付</th>
                <td><input class="form-set js-datepicker" type="text" wire:model.defer="date"></td>
            </tr>
        </table>
    </div>
    <div class="text-center mt-5">
        <button class="btn -blue" wire:click="addTransaction">登録する</button>
    </div>
</div>
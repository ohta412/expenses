<div>
    <p wire:click="changeMonth('back')">{{ $last_month->format('Y年m月') }}</p>
    <p>{{ $this_month->format('Y年m月') }}</p>
    <p wire:click="changeMonth('forword')">{{ $next_month->format('Y年m月') }}</p>
    <hr>
    @if ($categories)
        @foreach ($categories as $category)
            <label><input type="checkbox" wire:model.defer="category_ids.{{ $loop->index }}" value="{{ $category->id }}"> {{ $category->name }}</label>
        @endforeach
    @endif
    <hr>
    <input type="text" wire:model.blur="word">
    <button wire:click="search">検索</button>
    <button wire:click="resetWord">リセット</button>
    @if ($transactions->isNotEmpty())
        <table class="table-auto w-full text-left whitespace-no-wrap mt-5">
            <tr>
                <th class="px-4 py-3 bg-gray-100 rounded-tl rounded-bl">商品名</th>
                <th class="px-4 py-3 bg-gray-100 w-40">カテゴリ</th>
                <th class="px-4 py-3 bg-gray-100 w-40">方法</th>
                <th class="px-4 py-3 bg-gray-100 w-24">収支</th>
                <th class="px-4 py-3 bg-gray-100 text-right w-32">金額</th>
                <th class="px-4 py-3 bg-gray-100 w-24 text-right rounded-tr rounded-br">日付</th>
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
                    </td>
                    <td class="px-4 {{ $class_name }} text-right">{{ date('n/j', strtotime($transaction->date)) }}</td>
                </tr>
            @endforeach
            <tr>
                <td class="px-4 py-3 font-bold" colspan="4">合計</td>
                <td class="px-4 py-3 font-bold text-right">{{ number_format($sum) }}</td>
            </tr>
        </table>
    @endif
    <hr>
    @if ($categories)
        @foreach ($categories as $category)
            <label><input type="radio" wire:model.defer="category_id" name="category_id" value="{{ $category->id }}"> {{ $category->name }}</label>
        @endforeach
    @endif
    <hr>
    @if ($methods)
        @foreach ($methods as $key => $value)
            <label><input type="radio" wire:model.defer="method" name="method" value="{{ $key }}"> {{ $value }}</label>
        @endforeach
        @error('method') <span class="error">{{ $message }}</span> @enderror
    @endif
    <hr>
    @if ($types)
        @foreach ($types as $key => $value)
            <label><input type="radio" wire:model.defer="type" name="type" value="{{ $key }}"> {{ $value }}</label>
        @endforeach
        @error('type') <span class="error">{{ $message }}</span> @enderror
    @endif
    <hr>
    @for ($i = 0; $i < $repeat; $i++)
        <input type="text" wire:model.defer="name.{{ $i }}"><input type="number" wire:model.defer="amount.{{ $i }}">
        <hr>
    @endfor
    <input type="number" wire:model.defer="count"><button wire:click="addField">add</button>
    <hr>
    <input type="text" wire:model.defer="date" id="js-datepicker">
    <button wire:click="addTransaction">登録する</button>
</div>
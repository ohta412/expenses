<div>
    @if ($categories->isNotEmpty())
        @foreach ($categories as $category)
            <label>
                <input
                    type="checkbox"
                    value="{{ $category->id }}"
                    wire:model.defer="category_ids"
                > {{ $category->name }}
            </label>
        @endforeach
        <button wire:click="deleteCategory">削除する</button>
    @endif
    <input type="text" wire:model.defer="category_name">
    <button wire:click="addCategory">追加する</button>
</div>

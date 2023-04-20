<div>
    <div class="f-center -column">
        @if ($categories->isNotEmpty())
            <div class="radio-wrap">
                @foreach ($categories as $category)
                    <label>
                        <input
                            type="checkbox"
                            value="{{ $category->id }}"
                            wire:model.defer="category_ids"
                        > {{ $category->name }}
                    </label>
                @endforeach
            </div>
            <button class="btn -red mt-4" wire:click="deleteCategory">削除する</button>
        @endif
        <div class="mt-8">
            <input class="form-set" type="text" wire:model.defer="category_name">
            <button class="btn -blue ml-2" wire:click="addCategory">追加する</button>
        </div>
    </div>
</div>
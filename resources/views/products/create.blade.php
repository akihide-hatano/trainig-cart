<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('商品登録') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"> --}}
                        @csrf
                        <div>
                            <label for="name">商品名</label>
                            <input type="text" name="name" id="name" required>
                        </div>
                        <div>
                            <label for="description">説明</label>
                            <textarea name="description" id="description"></textarea>
                        </div>
                        <div>
                            <label for="price">価格</label>
                            <input type="number" name="price" id="price" required>
                        </div>
                        <div>
                            <label for="image">画像</label>
                            <input type="file" name="image" id="image">
                        </div>
                        <button type="submit">登録</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
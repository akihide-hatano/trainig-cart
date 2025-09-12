<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('商品編集') }}
        </h2>
    </x-slot>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- フォームのアクションとメソッドを変更 --}}
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH') {{-- または @method('PATCH') --}}

                        <div class="space-y-4">
                            {{-- 既存のデータを表示 & old()で再入力を保持 --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">商品名</label>
                                <input type="text" name="name" id="name" required
                                    value="{{ old('name', $product->name) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">説明</label>
                                <textarea name="description" id="description"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">価格</label>
                                <input type="number" name="price" id="price" required
                                    value="{{ old('price', $product->price) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">商品画像</label>
                                <input id="image" name="image" type="file" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-600
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100"
                                    onchange="previewFile(this)">
                                <p class="mt-1 text-xs text-gray-500">※ 画像を選択すると下にプレビューが表示されます。</p>

                                {{-- 現在の画像を表示 --}}
                                @if ($product->image_url)
                                    <div class="mt-3">
                                        <p class="text-sm font-medium text-gray-700">現在の画像:</p>
                                        <img src="{{ $product->image_url }}" alt="現在の画像" class="max-w-xs rounded border">
                                    </div>
                                @endif

                                {{-- プレビューエリア --}}
                                <div id="image-preview" class="mt-3">
                                    <img id="preview-img" alt="新しい画像のプレビュー" class="max-w-xs rounded border hidden">
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">更新</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @vite('resources/js/products/image.js');
</x-app-layout>
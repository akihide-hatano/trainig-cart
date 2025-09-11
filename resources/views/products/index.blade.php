<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        商品一覧
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

        {{-- 検索と並び替え --}}
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap items-end gap-3 mb-6">
        <div>
            <label class="block text-sm text-gray-600 mb-1">キーワード</label>
            <input type="search" name="q" value="{{ $q ?? '' }}"
                placeholder="商品名・説明で検索"
                class="w-64 rounded-md border-gray-300" />
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">並び替え</label>
            <select name="sort" class="w-44 rounded-md border-gray-300">
                <option value="new"        @selected(($sort ?? 'new')==='new')>新着順</option>
                <option value="price_asc"  @selected(($sort ?? '')==='price_asc')>価格の安い順</option>
                <option value="price_desc" @selected(($sort ?? '')==='price_desc')>価格の高い順</option>
            </select>
        </div>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            検索
        </button>
        </form>

        {{-- 空表示 --}}
        @if($products->isEmpty())
            <div class="text-center p-10 border-2 border-dashed rounded-xl text-gray-600">
                条件に一致する商品がありませんでした。
            </div>
        @else
        {{-- カードグリッド --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                <article class="border rounded-xl overflow-hidden hover:shadow-sm transition bg-white flex flex-col">
                {{-- 画像は正方形でトリミング --}}
                <div class="w-full aspect-square bg-gray-100">
                    @if ($product->image)
                        @if (Str::startsWith($product->image, ['http://', 'https://']))
                            {{-- URLで保存された画像の場合 --}}
                            <img src="{{ $product->image }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover" />
                        @else
                            {{-- ファイルとしてアップロードされた画像の場合 --}}
                            <img src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover" />
                        @endif
                    @else
                        {{-- 画像が登録されていない場合 --}}
                        <img src="https://via.placeholder.com/600x600?text=No+Image"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover" />
                    @endif
                </div>

                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-semibold line-clamp-2">{{ $product->name }}</h3>
                    <p class="mt-1 text-gray-600 text-sm line-clamp-2">{{ $product->description }}</p>

                    <div class="mt-3 font-bold text-lg">¥{{ number_format($product->price) }}</div>

                {{-- ボタン群 --}}
                <div class="mt-4 flex items-center gap-2">
                    <a href="{{ route('products.show', $product) }}"
                        class="flex-1 text-center px-3 py-2 rounded-md border hover:bg-gray-50">
                        詳細
                    </a>

                    {{-- カートに追加（最小） --}}
                    <form action="{{ route('cart.items.store') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit"
                            class="w-full px-3 py-2 rounded-md bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                        カートに追加
                    </button>
                    </form>
                </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- ページネーション --}}
        <div class="mt-6">
            {{-- {{ $products->links() }} --}}
        </div>
        @endif

    </div>
    </div>
</div>
</x-app-layout>

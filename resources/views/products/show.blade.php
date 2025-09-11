<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('商品詳細') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                {{-- 商品カード --}}
                <div class="max-w-xl mx-auto">
                    <article class="border rounded-xl overflow-hidden bg-white flex flex-col md:flex-row">
                        {{-- 画像は正方形でトリミング --}}
                        <div class="w-full md:w-1/2 aspect-square bg-gray-100">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/600x600?text=No+Image' }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover" />
                        </div>

                        <div class="p-4 flex-1 flex flex-col md:w-1/2">
                            <h3 class="font-semibold text-2xl">{{ $product->name }}</h3>
                            <p class="text-gray"><span class="mr-2">登録日</span>{{$product->created_at->format('Y年m月d日')}}</p>
                            <p class="mt-2 text-gray-600">{{ $product->description }}</p>

                            <div class="mt-4 font-bold text-3xl">¥{{ number_format($product->price) }}</div>

                            {{-- カートに追加フォーム --}}
                            <form action="{{ route('cart.items.store') }}" method="POST" class="mt-6">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="flex items-center gap-2">
                                    <label for="quantity" class="text-gray-600">数量:</label>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="99" class="w-20 rounded-md border-gray-300">
                                </div>
                                <button type="submit"
                                        class="w-full mt-4 px-4 py-2 rounded-md bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                                    カートに追加
                                </button>
                            </form>

                            <div class="mt-6 text-center">
                                <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">
                                    &larr; 商品一覧に戻る
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
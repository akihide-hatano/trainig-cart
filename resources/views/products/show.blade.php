<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        商品詳細
        </h2>
    </x-slot>

    {{-- フラッシュメッセージ --}}
    <x-flash.message />

<div class="py-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

        <div class="max-w-5xl mx-auto">
        <article class="border rounded-xl sm:rounded-none overflow-hidden bg-white grid grid-cols-1 md:grid-cols-2">
            <div class="w-full md:h-full bg-gray-100">
            <img
                src="{{ $product->image ?? 'https://via.placeholder.com/800x800?text=No+Image' }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-contain object-top"
            >
            </div>


            {{-- 本文 --}}
            <div class="p-6 flex flex-col">
              <h1 class="text-2xl font-bold">{{ $product->name }}</h1>

              <p class="mt-2 text-gray-500">
                <span class="mr-2">登録日</span>
                {{ optional($product->created_at)->format('Y年m月d日') }}
              </p>

              <p class="mt-3 text-gray-700 leading-relaxed">
                {{ $product->description ?? '商品説明は準備中です。' }}
              </p>

              <div class="mt-5 text-3xl font-bold">
                ¥{{ number_format((int)$product->price) }}
              </div>

              {{-- カートに追加 + カートを見る --}}
              <div class="mt-6 space-y-3">
                <form action="{{ route('cart.items.store') }}" method="POST" class="flex items-end gap-3">
                  @csrf
                  <input type="hidden" name="product_id" value="{{ $product->id }}">
                  <div>
                    <label for="quantity" class="block text-sm text-gray-600">数量</label>
                    <input id="quantity" type="number" name="quantity" value="1" min="1" max="99"
                           class="w-24 rounded-md border-gray-300">
                  </div>
                  <button type="submit"
                          class="px-4 py-2 rounded-md bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                    カートに追加
                  </button>

                  <a href="{{ route('cart.index') }}"
                     class="ml-auto px-4 py-2 rounded-md border hover:bg-gray-50">
                    カートを見る
                  </a>
                </form>

                <div class="flex items-center gap-2">
                  <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">
                    &larr; 商品一覧に戻る
                  </a>
                </div>
              </div>

            </div>
          </article>
        </div>

      </div>
    </div>
  </div>
</x-app-layout>

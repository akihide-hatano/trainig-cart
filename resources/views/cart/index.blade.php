<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('ショッピングカート') }}
</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">{{ session('error') }}</div>
                @endif

                @if($items->isEmpty())
                    <div class="text-center p-8 border-2 border-dashed border-gray-300 rounded-xl">
                        <p class="text-gray-600 mb-2">カートに商品がありません。</p>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            商品一覧へ
                        </a>
                    </div>
                @else
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($items as $item)
                            <article class="flex gap-4 p-3 border border-gray-200 rounded-xl">
                            <div class="flex-shrink-0 w-24 h-24 md:w-20 md:h-20">
                                <img src="{{ $item->product->image ?? '' }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg bg-gray-100">
                            </div>
                                <div class="flex-1">
                                    <div class="font-bold mb-1">{{ $item->product->name }}</div>
                                    <div class="text-gray-600 text-sm">単価：{{ number_format((int)$item->product->price) }} 円</div>
                                    
                                    {{-- 数量ステッパー（0で削除扱い） --}}
                                    <form method="POST" action="{{ route('cart.items.update', $item) }}" class="flex items-center gap-2 mt-2">
                                        @csrf @method('PATCH')
                                        <button type="button" class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300 text-gray-800 font-bold" aria-label="数量を減らす">−</button>
                                        <input
                                            type="number"
                                            name="quantity"
                                            value="{{ $item->quantity }}"
                                            min="0" max="99"
                                            class="w-16 text-center border-gray-300 rounded-md shadow-sm"
                                        >
                                        <button type="button" class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300 text-gray-800 font-bold" aria-label="数量を増やす">＋</button>
                                        <button type="submit" class="hidden md:inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            更新
                                        </button>
                                    </form>

                                    {{-- 削除ボタン --}}
                                    <form method="POST" action="{{ route('cart.items.destroy', $item) }}" class="mt-2">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold">
                                            削除
                                        </button>
                                    </form>
                                </div>

                                <div class="flex-shrink-0 text-right">
                                    <div class="text-gray-600 text-sm">小計</div>
                                    <div class="font-bold">{{ number_format((int)$item->product->price * (int)$item->quantity) }} 円</div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- 合計サマリー --}}
                    <div class="flex justify-end items-center gap-4 mt-8 sticky bottom-0 bg-white p-4 border-t border-gray-200">
                        <div class="text-xl font-bold">合計：{{ number_format($total) }} 円</div>
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            買い物を続ける
                        </a>
                        {{-- チェックアウト（ルートはご自身の命名に合わせて変更してください） --}}
                        @if(Route::has('orders.checkout'))
                            <form method="POST" action="{{ route('orders.checkout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    レジへ進む
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

</x-app-layout>
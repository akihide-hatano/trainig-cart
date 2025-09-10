<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">注文を編集</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('orders.update', $order) }}" class="space-y-6">
            @csrf
            @method('PATCH')

        <div>
            <label class="block text-sm font-medium text-gray-700">注文番号</label>
            <div class="mt-1 text-gray-900">#{{ $order->id }}</div>
        </div>

        <div class="mt-6">
        <h3 class="text-md font-semibold mb-2">注文内容（参照）</h3>

        @if($order->items->isEmpty())
            <p class="text-gray-500">アイテムがありません。</p>
        @else
            <ul class="divide-y divide-gray-200 border rounded-md">
            @foreach($order->items as $it)
                <li class="flex items-center gap-3 p-3">
                {{-- 画像（任意） --}}
                <img
                    src="{{ $it->product->image ?? 'https://via.placeholder.com/64x64' }}"
                    alt="{{ $it->product->name ?? '商品画像' }}"
                    class="w-12 h-12 object-cover rounded bg-gray-100"
                >
                <div class="flex-1">
                    <div class="font-medium">
                    {{ $it->product->name ?? '商品不明' }}
                    </div>
                    <div class="text-sm text-gray-600">
                    単価 ¥{{ number_format($it->unit_price) }} / 小計 ¥{{ number_format($it->subtotal) }}
                    </div>
                </div>
                <div class="text-sm">
                    数量：<span class="font-semibold">{{ $it->quantity }}</span>
                </div>
                </li>
            @endforeach
            </ul>
            <div class="text-right mt-3 text-sm text-gray-600">
            合計：<span class="font-semibold">¥{{ number_format($order->total_amount) }}</span>
            （{{ $order->items_count ?? $order->items->count() }} 点）
            </div>
        @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">ステータス</label>
            <select name="status" class="mt-1 block w-60 rounded-md border-gray-300">
                @foreach (['pending'=>'保留','paid'=>'支払い済み','shipped'=>'発送済み','cancelled'=>'キャンセル'] as $val => $label)
                    <option value="{{ $val }}" @selected($order->status === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    更新する
                </button>

                <a href="{{ route('orders.show', $order) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    <x-icon-back />
                    <span>詳細へ戻る</span>
                </a>
            </div>
        </form>
        </div>
        </div>
    </div>
</x-app-layout>

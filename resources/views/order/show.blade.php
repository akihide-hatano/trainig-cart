<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            注文詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @php
                // ステータスごとの色マップ（Tailwind）
                $statusClassMap = [
                    'pending'   => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
                    'paid'      => 'bg-green-100 text-green-800 ring-green-600/20',
                    'cancelled' => 'bg-red-100 text-red-800 ring-red-600/20',
                    // もし使うなら
                    'shipped'   => 'bg-blue-100 text-blue-800 ring-blue-600/20',
                ];
                $badgeClass = $statusClassMap[$order->status] ?? 'bg-gray-100 text-gray-800 ring-gray-600/20';
                @endphp

                {{-- 注文情報 --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">注文情報</h3>
                    <p><strong>注文番号:</strong> #{{ $order->id }}</p>
                    <p><strong>注文日:</strong> {{ $order->placed_at->format('Y-m-d H:i') }}</p>
                    <p>
                    <strong>ステータス:</strong>
                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $badgeClass }}">
                        {{ __($order->status) }}
                    </span>
                    </p>
                    <p><strong>合計金額:</strong> ¥{{ number_format($order->total_amount) }}</p>
                </div>

                {{-- 注文アイテム --}}
                <div>
                    <h3 class="text-lg font-semibold mb-2">注文内容</h3>
                    <table class="w-full text-left border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 border">商品</th>
                                <th class="p-2 border text-right">単価</th>
                                <th class="p-2 border text-right">数量</th>
                                <th class="p-2 border text-right">小計</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="p-2 border flex items-center gap-2">
                                        {{-- 商品画像 --}}
                                        <img src="{{ $item->product->image ?? 'https://via.placeholder.com/50x50' }}"
                                             alt="{{ $item->product->name ?? '商品画像' }}"
                                             class="w-24 h-24 object-cover rounded">
                                        <span>{{ $item->product->name ?? '商品不明' }}</span>
                                    </td>
                                    <td class="p-2 border text-right">
                                        ¥{{ number_format($item->unit_price) }}
                                    </td>
                                    <td class="p-2 border text-right">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="p-2 border text-right">
                                        ¥{{ number_format($item->subtotal) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- 戻るリンク --}}
                <div class="mt-6">
                    <a href="{{ route('orders.index') }}"
                       class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        注文一覧に戻る
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      注文履歴
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          @if($orders->isEmpty())
            <p>注文はまだありません。</p>
          @else
            <table class="min-w-full border border-gray-200">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-4 py-2 text-left">注文日</th>
                  <th class="px-4 py-2 text-left">商品数</th>
                  <th class="px-4 py-2 text-left">合計金額</th>
                  <th class="px-4 py-2 text-left">状態</th>
                  <th class="px-4 py-2"></th>
                </tr>
              </thead>
              <tbody>
                @foreach($orders as $order)
                  <tr class="border-t">
                    <td class="px-4 py-2">{{ $order->placed_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">{{ $order->items_count }}</td>
                    <td class="px-4 py-2">¥{{ number_format($order->total_amount) }}</td>
                    <td class="px-4 py-2">
                        <x-status-badge :status="$order->status" />
                    </td>
                    <td class="px-4 py-2">
                            <a href="{{ route('orders.show', $order) }}"
                                class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm font-semibold rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                詳細
                            </a>
                    </td>
                </tr>
                @endforeach
              </tbody>
            </table>

            {{-- ページネーション --}}
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
        </div>
    </div>
    </div>
  </div>
</x-app-layout>

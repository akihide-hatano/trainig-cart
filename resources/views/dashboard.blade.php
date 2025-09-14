<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">ようこそ、{{ $user->name }} さん</h2>
  </x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

      {{-- ① 今日の注文（if で分岐表示） --}}
      <div class="bg-white rounded-xl shadow p-5">
        <h3 class="font-semibold mb-3">今日の注文状況</h3>

        @if($todayPaid->isEmpty() && $todayPending->isEmpty())
          <p class="text-gray-600">本日の注文はまだありません。</p>
        @else
          @if($todayPaid->isNotEmpty())
            <div class="mb-3">
              <div class="text-sm text-green-700 font-semibold mb-1">支払い済み</div>
              <ul class="space-y-1">
                @foreach($todayPaid as $o)
                  <li class="flex justify-between text-sm border-b py-1">
                    <span>#{{ $o->id }} / {{ $o->placed_at->format('H:i') }}</span>
                    <span>¥{{ number_format($o->total_amount) }}</span>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif

          @if($todayPending->isNotEmpty())
            <div>
              <div class="text-sm text-yellow-700 font-semibold mb-1">保留</div>
              <ul class="space-y-1">
                @foreach($todayPending as $o)
                  <li class="flex justify-between text-sm border-b py-1">
                    <span>#{{ $o->id }} / {{ $o->placed_at->format('H:i') }}</span>
                    <span>¥{{ number_format($o->total_amount) }}</span>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
        @endif
      </div>

      {{-- ② 最近の購入履歴（5件） --}}
      <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center justify-between mb-3">
          <h3 class="font-semibold">最近の購入履歴</h3>
          <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:underline text-sm">注文一覧へ</a>
        </div>

        @if($recentOrders->isEmpty())
          <p class="text-gray-600">まだ注文履歴がありません。</p>
        @else
          <table class="w-full text-sm">
            <thead class="text-gray-500">
              <tr>
                <th class="text-left py-2">日時</th>
                <th class="text-right">金額</th>
                <th class="text-left">状態</th>
                <th class="text-right">商品数</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($recentOrders as $o)
                <tr class="border-t">
                  <td class="py-2">{{ $o->placed_at->format('Y-m-d H:i') }}</td>
                  <td class="text-right">¥{{ number_format($o->total_amount) }}</td>
                  <td><x-status-badge :status="$o->status" /></td>
                  <td class="text-right">{{ $o->items_count }}</td>
                  <td class="text-right">
                    <a href="{{ route('orders.show', $o) }}" class="text-indigo-600 hover:underline">詳細</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>

{{-- 新着商品スライダー --}}
<section class="mt-8">
  <div class="flex items-center justify-between mb-3">
    <h3 class="text-lg font-semibold">新着商品</h3>
    <div class="space-x-2">
      <button id="prevBtn" class="px-3 py-1 border rounded">←</button>
      <button id="nextBtn" class="px-3 py-1 border rounded">→</button>
    </div>
  </div>

  <div id="track" class="flex gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth
        [&::-webkit-scrollbar]:h-2 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-track]:bg-gray-100"
    aria-label="新着商品スライダー" >
    @foreach($newProducts as $p)
      <article class="snap-start shrink-0 w-[240px] sm:w-[280px] border rounded-xl overflow-hidden bg-white">
        <div class="w-full aspect-square bg-gray-100">
            <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
        </div>
        <div class="p-3">
            <h4 class="font-semibold line-clamp-2">{{ $p->name }}</h4>
            <div class="mt-1 text-sm text-gray-500">¥{{ number_format($p->price) }}</div>

        <div class="mt-3 flex gap-2">
            <a href="{{ route('products.show', $p) }}"
                class="flex-1 text-center px-3 py-2 rounded border hover:bg-gray-50">
            詳細
            </a>
            <form action="{{ route('cart.items.store') }}" method="POST" class="flex-1">
              @csrf
              <input type="hidden" name="product_id" value="{{ $p->id }}">
              <button class="w-full px-3 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                カート
              </button>
            </form>
          </div>
        </div>
      </article>
    @endforeach
  </div>
</section>

<script>
  (function () {
    const track = document.getElementById('track');
    const prev  = document.getElementById('prevBtn');
    const next  = document.getElementById('nextBtn');

    // 1カード分の幅（gap-4=16px）を計算
    function cardWidth() {
      const card = track.querySelector('article');
      return card ? card.getBoundingClientRect().width + 16 : 300;
    }

    // 手動ナビ
    if(prev){
      prev.addEventListener('click',()=> scrollByOne(-1));
    }
    if(next){
      next.addEventListener('click',()=> scrollByOne(+1));

    }

    function scrollByOne(dir = +1) {
      track.scrollBy({ left: dir * cardWidth(), behavior: 'smooth' });
    }

    // ====== オートプレイ ======
    let timer = null;
    const INTERVAL_MS = 2500; // 何秒ごとに次へ進むか

    function atEnd() {
      return track.scrollLeft + track.clientWidth >= track.scrollWidth - 2;
    }

    function autoplayTick() {
      if (atEnd()) {
        // 末尾まで来たら先頭へ（スナップのガタつきを避けるため instantly）
        track.scrollTo({ left: 0, behavior: 'instant' });
      } else {
        scrollByOne(+1);
      }
    }

    function startAutoplay() {
      // OSが「動きを減らす」設定なら自動再生しない
      const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (prefersReduced) return;
      stopAutoplay();
      timer = setInterval(autoplayTick, INTERVAL_MS);
    }

    function stopAutoplay() {
      if (timer) {
        clearInterval(timer);
        timer = null;
      }
    }

    // マウス乗せ/フォーカスで一時停止、離れたら再開
    track.addEventListener('mouseenter', stopAutoplay);
    track.addEventListener('mouseleave', startAutoplay);
    track.addEventListener('focusin',    stopAutoplay);
    track.addEventListener('focusout',   startAutoplay);

    // タブが非表示のときは止める
    document.addEventListener('visibilitychange', () => {
      document.hidden ? stopAutoplay() : startAutoplay();
    });

    // スワイプ/ドラッグ中は止める → 終了後に再開
    let isPointerDown = false;
    track.addEventListener('pointerdown', () => { isPointerDown = true; stopAutoplay(); });
    window.addEventListener('pointerup', () => { if (isPointerDown) { isPointerDown = false; startAutoplay(); } });

    // 初期起動
    startAutoplay();
  })();
</script>


    </div>
  </div>
</x-app-layout>

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
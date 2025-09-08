document.addEventListener("DOMContentLoaded", () => {
  // すべての「数量変更フォーム」を探す
  document.querySelectorAll(".stepper-form").forEach((form) => {
    const input = form.querySelector('input[name="quantity"]');
    const inc = form.querySelector(".js-inc");
    const dec = form.querySelector(".js-dec");

    // 値を 0〜99 の間に収める関数
    const clamp = (value) => {
      let num = parseInt(value || "0", 10);
      if (isNaN(num)) num = 0;
      return Math.max(0, Math.min(99, num));
    };

    // プラスボタン
    inc?.addEventListener("click", () => {
      input.value = clamp(parseInt(input.value, 10) + 1);
      form.submit(); // 自動で送信
    });

    // マイナスボタン
    dec?.addEventListener("click", () => {
      input.value = clamp(parseInt(input.value, 10) - 1);
      form.submit(); // 自動で送信
    });
  });
});

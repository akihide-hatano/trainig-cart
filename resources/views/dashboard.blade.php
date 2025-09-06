<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ダッシュボード</title>
</head>
<body>
  <h1>ダッシュボード</h1>

  <p>ようこそ、{{ auth()->user()->name }} さん！</p>

  {{-- ログアウト --}}
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">ログアウト</button>
  </form>

  {{-- お試しリンク（任意） --}}
  <p><a href="/">トップへ戻る</a></p>
  <p><a href="/cart">カートへ</a></p>
</body>
</html>

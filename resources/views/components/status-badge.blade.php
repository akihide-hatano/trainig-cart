@props(['status'])

@php
    // 色マッピング
    $map = [
        'pending'   => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
        'paid'      => 'bg-green-100 text-green-800 ring-green-600/20',
        'cancelled' => 'bg-red-100 text-red-800 ring-red-600/20',
        'shipped'   => 'bg-blue-100 text-blue-800 ring-blue-600/20',
    ];
    $cls = $map[$status] ?? 'bg-gray-100 text-gray-800 ring-gray-600/20';

    // 日本語ラベルマッピング
    $labels = [
        'pending'   => '保留',
        'paid'      => '支払い済み',
        'cancelled' => 'キャンセル',
        'shipped'   => '発送済み',
    ];
    $label = $labels[$status] ?? $status;
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset $cls"
    ]) }}>
    {{ $label }}
</span>

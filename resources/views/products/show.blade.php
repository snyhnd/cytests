@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">商品情報詳細画面</h2>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">ID</label>
        <div class="col-sm-10 pt-2">{{ $product->id }}</div>
    </div>

    <div class="row mb-3 align-items-start">
        <label class="col-sm-2 col-form-label">商品画像</label>
        <div class="col-sm-10">
            <div style="border: 1px solid #ccc; padding: 10px; min-height: 150px; height: auto;">
                @if ($product->img_path)
                    <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" class="img-fluid" style="max-height: 130px;">
                @else
                    <p>画像</p>
                @endif
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">商品名</label>
        <div class="col-sm-10 pt-2">{{ $product->product_name }}</div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">メーカー</label>
        <div class="col-sm-10 pt-2">{{ $product->company->company_name }}</div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">価格</label>
        <div class="col-sm-10 pt-2">¥{{ number_format($product->price) }}</div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">在庫数</label>
        <div class="col-sm-10 pt-2">{{ $product->stock }}</div>
    </div>

    <div class="row mb-4">
        <label class="col-sm-2 col-form-label">コメント</label>
        <div class="col-sm-10">
            <div style="border: 1px solid #ccc; padding: 10px; min-height: 100px;">
                {!! nl2br(e($product->comment)) !!}
            </div>
        </div>
    </div>

    <div class="mb-3">
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary me-2">編集</a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
    </div>
</div>
@endsection
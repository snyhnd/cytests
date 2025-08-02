@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">商品新規登録画面</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 商品名 --}}
        <div class="form-group row">
            <label for="product_name" class="col-sm-2 col-form-label">商品名<span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" name="product_name" id="product_name" class="form-control" required>
            </div>
        </div>

        {{-- メーカー名 --}}
        <div class="form-group row mt-3">
            <label for="company_id" class="col-sm-2 col-form-label">メーカー名<span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <select name="company_id" id="company_id" class="form-control" required>
                    <option value="" disabled selected>選択してください</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- 価格 --}}
        <div class="form-group row mt-3">
            <label for="price" class="col-sm-2 col-form-label">価格<span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="number" name="price" id="price" class="form-control" required>
            </div>
        </div>

        {{-- 在庫数 --}}
        <div class="form-group row mt-3">
            <label for="stock" class="col-sm-2 col-form-label">在庫数<span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="number" name="stock" id="stock" class="form-control" required>
            </div>
        </div>

        {{-- コメント --}}
        <div class="form-group row mt-3">
            <label for="comment" class="col-sm-2 col-form-label">コメント</label>
            <div class="col-sm-10">
                <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
            </div>
        </div>

        {{-- 商品画像 --}}
        <div class="form-group row mt-3">
            <label for="img_path" class="col-sm-2 col-form-label">商品画像</label>
            <div class="col-sm-10">
                <label class="btn btn-secondary">
                    ファイルを選択
                    <input type="file" name="img_path" id="img_path" style="display: none;">
                </label>
            </div>
        </div>

        {{-- ボタン --}}
        <div class="form-group row mt-4">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">新規登録</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
            </div>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">商品情報編集画面</h2>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <label class="col-md-3 col-form-label">ID</label>
            <div class="col-md-9 d-flex align-items-center">
                {{ $product->id }}
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-md-3 col-form-label">商品名 <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}">
                @if($errors->has('product_name'))
                        <p>{{ $errors->first('product_name') }}</p>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-md-3 col-form-label">メーカー名 <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select name="company_id" class="form-select">
                    <option value="">選択してください</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ $product->company_id == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('company_id'))
                        <p>{{ $errors->first('company_id') }}</p>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-md-3 col-form-label">価格 <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}">
                @if($errors->has('price'))
                        <p>{{ $errors->first('price') }}</p>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-md-3 col-form-label">在庫数 <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
                @if($errors->has('stock'))
                        <p>{{ $errors->first('stock') }}</p>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-md-3 col-form-label">コメント</label>
            <div class="col-md-9">
                <textarea name="comment" class="form-control" rows="3">{{ old('comment', $product->comment) }}</textarea>
                @if($errors->has('comment'))
                        <p>{{ $errors->first('comment') }}</p>
                @endif
            </div>
        </div>

        <div class="row mb-4">
            <label class="col-md-3 col-form-label">商品画像</label>
            <div class="col-md-9">
                <label class="btn btn-secondary">
                    ファイルを選択
                    <input type="file" name="img_path" id="img_path" style="display: none;">
                </label>
                @if ($product->img_path)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" class="img-fluid" style="max-height: 150px;">
                    </div>
                @endif
                @if($errors->has('img_path'))
                        <p>{{ $errors->first('img_path') }}</p>
                @endif
            </div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary me-2">更新</button>
            <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary">戻る</a>
        </div>
    </form>
</div>
@endsection

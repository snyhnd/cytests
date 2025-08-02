@extends('layouts.app')

@section('content')
<div class="container">
    <h2>商品一覧画面</h2>

    {{-- 検索フォーム --}}
    <form action="{{ route('products.index') }}" method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="keyword" class="form-control"
                   placeholder="検索キーワード" value="{{ request('keyword') }}">
        </div>
        <div class="col-md-4">
            <select name="company_id" class="form-select">
                <option value="">メーカー名</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}"
                        {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">検索</button>
        </div>
    </form>

    {{-- 商品一覧 --}}
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>メーカー名</th>
                <th>
                    <a href="{{ route('products.create') }}" class="btn btn-sm btn-success">新規登録</a>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if ($product->img_path)
                            <img src="{{ asset('storage/' . $product->img_path) }}"
                                 alt="商品画像" width="80" height="80">
                        @else
                            <span>商品画像</span>
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ number_format($product->price) }}円</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company->company_name ?? '不明' }}</td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">詳細</a>
                        <form action="{{ route('products.destroy', $product->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">削除</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">該当する商品がありません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ページネーション --}}
    <div class="d-flex justify-content-center">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection

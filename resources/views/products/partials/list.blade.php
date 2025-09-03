 <table class="table table-striped table-bordered align-middle">
   <thead class="table-light">
        <tr>
            <th class="sortable" data-sort="id">
                ID {!! ($sort ?? '') === 'id' ? ($direction === 'asc' ? '▲' : '▼') : '' !!}
            </th>
            <th>商品画像</th>
            <th class="sortable" data-sort="product_name">
                商品名 {!! ($sort ?? '') === 'product_name' ? ($direction === 'asc' ? '▲' : '▼') : '' !!}
            </th>
            <th class="sortable" data-sort="price">
                価格 {!! ($sort ?? '') === 'price' ? ($direction === 'asc' ? '▲' : '▼') : '' !!}
            </th>
            <th class="sortable" data-sort="stock">
                在庫数 {!! ($sort ?? '') === 'stock' ? ($direction === 'asc' ? '▲' : '▼') : '' !!}
            </th>
            <th>メーカー名</th>
            <th>
                <a href="{{ route('products.create') }}" class="btn btn-sm btn-success">新規登録</a>
            </th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $product)
            <tr id="row-{{ $product->id }}">
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
                    <a href="{{ route('products.show', $product->id) }}"
                       class="btn btn-info btn-sm">詳細</a>

                    {{-- 非同期削除ボタン（フォームは使わない） --}}
                    <button type="button"
                            class="btn btn-danger btn-sm btn-delete"
                            data-id="{{ $product->id }}">
                        削除
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">該当する商品がありません。</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {!! $products->links() !!}
</div>
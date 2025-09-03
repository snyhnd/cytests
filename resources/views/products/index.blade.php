@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
<div class="container">
    <h2 class="mb-3">商品一覧</h2>

    {{-- 検索フォーム （Ajax用） --}}
    <form id="search-form" class="row g-2 mb-3"method="GET"data-index-url="{{ route('products.index') }}">
        <div class="col-md-3">
            <input type="text" name="keyword" class="form-control"
                   placeholder="検索キーワード" value="{{ request('keyword') }}">
        </div>

        <div class="col-md-3">
            <select name="company_id" class="form-select">
                <option value="">メーカー名</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 価格範囲 --}}
        <div class="col-md-2">
            <input type="number" name="price_min" class="form-control"
                   placeholder="価格 最小" value="{{ request('price_min') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="price_max" class="form-control"
                   placeholder="価格 最大" value="{{ request('price_max') }}">
        </div>

        {{-- 在庫範囲 --}}
        <div class="col-md-2">
            <input type="number" name="stock_min" class="form-control"
                   placeholder="在庫 最小" value="{{ request('stock_min') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="stock_max" class="form-control"
                   placeholder="在庫 最大" value="{{ request('stock_max') }}">
        </div>

        {{-- ソート状態（hiddenで持つ） --}}
        <input type="hidden" name="sort" value="{{ $sort ?? 'id' }}">
        <input type="hidden" name="direction" value="{{ $direction ?? 'desc' }}">

        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">検索</button>
        </div>
    </form>

    {{-- 一覧（部分置換エリア） --}}
    <div id="list-container">
        @include('products.partials.list', [
            'products' => $products,
            'sort' => $sort ?? 'id',
            'direction' => $direction ?? 'desc'
        ])
    </div>
</div>


{{-- このページ専用の外部JSを差し込む --}}
@push('scripts')
    <script src="{{ asset('js/products/index.js') }}"></script>
@endpush

{{-- 一覧用スクリプト --}}
<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // 共通：一覧を非同期再描画
    function fetchList(params) {
        const url = "{{ route('products.index') }}";
        return $.get(url, params).done(function (html) {
            $('#list-container').html(html);
        });
    }

    // 検索フォーム送信
    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        const params = $(this).serialize();
        fetchList(params);
    });

    // ページネーション（イベント委譲）
    $(document).on('click', '#list-container .pagination a', function (e) {
        e.preventDefault();
        const href = $(this).attr('href');
        // href のクエリをそのまま GET
        $.get(href).done(function (html) {
            $('#list-container').html(html);
        });
    });

    // ソートヘッダ
    $(document).on('click', '.sortable', function () {
        const field = $(this).data('sort');
        const current = $('input[name="sort"]').val();
        let direction = $('input[name="direction"]').val();

        if (current === String(field)) {
            direction = (direction === 'asc') ? 'desc' : 'asc';
        } else {
            direction = 'asc';
        }
        $('input[name="sort"]').val(field);
        $('input[name="direction"]').val(direction);

        const params = $('#search-form').serialize();
        fetchList(params);
    });

    // 非同期削除
    $(document).on('click', '.btn-delete', function () {

        const id = $(this).data('id');
        $.ajax({
            url: "{{ url('/products') }}/" + id,
            type: 'POST',
            data: {_method: 'DELETE'},
        }).done(function (res) {
            if (res.ok) {
                $('#row-' + id).remove();
            } else {
                alert(res.message || '削除に失敗しました');
            }
        }).fail(function () {
            alert('削除に失敗しました');
        });
    });
});
</script>
@endsection

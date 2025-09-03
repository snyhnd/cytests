$(document).ready(function () {
  // CSRFトークンを Ajax に自動付与
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  /**
   * 商品一覧の検索
   */
  function searchProducts() {
    $.ajax({
      url: '/cytests/public/api/products',
      type: 'GET',
      data: $('#search-form').serialize(),
      success: function (response) {
        var results = $('#search-results');
        results.empty();

        if (response.products.length > 0) {
          var table = $('<table>').addClass('table tablesorter');
          var thead = $('<thead>').append(
            '<tr>' +
              '<th class="sortable">ID</th>' +
              '<th>商品画像</th>' +
              '<th class="sortable">商品名</th>' +
              '<th class="sortable">価格</th>' +
              '<th class="sortable">在庫数</th>' +
              '<th class="sortable">メーカー名</th>' +
              '<th></th>' +
              '<th></th>' +
            '</tr>'
          );
          table.append(thead);

          var tbody = $('<tbody>');
          response.products.forEach(function (product) {
            var row = $('<tr>');

            row.append('<td>' + product.id + '</td>');

            if (product.img_path) {
              row.append('<td><img src="/cytests/public/storage/productImages/' + product.img_path +
                         '" style="width: 100px; height: 100px; object-fit: cover;"></td>');
            } else {
              row.append('<td>商品画像なし</td>');
            }

            row.append('<td>' + product.product_name + '</td>');
            row.append('<td>' + product.price + '</td>');
            row.append('<td>' + product.stock + '</td>');
            row.append('<td>' + product.company_name + '</td>');

            var showUrl = "/cytests/public/products/" + product.id;
            row.append('<td><a href="' + showUrl + '"><button>詳細</button></a></td>');

            row.append('<td><button class="deletebutton" type="button" data-product-id="' + product.id + '">削除</button></td>');

            tbody.append(row);
          });

          table.append(tbody);
          results.append(table);

          // tablesorter 初期化
          $(".tablesorter").tablesorter({
            headers: {
              1: { sorter: false },
              6: { sorter: false },
              7: { sorter: false }
            }
          });
        } else {
          results.append('<p>該当する商品はありません。</p>');
        }
      },
      error: function (xhr) {
        console.error('検索エラー:', xhr.responseText);
        alert('検索に失敗しました');
      }
    });
  }

  // 検索フォーム送信
  $('#search-form').on('submit', function (event) {
    event.preventDefault();
    searchProducts();
  });

  // 初期表示
  searchProducts();

  /**
   * 削除処理
   */
  $(document).on('click', ".deletebutton", function () {
    if (!confirm('本当に削除してもよろしいですか？')) return;

    const productId = $(this).data("product-id");
    const $row = $(this).closest('tr');

    $.ajax({
      url: '/cytests/public/products/' + productId,
      type: 'POST',
      data: { _method: 'DELETE' },
      success: function (response) {
        if (response.ok) {
          $row.remove();
          alert('削除が成功しました');
        } else {
          alert(response.message || '削除に失敗しました');
        }
      },
      error: function (xhr) {
        console.error('削除エラー:', xhr.responseText);
        alert('削除に失敗しました');
      }
    });
  });
});
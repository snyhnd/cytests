<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function store(Request $request)
    
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            DB::beginTransaction();

            // 商品取得
            $product = Product::findOrFail($request->product_id);

            // 在庫チェック
            if ($product->stock < 1) {
                return response()->json([
                    'ok'      => false,
                    'message' => '在庫が不足しています'
                ], 400);
            }

            // sales テーブルに購入履歴を追加
            Sale::create([
                'product_id' => $product->id,
            ]);

            // 在庫を1減らす
            $product->decrement('stock', 1);

            DB::commit();

            return response()->json([
                'ok'      => true,
                'message' => '購入完了しました'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'ok'      => false,
                'message' => '購入処理に失敗しました'
            ], 500);
        }
    }
}

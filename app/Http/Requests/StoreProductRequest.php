<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 商品新規登録のためのバリデーションリクエストクラス
 */

class StoreProductRequest extends FormRequest
{
    /**
     * 認可設定
     *
     * @return bool 認可する場合は true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルールを定義
     *
     *  @return array<string, mixed> 各入力項目に対応するバリデーションルール
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'comment' => 'nullable|string|max:10',
            'img_path' => 'nullable|image|max:2048',
        ];
    }

    /**
     * バリデーションエラーメッセージを定義
     *
     * @return array<string, string> 各バリデーションルールに対応するエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'product_name.required' => '商品名は必須項目です。',
            'company_id.required' => 'メーカー名は必須です。',
            'company_id.exists' => '選択されたメーカーは存在しません。',
            'price.required' => '価格は必須です。',
            'price.integer' => '価格は整数で入力してください。',
            'stock.required' => '在庫数は必須です。',
            'stock.integer' => '在庫数は整数で入力してください。',
            'comment.max' => 'コメントは10文字以内に入力してください。',
            'img_path.image' => '画像ファイルを選択してください。',
            'img_path.max' => '画像サイズは2MB以下にしてください。',
        ];
    }

    /**
     * 項目名の属性ラベルを定義
     *
     * @return array<string, string> 項目キーと表示名の対応
     */
    public function attributes(): array
    {
        return [
            'product_name' => '商品名',
            'company_id' => 'メーカー名',
            'price' => '価格',
            'stock' => '在庫数',
            'comment' => 'コメント',
            'img_path' => '商品画像',
        ];
    }
}
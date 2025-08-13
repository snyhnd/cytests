<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 商品更新のためのバリデーションリクエストクラス
 */
class UpdateProductRequest extends FormRequest
{
    /**
     * このリクエストをユーザーが実行できるかを判定
     *
     * @return bool 認可する場合はtrue
     */
    public function authorize(): bool
    {
        return true; // 認証済みユーザーに制限する場合は条件を追加
    }

    /**
     * バリデーションルールを定義
     *
     * @return array<string, mixed> バリデーションルール配列
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'comment' => 'nullable|string|max:10',
            'img_path' => 'nullable|image|max:2048',
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

    /**
     * バリデーションエラーメッセージを定義
     *
     * @return array<string, string> バリデーションルールに対応するエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'product_name.required' => '商品名は必須項目です。',
            'company_id.required' => 'メーカー名を選択してください。',
            'company_id.exists' => '選択されたメーカーは存在しません。',
            'price.required' => '価格は必須です。',
            'price.integer' => '価格は整数で入力してください。',
            'price.min' => '価格は0以上でなければなりません。',
            'stock.required' => '在庫数は必須です。',
            'stock.integer' => '在庫数は整数で入力してください。',
            'stock.min' => '在庫数は0以上でなければなりません。',
            'comment.max' => 'コメントは10文字以内に入力してください。',
            'img_path.image' => 'アップロードできるのは画像ファイルのみです。',
            'img_path.max' => '画像サイズは2MB以下にしてください。',
        ];
    }
}
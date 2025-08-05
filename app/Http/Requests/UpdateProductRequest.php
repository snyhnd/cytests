<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'company_id' => 'required|exists:companies,id',
            'img_path' => 'nullable|image|max:2048',
        ];
    }

    public function attributes()
    {
        return [
            'product_name' => '商品名',
            'price' => '価格',
            'stock' => '在庫数',
            'company_id' => 'メーカー',
            'img_path' => '商品画像',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute は必須です。',
            'integer' => ':attribute は数値で入力してください。',
            'max' => ':attribute は :max 文字以内で入力してください。',
            'image' => ':attribute は画像ファイルを選択してください。',
            'exists' => '選択された :attribute は存在しません。',
        ];
    }
}
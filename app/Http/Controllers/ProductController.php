<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('company');

        // 商品名の部分一致検索
        if ($request->filled('keyword')) {
            $query->where('product_name', 'like', '%' . $request->keyword . '%');
        }

        // 会社IDでの絞り込み
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $products = $query->paginate(3)->appends($request->query());
        $companies = Company::all();

        return view('products.index', compact('products', 'companies'));
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('products.index')->with('success', '商品を削除しました。');
    }

    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'company_id' => 'required|exists:companies,id',
            'img_path' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('img_path')) {
            $path = $request->file('img_path')->store('images', 'public');
            $validated['img_path'] = $path;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', '商品を登録しました。');
    }

    public function show($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($id);

        $product->product_name = $request->product_name;
        $product->company_id = $request->company_id;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->comment = $request->comment;

        if ($request->hasFile('img_path')) {
            $path = $request->file('img_path')->store('images', 'public');
            $product->img_path = $path;
        }

        $product->save();

        return redirect()->route('products.show', $product->id)->with('success', '商品情報を更新しました。');
    }
}
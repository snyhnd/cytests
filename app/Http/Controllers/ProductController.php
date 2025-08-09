<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

/**
 * 商品管理に関する操作を行うコントローラ
 */
class ProductController extends Controller
{
    /**
     * 商品一覧を表示
     *
     * @param Request $request 検索キーワード・メーカーIDなどのクエリパラメータ
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Product::with('company');

        if ($request->filled('keyword')) {
            $query->where('product_name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $products = $query->paginate(3)->appends($request->query());
        $companies = Company::all();

        return view('products.index', compact('products', 'companies'));
    }

    /**
     * 商品作成フォームを表示
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    /**
     * 新しい商品を登録
     *
     * @param StoreProductRequest $request バリデーション済みの商品データ
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            if ($request->hasFile('img_path')) {
                $path = $request->file('img_path')->store('images', 'public');
                $data['img_path'] = $path;
            }

            Product::create($data);

            DB::commit();
            return redirect()->route('products.index')->with('success', '商品を登録しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => '商品登録中にエラーが発生しました。']);
        }
    }

    /**
     * 商品詳細を表示
     *
     * @param int $id 商品ID
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * 商品編集フォームを表示
     *
     * @param int $id 商品ID
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    /**
     * 商品情報を更新
     *
     * @param UpdateProductRequest $request バリデーション済みの商品データ
     * @param int $id 商品ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProductRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($id);
            $data = $request->validated();

            if ($request->hasFile('img_path')) {
                $path = $request->file('img_path')->store('images', 'public');
                $data['img_path'] = $path;
            }

            $product->update($data);

            DB::commit();
            return redirect()->route('products.show', $product->id)->with('success', '商品情報を更新しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => '商品更新中にエラーが発生しました。']);
        }
    }

    /**
     * 商品を削除
     *
     * @param int $id 商品ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->route('products.index')->with('success', '商品を削除しました。');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->withErrors(['error' => '商品削除中にエラーが発生しました。']);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
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

        // キーワード
        if ($request->filled('keyword')) {
            $query->where('product_name', 'like', '%' . $request->keyword . '%');
        }

        // メーカー
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // 価格範囲
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (int)$request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (int)$request->price_max);
        }

        // 在庫範囲
        if ($request->filled('stock_min')) {
            $query->where('stock', '>=', (int)$request->stock_min);
        }
        if ($request->filled('stock_max')) {
            $query->where('stock', '<=', (int)$request->stock_max);
        }

        // ソート（許可フィールドのみ）
        $allowedSorts = ['id', 'product_name', 'price', 'stock'];
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');
        if (!in_array($sort, $allowedSorts, true)) $sort = 'id';
        if (!in_array(strtolower($direction), ['asc', 'desc'], true)) $direction = 'desc';

        $query->orderBy($sort, $direction);


        $products = $query->paginate(3)->appends($request->query());
        $companies = Company::all();

        // Ajax のときは一覧部分のみ返す
        if ($request->ajax()) {
            return view('products.partials.list', compact('products', 'sort', 'direction'))->render();
        }

        return view('products.index', compact('products', 'companies', 'sort', 'direction'));
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
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('store error', ['e' => $e]);
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

            // バリデーションを実行
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            // 他のフィールドのバリデーションルールを追加
        ]);

        // 更新対象の商品を取得
        $product = Product::findOrFail($id);

        // フォームから受け取ったデータで商品情報を更新
            $product->product_name = $request->input('product_name');
            $product->price = $request->input('price');
            $product->stock = $request->input('stock');
            $product->company_id = $request->input('company_id');
            $product->comment = $request->input('comment');
            if($request->hasFile('img_path')) {
                $path=$request->file('img_path')->store('public/productimages');
                $product->img_path=basename($path);
            }
        // 他の更新するフィールドがあれば同様に追加

            $product->update($data);

            DB::commit();
            return redirect()->route('products.show', $product->id)->with('success', '商品情報を更新しました。');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('update error', ['e' => $e]);
            return back()->withInput()->withErrors(['error' => '商品更新中にエラーが発生しました。']);
        }
    }

    /**
     * 商品を削除
     *
     * @param int $id 商品ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            if ($request->ajax()) {
                return response()->json(['ok' => true, 'id' => (int)$id]);
            }

            return redirect()->route('products.index')->with('success', '商品を削除しました。');
        } catch (\Throwable $e) {
            
            Log::error('destroy error', ['e' => $e]);
            if ($request->ajax()) {
                return response()->json(['ok' => false, 'message' => '削除に失敗しました。'], 500);
            }
            return redirect()->route('products.index')->withErrors(['error' => '商品削除中にエラーが発生しました。']);
        }
    }
}
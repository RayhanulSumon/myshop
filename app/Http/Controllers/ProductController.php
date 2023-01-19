<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function index()
    {
        //$products = Product::orderBy('name','ASC')->paginate(10);
        return new ProductCollection(Product::orderBy('name','ASC')->where('user_id', '=', Auth::id())->paginate(10));
    }

    public function search($field,$query)
    {
        return new ProductCollection(Product::where($field,'LIKE',"%$query%")->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $this->authorize('isAdmin');
            $this->validate($request, [
                'name' => 'required|string|max:191',
                'quantity' => 'sometimes|numeric|min:0',
                'stock_price' => 'required|numeric|min:0',
            ]);

            // Store data
            return Product::create([
                'user_id' => Auth::id(),
                'name' => $request['name'],
                'quantity' => $request['quantity'],
                'stock_price' => $request['stock_price'],
            ]);


    }

    public function update(Request $request, $id)
    {
        $this->authorize('isAdmin');
            $product = Product::findOrFail($id);

            if ($product->user_id=== Auth::id() ){
                $this->validate($request, [
                    'name' => 'required|string|max:191',
                    'quantity' => 'sometimes|numeric',
                    'stock_price' => 'required|numeric',
                ]);

                $product->update($request->all());
            }




    }
    public function outstock(){
        return new ProductCollection(Product::orderBy('name','ASC')
            ->where('quantity','<=',"0")
            ->where('user_id','=',Auth::id())
            ->paginate(100)
        );
    }
    public function destroy($id)
    {
        $this->authorize('isAdmin');

            $product = Product::findOrFail($id);

        if ($product->user_id=== Auth::id() ){
            Product::destroy($id);
        }


    }
}

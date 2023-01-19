<?php

namespace App\Http\Controllers;

use App\Http\Resources\SellCollection;
use App\Models\Product;
use App\Models\Sell;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SellController extends Controller
{
    public function getSell(Request $request)
        {
                $para = $request->para;

                switch ($para) {
                    case "today":
                        $products = DB::table('sells')
                            ->where(DB::raw('date(created_at)'), Carbon::today())
                            ->where('user_id','=',Auth::id())
                            ->orderBy('name')
                            ->paginate(10);
                        return new SellCollection($products);
                        break;
                    case "monthly":
                        $products = DB::table('sells')
                            ->where('created_at', '>=', Carbon::now()->startOfMonth())
                            ->where('user_id','=',Auth::id())
                            ->orderBy('name')
                            ->paginate(10);
                        return new SellCollection($products);
                        break;
                    case "all":
                        $products = DB::table('sells')
                            ->orderBy('name')
                            ->where('user_id','=',Auth::id())
                            ->paginate(10);
                        return new SellCollection($products);
                        break;
                    default:
                        $products = DB::table('sells')
                            ->where(DB::raw('date(created_at)'), Carbon::today())
                            ->where('user_id','=',Auth::id())
                            ->orderBy('name')
                            ->paginate(10);
                        return new SellCollection($products);;
                }

        }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'category' => 'string|max:191',
            'qty' => 'required|lte:quantity|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
        ]);
        $stock_price = $request['qty']*$request['stock_price'];
        $sell_price = $request['sell_price'];
        $profit = $sell_price - $stock_price;

        $q = $request['qty'];
        $id = $request['id'];
        // Store data
        Sell::create([
            'name' => $request['name'],
            'user_id' => Auth::id(),
            'category' => $request['category'],
            'quantity' => $q,
            'sell_price' => $request['sell_price'],
            'profit' => $profit,
        ]);

        return Product::where('id', $id)->decrement('quantity', $q);

    }
    public function search($field,$query)
    {
        if (Gate::allows('isAdmin', auth()->user())) {
            return new SellCollection(Sell::where($field,'LIKE',"%$query%")->latest()->paginate(10));

        }else{
            return new SellCollection(Sell::where($field,'LIKE',"%$query%")->where(DB::raw('date(created_at)'), Carbon::today())->latest()->paginate(10));

        }
    }
    public function sell_stats(){
        $todaySell = DB::table('sells')
            ->where(DB::raw('date(created_at)'), Carbon::today())
            ->where('user_id','=',Auth::id())
            ->sum('sell_price');
        $monthlysell = DB::table('sells')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->where('user_id','=',Auth::id())
            ->sum('sell_price');
        $todayprofit = DB::table('sells')
            ->where(DB::raw('date(created_at)'), Carbon::today())
            ->where('user_id','=',Auth::id())
            ->sum('profit');
        $monthlyprofit = DB::table('sells')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->where('user_id','=',Auth::id())
            ->sum('profit');

        return response()->json(['todaysell' => $todaySell, 'monthlysell' => $monthlysell,'todayprofit' =>$todayprofit ,'monthlyprofit' =>$monthlyprofit ]);
    }
    public function destroy($id)
    {
        if (Gate::allows('isAdmin', auth()->user())) {
            $sell = Sell::findOrFail($id);

            Sell::destroy($id);
        }else{
            $sell = Sell::findOrFail($id);
            if ($sell->created_at = Carbon::today()){
                Sell::destroy($id);
            }

        }


    }
}

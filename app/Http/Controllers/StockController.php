<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Stock;
use App\Models\DailyFeed;

use Auth;

class StockController extends Controller
{
  /**
  * Show the accounts grid view.
  *
  * @return Response
  */
  public function index(Request $request)
  {
    $search=null;
    $sort=null;
    $order=null;
    $status=null;
    $checks=Auth::user()->checkSummary()->paginate(50);
    return view('reporting.checks.index',
      compact('checks','search','sort','order','status')
    );
  }

  /**
  * Show the accounts grid view.
  *
  * @return Response
  */
  public function show($ticker)
  {
    $stock=Stock::where('ticker',$ticker)->first();

    $history=DailyFeed::where('stock_id',$stock->ticker)->orderBy('date','ASC')->pluck('close','date');

    $max=DailyFeed::where('stock_id',$stock->ticker)->max('high');

    $start=DailyFeed::where('stock_id',$stock->ticker)->orderBy('date','ASC')->first();

    return view('future.show',
      compact('stock','history','max','start')
    );
  }
}

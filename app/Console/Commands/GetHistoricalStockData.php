<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stock;
use App\Models\DailyFeed;

class GetHistoricalStockData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetHistData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $this->info("...");
       if($this->confirm('Do you wish to continue?'))
       {

           $this->info("Downloading historical financials...");
           $numberOfStocks = Stock::count();
           $this->output->progressStart($numberOfStocks);

           foreach(Stock::orderBy('ticker','DESC')->take(43607)->skip(2800)->pluck('ticker') as $key => $stockCode){

               if(!DailyFeed::where(['stock_id' => $stockCode, 'date' => '2016-09-25'])->first()){
                   $historicalSheetUrl = "http://real-chart.finance.yahoo.com/table.csv?s=".$stockCode."&a=01&b=01&c=2000&d=09&e=25&f=2016&g=d&ignore=.csv";

                   if(get_headers($historicalSheetUrl, 1)[0] == 'HTTP/1.1 200 OK')
                   {
                       file_put_contents('storage/files/spreadsheet.txt', trim(str_replace("Date,Open,High,Low,Close,Volume,Adj Close", "", file_get_contents($historicalSheetUrl))));
                       $spreadSheetFile = fopen('storage/files/spreadsheet.txt', 'r');
                       $dailyTradeRecord = array();
                       while(!feof($spreadSheetFile)){
                           $line = fgets($spreadSheetFile);
                           $pieces = explode(',', $line);
                           array_push($dailyTradeRecord, array(
                               'stock_id' => $stockCode,
                               'date' => $pieces[0],
                               'open' => $pieces[1],
                               'high' => $pieces[2],
                               'low' => $pieces[3],
                               'close' => $pieces[4],
                               'volume' => $pieces[5],
                               'adj_close' => $pieces[6],
                               'created_at' => date("Y-m-d H:i:s"),
                               'updated_at' => date("Y-m-d H:i:s")
                           ));
                       }
                       //\DB::table('historicals')->where('ticker', $stockCode)->delete();
                       \DB::table('daily_feeds')->insert($dailyTradeRecord);
                   }

               }
               $this->output->progressAdvance();
           }
           $this->output->progressFinish();
           $this->info("All historical data has been downloaded.");
       }

    }
}

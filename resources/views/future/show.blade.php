@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h2 class="text-center">What if you purchased {{$stock->name}}<br> on {{date('l M m, Y',strtotime($start->date))}}</h2>
            <div class="panel panel-default panel-body">
              <chartDiv>
                  <!-- Render Chart -->
                  <graph
                    :labels="{{$history->keys()}}"
                    :values="{{$history->values()}}"
                    :color="blue"
                  ></graph>

              </chartDiv>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="panel panel-default panel-body text-center">
                    <h2>${{number_format($start->close,2)}}</h2>
                    <p>
                      Starting Share Price
                    </p>
                </div>
              </div>

              <div class="col-md-4">
                <div class="panel panel-default panel-body text-center">
                  <h2>${{number_format($max,2)}}</h2>
                    <p>
                      Max Share Price
                    </p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="panel panel-default panel-body text-center">
                    <h2 style="color:green">${{number_format( (($max - $start->close) * 1000) - 1000,2)}}</h2>
                    <p>
                      Max Profit
                    </p>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection

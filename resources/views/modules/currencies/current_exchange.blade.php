<div class="small-box bg-green">
            <div class="inner">
              <h3>{{ number_format($USDCRC,2) }} <sup style="font-size: 20px">{{  "Colones"  }}</sup></h3>

              <p>{{ "Tipo de cambio actual"  }}<sub>{{ $last_update }}</sub></p>
            </div>
            <div class="icon">
              <i class="ion ion-social-usd"></i>
            </div>
            <a href="{{  $Module->url("show") }}" class="small-box-footer">{{ "Más información" }} <i class="fa fa-arrow-circle-right"></i></a>
  </div>

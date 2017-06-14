<div id="regions_div" style="width: 100%; height: 95vh;"></div>
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

   <script type="text/javascript">

     google.charts.load('current', {'packages':['geochart']});
      google.charts.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {

        var data = google.visualization.arrayToDataTable([
             ['País', 'Visitas'],
          @if( isset($countries) )
              @foreach($countries AS $country)
                ['{!! $country->country !!}', {{ $country->total  }}],
              @endforeach
          @endif
          ['EMPTY', 0]
        ]);

        var options = {};

        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

        chart.draw(data, options);
      }

   </script>
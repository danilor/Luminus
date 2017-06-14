<!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ "Bienvenido al ClickCounter" }}</h3>


        </div>
        <div class="box-body">
          <p>{{"Cantidad de clics registrados"}}</p>
          <!-- La cantidad de clics registrados hasta el momento -->
          <p url="{{ $Module->url("registeredclicks",["extra"=>"clics"])  }}" id="main_number">
              0
          </p>
          <p>
              <a class="generate_click" href="#" url="{{ $Module->url("registerclick",["user_id"=>$Request->getUser()->id])  }}">{{ "Generar Clic" }}</a>
          </p>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
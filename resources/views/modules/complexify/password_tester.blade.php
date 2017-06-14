<p>{{ "En este momento, vamos a probar su contraseña"  }}</p>
<div class="col-xs-6 col-sm-6 col-md-8 col-lg-6">
    <form onsubmit="return false;"  >
                <div class="form-group">
                    <label for="name">{{ "Contraseña"  }}</label>
                    <input autocomplete="off" type="password" name="password" class="form-control" required="required" id="password" placeholder="{{ "Contraseña"  }}" value="" />
                </div>
    </form>
    <div class="strong_bar col-xs-12">
        <div class="bar" style="width: 0%; background-color:green;"></div>
    </div>
</div>
<!DOCTYPE html>
<html lang="{{ config("app.locale") }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <title>{{ config("app.name")  }}</title>
        <!-- BOOTSTRAP -->
        <link rel="stylesheet" type="text/css" href="/assets/bootstrap/{{ config("plugins.bootstrap_version")  }}/css/bootstrap.min.css">
        <!-- Website Font style -->
        <link rel="stylesheet" href="/assets/fontawesome/{{ config("plugins.font_awesome_version")  }}/css/font-awesome.min.css">
        <style type="text/css">
              input::-webkit-input-placeholder {
                color: #666;
              }
              input:-moz-placeholder {
                color: #666;
              }

              input:-ms-input-placeholder {
                color: #666;
              }
              body {
                font-family: Helvetica,sans-serif;
                font-size: 22px;
              }
              .login-wrap {
                width: 640px;
                height: 927px;
                position: relative;
                padding: 194px 0 0 56px;
                overflow: hidden;
              }
              .login-form-wrap {
                width: 528px;
                height: 702px;
                background: rgba(140, 139, 139, 0.7);
                border: 1px solid #767676;
                border-radius: 15px;
                box-shadow: 0 0 3px 1px rgba(0, 0, 0, 0.5),
                0 0 4px 0px rgba(255, 255, 255, 0.5) inset,
                0 0 4px 0px rgba(255, 255, 255, 0.5),
                0 0 65px 25px rgba(255, 255, 255, 0.14);
              }

              .card-holder-wrap {
                margin: 25px auto 40px auto;
                width: 145px;
                position: relative;
              }

              .hole {
                background: #000;
                height: 20px;
                border-radius: 10px;
                box-shadow: 0 1px 3px 1px rgba(255, 255, 255, 0.56);
              }

              .ring-large-wrap {
                margin: -170px auto 0 auto;
                z-index: 2;
                position: relative;
                width: 118px;
                height: 165px;
                background: rgba(140, 139, 139, 0.7);
                border: 1px solid #767676;
                border-radius: 15px;
                box-shadow: 0 0 3px 1px rgba(0, 0, 0, 0.5), 0 0 4px 0px rgba(255, 255, 255, 0.5) inset, 0 0 4px 0px rgba(255, 255, 255, 0.5);
              }
              .ring-large-wrap:before {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                content: '';
                display: block;
                margin: 15px auto 0 auto;
                background: #cdcdcd;
                background: -moz-linear-gradient(top, #cdcdcd 1%, #cbcbcb 18%, #5c5c5c 43%, #424242 53%, #5c5c5c 70%, #bcbcbc 93%, #d6d6d6 100%);
                background: -webkit-gradient(linear, left top, left bottom, color-stop(1%, #cdcdcd), color-stop(18%, #cbcbcb), color-stop(43%, #5c5c5c), color-stop(53%, #424242), color-stop(70%, #5c5c5c), color-stop(93%, #bcbcbc), color-stop(100%, #d6d6d6));
                background: -webkit-linear-gradient(top, #cdcdcd 1%, #cbcbcb 18%, #5c5c5c 43%, #424242 53%, #5c5c5c 70%, #bcbcbc 93%, #d6d6d6 100%);
                background: -o-linear-gradient(top, #cdcdcd 1%, #cbcbcb 18%, #5c5c5c 43%, #424242 53%, #5c5c5c 70%, #bcbcbc 93%, #d6d6d6 100%);
                background: -ms-linear-gradient(top, #cdcdcd 1%, #cbcbcb 18%, #5c5c5c 43%, #424242 53%, #5c5c5c 70%, #bcbcbc 93%, #d6d6d6 100%);
                background: linear-gradient(to bottom, #cdcdcd 1%, #cbcbcb 18%, #5c5c5c 43%, #424242 53%, #5c5c5c 70%, #bcbcbc 93%, #d6d6d6 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#cdcdcd', endColorstr='#d6d6d6',GradientType=0 );
              }
              .ring-large-wrap:after {
                width: 50px;
                height: 50px;
                border: 7px solid #fff;
                background: #000;
                content: '';
                display: block;
                border-radius: 50%;
                margin: -55px auto 0 auto;
              }

              .ring-small-wrap {
                margin: -72px auto 0 auto;
                z-index: 3;
                position: relative;
                width: 118px;
                height: 72px;
                background: rgba(140, 139, 139, 0.7);
                border: 1px solid #767676;
                border-radius: 15px;
                box-shadow: 0 0 3px 1px rgba(0, 0, 0, 0.5), 0 0 4px 0px rgba(255, 255, 255, 0.5) inset, 0 0 4px 0px rgba(255, 255, 255, 0.5);
              }
              .ring-small-wrap:before {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                content: '';
                display: block;
                margin: 15px auto 0 auto;
                background: #cdcdcd;
                background: -moz-linear-gradient(top, #cdcdcd 1%, #cbcbcb 18%, #5c5c5c 43%, #424242 53%, #5c5c5c 70%, #bcbcbc 93%, #d6d6d6 100%);
                background: -webkit-gradient(linear, left top, left bottom, color-stop(1%, #cdcdcd), color-stop(18%, #cbcbcb), color-stop(43%, #5c5c5c), color-stop(53%, #424242), color-stop(70%, #5c5c5c), color-stop(93%, #bcbcbc), color-stop(100%, #d6d6d6));
                background: -webkit-linear-gradient(top, #cdcdcd 1%, #cbcbcb 18%, #5c5c5c 43%, #424242 53%, #5c5c5c 70%, #bcbcbc 93%, #d6d6d6 100%);
                background: -o-linear-gradient(top, #cdcdcd 1%, #cbcbcb 18%, #5c5c5c 43%, #424242 53%, #5c5c5c 70%, #bcbcbc 93%, #d6d6d6 100%);
                background: -ms-linear-gradient(top, #cdcdcd 1%, #cbcbcb 18%, #5c5c5c 43%, #424242 53%, #5c5c5c 70%, #bcbcbc 93%, #d6d6d6 100%);
                background: linear-gradient(to bottom, #cdcdcd 1%, #cbcbcb 18%, #5c5c5c 43%, #424242 53%, #5c5c5c 70%, #bcbcbc 93%, #d6d6d6 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#cdcdcd', endColorstr='#d6d6d6',GradientType=0 );
              }
              .ring-small-wrap:after {
                width: 34px;
                height: 34px;
                border: 5px solid #fff;
                background: #000;
                content: '';
                display: block;
                border-radius: 50%;
                margin: -38px auto 0 auto;
              }

              .l-stroke {
                position: absolute;
                top: -223px;
                left: 40px;
                width: 34px;
                z-index: 1;
                height: 108px;
                background: url(http://i.imgur.com/HECNjze.png);
              }

              .r-stroke {
                position: absolute;
                top: -223px;
                left: 66px;
                width: 34px;
                z-index: 3;
                height: 108px;
                background: url(http://i.imgur.com/d8qMj72.png);
              }

              .login-form {
                background: #fff;
                width: 446px;
                height: 580px;
                margin: 0 auto;
                border-radius: 15px;
                box-shadow: 0 0 25px 5px rgba(255, 255, 255, 0.55);
                padding: 55px 20px 0 20px;
                text-align: center;
                background: url(http://i.imgur.com/hzzCvwa.png) no-repeat;
              }

              .freeb {
                margin: 0 auto 40px auto;
                width: 253px;
                height: 65px;
                display: block;
              }

              .input-wrap {
                border: 1px solid #dbdbdb;
                border-radius: 15px;
                box-shadow: 0 0 8px 1px rgba(0, 0, 0, 0.13) inset;
                background: #fff;
              }

              .user-id {
                background: url(http://i.imgur.com/OSo6WLs.png) no-repeat right;
                width: 385px;
                display: block;
                margin-bottom: 12px !important;
              }
              .user-id input {
                display: block;
                width: 350px;
                border-radius: 15px 15px 0 0;
                margin: 7px 5px 0 7px;
                padding: 18px 25px 8px 15px;
                color: #666;
                outline: none;
                border: none;
              }

              .form-hr {
                border: none;
                border-top: 1px solid #dbdbdb;
              }

              .password {
                background: url(http://i.imgur.com/PhJdR34.png) no-repeat right;
                width: 385px;
                display: block;
                margin-bottom: 12px !important;

              }
              .password input {
                display: block;
                width: 350px;
                border-radius: 15px 15px 0 0;
                margin: 7px 5px 0 7px;
                padding: 12px 25px 9px 15px;
                color: #666;
                outline: none;
                border: none;
              }

              .remember {
                border: 1px solid #dbdbdb;
                background: #fff;
                height: 60px;
                border-radius: 15px;
                box-shadow: 0 0 8px 1px rgba(0, 0, 0, 0.13) inset;
                margin: 20px 0 30px 0;
                padding: 15px 0 9px 20px;
                color: #666;
                text-align: left;
              }
              .remember span {
                vertical-align: top;
              }

              .switch,
              .switch-label,
              .switch-slider {
                width: 108px;
                height: 31px;
              }

              .switch {
                position: relative;
                display: inline-block;
                margin-left: 110px;
              }

              .switch-check {
                position: absolute;
                visibility: hidden;
              }

              .switch-label,
              .switch-slider {
                position: absolute;
                top: 0;
              }

              .switch-label {
                left: 0;
                cursor: pointer;
              }

              .switch-slider {
                border-radius: 3px;
                box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.21), 0 18px 0 rgba(0, 0, 0, 0.03) inset, 0 1px 1px rgba(0, 0, 0, 0.03) inset;
                -webkit-transition: width 0.2s linear;
                -moz-transition: width 0.2s linear;
                -o-transition: width 0.2s linear;
                transition: width 0.2s linear;
              }

              .switch-slider-on {
                left: 0;
                background: url(http://i.imgur.com/JcZNuXn.png) no-repeat left;
              }

              .switch-slider-off {
                right: 0;
                background: url(http://i.imgur.com/9DfUgx3.png) no-repeat right;
              }

              .switch-slider-off:after {
                position: absolute;
                top: 0;
                left: 0;
                width: 46px;
                border-radius: 3px;
                height: 31px;
                content: '';
                box-shadow: 0 1px 0 rgba(255, 255, 255, 0.6) inset, 1px 0 0 rgba(0, 0, 0, 0.4);
                background: #c8c6c6;
                background: -moz-linear-gradient(top, #c8c6c6 0%, #efebeb 100%);
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #c8c6c6), color-stop(100%, #efebeb));
                background: -webkit-linear-gradient(top, #c8c6c6 0%, #efebeb 100%);
                background: -o-linear-gradient(top, #c8c6c6 0%, #efebeb 100%);
                background: -ms-linear-gradient(top, #c8c6c6 0%, #efebeb 100%);
                background: linear-gradient(to bottom, #c8c6c6 0%, #efebeb 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#c8c6c6', endColorstr='#efebeb',GradientType=0 );
              }

              .switch-label:active .switch-slider-off:after {
                background: #D5D5D5;
                background: -webkit-linear-gradient(#C8C8C8, #E4E4E4);
                background: -moz-linear-gradient(#C8C8C8, #E4E4E4);
                background: -o-linear-gradient(#C8C8C8, #E4E4E4);
                background: linear-gradient(#c8c8c8, #e4e4e4);
              }

              .switch-check:checked + .switch-label .switch-slider-on {
                width: 108px;
              }

              .switch-check:checked + .switch-label .switch-slider-off {
                width: 45px;
              }

              .button {
                width: 406px;
                height: 62px;
                background: #6b8bad;
                border-radius: 15px;
                border: none;
                color: #fff;
                text-shadow: 0 1px 2px black;
                font-size: 26px;
                font-weight: bold;
                margin-bottom: 40px;
                background: -moz-linear-gradient(top, #6b8bad 0%, #39577f 48%, #254b72 52%, #102d4c 100%);
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #6b8bad), color-stop(48%, #39577f), color-stop(52%, #254b72), color-stop(100%, #102d4c));
                background: -webkit-linear-gradient(top, #6b8bad 0%, #39577f 48%, #254b72 52%, #102d4c 100%);
                background: -o-linear-gradient(top, #6b8bad 0%, #39577f 48%, #254b72 52%, #102d4c 100%);
                background: -ms-linear-gradient(top, #6b8bad 0%, #39577f 48%, #254b72 52%, #102d4c 100%);
                background: linear-gradient(to bottom, #6b8bad 0%, #39577f 48%, #254b72 52%, #102d4c 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6b8bad', endColorstr='#102d4c',GradientType=0 );
              }
              .button:hover {
                background: #7290b0;
                background: -moz-linear-gradient(top, #7290b0 0%, #3d5e89 48%, #29537d 52%, #133559 100%);
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #7290b0), color-stop(48%, #3d5e89), color-stop(52%, #29537d), color-stop(100%, #133559));
                background: -webkit-linear-gradient(top, #7290b0 0%, #3d5e89 48%, #29537d 52%, #133559 100%);
                background: -o-linear-gradient(top, #7290b0 0%, #3d5e89 48%, #29537d 52%, #133559 100%);
                background: -ms-linear-gradient(top, #7290b0 0%, #3d5e89 48%, #29537d 52%, #133559 100%);
                background: linear-gradient(to bottom, #7290b0 0%, #3d5e89 48%, #29537d 52%, #133559 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7290b0', endColorstr='#133559',GradientType=0 );
              }
              .button:active {
                background: #6385a9;
                background: -moz-linear-gradient(top, #6385a9 0%, #375479 48%, #24496e 52%, #0f2b49 100%);
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #6385a9), color-stop(48%, #375479), color-stop(52%, #24496e), color-stop(100%, #0f2b49));
                background: -webkit-linear-gradient(top, #6385a9 0%, #375479 48%, #24496e 52%, #0f2b49 100%);
                background: -o-linear-gradient(top, #6385a9 0%, #375479 48%, #24496e 52%, #0f2b49 100%);
                background: -ms-linear-gradient(top, #6385a9 0%, #375479 48%, #24496e 52%, #0f2b49 100%);
                background: linear-gradient(to bottom, #6385a9 0%, #375479 48%, #24496e 52%, #0f2b49 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6385a9', endColorstr='#0f2b49',GradientType=0 );
                padding: 1px 0 0 0;
              }

              .forgot {
                color: #1e4168;
                font-size: 19px;
                text-decoration: none;
                padding-bottom: 0px;
                border-bottom: 1px solid #1e4168;
              }

            </style>
    </head>
      <body class="loginpages">
        <div class="container">
          <div class="row">
            <div class="col-md-4 col-md-offset-2">
             <div class="login-wrap">
              <div class="login-form-wrap">
                <div class="card-holder-wrap">
                  <div class="hole"></div>
                  <div class="l-stroke"></div>
                  <div class="r-stroke"></div>
                  <div class="ring-large-wrap"></div>
                  <div class="ring-small-wrap"></div>
                </div>
                @yield("content")
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--JQuery -->
        <script src="/assets/jquery/{{ config("plugins.jquery_version")  }}/jquery.min.js"></script>
        <!-- JQuery UI -->
        <script src="/assets/jquery-ui/{{ config("plugins.jqueryui_version")  }}/jquery-ui.min.js"></script>
        <!-- Bootstrap -->
        <script src="/assets/bootstrap/{{ config("plugins.bootstrap_version")  }}/js/bootstrap.min.js"></script>
        @include("login.block.general_footer")
      </body>
</html>
@include("templates.components.doctype")
<html>
    <head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@include("templates.components.headercss")
		<link href='/css/login/login.css' rel='stylesheet' type='text/css'>
		<title>{{ "Login"  }}</title>
	</head>
	<body>
		<div class="container">
			<div class="row main">
				<div class="panel-heading">
	               <div class="panel-title text-center">
	               		<h1 class="title">{{ config("app.name")  }}</h1>
	               		<hr />
	               	</div>
	            </div>
				<div class="main-login main-center">
                       @yield("content")
				</div>
			</div>
		</div>

		@include("login.block.general_footer")
	</body>
</html>
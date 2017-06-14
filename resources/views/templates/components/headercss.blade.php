<!-- Website Font style -->
<link rel="stylesheet" href="/assets/fontawesome/{{ config("plugins.font_awesome_version")  }}/css/font-awesome.min.css">
<!-- ION ICONS -->
<link rel="stylesheet" href="/assets/ionicons/{{ config("plugins.ionicons_version")  }}/css/ionicons.min.css">
<!-- Google Fonts -->
<link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="/assets/jquery-ui/{{ config("plugins.jqueryui_version")  }}/jquery-ui.min.css">

<!-- BOOTSTRAP -->
<link rel="stylesheet" type="text/css" href="/assets/bootstrap/{{ config("plugins.bootstrap_version")  }}/css/bootstrap.min.css">

<!-- BOOTSTRAP - DIALOG -->
<link rel="stylesheet" type="text/css" href="/assets/bootstrap-dialog/{{ config("plugins.bootstrap-dialog_version")  }}/bootstrap-dialog.css">

<!-- SBUBBLE -->
<!-- <link rel="stylesheet" type="text/css" href="/assets/sBubble/{{ config("plugins.sBubble_version")  }}/sBubble.css"> -->
<!-- DATATABLES -->
<link rel="stylesheet" href="/assets/datatables/{{ config("plugins.datatables_version")  }}/datatables.min.css">

<!-- SUMMERNOTE -->
<link rel="stylesheet" href="/assets/summernote/{{ config("plugins.summernote_version")  }}/summernote.css">

<!-- PACE -->
<link rel="stylesheet" href="/assets/pace/{{ config("plugins.pace_version")  }}/macos.css">

<!-- CHOSEN -->
<link rel="stylesheet" href="/assets/chosen/{{ config("plugins.chosen_version")  }}/chosen.min.css">

<!-- CHARTIST -->
<link rel="stylesheet" href="/assets/chartist/{{  config("plugins.chartist_version")  }}/chartist.min.css" />
<link rel="stylesheet" href="/assets/chartist/plugins/tooltip/tooltip.css" />

<!-- TEMPLATE CSS ADMIN LTE -->
<link rel="stylesheet" href="/assets/adminlte/{{  config("plugins.adminlte_version")  }}/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="/assets/adminlte/{{  config("plugins.adminlte_version")  }}/skins/_all-skins.min.css">

<!-- CUSTOM CSS -->
<link rel="stylesheet" href="/css/general.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/{{ config("plugins.html5shiv_version") }}/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/{{ config("plugins.respond_version") }}/respond.min.js"></script>
<![endif]-->



{!! \App\Classes\Site\Navigation::getModuleCSS() !!}

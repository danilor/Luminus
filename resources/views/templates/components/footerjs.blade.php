<!--JQuery -->
<script src="/assets/jquery/{{ config("plugins.jquery_version")  }}/jquery.min.js"></script>
<!-- JQuery UI -->
<script src="/assets/jquery-ui/{{ config("plugins.jqueryui_version")  }}/jquery-ui.min.js"></script>
<!-- Bootstrap -->
<script src="/assets/bootstrap/{{ config("plugins.bootstrap_version")  }}/js/bootstrap.min.js"></script>
<!-- Bootstrap - Dialog -->
<script src="/assets/bootstrap-dialog/{{ config("plugins.bootstrap-dialog_version")  }}/bootstrap-dialog.js"></script>
<!-- MOMENT -->
<script src="/assets/moment/{{ config("plugins.moment_version")  }}/moment-with-locales.js"></script>
<!-- LIVESTAMP -->
<!-- <script src="/assets/livestamp/{{ config("plugins.livestamp_version")  }}/livestamp.min.js"></script> -->
<!-- DataTable -->
<script src="/assets/datatables/{{ config("plugins.datatables_version")  }}/datatables.min.js"></script>
<!-- SlimScroll -->
<!-- We are hidding the SlimScroll temporally -->
<!--<script src="/assets/slimscroll/{{ config("plugins.slimscroll_version")  }}/jquery.slimscroll.min.js"></script>-->
<!-- FastClick -->
<script src="/assets/fastclick/{{ config("plugins.fastclick_version")  }}/fastclick.min.js"></script>

<!-- TIMEAGO -->
<script src="/assets/timeago/{{ config("plugins.timeago_version")  }}/timeago.js"></script>

<!-- BLOCK UI -->
<script src="/assets/blockui/{{ config("plugins.blockUI_version")  }}/blockUI.js"></script>

<!-- NOTY -->
<script src="/assets/noty/{{ config("plugins.noty_version")  }}/packaged/jquery.noty.packaged.min.js"></script>

<!-- CHARTIST -->
<script src="/assets/chartist/{{ config("plugins.chartist_version")  }}/chartist.min.js"></script>
<script src="/assets/chartist/plugins/tooltip/tooltip.js"></script>

<!-- BOOTSTRAP CHECKBOX -->
<script src="/assets/bootstrap-checkbox/{{ config("plugins.bootstrap-checkbox")  }}/bootstrap-checkbox.min.js"></script>

<!-- PAGE -->
<script src="/assets/pace/{{ config("plugins.pace_version")  }}/pace.min.js"></script>

<!-- CHOSEN -->
<script src="/assets/chosen/{{ config("plugins.chosen_version")  }}/chosen.jquery.js"></script>

<!-- SUMMERNOTE -->
<!-- http://summernote.org/ -->
<script src="/assets/summernote/{{ config("plugins.summernote_version")  }}/summernote.js"></script>
<script src="/assets/summernote/{{ config("plugins.summernote_version")  }}/lang/summernote-{{ config("app.locale") }}.js"></script>

<!-- JQUERY VALIDATION -->
<script src="/assets/jquery-validation/{{ config("plugins.jquery_validation_version")  }}/jquery.validate.min.js"></script>
<script src="/assets/jquery-validation/{{ config("plugins.jquery_validation_version")  }}/additional-methods.min.js"></script>
<script src="/assets/jquery-validation/{{ config("plugins.jquery_validation_version")  }}/localization/messages_{{ config("app.locale")  }}.min.js"></script>

<!-- sBubble -->
<!-- <script src="/assets/sBubble/{{ config("plugins.sBubble_version")  }}/sBubble.js"></script> -->

<!-- ADMIN LTE -->
<script src="/assets/adminlte/{{  config("plugins.adminlte_version")  }}/app.min.js"></script>
<script src="/assets/adminlte/{{  config("plugins.adminlte_version")  }}/demo.js"></script>
<!-- GENERAL SCRIPT -->
<script src="/js/general.js"></script>
{!! \App\Classes\Site\Navigation::getModuleJS() !!}

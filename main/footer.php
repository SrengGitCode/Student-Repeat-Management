<!-- Stylesheets -->
<link href="vendors/chosen.min.css" rel="stylesheet" media="screen">

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Ensure jQuery is loaded first -->
<script src="vendors/chosen.jquery.min.js"></script>
<script src="vendors/bootstrap-datepicker.js"></script>

<!-- Init Scripts -->
<script>
        $(function() {
                // Enable datepicker if elements exist
                if ($(".datepicker").length) {
                        $(".datepicker").datepicker();
                }

                // Enable Chosen dropdowns
                if ($(".chzn-select").length) {
                        $(".chzn-select").chosen();
                }

                // If you plan to use .uniform_on or .wysihtml5, uncomment and ensure their libraries are included
                /*
                if ($(".uniform_on").length) {
                  $(".uniform_on").uniform();
                }

                if ($(".textarea").length) {
                  $('.textarea').wysihtml5();
                }
                */
        });
</script>

<!-- Bootstrap Components -->
<script src="js/bootstrap-transition.js"></script>
<script src="js/bootstrap-alert.js"></script>
<script src="js/bootstrap-modal.js"></script>
<script src="js/bootstrap-dropdown.js"></script>
<script src="js/bootstrap-scrollspy.js"></script>
<script src="js/bootstrap-tab.js"></script>
<script src="js/bootstrap-tooltip.js"></script>
<script src="js/bootstrap-popover.js"></script>
<script src="js/bootstrap-button.js"></script>
<script src="js/bootstrap-collapse.js"></script>
<script src="js/bootstrap-carousel.js"></script>
<script src="js/bootstrap-typeahead.js"></script>
<script src="js/bootstrap.js"></script>

<!-- DataTables -->
<script src="js/jquery.dataTables.js" type="text/javascript" charset="utf-8"></script>
<script src="js/DT_bootstrap.js" type="text/javascript" charset="utf-8"></script>
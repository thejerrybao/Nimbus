<?php 
/** Project Name: Nimbus (Circle K Club Management)
 ** MRP Administration (mrp.php)
 **
 ** Author: Jerry Bao (jbao@berkeley.edu)
 ** Author: Robert Rodriguez (rob.rodriguez@berkeley.edu)
 ** Author: Diyar Aniwar (diyaraniwar@berkeley.edu)
 ** 
 ** CIRCLE K INTERNATIONAL
 ** COPYRIGHT 2014-2015 - ALL RIGHTS RESERVED
 **/

?>

<!-- jQuery Version 1.11.0 -->
<script src="js/jquery-1.11.0.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="js/sb-admin-2.js"></script>

<!-- Open Source date.js File -->
<script src="js/date.js"></script>

<!-- Open Source Chosen JQuery Plugin -->
<script src="js/chosen.jquery.min.js"></script>

<!-- Open Source Masked Input JQuery Plugin -->
<script src="js/maskedinput.jquery.min.js"></script>

<? if ($customJS) { ?>
<!-- Page Specific JS -->
<script src="js/<?= $page ?>.js"></script>
<? } ?>
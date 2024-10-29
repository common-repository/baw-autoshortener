<?php
 get_header();
?>
<link rel="stylesheet" type="text/css" href="'.BAWAS_PLUGIN_URL.'/css/baw_as.css">
<div id="content">
<?php
 bawas_get_and_write_links( 0, $ID, $from, BAWAS_MODE_INFOS );
?>
</div>
<?php
 get_footer();
?>
<?php
include_once('start.php');
$return = $Common->monitorEachSite();
echo "<pre>";
print_r($return);
exit;
?>
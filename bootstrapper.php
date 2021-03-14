<?php
if (!defined("ALLOW_INCLUDE"))	die('Access forbidden');

include('functions/functions.php');

define("PATH", get_config('app_default_path'));

session_start();

include('functions/autostart.php');
?>
<script>
var PATH = "<?= PATH ?>";
</script>
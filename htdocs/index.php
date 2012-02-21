<?php
require_once( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'bootstrap.php' );
require_once('site.header.php');

echo '<pre>';
print_r( sw_list_inbox() );
echo '</pre>';

?>

<?php
require_once('site.footer.php');
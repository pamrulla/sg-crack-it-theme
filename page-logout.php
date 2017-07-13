<?php
/*
    Template Name: Logout
*/

wp_logout();
header( 'Location:' . home_url() );

?>

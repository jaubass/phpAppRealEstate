<?php
/*
Plugin Name: Eliminar items del menú Wordpress 
Description: Plugin para eliminar items del menú de Wordpress para Bianca Nguema
Version: 0.1
License: GPL
Author: Jaume Fite
Author URI: http://bububass.com
*/

    add_action( 'admin_menu', 'my_remove_menu_pages' );

    function my_remove_menu_pages() {
        remove_menu_page( 'edit.php' );                   //Posts
        remove_menu_page( 'upload.php' );                 //Media
        remove_menu_page( 'edit.php?post_type=page' );    //Pages
        remove_menu_page( 'edit-comments.php' );          //Comments
        remove_menu_page( 'themes.php' );                 //Appearance
        remove_menu_page( 'users.php' );                  //Users
        remove_menu_page( 'tools.php' );                  //Tools
        remove_menu_page( 'options-general.php' );        //Settings
        remove_menu_page( 'sg-security.php' );            //SG Security

    }
?>
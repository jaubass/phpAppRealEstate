<?php
/*
Plugin Name: Eliminar items del menú Naia Invest 
Description: Plugin para eliminar items del menú de Wordpress para Naia Invest
Version: 1.2
License: GPL
Author: Jaume Fite
Author URI: http://bububass.com
*/

    add_action( 'admin_menu', 'my_remove_menu_pages' );

    function my_remove_menu_pages() {
        remove_menu_page('users.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('themes.php');
        remove_menu_page('users.php');
        remove_menu_page('options-general.php');
        remove_menu_page('edit.php?post_type=avada_faq');
        remove_menu_page('tools.php');
        remove_menu_page('admin.php?page=gadash_settings'); 
        remove_menu_page('edit.php?post_type=cookielawinfo&page=cookie-law-info');
    }
?>
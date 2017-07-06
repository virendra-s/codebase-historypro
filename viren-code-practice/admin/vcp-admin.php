<?php

/* ===============================
ADMIN SECTION - ADD MENU ITEM TO WORDPRESS SETTINGS MENU
================================= */

//Virendra : my template add_action usage 

function vcp_admin_actions ()
{
    add_menu_page("VCP Menu Page Title", "VCP Settings", 1, "viren-code-practice/viren-code-practice.php", "vcp_admin_settings", "", 6);
    add_submenu_page("viren-code-practice/viren-code-practice.php", "VCP Sub Menu Page Title", "VCP Products Settings", 1, "viren-code-practice/admin/vcp-products-settings.php", "vcp_admin_products");
}
 
add_action ('admin_menu', 'vcp_admin_actions');

// Virendra Function to create settings page for plugin and to include the file that has all settings form options.
function vcp_admin_settings() {
    include('vcp-admin-settings.php');
}

// Virendra Function to create settings page for plugin and to include the file that has all settings form options.
function vcp_admin_products() {
    include('vcp-admin-products-settings.php');
}

<?php
/**
 * Plugin Name: Monetizer
 * Description: Bloquez le contenu de certains de vos articles dont le titre est débute par la mention "[Premium]" aux visiteurs, seuls les connectés peuvent y accéder.
 * Version: 1.0
 */

add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
	add_menu_page('Monetizer Settings', 'Monetizer Settings', 'administrator', 'monetizer-settings', 'my_plugin_settings_page', 'dashicons-admin-generic');
}

function my_plugin_settings_page() {
echo '<div class="wrap">
<h2>Monetizer settings</h2>

<form method="post" action="options.php">';
    settings_fields( 'my-plugin-settings-group' );
    do_settings_sections( 'my-plugin-settings-group' );
    echo "<table class=\"form-table\">
            <tr valign=\"top\">
                <th scope=\"row\">Pourcentage des articles à afficher</th>
                <td><input type=\"text\" name=\"prc_aff\" value=\"". esc_attr( get_option('prc_aff') ) ."\" /></td>
            </tr>
    </table>
    ";
    submit_button();
 echo '
</form>
</div>';
}

add_action('admin_init', 'my_plugin_settings' );

function my_plugin_settings() {
	register_setting( 'my-plugin-settings-group', 'prc_aff' );
}

function is_premium() {
  $prem = get_the_title();
	return ((strtolower(substr($prem, 0, 9)) == "[premium]")) ? 1 : 0;
}

function add_c($content) {
    $art = 1/3;
    if (esc_attr( get_option('prc_aff') ) != "")
        $art = (esc_attr( get_option('prc_aff') )) / 100;
    if (!is_user_logged_in() && (is_premium() == 1) || wp_get_current_user()->roles[0] == "customer")
        $content = substr($content, 0, $art * strlen($content))."<strong>[...]</strong><br/><b> Pour lire la suite veuillez vous <a href=\"/wp-login.php\" > connecter</a> ! </b><br/>";
    return $content;

}

add_action('the_content', 'add_c');
?>

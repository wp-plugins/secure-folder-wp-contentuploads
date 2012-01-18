<?php
/*
Plugin Name: Secure Folder wp-content/uploads
Plugin URI: http://ruanglaba.com/clients-center/plugins/wordpress-plugin-secure-folder-wp-contentuploads
Description: Indonesia: Plugin ini akan menempatkan file index.html kosong di setiap folder dibawah wp-content/uploads untuk mencegah pencurian data. English: This is plugin will put empty index.html on every folders on wp-content/uploads to prevent content theft
Version: 1.2
Author: SuplentonkJaya a/n RuangLaba
Author URI: http://ruanglaba.com
License: GPL2

Copyright 2012  RuangLaba  (email : info@ruanglaba.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


*/

$PLUGIN_NAME = 'Secure Folder wp-content/uploads';
$PLUGIN_VERSION = '1.0';
$PLUGIN_PATH = WP_PLUGIN_URL.'/secure-folder-uploads';

$empty_file = realpath( dirname( __FILE__ ) ) . '/index.html';
$start_dir = wp_upload_dir(); 

add_action( 'admin_menu', 'addPluginToSubmenu');

/* =====================================================================
 * =====================================================================
 * ==================== PLUGIN FUNCTION ===========================
 */

function addPluginToSubmenu()
{
	add_submenu_page('options-general.php', 'Secure Folder', 'Secure Folder', 10, __FILE__, 'initPluginMenu');
}

function initPluginMenu()
{
	global $start_dir;
	
	if( $_POST['secure'] == 'Y' ) {

		secure_folder_uploads();
?>
<div class="updated"><p><strong><?php _e('Process Secure Done.', 'rl_process_done' ); ?></strong></p></div>
<?php

	}
	echo '<div class="wrap">';
	echo "<h2>" . __( 'Secure Folder Options', 'rl_secure_option' ) . "</h2>";
	?>
	<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="secure" value="Y" />

		<p>
		<label>
		<?php 
		echo "
		<strong>Indonesia:</strong>
		<br/>
		Plugin ini berguna untuk melindungi folder <strong>wp-content/uploads</strong> sehingga orang luar tidak bisa mencuri konten anda. 
		<br/>
		Cara kerja plugin ini adalah dengan menempatkan file <strong>index.html</strong> kosong di setiap folder didalam wp-content/uploads
		<br/>
		Anda perlu lakukan ini secara manual setiap kali anda sempat (paling baik adalah tiap bulan)
		<br/><br/>
		<strong>English:</strong>
		<br/>
		This is plugin to protect your <strong>wp-content/uploads</strong> folder, where everybody can see it, browse it and steal you content.
		<br/>
		This plugin works by put empty <strong>index.html</strong> file on every folder inside wp-content/uploads
		<br/>
		You need to this manually every time you want (preferred once a month)
		<br/><br/>
		<a href='".$start_dir['baseurl']."'>Click here to check is your wp-content/uploads folder are open</a>
		<br/>
		If you can browse this folder using your browser, you need to secure this folder!
		";
		?>
		</label>
		</p>

		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Secure Folder', 'rl_secure_folder' ) ?>" />
		</p>

	</form>

<p>Mister mister and Miss and Misstress, if you like this plugin come give it a <a href="http://wordpress.org/extend/plugins/secure-folder-wp-contentuploads/">good rating</a> on wordpress, or tell this on your sites!</p>

</div>
	<?php
}//eof func initPlugin


function secure_folder_uploads(){

	global $start_dir;
	
	search_and_copy_to($start_dir['basedir']);
	
}//eof secure_folder_uploads
function search_and_copy_to($dir){
	
	global $empty_file;
		
	// copy index.html to root dir
	copy($empty_file, $dir . '/index.html');		

	//cek for loop recursive
	if ($dh = opendir($dir)) { 
		
		// loop for dir
		while (($file = readdir($dh)) !== false) { 
			
			// Open a known directory, and proceed to read its contents
			if ( is_dir($dir . '/' . $file) && $file!='.' && $file!='..' ) {
				search_and_copy_to( $dir . '/' . $file );
			}
			
		}
		closedir($dh);
		
	}

}//eof search_and_copy_to
?>

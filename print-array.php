<?php
/* 
Plugin Name: Print Array
Plugin URI: http://www.baileygp.com/opensource/wordpress/plugins/print-array.zip
Version: 0.2
Author: Justin Thomas for Bailey Brand Consulting
Description: Add something here...
 
Copyright 2011  Justin Thomas

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// simple restyling of array output

function echo_array ($arr) {
			
			$options = get_option('PrintArray');
			echo('
				<style>
					div#echo_array{
						margin: 10px 0;
					}
					div#echo_array pre{
						display: block;
						position: relative;
						float: left;
						clear: both;
						color: #'.$options['color'].';
						font-size: '.$options['size'].';
						font-family: '.$options['font'].';
						background: #'.$options['background'].';
						-moz-border-radius: 1em;
				    -moz-box-shadow: #123456 0 0 10px;
				    -webkit-box-shadow: #123456 0 0 10px;
				    -webkit-border-radius: 1em;
				    border-radius: 1em;
				    box-shadow: #123456 0 0 10px
					}
				</style>
			');
			echo('<div id="echo_array"><pre>');
  		print_r($arr);
  		echo('</pre></div>');
		}


if (!class_exists("PrintArray")) {
	class PrintArray {
		
		var $adminOptionsName = "PrintArray";
		
		
		function PrintArray() { //constructor
			
		}
		function init() {
			$this->getAdminOptions();
		}
		//Returns an array of admin options
		function getAdminOptions() {
			
			//global $devOptions;
			
			$devloungeAdminOptions = array('font' => 'Helvetica, Arial, sans-serif',
				'color' => 'ff0',
				'background' => 'eee', 
				'size' => '14px');
			$devOptions = get_option($this->adminOptionsName);
			if (!empty($devOptions)) {
				foreach ($devOptions as $key => $option)
					$devloungeAdminOptions[$key] = $option;
			}				
			update_option($this->adminOptionsName, $devloungeAdminOptions);
			return $devloungeAdminOptions;
		}
		
		function addHeaderCode() {
			$devOptions = $this->getAdminOptions();
			if ($devOptions['show_header'] == "false") { return; }
			?>
<!-- Devlounge Was Here -->
<?php
		
		}
		function addContent($content = '') {
			$devOptions = $this->getAdminOptions();
			if ($devOptions['add_content'] == "true") {
				$content .= $devOptions['content'];
			}
			return $content;
		}
		function authorUpperCase($author = '') {
			$devOptions = $this->getAdminOptions();
			if ($devOptions['comment_author'] == "true") {
				$author = strtoupper($author);
			}
			return $author;
		}
		//Prints out the admin page
		function printAdminPage() {
					$devOptions = $this->getAdminOptions();
										
					if (isset($_POST['update_printArraySettings'])) { 
						if (isset($_POST['array_font'])) {
							$devOptions['font'] = $_POST['array_font'];
						}	
						if (isset($_POST['array_color'])) {
							$devOptions['color'] = $_POST['array_color'];
						}	
						if (isset($_POST['array_size'])) {
							$devOptions['size'] = $_POST['array_size'];
						}	
						if (isset($_POST['array_background'])) {
							$devOptions['background'] = $_POST['array_background'];
						}	
						update_option($this->adminOptionsName, $devOptions);
						
						?>
  <div class="updated">
    <p><strong>
      <?php _e("Settings Updated.", "PrintArray");?>
      </strong></p>
  </div>
  <?php
					} ?>
<div class=wrap>
  <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <h2>Print Array</h2>
    <h3>Display Font</h3>
    <p>
      <label for="array_font">Font family: </label>
      <select id="array_font" name="array_font">
      	<?php
					$font_family_array = array( 'Helvetica, Arial, sans-serif', 'Georgia, Times, serif', 'Courier, monospace' );
					
					for( $j = 0; $j < 3; $j++ ){
						echo( '<option value="'.$font_family_array[$j].'"' );
						if( $devOptions['font'] == $font_family_array[$j] ){
							echo( 'selected="selected"' );
						}
						echo( '>'.$font_family_array[$j].'</option>' );
					}
					
				?>
      </select>
    </p>
    <h3>Text Color</h3>
    <p>
      <label for="array_color">Display color:</label>
        <input type="text" id="array_color" name="array_color" value="<?php echo $devOptions['color']; ?>" />
    </p>
    <h3>Background Color</h3>
    <p>
      <label for="array_background">Display color:</label>
        <input type="text" id="array_background" name="array_background" value="<?php echo $devOptions['background']; ?>" />
    </p>
    <h3>Font Size</h3>
    <p>
      <label for="array_size">Font size: </label>
      <select name="array_size" id="array_size">
      	<?php
					$i = 8;
					for( $i; $i < 21; $i++ ){
						echo( '<option value="'.$i.'px"' );
						if( $devOptions['size'] == $i ){
							echo( 'selected="selected"' );
						}
						echo( '>'.$i.'px</option>' );
					}
				?>
      </select>
        
    </p>
    <div class="submit">
      <input type="submit" name="update_printArraySettings" value="<?php _e('Update Settings', 'PrintArray') ?>" />
    </div>
  </form>
</div>
<?php
				}//End function printAdminPage()
	
	}

} //End Class PrintArray

if (class_exists("PrintArray")) {
	$dl_printArray = new PrintArray();
}

//Initialize the admin panel
if (!function_exists("PrintArray_AdminPage")) {
	function PrintArray_AdminPage() {
		global $dl_printArray;
		if (!isset($dl_printArray)) {
			return;
		}
		if (function_exists('add_options_page')) {
	add_options_page('Print Array', 'Print Array', 9, basename(__FILE__), array(&$dl_printArray, 'printAdminPage'));
		}
	}	
}

//Actions and Filters	
if (isset($dl_printArray)) {
	//Actions
	add_action('admin_menu', 'PrintArray_AdminPage');
	//add_action('wp_head', array(&$dl_printArray, 'addHeaderCode'), 1);
	add_action('activate_print-array/print-array.php',  array(&$dl_printArray, 'init'));
}

?>

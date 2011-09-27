<?php

// OptionsFramework Admin Interface

/*-----------------------------------------------------------------------------------*/
/* Create the Options_Machine object - optionsframework_admin_init */
/*-----------------------------------------------------------------------------------*/

function optionsframework_admin_init() {
		// Rev up the Options Machine
		global $my_options, $options_machine;
		$options_machine = new Options_Machine($my_options);
		
		
	    //if reset is pressed->replace options with defaults
    if ( isset($_REQUEST['page']) && $_REQUEST['page'] == 'optionsframework' ) {
		if (isset($_REQUEST['of_reset']) && 'reset' == $_REQUEST['of_reset']) {
			
			$nonce=$_POST['security'];

			if (!wp_verify_nonce($nonce, 'of_ajax_nonce') ) { 
				header('Location: themes.php?page=optionsframework&reset=error');
				die('Security Check'); 
			} else {
				$defaults = (array) $options_machine->Defaults;
				update_option(OPTIONS,$options_machine->Defaults);	
				header('Location: themes.php?page=optionsframework&reset=true');
				die($options_machine->Defaults);
			} 
		}
    }
}
add_action('admin_init','optionsframework_admin_init');

/*-----------------------------------------------------------------------------------*/
/* Options Framework Admin Interface - optionsframework_add_admin */
/*-----------------------------------------------------------------------------------*/

function optionsframework_add_admin() {
	
    $of_page = add_submenu_page('themes.php', THEMENAME, 'Theme Options', 'edit_theme_options', 'optionsframework','optionsframework_options_page'); // Default

	// Add framework functionaily to the head individually
	add_action("admin_print_scripts-$of_page", 'of_load_only');
	add_action("admin_print_styles-$of_page",'of_style_only');
} 

add_action('admin_menu', 'optionsframework_add_admin');


/*-----------------------------------------------------------------------------------*/
/* Build the Options Page - optionsframework_options_page */
/*-----------------------------------------------------------------------------------*/

function optionsframework_options_page(){
global $options_machine;
	/*
	//for debugging
	$data = get_option(OPTIONS);
	print_r($data);
	*/	
?>

<div class="wrap" id="of_container">
  <div id="of-popup-save" class="of-save-popup">
    <div class="of-save-save">Options Updated</div>
  </div>
  <div id="of-popup-reset" class="of-save-popup">
    <div class="of-save-reset">Options Reset</div>
  </div>
   <div id="of-popup-fail" class="of-save-popup">
    <div class="of-save-fail">Error!</div>
  </div>

  <form id="ofform" method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" enctype="multipart/form-data" >
    <div id="header">
      <div class="logo">
        <h2><?php echo THEMENAME; ?></h2>
      </div>
	  <div id="js-warning">Warning- This options panel will not work properly without javascript!</div>
      <div class="icon-option"> </div>
	  <a><span id="expand_options">[+]Expand</span></a>
      <div class="clear"></div>
    </div>
    
    <div id="main">
      <div id="of-nav">
        <ul>
          <?php echo $options_machine->Menu ?>
        </ul>
      </div>
      <div id="content"> <?php echo $options_machine->Inputs /* Settings */ ?> </div>
      <div class="clear"></div>
    </div>
	<div class="save_bar_top"> 
    <img style="display:none" src="<?php echo ADMIN; ?>images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
    <input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('of_ajax_nonce'); ?>" />
	<input type="hidden" name="of_reset" value="reset" />

	
	<button id ="of_save" type="button" class="button-primary"><?php _e('Save All Changes');?></button>
	<button id ="of_reset" type="submit" class="button submit-button reset-button" ><?php _e('Options Reset');?></button>
	</div><!--#save_bar_top--> 
 
  </form>

  
</div>
<?php  if (!empty($update_message)) echo $update_message; ?>
<div style="clear:both;"></div>

</div><!--wrap-->
<?php
}

/*-----------------------------------------------------------------------------------*/
/* Load required styles for Options Page - of_style_only */
/*-----------------------------------------------------------------------------------*/

function of_style_only(){
	wp_enqueue_style('admin-style', ADMIN . 'admin-style.css');
	wp_enqueue_style('color-picker', ADMIN . 'css/colorpicker.css');
}	

/*-----------------------------------------------------------------------------------*/
/* Load required javascripts for Options Page - of_load_only */
/*-----------------------------------------------------------------------------------*/

function of_load_only() {

	add_action('admin_head', 'of_admin_head');
	
	wp_enqueue_script('jquery-ui-core');
	wp_register_script('jquery-input-mask', ADMIN .'js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_enqueue_script('jquery-input-mask');
	wp_enqueue_script('color-picker', ADMIN .'js/colorpicker.js', array('jquery'));
	wp_enqueue_script('ajaxupload', ADMIN .'js/ajaxupload.js', array('jquery'));
}


function of_admin_head() { 
			
	$data = get_option(OPTIONS); ?>
		
	<script type="text/javascript" language="javascript">

	jQuery.noConflict();
	jQuery(document).ready(function($){

	//delays until AjaxUpload is finished loading
	//fixes bug in Safari and Mac Chrome
	if (typeof AjaxUpload != 'function') { 
			return ++counter < 6 && window.setTimeout(init, counter * 500);
	}
	//hides warning if js is enabled			
	$('#js-warning').hide();
	
	//Tabify Options			
	$('.group').hide();
	$('.group:first').fadeIn();
				
	$('.group .collapsed').each(function(){
		$(this).find('input:checked').parent().parent().parent().nextAll().each( 
			function(){
				if ($(this).hasClass('last')) {
           			$(this).removeClass('hidden');
           			return false;
           		}
				$(this).filter('.hidden').removeClass('hidden');
		});
    });
           					
	$('.group .collapsed input:checkbox').click(unhideHidden);
				
	function unhideHidden(){
		if ($(this).attr('checked')) {
			$(this).parent().parent().parent().nextAll().removeClass('hidden');
		} else {
			$(this).parent().parent().parent().nextAll().each( 
				function(){
           			if ($(this).filter('.last').length) {
           				$(this).addClass('hidden');
						return false;
           			}
           			$(this).addClass('hidden');
           		});
           					
		}
	}
	
	//Current Menu Class
	$('#of-nav li:first').addClass('current');
	$('#of-nav li a').click(function(evt){
				
	$('#of-nav li').removeClass('current');
	$(this).parent().addClass('current');
						
	var clicked_group = $(this).attr('href');
		 
	$('.group').hide();
						
	$(clicked_group).fadeIn();
	
	evt.preventDefault();
						
});

	//Expand Options 
	var flip = 0;
				
	$('#expand_options').click(function(){
		if(flip == 0){
			flip = 1;
			$('#of_container #of-nav').hide();
			$('#of_container #content').width(820);
			$('#of_container .group').add('#of_container .group h2').show();
	
			$(this).text('[-]Shrink');
					
		} else {
			flip = 0;
			$('#of_container #of-nav').show();
			$('#of_container #content').width(660);
			$('#of_container .group').add('#of_container .group h2').hide();
			$('#of_container .group:first').show();
			$('#of_container #of-nav li').removeClass('current');
			$('#of_container #of-nav li:first').addClass('current');
					
			$(this).text('[+]Expand');
				
		}
			
	});



	// Reset Message Popup
	var reset = "<?php echo $_REQUEST['reset'] ?>";
				
	if ( reset.length ){
		if ( reset == 'true') {
			var message_popup = $('#of-popup-reset');
		} else {
			var message_popup = $('#of-popup-fail');
	}
		message_popup.fadeIn();
		window.setTimeout(function(){
	    message_popup.fadeOut();                        
		}, 2000);	
	}
	
	//Update Message popup
	$.fn.center = function () {
		this.animate({"top":( $(window).height() - this.height() - 200 ) / 2+$(window).scrollTop() + "px"},100);
		this.css("left", 250 );
		return this;
	}
		
			
	$('#of-popup-save').center();
	$('#of-popup-reset').center();
	$('#of-popup-fail').center();
			
	$(window).scroll(function() { 
		$('#of-popup-save').center();
		$('#of-popup-reset').center();
		$('#of-popup-fail').center();
	});
			

	//Masked Inputs (images as radio buttons)
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});
	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();
	
	// COLOR Picker			
	$('.colorSelector').each(function(){
		var Othis = this; //cache a copy of the this variable for use inside nested function
			
		$(this).ColorPicker({
				color: '<?php echo $color; ?>',
				onShow: function (colpkr) {
					$(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					$(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					$(Othis).children('div').css('backgroundColor', '#' + hex);
					$(Othis).next('input').attr('value','#' + hex);
					
				}
		});
			  
	}); //end color picker
	
	//AJAX Upload
	$('.image_upload_button').each(function(){
			
	var clickedObject = $(this);
	var clickedID = $(this).attr('id');	
			
	var nonce = $('#security').val();
			
	new AjaxUpload(clickedID, {
		action: ajaxurl,
		name: clickedID, // File upload name
		data: { // Additional data to send
			action: 'of_ajax_post_action',
			type: 'upload',
			security: nonce,
			data: clickedID },
		autoSubmit: true, // Submit file after selection
		responseType: false,
		onChange: function(file, extension){},
		onSubmit: function(file, extension){
			clickedObject.text('Uploading'); // change button text, when user selects file	
			this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
			interval = window.setInterval(function(){
				var text = clickedObject.text();
				if (text.length < 13){	clickedObject.text(text + '.'); }
				else { clickedObject.text('Uploading'); } 
				}, 200);
		},
		onComplete: function(file, response) {
			window.clearInterval(interval);
			clickedObject.text('Upload Image');	
			this.enable(); // enable upload button
				
	
			// If nonce fails
			if(response==-1){
				var fail_popup = $('#of-popup-fail');
				fail_popup.fadeIn();
				window.setTimeout(function(){
				fail_popup.fadeOut();                        
				}, 2000);
			}				
					
			// If there was an error
			else if(response.search('Upload Error') > -1){
				var buildReturn = '<span class="upload-error">' + response + '</span>';
				$(".upload-error").remove();
				clickedObject.parent().after(buildReturn);
				
				}
			else{
				var buildReturn = '<img class="hide of-option-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';

				$(".upload-error").remove();
				$("#image_" + clickedID).remove();	
				clickedObject.parent().after(buildReturn);
				$('img#image_'+clickedID).fadeIn();
				clickedObject.next('span').fadeIn();
				clickedObject.parent().prev('input').val(response);
			}
		}
	});
			
	});
			
	//AJAX Remove (clear option value)
	$('.image_reset_button').click(function(){
	
		var clickedObject = $(this);
		var clickedID = $(this).attr('id');
		var theID = $(this).attr('title');	
				
		var nonce = $('#security').val();
	
		var data = {
			action: 'of_ajax_post_action',
			type: 'image_reset',
			security: nonce,
			data: theID
		};
					
		$.post(ajaxurl, data, function(response) {
						
			//check nonce
			if(response==-1){ //failed
							
				var fail_popup = $('#of-popup-fail');
				fail_popup.fadeIn();
				window.setTimeout(function(){
					fail_popup.fadeOut();                        
				}, 2000);
			}
						
			else {
						
				var image_to_remove = $('#image_' + theID);
				var button_to_hide = $('#reset_' + theID);
				image_to_remove.fadeOut(500,function(){ $(this).remove(); });
				button_to_hide.fadeOut();
				clickedObject.parent().prev('input').val('');
			}
						
						
		});
					
	});   	 	
			
	//save everything else
	$('#of_save').live("click",function() {
			
		var nonce = $('#security').val();
					
		$('.ajax-loading-img').fadeIn();
										
		var serializedReturn = $('#ofform :input[name][name!="security"][name!="of_reset"]').serialize();
										
		//alert(serializedReturn);
						
		var data = {
			<?php if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'optionsframework'){ ?>
			type: 'save',
			<?php } ?>

			action: 'of_ajax_post_action',
			security: nonce,
			data: serializedReturn
		};
					
		$.post(ajaxurl, data, function(response) {
			var success = $('#of-popup-save');
			var fail = $('#of-popup-fail');
			var loading = $('.ajax-loading-img');
			loading.fadeOut();  
						
			if (response==1) {
				success.fadeIn();
			} else { 
				fail.fadeIn();
			}
						
			window.setTimeout(function(){
				success.fadeOut(); 
				fail.fadeOut();				
			}, 2000);
		});
			
	return false; 
					
	});   
			
	//confirm reset			
	$('#of_reset').click(function() {
		var answer = confirm("<?php _e('Click OK to reset. All settings will be lost!');?>")
		if (answer){ 	return true; } else { return false; }
});
			
						
	
}); //end doc ready
</script>
<?php }

/*-----------------------------------------------------------------------------------*/
/* Ajax Save Action - of_ajax_callback */
/*-----------------------------------------------------------------------------------*/

add_action('wp_ajax_of_ajax_post_action', 'of_ajax_callback');

function of_ajax_callback() {
	global $options_machine;

	$nonce=$_POST['security'];
	
	if (! wp_verify_nonce($nonce, 'of_ajax_nonce') ) die('-1'); 
			
	//get options array from db
	$all = get_option(OPTIONS);
		
	$save_type = $_POST['type'];
	
	//Uploads
	if($save_type == 'upload'){
		
		$clickedID = $_POST['data']; // Acts as the name
		$filename = $_FILES[$clickedID];
       	$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']); 
		
		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';    
		$uploaded_file = wp_handle_upload($filename,$override);
		 
			$upload_tracking[] = $clickedID;
				
			//update $options array w/ image URL			  
			$upload_image = $all; //preserve current data
			
			$upload_image[$clickedID] = $uploaded_file['url'];
			
			update_option(OPTIONS, $upload_image ) ;
		
				
		 if(!empty($uploaded_file['error'])) {echo 'Upload Error: ' . $uploaded_file['error']; }	
		 else { echo $uploaded_file['url']; } // Is the Response
		 
	}
	elseif($save_type == 'image_reset'){
			
			$id = $_POST['data']; // Acts as the name
			
			$delete_image = $all; //preserve rest of data
			$delete_image[$id] = ''; //update array key with empty value	 
			update_option(OPTIONS, $delete_image ) ;
	
	}	
	elseif ($save_type == 'save') {
		
		parse_str($_POST['data'], $data);
		unset($data['security']);
		unset($data['of_save']);
   
		update_option(OPTIONS, $data);
		die('1'); 
		
	} elseif ($save_type == 'reset') {
		update_option(OPTIONS,$options_machine->Defaults);
        die(1); //options reset
        
	}

  die();

}


/*-----------------------------------------------------------------------------------*/
/* Class that Generates The Options Within the Panel - optionsframework_machine */
/*-----------------------------------------------------------------------------------*/

class Options_Machine {

function __construct($options) {
	
	$return = $this->optionsframework_machine($options);
	
	$this->Inputs = $return[0];
	$this->Menu = $return[1];
	$this->Defaults = $return[2];
	
}


/*-----------------------------------------------------------------------------------*/
/* Generates The Options Within the Panel - optionsframework_machine */
/*-----------------------------------------------------------------------------------*/

public static function optionsframework_machine($options) {
    $data = get_option(OPTIONS);
	
	$defaults = array();   
    $counter = 0;
	$menu = '';
	$output = '';
	foreach ($options as $value) {
	   
		$counter++;
		$val = '';
		
		//create array of defaults		
		if ($value['type'] == 'multicheck'){
			if (is_array($value['std'])){
				foreach($value['std'] as $i=>$key){
					$defaults[$value['id']][$key] = true;
				}
			} else {
					$defaults[$value['id']][$value['std']] = true;
			}
		} else {
		$defaults[$value['id']] = $value['std'];
		}
		
		//Start Heading
		 if ( $value['type'] != "heading" )
		 {
		 	$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
			
			$output .= '<div class="section section-'.$value['type'].' '. $class .'">'."\n";
			$output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
			$output .= '<div class="option">'."\n" . '<div class="controls">'."\n";

		 } 
		 //End Heading
		                                 
		switch ( $value['type'] ) {
		
		case 'text':
			$output .= '<input class="of-input" name="'.$value['id'].'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $data[$value['id']] .'" />';
		break;
		case 'select':
			$output .= '<select class="of-input" name="'.$value['id'].'" id="'. $value['id'] .'">';
			foreach ($value['options'] as $option) {			
				$output .= '<option value="'.$option.'" ' . selected($data[$value['id']], $option, false) . ' />'.$option.'</option>';	 
			 } 
			 $output .= '</select>';
		break;
		case 'select2':
			$output .= '<select class="of-input" name="'.$value['id'].'" id="'. $value['id'] .'">';
			foreach ($value['options'] as $option=>$name) {
				$output .= '<option value="'.$option.'" ' . selected($data[$value['id']], $option, false) . ' />'.$name.'</option>';
			 } 
			 $output .= '</select>';		
		break;
		case 'textarea':	
			$cols = '8';
			$ta_value = '';
			
			if(isset($value['options'])){
					$ta_options = $value['options'];
					if(isset($ta_options['cols'])){
					$cols = $ta_options['cols'];
					} 
				}
				
				$ta_value = stripslashes($data[$value['id']]);			
				$output .= '<textarea class="of-input" name="'.$value['id'].'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.$ta_value.'</textarea>';		
		break;
		case "radio":
			
			 foreach($value['options'] as $option=>$name) {
				$output .= '<input class="of-input of-radio" name="'.$value['id'].'" type="radio" value="'.$option.'" ' . checked($data[$value['id']], $option, false) . ' />'.$name.'<br/>';				
			}	 
		break;
		case 'checkbox': 	
			$output .= '<input type="checkbox" class="checkbox of-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="1" '. checked($data[$value['id']], true, false) .' />';			
		break;
		case 'multicheck': 			
			$multi_stored = $data[$value['id']];
						
			foreach ($value['options'] as $key => $option) {										 
				$of_key_string = $value['id'] . '_' . $key;
				$output .= '<input type="checkbox" class="checkbox of-input" name="'.$value['id'].'['.$key.']'.'" id="'. $of_key_string .'" value="1" '. checked($multi_stored[$key], 1, false) .' /><label for="'. $of_key_string .'">'. $option .'</label><br />';								
			}			 
		break;
		case 'upload':			
			$output .= Options_Machine::optionsframework_uploader_function($value['id'],$value['std'],null);			
		break;
		case 'upload_min':			
			$output .= Options_Machine::optionsframework_uploader_function($value['id'],$value['std'],'min');			
		break;
		case 'color':		
			$output .= '<div id="' . $value['id'] . '_picker" class="colorSelector"><div style="background-color: '.$data[$value['id']].'"></div></div>';
			$output .= '<input class="of-color" name="'.$value['id'].'" id="'. $value['id'] .'" type="text" value="'. $data[$value['id']] .'" />';
		break;   	
		case 'typography':								
			$typography_stored = $data[$value['id']];		
			/* Font Size */ 
			$output .= '<select class="of-typography of-typography-size" name="'.$value['id'].'[size]" id="'. $value['id'].'_size">';
				for ($i = 9; $i < 71; $i++){ 
					$test = $i.'px';
					$output .= '<option value="'. $i .'px" ' . selected($typography_stored['size'], $test, false) . '>'. $i .'px</option>'; 
					}
	
			$output .= '</select>';
	
			/* Font Face */
			$output .= '<select class="of-typography of-typography-face" name="'.$value['id'].'[face]" id="'. $value['id'].'_face">';
			
			$faces = array('arial'=>'Arial',
							'verdana'=>'Verdana, Geneva',
							'trebuchet'=>'Trebuchet',
							'georgia' =>'Georgia',
							'times'=>'Times New Roman',
							'tahoma'=>'Tahoma, Geneva',
							'palatino'=>'Palatino',
							'helvetica'=>'Helvetica*' );			
			foreach ($faces as $i=>$face) {
				$output .= '<option value="'. $i .'" ' . selected($typography_stored['face'], $i, false) . '>'. $face .'</option>';
			}			
							
			$output .= '</select>';	
			
			/* Font Weight */	
			$output .= '<select class="of-typography of-typography-style" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';
			$styles = array('normal'=>'Normal',
							'italic'=>'Italic',
							'bold'=>'Bold',
							'bold italic'=>'Bold Italic');
							
			foreach ($styles as $i=>$style){
			
				$output .= '<option value="'. $i .'" ' . selected($typography_stored['style'], $i, false) . '>'. $style .'</option>';		
			}
			$output .= '</select>';
			
			/* Font Color */			
			$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div style="background-color: '.$typography_stored['color'].'"></div></div>';
			$output .= '<input class="of-color of-typography of-typography-color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $typography_stored['color'] .'" />';
		break;  
		case 'border':
			$default = $value['std'];
			$border_stored = array('width' => $data[$value['id'] . '_width'] ,
									'style' => $data[$value['id'] . '_style'],
									'color' => $data[$value['id'] . '_color'],
									);
				
			/* Border Width */
			$border_stored = $data[$value['id']];
			
			$output .= '<select class="of-border of-border-width" name="'.$value['id'].'[width]" id="'. $value['id'].'_width">';
				for ($i = 0; $i < 21; $i++){ 
				$output .= '<option value="'. $i .'" ' . selected($border_stored['width'], $i, false) . '>'. $i .'</option>';				 }
			$output .= '</select>';
			
			/* Border Style */
			$output .= '<select class="of-border of-border-style" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';
			
			$styles = array('none'=>'None',
							'solid'=>'Solid',
							'dashed'=>'Dashed',
							'dotted'=>'Dotted');
							
			foreach ($styles as $i=>$style){
				$output .= '<option value="'. $i .'" ' . selected($border_stored['style'], $i, false) . '>'. $style .'</option>';		
			}
			
			$output .= '</select>';
			
			/* Border Color */		
			$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div style="background-color: '.$border_stored['color'].'"></div></div>';
			$output .= '<input class="of-color of-border of-border-color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $border_stored['color'] .'" />';
		break;   
		case 'images':
			$i = 0;
			$select_value = $data[$value['id']];
  
			foreach ($value['options'] as $key => $option) 
			 { 
			 $i++;

				$checked = '';
				$selected = '';
				if(NULL!=checked($data[$value['id']], $key, false)) {
					$checked = checked($data[$value['id']], $key, false);
					$selected = 'of-radio-img-selected';  
				} 	
				$output .= '<span>';
				$output .= '<input type="radio" id="of-radio-img-' . $value['id'] . $i . '" class="checkbox of-radio-img-radio" value="'.$key.'" name="'.$value['id'].'" '.$checked.' />';
				$output .= '<div class="of-radio-img-label">'. $key .'</div>';
				$output .= '<img src="'.$option.'" alt="" class="of-radio-img-img '. $selected .'" onClick="document.getElementById(\'of-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
				$output .= '</span>';				
			}		
		break; 	
		case "info":
			$default = $value['std'];
			$output .= $default;
		break;                                   	
		case 'heading':
			if($counter >= 2){
			   $output .= '</div>'."\n";
			}
			$jquery_click_hook = ereg_replace("[^A-Za-z0-9]", "", strtolower($value['name']) );
			$jquery_click_hook = "of-option-" . $jquery_click_hook;
			$menu .= '<li><a title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'">'.  $value['name'] .'</a></li>';
			$output .= '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
		break;                                  
		} 
		
		// if TYPE is an array, formatted into smaller inputs... ie smaller values
		if ( is_array($value['type'])) {
			foreach($value['type'] as $array){
			
					$id = $array['id']; 
					$std = $array['std'];
					$saved_std = get_option($id);
					if($saved_std != $std){$std = $saved_std;} 
					$meta = $array['meta'];
					
					if($array['type'] == 'text') { // Only text at this point
						 
						 $output .= '<input class="input-text-small of-input" name="'. $id .'" id="'. $id .'" type="text" value="'. $std .'" />';  
						 $output .= '<span class="meta-two">'.$meta.'</span>';
					}
				}
		}
		if ( $value['type'] != 'heading' ) { 
			if ( $value['type'] != 'checkbox' ) 
				{ 
				$output .= '<br/>';
				}
			if(!isset($value['desc'])){ $explain_value = ''; } else{ $explain_value = $value['desc']; } 
			$output .= '</div><div class="explain">'. $explain_value .'</div>'."\n";
			$output .= '<div class="clear"> </div></div></div>'."\n";
			}
	   
	}
    $output .= '</div>';
    return array($output,$menu,$defaults);	
}


/*-----------------------------------------------------------------------------------*/
/* OptionsFramework Uploader - optionsframework_uploader_function */
/*-----------------------------------------------------------------------------------*/

public static function optionsframework_uploader_function($id,$std,$mod){

    $data =get_option(OPTIONS);
	
	$uploader = '';
    $upload = $data[$id];
	
    if ( $upload != "") { $val = $upload; } else {$val = $std;}
    
	$uploader .= '<input class="of-input" name="'. $id .'" id="'. $id .'_upload" type="hidden" value="'. $val .'" />';
	
	
	$uploader .= '<div class="upload_button_div"><span class="button image_upload_button" id="'.$id.'">Upload Image</span>';
	
	if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
	
	$uploader .= '<span class="button image_reset_button '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
	$uploader .='</div>' . "\n";
    $uploader .= '<div class="clear"></div>' . "\n";
	if(!empty($upload)){
    	$uploader .= '<a class="of-uploaded-image" href="'. $upload . '">';
    	$uploader .= '<img class="of-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
    	$uploader .= '</a>';
		}
	$uploader .= '<div class="clear"></div>' . "\n"; 

return $uploader;
}
}//end class
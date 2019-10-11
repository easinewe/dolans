/**
 * Extra Examples for NetTuts article:
 * Creating Custom Fields for Attachments in WordPress
 * http://net.tutsplus.com/tutorials/wordpress/creating-custom-fields-for-attachments-in-wordpress/
 */

function attachment_checkbox_edit($form_fields, $post) {
	
	//get an array of values
	$relatives = get_relatives_by_id();

	// get the current value of our custom field
	$current_value = get_post_meta($post->ID, "_myCheckBox", true);
	$current_value_count = count($current_value);
	
		
	// update 2010-08-05 @ 5:10pm CDT: hidden field takes over if the checkbox is unchecked, in essence deleting the value
	//$myCheckBoxHtml = "
	//<input type='hidden' name='attachments[{$post->ID}][myCheckBox]' value='' />
	//<input type='checkbox' name='attachments[{$post->ID}][myCheckBox]' id='attachments[{$post->ID}][myCheckBox]' value='accepted_terms' {$checked} />
	//";

		$myCheckBoxHtml = "<input type='hidden' name='attachments[{$post->ID}][]' value='null' />";

	foreach($relatives as $relative){
	
		// if this value is the current_value we'll mark it selected
		//$checked = ($current_value == $relative['id']) ? ' checked ' : '';
	    //$checked = (in_array($relative['id'], $current_value))? ' checked ' : '';
		//$current_val_array = array_values ( $current_value );

		$myCheckBoxHtml .= "<input type='checkbox' name='attachments[{$post->ID}][myCheckbox]' id='attachments{$relative['id']}[{$post->ID}]'"; 
		$myCheckBoxHtml .= "value='{$relative['id']}' {$checked} />";
		$myCheckBoxHtml .= "{$relative['name']} {$relative['name_last']} {$current_value} {$current_value_count}";
		$myCheckBoxHtml .= "</input><br/>";
	}
	
	$myCheckBoxHtml .= "<h1>".$attached_customs_array."</h1>";
		
	$form_fields["myCheckBox"]["label"] = __("Who is in this picture");
	$form_fields["myCheckBox"]["input"] = "html";
	$form_fields["myCheckBox"]["html"] = $myCheckBoxHtml;

	return $form_fields;
}
add_filter("attachment_fields_to_edit", "attachment_checkbox_edit", null, 2);



function save_dolan_attachment_tag_data($post, $attachment) {
	global $wpdb;
	
	//$myCustomer = $wpdb->get_row("SELECT * FROM wp_dolan_people_tags");
	//Add column if not present.
	//if(!isset($myCustomer->dolan_person_age)){
		//$wpdb->query("ALTER TABLE wp_dolan_people_tags ADD dolan_person_age INT(1) NOT NULL DEFAULT 1");
	//}
	$current_value = get_post_meta($post['ID'], $attachment['myCheckbox']);
	
	$name = 'Toby';
	$last = 'girl';
	$post_id = $attachment;
	
	$wpdb->insert('wp_dolan_people_tags',
		 array(
			  'first_name'=>$name,
			  'last_name'=>var_dump($current_value)
		 ),
		 array( 
			  '%s',
			  '%s'
		 )
	);
}

add_filter("attachment_fields_to_save", "save_dolan_attachment_tag_data", null, 2);
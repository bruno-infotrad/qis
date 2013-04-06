<div class='user-first-line'>
	<label><?php echo elgg_echo('user:name:label'); ?></label>
	<?php echo elgg_echo($vars['entity']->name); ?>
</div>
<div class='user-first-line'>
        <label><?php echo elgg_echo('username'); ?></label>
        <?php echo $vars['entity']->username ?>
</div>
<div class='user-first-line'>
        <label><?php echo elgg_echo('email'); ?></label><br />
        <?php echo $vars['entity']->email ?>
</div>
	<?php 
//Profile manager piece
	
	// Build fields
	$categorized_fields = profile_manager_get_categorized_fields($user);
	$cats = $categorized_fields['categories'];
	$fields = $categorized_fields['fields'];
	
	if($show_profile_type_on_profile != "no"){
		if($profile_type_guid = $user->custom_profile_type){
			if(($profile_type = get_entity($profile_type_guid)) && ($profile_type instanceof ProfileManagerCustomProfileType)){
				$details_result .= "<div class='even'><b>" . elgg_echo("profile_manager:user_details:profile_type") . "</b>: " . $profile_type->getTitle() . " </div>";
			}
		}
	}
	
	if(count($cats) > 0){
				
		// only show category headers if more than 1 category available
		if(count($cats) > 1){
			$show_header = true;
		} else {
			$show_header = false;
		}
		
		foreach($cats as $cat_guid => $cat){
			$cat_title = "";
			$field_result = "";
			$even_odd = "even";
			
			if($show_header){
				// make nice title
				if($cat_guid == -1){
					$title = elgg_echo("profile_manager:categories:list:system");
				} elseif($cat_guid == 0){
					if(!empty($cat)){
						$title = $cat;
					} else {
						$title = elgg_echo("profile_manager:categories:list:default");
					}
				} elseif($cat instanceof ProfileManagerCustomFieldCategory) {
					$title = $cat->getTitle();
				} else {
					$title = $cat;
				}
				
				$params = array(
					'text' => ' ',
					'href' => "#",
					'class' => 'elgg-widget-collapse-button',
					'rel' => 'toggle',
				);
				$collapse_link = elgg_view('output/url', $params);
				
				$cat_title = "<h3>" . $title . "</h3>\n";
			}
			
			foreach($fields[$cat_guid] as $field){
				
				$metadata_name = $field->metadata_name;
				
				if($metadata_name != "description"){
					// give correct class
					if($even_odd != "even"){
						$even_odd = "even";
					} else {
						$even_odd = "odd";
					}
					
					// make nice title
					$title = $field->getTitle();
					
					// get user value
					$value = $user->$metadata_name;
					
					// adjust output type
					if($field->output_as_tags == "yes"){
						$output_type = "tags";
 						if (! preg_match('/,/',$value)){
                                                        $value = $value.',';
                                                }
						$value = string_to_tag_array($value);
					} else {
						$output_type = $field->metadata_type;
					}
					
					if($field->metadata_type == "url"){
						$target = "_blank";
					} else {
						$target = null;
					}
					
					// build result
					$field_result .= "<div class='" . $even_odd . "'>";
					$field_result .= "<b>" . $title . "</b>:&nbsp;";
					$field_result .= elgg_view("output/" . $output_type, array("value" =>  $value, "target" => $target));
					$field_result .= "</div>\n";
				}
			}
			
			if(!empty($field_result)){
				$details_result .= $cat_title;
				$details_result .= "<div>" . $field_result . "</div>";	
			}
		}
	}
	
	if(!empty($details_result)){
		echo "<div id='custom_fields_userdetails'>" . $details_result . "</div>";
		if(elgg_get_plugin_setting("display_categories", "profile_manager") == "accordion"){
			?>
			<script type="text/javascript">
				$('#custom_fields_userdetails').accordion({
					header: 'h3',
					autoHeight: false
				});
			</script>
			<?php 
		}
	}
	
	if($description_position != "top"){
		echo $about;
	}

	echo '</div>';

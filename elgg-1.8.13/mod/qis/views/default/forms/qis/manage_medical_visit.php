<?php
elgg_load_library('elgg:qis');
$request_guid = elgg_extract('request_guid', $vars, 0);
$group_guid = elgg_extract('group_guid', $vars, 0);
$access_id = elgg_extract('access_id', $vars, 0);
$site_url = elgg_get_site_url();

//Existing request
if ($request_guid) {
	$submitter = elgg_get_logged_in_user_entity();
	$submit_text = elgg_echo('save');
	$request = get_entity($request_guid);
	$user_guid = $request->user_guid;
	$user = get_entity($user_guid);
	$user_name = $user->name;
	$passports = elgg_get_entities_from_metadata(array(
                'types' => 'object',
                'subtypes' => 'file',
                'container_guid' => $group->guid,
                'metadata_name_value_pairs' => array(
                                                        array('name'  => 'employee_guid', 'value' => $user_guid),
                                                        array('name'  => 'qistype', 'value' => 'passport'),
                                                ),
                'full_view' => FALSE,
        ));
	foreach ($passports as $passport) {
		$passport_values[$passport->guid] = $passport->country;
	}
	$passport_guid = $request->passport_guid;
	$relationship_with_sponsor = $request->relationship_with_sponsor;
	$duration = $request->duration;
	$in_quatar = $request->in_quatar;
	$current_visa_type = $request->current_visa_type;
	$visa_number = $request->visa_number;
	$visa_expiry_date = $request->visa_expiry_date;
	$sponsored = $request->sponsored;
	$same_sponsor = $request->same_sponsor;
	$noc = $request->noc;
	$passport_photocopy = $request->passport_photocopy;
	$registration_card = $request->registration_card;
	$labour_contract = $request->labour_contract;
	$proceed = $request->proceed;
	//Write message if no quota or quota request
	$qis_message = check_object_state($group_guid,$request,TRUE);
	if (! $qis_message) {
		$qis_message = '<h3><font color="green">READY TO BE PROCESSED</font></h3>';
	}
	?>
<div id="request-immigration-service">
	<div><?php echo $qis_message; ?></div>
	<div>
	        <label><?php echo elgg_echo("person"); ?>: </label>
	        <?php echo elgg_echo($user_name); ?>
	        <?php echo elgg_view('input/hidden', array('name' => 'user_guid', 'value' => $user_guid)); ?>
	</div>
	<div id="qis-selected-user">
		<?php echo elgg_view('qis/view_person_and_citizenships',array('user_guid' => $user_guid)); ?>
	</div>
	<div>
	        <label><?php echo elgg_echo("passport_country"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'passport_guid',
				'value' => $passport_guid,
				'options_values' => $passport_values,
				));
		?>
	</div>
	<div>
	        <label><?php echo elgg_echo("sponsor"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'relationship_with_sponsor',
				'value' => $relationship_with_sponsor,
				'options' => array('BSG','IBM','QSHIELD'),
				));
		?>
	</div>
	<div>
	        <label><?php echo elgg_echo("duration"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'duration',
				'value' => $duration,
				'options' => array('1','2','3'),
				));
		?>
	</div>
	<div>
	        <label><?php echo elgg_echo("in_quatar"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'in_quatar',
				'value' => $in_quatar,
				'options' => array('no','yes'),
				'id' => 'qis-in-quatar-dropdown',
				));
		?>
	</div>
	<div class='qis-current-visa-type'>
	        <label><?php echo elgg_echo("current_visa_type"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'current_visa_type',
				'value' => $current_visa_type,
				'options' => array('tourist','business'),
				));
		?>
	        <label><?php echo elgg_echo("visa_number"); ?>: </label>
	        <?php echo elgg_view('input/text', array('name' => 'visa_number', 'value' => $visa_number,'class' => 'visa')); ?>
	        <label><?php echo elgg_echo("visa_expiry_date"); ?>: </label>
	        <?php echo elgg_view('input/text', array('name' => 'visa_expiry_date', 'value' => $visa_expiry_date, 'class' => 'visa')); ?>
	</div>
	<div class='qis-current-visa-type'>
	        <label><?php echo elgg_echo("sponsored"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'sponsored',
				'value' => $sponsored,
				'options' => array('yes','no'),
				'class' => 'qis-current-visa-sponsor-dropdown',
				));
		?>
	</div>
	<div class='qis-visa-sponsored'>
	        <label><?php echo elgg_echo("same_sponsor"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'same_sponsor',
				'value' => $same_sponsor,
				'options' => array('yes','no'),
				'id' => 'qis-same-sponsor-dropdown',
				));
		?>
	</div>
	<div class='qis-noc'>
		<label><?php echo elgg_echo('upload_noc'); ?></label><br />
        	<?php echo elgg_view('input/file', array('name' => 'noc', 'value' => $noc)); ?>
	</div>
<?php
	if ($submitter->qisusertype == 'Immigration Agency Portal Coordinator') {
?>
		<div><label><?php echo elgg_echo("passport_photocopy"); ?>: </label>
		<?php echo elgg_view("input/checkbox", array( 'name' => 'passport_photocopy', 'value' => 1, 'checked' => $passport_photocopy == 1, 'class' => 'qis-passport-photocopy-checkbox')); ?>
		</div>
		<div><label><?php echo elgg_echo("company_registration_card"); ?>: </label>
		<?php echo elgg_view("input/checkbox", array( 'name' => 'registration_card', 'value' => 1, 'checked' => $registration_card == 1, 'class' => 'qis-registration-card-checkbox')); ?>
		</div>
		<div><label><?php echo elgg_echo("company_labour_contract"); ?>: </label>
		<?php echo elgg_view("input/checkbox", array( 'name' => 'labour_contract', 'value' => 1, 'checked' => $labour_contract == 1, 'class' => 'qis-labour-contract-checkbox')); ?>
		</div>
		<div class="elgg-foot mts">
			<div class='qis-rp-proceed'>
			<?php
			if ($proceed) {
				echo '<h3>'.elgg_echo($proceed).'</h3>';
			} else {
				echo elgg_view('input/submit', array('name' => 'submit', 'value' => 'Proceed with egov', 'id' => 'qis-submit-button',));
				echo elgg_view('input/submit', array('name' => 'submit', 'value' => 'Proceed with paper application', 'id' => 'qis-submit-button',));
			}
			?>
			</div></br>
		<?php
		echo elgg_view('input/hidden', array('name' => 'access_id', 'value' => $access_id));
		echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $group_guid));
		echo elgg_view('input/hidden', array('name' => 'request_guid', 'value' => $request_guid));
		echo elgg_view('input/submit', array('name' => 'submit', 'value' => $submit_text, 'id' => 'qis-submit-button',));
		echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('delete'), 'id' => 'qis-submit-button',));
		?>
		</div>
<?php
	}
?>
</div>
<?php
//New request
} else {
	$submit_text = elgg_echo('submit');
	$group_members = get_group_members($group_guid,0);
	$request_options = array();
	$request_options[] = '';
	foreach ($group_members as $member) {
		if (! $member->isAdmin()){
	        	$request_options[$member->guid] = $member->name;
		}
	}
	$request_drop_down = elgg_view('input/dropdown', array(
				'name' => 'user_guid',
				'value' => 'user_guid',
				'options_values' => $request_options,
				'id' => 'qisuser-dropdown',
				));
	?>
	<div id="request-immigration-service">
	<div>
	        <label><?php echo elgg_echo("person"); ?>: </label>
	        <?php echo $request_drop_down; ?>
	</div>
	<div id="qis-selected-user">
	</div>
	<div>
	        <label><?php echo elgg_echo("passport_country"); ?>: </label>
		<?php echo elgg_view("input/citizenship_document_dropdown", array('group_guid' => $group_guid)); ?>
	</div>
	<div>
	        <label><?php echo elgg_echo("sponsor"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'relationship_with_sponsor',
				'value' => 'relationship_with_sponsor',
				'options' => array('BSG','IBM','QSHIELD'),
				));
		?>
	</div>
	<div>
	        <label><?php echo elgg_echo("duration"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'duration',
				'value' => 'duration',
				'options' => array('1','2','3'),
				));
		?>
	</div>
	<div>
	        <label><?php echo elgg_echo("in_quatar"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'in_quatar',
				'value' => 'in_quatar',
				'options' => array('no','yes'),
				'id' => 'qis-in-quatar-dropdown',
				));
		?>
	</div>
	<div class='qis-current-visa-type'>
	        <label><?php echo elgg_echo("current_visa_type"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'current_visa_type',
				'value' => 'current_visa_type',
				'options' => array('tourist','business'),
				));
		?>
	        <label><?php echo elgg_echo("visa_number"); ?>: </label>
	        <?php echo elgg_view('input/text', array('name' => 'visa_number', 'class' => 'visa')); ?>
	        <label><?php echo elgg_echo("visa_expiry_date"); ?>: </label>
	        <?php echo elgg_view('input/text', array('name' => 'visa_expiry_date', 'class' => 'visa')); ?>
	</div>
	<div class='qis-current-visa-type'>
	        <label><?php echo elgg_echo("sponsored"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'sponsored',
				'value' => 'no',
				'options' => array('yes','no'),
				'class' => 'qis-current-visa-sponsor-dropdown',
				));
		?>
	</div>
	<div class='qis-visa-sponsored'>
	        <label><?php echo elgg_echo("same_sponsor"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'same_sponsor',
				'options' => array('yes','no'),
				'id' => 'qis-same-sponsor-dropdown',
				));
		?>
	</div>
	<div class='qis-noc'>
		<label><?php echo elgg_echo('upload_noc'); ?></label><br />
        	<?php echo elgg_view('input/file', array('name' => 'upload')); ?>
	</div>

	</div>
	<div class="elgg-foot mts">
	<?php
	echo elgg_view('input/hidden', array('name' => 'access_id', 'value' => $access_id));
	echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $group_guid));
	echo elgg_view('input/hidden', array('name' => 'request_guid', 'value' => $request_guid));
	echo elgg_view('input/submit', array('name' => 'submit', 'value' => $submit_text, 'id' => 'qis-submit-button',));
	?>
	</div>
<?php	
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#qisuser-dropdown").change(function() {
		var $dropdown = $(this);
		//alert($dropdown.prop('outerHTML'));
		var user_guid = $dropdown.children("option").filter(":selected").val();
		var url = "<?php echo $site_url; ?>qis/view_person/<?php echo $group_guid;?>/"+user_guid;
		$.get( url, {},function(data){
			//alert($dropdown.children("option").filter(":selected").val());
			//alert(data.toSource());
			var $selectedUser = $("#qis-selected-user");
			$selectedUser.empty();
			$selectedUser.html(data);
		});
	});
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	var $dropdown = $("#qis-in-quatar-dropdown");
	var inQuatar = $dropdown.children("option").filter(":selected").val();
	if (inQuatar == 'yes') {
		$(".qis-current-visa-type").show();
	} else {
		$(".qis-current-visa-type").hide();
	};
	$("#qis-in-quatar-dropdown").change(function() {
		var $thisdropdown = $(this);
		var inQuatar = $thisdropdown.children("option").filter(":selected").val();
		if (inQuatar == 'yes') {
			$(".qis-current-visa-type").show();
		} else {
			$(".qis-current-visa-type").hide();
		}
	});
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	var $dropdown = $(".qis-current-visa-sponsor-dropdown");
	var sponsored = $dropdown.children("option").filter(":selected").val();
	if (sponsored == 'yes') {
		$(".qis-visa-sponsored").show();
	} else {
		$(".qis-visa-sponsored").hide();
	};
	$(".qis-current-visa-sponsor-dropdown").change(function() {
		var $thisdropdown = $(this);
		var sponsored = $thisdropdown.children("option").filter(":selected").val();
		if (sponsored == 'yes') {
			$(".qis-visa-sponsored").show();
		} else {
			$(".qis-visa-sponsored").hide();
		}
	});
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	var $dropdown = $("#qis-same-sponsor-dropdown");
	var sameSponsor = $dropdown.children("option").filter(":selected").val();
	if (sameSponsor == 'no') {
		$(".qis-noc").show();
	} else {
		$(".qis-noc").hide();
	};
	$("#qis-same-sponsor-dropdown").change(function() {
		var $thisdropdown = $(this);
		var sameSponsor = $thisdropdown.children("option").filter(":selected").val();
		if (sameSponsor == 'no') {
			$(".qis-noc").show();
		} else {
			$(".qis-noc").hide();
		}
	});
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	var n = $( "input:checked" ).length;
	if (n === 3) {
		$(".qis-rp-proceed").show();
	} else {
		$(".qis-rp-proceed").hide();
	};
	$("input[type=checkbox]").click(function() {
		var n = $( "input:checked" ).length;
		if (n === 3) {
			$(".qis-rp-proceed").show();
		} else {
			$(".qis-rp-proceed").hide();
		};
	});
});
</script>

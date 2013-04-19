<?php
// Function to diff incoming and existing objects and return HTML code for annotation in action
function process_diff($input_request,$existing_request) {
	$content = '';
	foreach ($input_request as $key => $value) {
		if (! $existing_request->$key) {
			$content .= "<p>Added $key=>$value to request</p>";
		} elseif ($existing_request->$key != $value) {
			$content .= "<p>modified $key from $existing_request->value to $value to request</p>";
		}
	}
	if($content) {
		$content = '<hr>'.$content;
	}
	return $content;
}
// State engine
function check_object_state($group = NULL, $object=NULL,$html) {
	$content = '';
	$submitter = elgg_get_logged_in_user_entity();
	if ($object) {
		if ($object->qistype == 'resident_permit_request') {
			$user_guid = $object->user_guid;
			$user = get_entity($user_guid);
			// First check details visa, NOC
			if ($submitter->qisusertype == 'Client Portal Administrator') {
				if ($object->in_quatar == 'yes') {
        				if (! $object->visa_number || ! $object->visa_expiry_date) {
						$content .= '<h3><font color="red">'.elgg_echo('need_visa_info_for ')."$user->name</font></h3>";
					}
					if (($object->sponsored == 'yes') && ($object->same_sponsor == 'no') && ! $object->noc) {
						$documents = elgg_get_entities_from_metadata(array(
									'types' => 'object',
									'subtypes' => 'file',
									'container_guid' => $group->guid,
									'metadata_name_value_pairs' => array(
										array('name'  => 'qistype', 'value' => 'document'),
										array('name'  => 'document_type', 'value' => 'noc'),
										array('name'  => 'request_guid', 'value' => $object->guid),
									),
									'full_view' => FALSE,
									'count' => true,
								));
						if ($documents != 1) {
							$content .= '<h3><font color="red">'.elgg_echo('need_noc_for ')."$user->name</font></h3>";
						}
					}
				}
			}
			// Then check quotas
			$occupation = $user->profession;
			$gender = $user->gender;
			$passport_guid = $object->passport_guid;
			$citizenship = get_entity($passport_guid)->country;
			$num_quotas[$citizenship][$occupation][$gender]+=1;
			foreach ($num_quotas as $citizenship_loop=>$value1) {
				foreach ($value1 as $occupation_loop=>$value2) {
					foreach ($value2 as $gender_loop=>$quantity_loop) {
						$quotas = elgg_get_entities_from_metadata(array(
							'types' => 'object',
							'subtypes' => 'quota',
							'container_guid' => $group->guid,
							'metadata_name_value_pairs' => array(
								array('name'  => 'citizenship', 'value' => $citizenship_loop),
								array('name'  => 'occupation', 'value' => $occupation_loop),
								array('name'  => 'gender', 'value' => $gender_loop),
							),
							'full_view' => FALSE,
							'count' => true
						));
						if ( $quotas ) {
							if ($quotas < $quantity_loop) {
								if ($submitter->qisusertype == 'Client Portal Administrator') {
									if ($html) {
										$content .= '<h3><font color="red">'.elgg_echo('need__additional_quota_request_for ')."$citizenship_loop $occupation_loop $gender_loop</font></h3>";
									} else {
										$content = 'NOT_ENOUGH_QUOTA_OR_QUOTA_REQUEST';
									}
								}
							//quota found go through things to do
							} else {
								if ($submitter->qisusertype == 'Immigration Agency Portal Coordinator') {
									//To do review RP
									if(! $object->passport_photocopy) {
										if ($html) {
											$content .= '<h3><font color="red">'.elgg_echo('check_passport_photocopy_for_request_id ')."$object->guid</font></h3>";
										} else {
											$content = 'PASSPORT_PHOTOCOPY';
										}

									}
									if(! $object->registration_card) {
										if ($html) {
											$content .= '<h3><font color="red">'.elgg_echo('check_registration_card_for_request_id ')."$object->guid</font></h3>";
										} else {
											$content = 'REGISTRATION_CARD';
										}

									}
									if(! $object->labour_contract) {
										if ($html) {
											$content .= '<h3><font color="red">'.elgg_echo('check_labour_contract_for_request_id ')."$object->guid</font></h3>";
										} else {
											$content = 'LABOUR_CONTRACT';
										}
									}
									//To do medical
									if($object->proceed) {
										if (! $object->medical_visit) {
											$content .= '<h3><font color="red">'.elgg_echo('schedule_medical_for ')."$user->name</font></h3>";
										}
										if (! $object->blood_test) {
											$content .= '<h3><font color="red">'.elgg_echo('schedule_blood_test_for ')."$user->name</font></h3>";
										}
										if (! $object->fingerprints) {
											$content .= '<h3><font color="red">'.elgg_echo('schedule_fingerprinting_for ')."$user->name</font></h3>";
										}
									}
								}
							}
						} else {
							if ($submitter->qisusertype == 'Client Portal Administrator') {
								$quota_requests = elgg_get_entities_from_metadata(array(
								'types' => 'object',
								'subtypes' => 'immigration_request',
								'container_guid' => $group->guid,
								'metadata_name_value_pairs' => array(
									array('name'  => 'qistype', 'value' => 'quota_request'),
									array('name'  => 'citizenship', 'value' => $citizenship_loop),
									array('name'  => 'occupation', 'value' => $occupation_loop),
									array('name'  => 'gender', 'value' => $gender_loop),
								),
								'full_view' => TRUE,
								'count' => true
								));
								/*
								$quota_requests = elgg_get_entity_metadata_where_sql('elgg_entities','elgg_metastrings',NULL,NULL, array( array('name'  => 'qistype', 'value' => 'quota_request'), array('name'  => 'citizenship', 'value' => $citizenship), array('name'  => 'occupation', 'value' => $occupation), array('name'  => 'gender', 'value' => $gender)));
								$content .= var_export($quota_requests,true).'<br>';
								//$content .= 'count='.$quota_requests;
								foreach ($quota_requests as $quota_request) {
								$content .= var_export($quota_request,true).'<br>';
								$content .= 'CITIZENSHIP='.var_export($quota_request->citizenship,true).'<br>';
								}
								*/
								if (! $quota_requests ) {
									if ($html) {
										$content .= '<h3><font color="red">'.elgg_echo('need_quota_request_for ')."$citizenship_loop $occupation_loop $gender_loop</font></h3>";
									} else {
										$content = 'NO_QUOTA_OR_QUOTA_REQUEST';
									}
								} elseif ($quota_requests < $quantity_loop) {
									if ($html) {
										$content .= '<h3><font color="red">'.elgg_echo('need__additional_quota_request_for ')."$citizenship_loop $occupation_loop $gender_loop</font></h3>";
									} else {
										$content = 'NOT_ENOUGH_QUOTA_OR_QUOTA_REQUEST';
									}
								}
							}
						}
					}
				}
			}
		} elseif ($object->qistype == 'quota_request') {
			if ($submitter->qisusertype == 'Immigration Agency Portal Coordinator') {
				$quantity = $object->quantity;
				if (is_array($quantity)){
					//number of array members
					$num_lines = count($quantity);
        				for ($i=0;$i<$num_lines;$i++) {
						if ($object->status[$i] == 'Pending') {
                					$content .= '<h3><font color="red">'.elgg_echo('pending_quota_request_for ').$object->guid.'</font></h3>';
						}
					}
				} else {
					if ($object->status == 'Pending') {
                				$content .= '<h3><font color="red">'.elgg_echo('pending_quota_request_for ').$object->guid.'</font></h3>';
					}
				}
			}
		}
	}
	return $content;
}

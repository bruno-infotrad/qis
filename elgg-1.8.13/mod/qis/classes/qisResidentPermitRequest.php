<?php
/**
 * Extended class to override the time_created
 */
class qisResidentPermitRequest extends ElggObject {

	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'immigration_request';
		//$this->attributes['qistype'] = 'resident_permit_request';
	}
}

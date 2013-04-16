<?php
/**
 * Extended class to override the time_created
 */
class qisWorkVisaRequest extends ElggObject {

	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'immigration_request';
	}
}

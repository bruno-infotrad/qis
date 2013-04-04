<?php
/**
 * Extended class to override the time_created
 */
class qisResidentPermit extends ElggObject {

	/**
	 * Set subtype to GCuser.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'resident_permit';
	}
}

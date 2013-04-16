<?php
/**
 * Extended class to override the time_created
 */
class qisQuota extends ElggObject {

	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'quota';
	}
}

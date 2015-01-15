<?php

/**
 * @file tests/data/ContentBaseTestCase.inc.php
 *
 * Copyright (c) 2014 Simon Fraser University Library
 * Copyright (c) 2000-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ContentBaseTestCase
 * @ingroup tests_data
 *
 * @brief Data build suite: Base class for content creation tests
 */

import('lib.pkp.tests.data.PKPContentBaseTestCase');

class ContentBaseTestCase extends PKPContentBaseTestCase {
	/**
	 * Handle any section information on submission step 1
	 * @return string
	 */
	protected function _handleSection($data) {
		$section = 'Articles'; // Default
		if (isset($data['section'])) $section = $data['section'];

		// Page 1
		$this->waitForElementPresent('id=sectionId');
		$this->select('id=sectionId', 'label=' . $this->escapeJS($section));
	}

	/**
	 * Get the number of items in the default submission checklist
	 * @return int
	 */
	protected function _getChecklistLength() {
		return 6;
	}

	/**
	 * Assign a copyeditor by name.
	 * @param $name string Needs to be in the form "lastname, firstname"
	 */
	protected function assignCopyeditor($name) {
		$this->clickAndWait('link=Editing');
		$this->clickAndWait('link=Assign Copyeditor');
		$this->clickAndWait('//td/a[contains(text(),\''. $this->escapeJS($name) . '\')]/../..//a[text()=\'Assign\']');
	}

	/**
	 * Assign a layout editor by name.
	 * @param $name string Needs to be in the form "lastname, firstname"
	 */
	protected function assignLayoutEditor($name) {
		$this->clickAndWait('link=Editing');
		$this->clickAndWait('link=Assign Layout Editor');
		$this->clickAndWait('//td/a[contains(text(),\''. $this->escapeJS($name) . '\')]/../..//a[text()=\'Assign\']');
	}

	/**
	 * Assign a layout editor by name.
	 * @param $name string Needs to be in the form "lastname, firstname"
	 */
	protected function assignProofreader($name) {
		$this->clickAndWait('link=Editing');
		$this->clickAndWait('link=Assign Proofreader');
		$this->clickAndWait('//td/a[contains(text(),\''. $this->escapeJS($name) . '\')]/../..//a[text()=\'Assign\']');
	}
	
	protected function uploadArticleGalley($galleyTypeSelector, $user = null, $title = null, $file = null) {
		if (is_null($file)) {
			$file = getenv('DUMMYFILE');
		}
		
		if (!is_null($user) && !is_null($title)) {
			$this->findSubmissionAsEditor($user, null, $title);
		}
		
		$this->clickAndWait('link=Editing');
		$this->waitForElementPresent('id=issueId');
		$this->clickAndWait('css=input.button.defaultButton');
		$this->waitForElementPresent('id=layoutFileTypeGalley');
		$this->click('id=layoutFileTypeGalley');
		$this->uploadFile($file, 'name=layoutFile', 'css=#layout input[value=Upload]');
		$this->waitForElementPresent('id=label');
		$this->type('id=label', "pdf");
		$this->clickAndWait('css=input.button.defaultButton');
	}
	
	protected function scheduleIssue($issueSelector) {
		$this->select('id=issueId', $issueSelector);
		$this->clickAndWait('//div[@id=\'scheduling\']//input[@value=\'Record\']');
		//$this->clickAndWait("xpath=(//input[@value='Record'])[2]");
	}
	
	protected function uploadIssueGalley($issueSelector, $isPublish = true, $user = null, $file = null) {
		if (is_null($file)) {
			$file = getenv('DUMMYFILE');
	    }
	    
	    if (!is_null($user)) {
	    	$this->logIn($user);
	    }
	    
	    $this->open(self::$baseUrl . '/index.php/publicknowledge/editor');
	    
	    if ($isPublish) {
	    	$this->clickAndWait('link=Back Issues');
	    } else {
	    	$this->clickAndWait('link=Future Issues');
	    }
	    
	    $this->clickAndWait($issueSelector);
	    $this->clickAndWait('link=Issue Galleys');
	    $this->uploadFile($file, 'galleyFile', 'css=#issueId > input.button');
	    $this->waitForElementPresent('id=label');
	    $this->type('id=label', 'pdf');
	    $this->clickAndWait('css=input.button.defaultButton');
	}
	
}

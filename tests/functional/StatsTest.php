<?php

/**
 * @file tests/functional/StatsTest.inc.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2000-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class StatsTest
 * @ingroup tests_functional
 *
 * @brief Integration/Functional test for stats.
 */


import('tests.data.ContentBaseTestCase');
import('lib.pkp.classes.file.PrivateFileManager');
import('lib.pkp.classes.scheduledTask.ScheduledTaskDAO');

class StatsTest extends ContentBaseTestCase {
	
	function testStatsProcessing(){
		//$this->uploadArticleGalley('id=layoutFileTypeGalley', 'dbarnes', 'The Facets Of Job Satisfaction: A Nine-Nation Comparative Study Of Construct Equivalence');
		//$this->scheduleIssue('label=Vol 1, No 1 (2014)');
		
		$fileManager = new PrivateFileManager();
		$plugin = PluginRegistry::loadPlugin('generic', 'usageStats');

		$source = $fileManager->getBasePath() . '/usageStats/usageEventLogs/' . $plugin->getUsageEventCurrentDayLogName();
		$dest = $fileManager->getBasePath() . '/usageStats/usageEventLogs/testStats.log';
		
		$fileManager->copyFile($source, $dest);
		
		if (file_exists($dest)) {
			chmod($dest, 0777);
		}
		
		$scheduledTaskDao = new ScheduledTaskDAO();
		$scheduledTaskDao->updateLastRunTime('plugins.generic.usageStats.UsageStatsLoader', time() - 60 * 60 * 24 * 2);

		$this->open(self::$baseUrl);
	}
}

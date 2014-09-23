<?php

require_once 'CiviTest/CiviUnitTestCase.php';

/**
 * FIXME
 */
class CRM_Training_MyTest extends CiviUnitTestCase {
  function setUp() {
    // If your test manipulates any SQL tables, then you should truncate
    // them to ensure a consisting starting point for all tests
    // $this->quickCleanup(array('example_table_name'));
    parent::setUp();
  }

  function tearDown() {
    parent::tearDown();
  }

  function testUpdateSettingsTrue() {
    $params = array(
      'display_membership' => 1,
      'display_contribution_total' => 1,
    );
    CRM_Training_Form_Setting::updateSettings($params);

    $isMemBlock = CRM_Core_DAO::singleValueQuery("SELECT value FROM civicrm_training_settings WHERE name = 'display_membership'");
    $this->assertEquals(1, $isMemBlock);

    $isContribBlock = CRM_Core_DAO::singleValueQuery("SELECT value FROM civicrm_training_settings WHERE name = 'display_contribution_total'");
    $this->assertEquals(1, $isContribBlock);
  }

  function testUpdateSettingsFalse() {
    $params = array(
      'display_membership' => 0,
      'display_contribution_total' => 0,
    );
    CRM_Training_Form_Setting::updateSettings($params);

    $isMemBlock = CRM_Core_DAO::singleValueQuery("SELECT value FROM civicrm_training_settings WHERE name = 'display_membership'");
    $this->assertEquals(0, $isMemBlock);

    $isContribBlock = CRM_Core_DAO::singleValueQuery("SELECT value FROM civicrm_training_settings WHERE name = 'display_contribution_total'");
    $this->assertEquals(0, $isContribBlock);
  }
}

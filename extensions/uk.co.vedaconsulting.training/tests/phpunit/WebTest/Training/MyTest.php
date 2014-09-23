<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.4                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License along with this program; if not, contact CiviCRM LLC       |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

require_once 'CiviTest/CiviSeleniumTestCase.php';
class WebTest_Training_MyTest extends CiviSeleniumTestCase {

  protected function setUp() {
    parent::setUp();
  }

  function testBlocksDisplayedPerSettings() {
    $this->webtestLogin();

    $this->openCiviPage('training/settings', "reset=1", "_qf_Setting_submit");

    if(!$this->isChecked("display_membership")) {
      $this->click("display_membership");
    }
    if(!$this->isChecked("display_contribution_total")) {
      $this->click("display_contribution_total");
    }
    // Clicking save.
    $this->click("_qf_Setting_submit");

    $this->waitForPageToLoad($this->getTimeoutMsec());
    $this->waitForText('crm-notification-container', "Training settings saved");

    // find contributions
    $this->openCiviPage('member/search', 'reset=1', '_qf_Search_refresh');
    $this->click("_qf_Search_refresh");

    $this->waitForElementPresent("_qf_Search_next_print");
    $this->click("xpath=//div[@id='memberSearch']/table/tbody/tr/td[3]/a");
    $this->waitForTextPresent("Employer");

    $this->assertElementContainsText("xpath=//div[@id='custom-contributions']/h3", 'Contribution Summary');
    $this->assertElementContainsText("xpath=//div[@id='custom-memberships']/h3", 'Memberships');
  }
}


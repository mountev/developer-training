<?php

require_once 'training.civix.php';

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function training_civicrm_config(&$config) {
  _training_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function training_civicrm_xmlMenu(&$files) {
  _training_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function training_civicrm_install() {
  return _training_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function training_civicrm_uninstall() {
  return _training_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function training_civicrm_enable() {
  return _training_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function training_civicrm_disable() {
  return _training_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function training_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _training_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function training_civicrm_managed(&$entities) {
  return _training_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function training_civicrm_caseTypes(&$caseTypes) {
  _training_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function training_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _training_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implementation of hook_civicrm_summary
 *
 * To display contribution summary total & membership information in Contact summary screen
 *
 */
function training_civicrm_summary( $contactID, &$content, &$contentPlacement = CRM_Utils_Hook::SUMMARY_BELOW ) {
  $contentPlacement = CRM_Utils_Hook::SUMMARY_ABOVE;

  $contributionContent = $membershipContent = '';

  // Get Contributions
  $result = civicrm_api3('Contribution', 'get', array(
    'contact_id' => $contactID,
  ));

  $total = 0;
  foreach ($result['values'] as $key => $value)  {
    $total += $value['total_amount'];
  }

  if ($total > 0) {
    $total = CRM_Utils_Money::format($total);
    $contributionContent = <<<EOT
      <div id="custom-contributions">
      <h3>Contribution Summary</h3>
      <div class="crm-summary-row">
        <div class="crm-label">Total</div>
        <div class="crm-content crm-contact-contribution_summary">{$total}</div>
      </div>
    </div>
EOT;
  }

  // Get Memberships
  $result = civicrm_api3('Membership', 'get', array(
    'contact_id' => $contactID,
  ));

  if (!empty($result['values'])) {

    $rows = "";

    foreach ($result['values'] as $key => $value) {
      // Get Membership Status label
      $statusResult = civicrm_api3('MembershipStatus', 'getsingle', array(
        'id' => $value['status_id'],
      ));

      $rows .= "<tr>
                  <td>{$value['membership_name']}</td>
                  <td>".CRM_Utils_Date::customFormat($value['join_date'])."</td>
                  <td>".CRM_Utils_Date::customFormat($value['start_date'])."</td>
                  <td>".CRM_Utils_Date::customFormat($value['end_date'])."</td>
                  <td>".$statusResult['label']."</td>
                </tr>";
    }

    $membershipContent = <<<EOT
      <div id="custom-memberships">
        <h3>Memberships</h3>
        <table class="selector row-highlight">
        <thead class="sticky">
            <th scope="col">Membership</th>
            <th scope="col">Member Since</th>
            <th scope="col">Start Date</th>
            <th scope="col">End Date</th>
            <th scope="col">Status</th>
        </thead>
        <tbody>
          {$rows}
        </tbody>
        </table>
      </div>
EOT;
  }

  $content = $contributionContent.$membershipContent;

}

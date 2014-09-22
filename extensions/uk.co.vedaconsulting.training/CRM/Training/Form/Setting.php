<?php

class CRM_Training_Form_Setting extends CRM_Core_Form {

  /**
   * Function to actually build the form
   *
   * @return None
   * @access public
   */
  public function buildQuickForm() {
    $this->addElement('checkbox', 'display_membership', ts('Membership details on summary screen?'));
    $this->addElement('checkbox', 'display_contribution_total', ts('Contribution totals on summary screen?'));
    
    // Create the Submit Button.
    $buttons = array(
      array(
        'type' => 'submit',
        'name' => ts('Save'),
      ),
      array(
        'type' => 'cancel',
        'name' => ts('Cancel'),
      ),
    );

    // Add the Buttons.
    $this->addButtons($buttons);
  }

  public function setDefaultValues() {
    $defaults = array();

    $sql = "SELECT * FROM civicrm_training_settings";
    $dao = CRM_Core_DAO::executeQuery($sql);
    while ($dao->fetch()) {
      $defaults[$dao->name] = $dao->value;
    }
    return $defaults;
  }

  /**
   * Function to process the form
   *
   * @access public
   *
   * @return None
   */
  public function postProcess() {
    $params = $this->controller->exportValues($this->_name);    

    $sql = "INSERT into civicrm_training_settings (name, value) VALUES (%1, %2), (%3, %4) ON DUPLICATE KEY UPDATE value = VALUES(value)";
    $sparams = array(
      1 => array('display_membership', 'String'),
      2 => array((int) CRM_Utils_Array::value('display_membership', $params), 'Integer'),
      3 => array('display_contribution_total', 'String'),
      4 => array((int) CRM_Utils_Array::value('display_contribution_total', $params), 'Integer')
    ); 
    CRM_Core_DAO::executeQuery($sql, $sparams);
    CRM_Core_Session::setStatus(ts('Training settings saved.'));
  }
}

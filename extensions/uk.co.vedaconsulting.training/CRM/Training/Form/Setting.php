<?php

class CRM_Training_Form_Setting extends CRM_Core_Form {

  /**
   * Function to actually build the form
   *
   * @return None
   * @access public
   */
  public function buildQuickForm() {
    $this->addElement('checkbox', 'display_membership', ts('Display membership details on summary screen?'));
    $this->addElement('checkbox', 'display_contribution_total', ts('Display contribution totals on summary screen?'));
    
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
      
    CRM_Core_Session::setStatus($message);
  }
}

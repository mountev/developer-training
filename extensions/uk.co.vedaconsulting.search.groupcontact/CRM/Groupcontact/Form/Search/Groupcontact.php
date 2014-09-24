<?php

/**
 * A custom contact search
 */
class CRM_Groupcontact_Form_Search_Groupcontact extends CRM_Contact_Form_Search_Custom_Base implements CRM_Contact_Form_Search_Interface {
  function __construct(&$formValues) {
    parent::__construct($formValues);
  }

  function preProcess() {
    if (!CRM_Core_Permission::check('access custom search form')) {
        CRM_Utils_System::permissionDenied();
        CRM_Utils_System::civiExit();
      }
  }

  /**
   * Prepare a set of search fields
   *
   * @param CRM_Core_Form $form modifiable
   * @return void
   */
  function buildForm(&$form) {
    CRM_Utils_System::setTitle(ts('Custom Search Group Contact'));
    $aElements = array();

    #get all groups
    $aGroups  = CRM_Core_PseudoConstant::group();

    $form->addElement('advmultiselect', 'group_contact',
      ts('Group(s)') . ' ', $aGroups,
      array(
        'size' => 5,
        'style' => 'width:240px',
        'class' => 'advmultiselect',
      )
    );
    #assign to element
    $aElements[] = 'group_contact';

    #date range
    $form->addDate('start_date', 'From :', FALSE, array('formatType' => 'searchDate'));
    $form->addDate('end_date', 'To :', FALSE, array('formatType' => 'searchDate'));

    $aElements[] = 'start_date';
    $aElements[] = 'end_date';

    #to get all status
    /*$oGroupContact  = new CRM_Contact_DAO_GroupContact;
    $aGroupFields   = $oGroupContact->fields();
    $sStatus        = $aGroupFields['status']['enumValues'];
    $aStatus        = explode(', ', $sStatus);*/

    $aStatus = CRM_Core_SelectValues::groupContactStatus();

    foreach( $aStatus as $status ){
      //$form->addElement('checkbox',  "status[{$status}]",$status,    '', array('class' => 'group_status'));
      $form->addElement('checkbox', $status,    ts("{$status}"), '', array('class' => 'group_status'));
      //$form->addElement('checkbox', "status[{$status}]" ,  $status, '', array('class' => 'group_status'));
      $aElements[] = $status;
    }

    /**
     * if you are using the standard template, this array tells the template what elements
     * are part of the search criteria
     */
    $this->_elements = $aElements;
    $form->assign('elements', $aElements);
  }

  /**
   * Get a list of summary data points
   *
   * @return mixed; NULL or array with keys:
   *  - summary: string
   *  - total: numeric
   */
  function summary() {
    return NULL;
    // return array(
    //   'summary' => 'This is a summary',
    //   'total' => 50.0,
    // );
  }

  /**
   * Get a list of displayable columns
   *
   * @return array, keys are printable column headers and values are SQL column names
   */
  function &columns() {
    // return by reference
    $columns = array(
      ts('Contact Id') => 'contact_id',
      ts('Contact Type') => 'contact_type',
      ts('Name') => 'sort_name',
      ts('Status') => 'status',
      ts('Date') => 'date_added',
    );
    return $columns;
  }

  /**
   * Construct a full SQL query which returns one page worth of results
   *
   * @return string, sql
   */
  function all($offset = 0, $rowcount = 0, $sort = NULL, $includeContactIDs = FALSE, $justIDs = FALSE) {
    // delegate to $this->sql(), $this->select(), $this->from(), $this->where(), etc.
    $sql = $this->sql($this->select(), $offset, $rowcount, $sort, $includeContactIDs, $this->groupBy());
//   print_r($sql); die();
    return $sql;
  }

  function groupBy(){
    return "Group By contact_a.id";
  }

  /**
   * Construct a SQL SELECT clause
   *
   * @return string, sql fragment with SELECT arguments
   */
  function select() {
    return "
      contact_a.id           as contact_id  ,
      contact_a.contact_type as contact_type,
      contact_a.sort_name    as sort_name,
      group_contact.status   as status,
      history.date           as date_added
    ";
  }
  /**
   * Construct a SQL FROM clause
   *
   * @return string, sql fragment with FROM and JOIN clauses
   */
  function from() {
    return "
      FROM      civicrm_contact contact_a
      JOIN civicrm_group_contact group_contact ON ( group_contact.contact_id       = contact_a.id  )
      JOIN civicrm_group cgroup ON ( cgroup.id = group_contact.group_id  )
      JOIN civicrm_subscription_history history ON ( cgroup.id = history.group_id  AND contact_a.id = history.contact_id )
    ";
  }
  /**
   * Construct a SQL WHERE clause
   *
   * @return string, sql fragment with conditional expressions
   */
  function where($includeContactIDs = FALSE) {
    $params = array();
    $aWhereClause = array();
    $count  = 1;
    #group
    $atemp = array();
    $aSelectedGroups = CRM_Utils_Array::value( 'group_contact', $this->_formValues );
    if(!empty($aSelectedGroups)){
      foreach ( $aSelectedGroups as $groupIds ){
        $params[$count] = array( $groupIds, 'Integer' );
        $aGroupname = "cgroup.id = %{$count}";
        $atemp[] = $aGroupname;
        $count++;
      }
    }
    if(!empty($atemp)){
    $aWhereClause[] = implode(" OR ",$atemp);
    }
    #Group end

    #status
    /*$oGroupContact  = new CRM_Contact_DAO_GroupContact;
    $aGroupFields   = $oGroupContact->fields();
    $aStatus        = explode(', ', $aGroupFields['status']['enumValues']);*/

    $aStatus = CRM_Core_SelectValues::groupContactStatus();
    $temp           = array();
    foreach($aStatus as $status){
      $aSelectedStatus = CRM_Utils_Array::value( $status, $this->_formValues );
      if(!empty($aSelectedStatus)){
        $params[$count] = array( $status, 'String' );
        $selectedStatus = " group_contact.status = %{$count} ";
        $temp[] = $selectedStatus;
        $count++;
      }
    }
    if(!empty($temp)){
    $aWhereClause[] = implode(" OR ",$temp);
    }
    #Status end

    #date range
    $fromDate = $this->_formValues['start_date'] ? date( 'Ymd', strtotime($this->_formValues['start_date']) ) : NULL;
    $toDate   = $this->_formValues['end_date'] ? date( 'Ymd', strtotime($this->_formValues['end_date']) ) : NULL;
    if(!empty($fromDate)){
        $aWhereClause[] = "history.date >= {$fromDate}";
    }
    if(!empty($toDate)){
      $aWhereClause[] = "history.date <= {$toDate}";
    }
    #end date range

    $sWhere = '( 1 )';
    if (!empty($aWhereClause)) {
      $sWhere = implode( ' AND ', $aWhereClause );
    }
    return $this->whereClause($sWhere, $params);
  }
  /**
   * Determine the Smarty template for the search screen
   *
   * @return string, template path (findable through Smarty template path)
   */
  function templateFile() {
//    return 'CRM/Contact/Form/Search/Custom.tpl';
    return 'CRM/Groupcontact/Form/Search/Groupcontact.tpl';
  }

  /**
   * Modify the content of each row
   *
   * @param array $row modifiable SQL result row
   * @return void
   */
  function alterRow(&$row) {
    $row['date_added'] =  CRM_Utils_Date::customFormat($row['date_added']);
    $row['sort_name'] .= ' ( altered )';
  }
}

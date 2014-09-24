<?php

class CRM_Training_BAO_Settings extends CRM_Training_DAO_Settings {

  public static function create($params) {
    $instance = new CRM_Training_DAO_Settings();
    $instance->name = $params['name'];
    $instance->find(TRUE);

    $instance->copyValues($params);
    $instance->save();
  }
}

<?php
  // app/models/user.php
class User extends AppModel {
	var $name = 'User';
	var $hasMany = array('Post','Event','SpecialPage');
	var $hasOne = array('Profile');
	
	var $hasAndBelongsToMany = array('Group');
	
	var $validate = array(
		'captcha' => array(
			'rule' => 'validateCaptcha',
			'message' => 'Invalid Captcha'
			)
		);
	

	
	function validateCaptcha($data, $params) {
		$val = array_values($data);
		$val = $val[0];
		//echo $val;
		if(!defined('captcha'))
			define('captcha', 'kcaptcha');
		if (!empty($_SESSION['captcha']) && $val == $_SESSION['captcha']) {
			// clear to prevent re-use
			unset($_SESSION['captcha']);
			return true;
		}
		return false; 
	} 
  }
?>

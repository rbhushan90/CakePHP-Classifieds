<?php
class ProfilesController extends AppController
{

	var $name = 'Profiles';
	//var $scaffold;
	var $helpers = array('Html','Javascript');
	var $components = array('Auth');
	

	/*	function beforeFilter() {
		$this->Auth->fields = array(
		'username' => 'email', 
		'password' => 'password'
		);
			
		$this->Auth->allow('captcha','register','login','logout','admin_login');
		$this->set('groups',$this->User->Group->find('list',array('fields' => 'name')));
		//$this->Auth->autoRedirect = false;
	
		} */

	function register()
	{
		if ($this->data)
		{
			if ($this->data['User']['password'] == $this->Auth->password($this->data['User']['confirm_password']))
			{
				$captcha = $this->Session->read('captcha');
				//debug($captcha);
				//die;
				$this->User->create();
				$this->User->save($this->data);
				
				
			}
		}
	}

	function index()
	{
		$this->set('users', $this->User->find('all'));
	}
	
	function add()
	{
		if(!empty($this->data))
		{
			$this->User->save($this->data);
		}
	}
		


	function edit($id = null)
	{
		//debug($_SESSION);
		$user_id = $this->Session->read('Auth.User.id');
		if (!empty($this->data)) {
			$this->data['Profile']['user_id'] = $user_id;
			if ($this->Profile->save($this->data)) {
				$this->flash(__('The Profile has been updated.', true), array('action'=>'index'));
			} else {
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Profile->find('first', array('conditions' => array('Profile.user_id' => $user_id)));
			//debug($this->data);
			//die;
		}
				
		
	}
		
	function delete($id)
	{
		$this->User->id = $id;
		$this->User->delete();
		$this->Session->setFlash('The Post with id: '.$id.' has been deleted.');
		$this->redirect(array('action'=>'index'));

	}
		
	function dashboard()
	{
		/* $user_id = $this->Session->read('Auth.User.id');
		   $data['User'] = $this->Auth->user();
		   //debug($data);
		   //die;
		   $this->data = $this->User->Post->find('all', array('conditions' => array('User.id' => $user_id))); */
	}
		
		
//Admin functions
	function admin_index()
	{
		$this->layout = 'admin';
		$this->set('users', $this->User->find('all'));
	}
	
	function admin_add()
	{
		$this->layout = 'admin';
		if(!empty($this->data))
		{
			if ($this->User->save($this->data)) 
			{
				$this->Session->setFlash('User has been added.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	function admin_edit($id = null)
	{
		//debug($_SESSION);
		$this->layout = 'admin';
		$this->User->id = $id;
		if (empty($this->data))
		{
			$this->data = $this->User->read();
		} 
		else 
		{
			if ($this->User->save($this->data)) 
			{
				$this->Session->setFlash('User has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	function admin_delete($id)
	{
		$this->layout = 'admin';
		$this->User->id = $id;
		$this->User->delete();
		$this->Session->setFlash('The user with id: '.$id.' has been deleted.');
		$this->redirect(array('action'=>'index'));

	}
	

} 
?>

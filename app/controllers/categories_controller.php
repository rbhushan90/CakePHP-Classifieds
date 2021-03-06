<?php
class CategoriesController extends AppController {

	var $name = 'Categories';
	var $paginate = array(
		'Post' => array('limit' => 10,'order' => array('Post.title' => 'asc')
			),
		'Category' => array('limit' => 10,'order' => array('Post.title' => 'asc')
			)
		);
			
	var $helpers = array('Html','Javascript','Text','Number');
	
	function beforeFilter()
	{
	
		parent::beforeFilter();
		$this->Auth->allow('*');
		$this->set('cities', $this->Category->Post->City->find('list',array('fields'=> 'city')));
		$this->set('categories',$this->Category->generatetreelist(null, null, null, '---'));
	}

	//var $scaffold;
		
	function index()
	{
		//$this->data = $this->Category->generatetreelist(null, null, null, '***');
		$this->set('categories',$this->Category->generatetreelist(null, null, null, '---'));
		//debug ($this->data);
		
	}
	function get_categories()
	{
		//$this->data = $this->Category->generatetreelist(null, null, null, '***');
		return $this->Category->generatetreelist(null, null, null, '---');
		//debug ($this->data);
		
	}
		
	function add()
	{
		$this->set('temp3', $this->Category->generatetreelist(null, null, null, '---'));
		if(!empty($this->data))
		{
			//debug($this->data);
			$this->Category->save($this->data);
		}

	}
		
	function edit($id)
	{
			
		// pseudo controller code
		$this->Category->id = $id; // id of Extreme knitting
		$this->set('temp3', $this->Category->generatetreelist(null, null, null, '---'));
		if (empty($this->data))
		{
			$this->data = $this->Category->read();
		} 
		else 
		{
			if ($this->Category->save($this->data)) 
			{
				//$this->Session->setFlash('Your Category has been updated.');
				//$this->redirect(array('action' => 'index'));
			}
		}
	}
		
	function delete($id)
	{
		// pseudo controller code
		$this->Category->id = $id;
		$this->Category->delete();
		$this->Session->setFlash('The category with id: '.$id.' has been deleted.');
		$this->redirect(array('action'=>'index'));
	}
		
	function list_home()
	{
		//$this->Category->id = 19;
		$this->Category->recursive = -1;
		$cat['ForSale'] = $this->Category->find('all', array('conditions' => array('Category.parent_id' => 19)));
		$cat['ForSale'][0]['slug'] = 'for-sale';
			
		$cat['Classes'] = $this->Category->find('all', array('conditions' => array('Category.parent_id' => 24)));
		$cat['Classes'][0]['slug'] = 'classes';
			
		$cat['Vehicles'] = $this->Category->find('all', array('conditions' => array('Category.parent_id' => 20)));
		$cat['Vehicles'][0]['slug'] = 'vehicles';
			
		$cat['Matrimonial'] = $this->Category->find('all', array('conditions' => array('Category.parent_id' => 21)));
		$cat['Matrimonial'][0]['slug'] = 'matrimonial';
			
		$cat['RealEstate'] = $this->Category->find('all', array('conditions' => array('Category.parent_id' => 22)));
		$cat['RealEstate'][0]['slug'] = 'real-estate';
			
		$cat['Jobs'] = $this->Category->find('all', array('conditions' => array('Category.parent_id' => 23)));
		$cat['Jobs'][0]['slug'] = 'jobs';
			
		$cat['Services'] = $this->Category->find('all', array('conditions' => array('Category.parent_id' => 25)));
		$cat['Services'][0]['slug'] = 'services';
			
		$cat['Resumes'] = $this->Category->find('all', array('conditions' => array('Category.parent_id' => 26)));
		$cat['Resumes'][0]['slug'] = 'resumes';
			
		$cat['Community'] = $this->Category->find('all', array('conditions' => array('Category.parent_id' => 27)));
		$cat['Community'][0]['slug'] = 'community';			
		$this->data = $cat;
		//$cat['Community1'] = $this->Category->find('all', array('conditions' => array('Category.parent_id' => array(25,26,27))));
		//debug($cat['Community1']);
		//debug($this->data);
		//die;
	}
	function list_postId($id = null)
	{
		$this->data = $this->Category->Post->find('all',array('conditions' => array('Category.id' => $id)));
		//debug($this->data);
		//die;
	}
		
	function list_post1($slug = null, $keyw = null)
	{
		if(isset($this->params['url']['keyw']))
		{
			$keyw = $this->params['url']['keyw'];
		}	
		if(isset($this->params['url']['cat']))
		{
			$cat = $this->params['url']['cat'];
		}	
		//die;
		if($this->Session->check('City.id'))
		{
			$conditions[] = array('City.id' => $this->Session->read('City.id')); 
		}
		if(!empty($keyw))
		{
			$conditions[] = array('Post.title LIKE' => '%'.$keyw.'%');
		}
		if(!empty($slug))
		{
			$conditions[] = array('Category.slug' => $slug);
		}
		if(!empty($cat))
		{
			$conditions[] = array('Category.id' => $cat);
		}
		$conditions[] = array('Post.Active' => 1);
		//$this->data = $this->Category->Post->find('all',array('conditions' => $conditions));
		$this->set('data',$this->paginate($this->Category->Post,array($conditions)));
			
		if(!empty($this->data))
		{
			$children = $this->Category->children($this->data[0]['Category']['id'], true);
			$this->set('children', $children);
		}
		//$children = $this->Category->find('list',array('conditions' => array('Category.parent_id' => $this->data[0]['Category']['id'])));
		//debug($children);
		//debug($directChildren);
			
		//die;
		//$this->data = $this->paginate('Category',array('conditions' => array('Category.slug' => $slug)));
		//debug($this->data);
		//die;
	}
		
	function list_post($slug = null, $keyw = null)
	{
		  
		$this->redirectToNamed();
		//$urlArray = $this->params['url'];
		//$urlArray['url'] = 'index';
		//debug($this->params);
						  
		//$this->redirect($urlArray,null,true);
		// build a URL will all the search elements in it
		// the resulting URL will be
		// example.com/cake/posts/index/Search.keywords:mykeyword/Search.tag_id:3
		// debug($this->data);
		/* $result = Set::extract($this->data,'Category');
		//debug($result);
		foreach ($this->data as $k=>$v){
		foreach ($v as $kk=>$vv){
		$url[$k.'-'.$kk]=$vv;
		}
		}
				
		foreach ($result as $k=>$v){
					
		$url[$k]=$v;
		}
				
				
		//debug($url);
		//die;

		// redirect the user to the url
		$this->redirect($url, null, true); */
		  
		if(isset($this->params['named']['keyw']))
		{
			$keyw = $this->params['named']['keyw'];
		}	
		if(isset($this->params['named']['cat']))
		{
			$cat = $this->params['named']['cat'];
		}	
		//die;
		if($this->Session->check('City.id'))
		{
			$conditions[] = array('City.id' => $this->Session->read('City.id')); 
		}
		if(!empty($keyw))
		{
			$conditions[] = array('Post.title LIKE' => '%'.$keyw.'%');
		}
		if(!empty($slug))
		{
			$conditions[] = array('Category.slug' => $slug);
		}
		if(!empty($cat))
		{
			$conditions[] = array('Category.id' => $cat);
		}
		$conditions[] = array('Post.Active' => 1);
		$this->data = $this->Category->Post->find('all',array('conditions' => $conditions));
		$this->set('data',$this->paginate($this->Category->Post,array($conditions)));
			
		if(!empty($this->data))
		{
			$children = $this->Category->children($this->data[0]['Category']['id'], true);
			$this->set('children', $children);
		}
		//$children = $this->Category->find('list',array('conditions' => array('Category.parent_id' => $this->data[0]['Category']['id'])));
		//debug($children);
		//debug($directChildren);
			
		//die;
		//$this->data = $this->paginate('Category',array('conditions' => array('Category.slug' => $slug)));
		//debug($this->data);
		//die;
	}
		
	function set_city()
	{
		debug($_SESSION);
		$this->Category->Post->City->id = $this->data['Category']['city_id'];
		$this->data = $this->Category->Post->City->read();
		$this->Session->write('City.id',$this->data['City']['id']);
		$this->Session->write('City.city',$this->data['City']['city']);
		$this->redirect(array('action'=>'list_home'));
		//$this->data = $this->Category->Post->find('all',array('conditions' => array('Category.slug' => $slug)));
		//$this->data = $this->paginate('Category',array('conditions' => array('Category.slug' => $slug)));
		//debug($this->data);
		//die;
	}
		
	function admin_index()
	{
		$this->layout = 'admin';
		//$this->Category->recursive = 0;
		//$this->set('', $this->Category->generatetreelist(null, null, null, '***'));
		//$this->set('posts',$this->paginate());
	}
	
	function admin_view($id = null) {
		$this->layout = 'admin';
		if (!$id) {
			$this->flash(__('Invalid Category', true), array('action'=>'index'));
		}
		$this->set('category', $this->Category->read(null, $id));
	}
	
	
	function admin_add($user_id = null)
	{
		$this->layout = 'admin';
		//$this->set('temp3', $this->Post->Category->generatetreelist(null, null, null, '---'));
		//$this->set('temp4', $this->Post->User->find('list'));
		if (!empty($this->data)) {
			$this->Category->create();
			if ($this->Category->save($this->data)) {
				$this->flash(__('Category saved.', true), array('action'=>'index'));
			} else {
			}
		}
	}
	
	function admin_edit($id)
	{
			
		$this->layout = 'admin';
		//$this->set('temp3', $this->Post->Category->generatetreelist(null, null, null, '---'));
		//$this->set('temp4', $this->Post->User->find('list'));
		if (!$id && empty($this->data)) {
			$this->flash(__('Invalid Category', true), array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Category->save($this->data)) {
				$this->flash(__('The Category has been updated.', true), array('action'=>'index'));
			} else {
			}
		}		
		if (empty($this->data)) {
			$this->data = $this->Category->read(null, $id);
		}
	}
		
	function admin_delete($id = null) {
		$this->layout = 'admin';
		if (!$id) {
			$this->flash(__('Invalid Post', true), array('action'=>'index'));
		}
		if ($this->Category->del($id)) {
			$this->flash(__('Post deleted', true), array('action'=>'index'));
		}
	}
	
		
  }
?>

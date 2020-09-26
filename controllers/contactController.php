<?php
/**
 * Contact controller 
 *
 * This class calls the model based on the form action
 * 
 * @package    controllers 
 * @author     anjanreddy <anjan111reddy@gmail.com>
 */
Class ContactController extends Controller {

	private $model=null;

	public function __construct(){ 
		$this->model = $this->loadModel('ContactModel'); 
		$ContactModel =new ContactModel();

		$this->groupmodel = $this->loadModel('GroupModel'); 
		$GroupModel =new GroupModel();
	}
	
	/* list all contacts */ 
	public function index(){  
		$data = array();  
	 	$data['contacts'] = $this->model->getAllContacts();  
		$data['city'] = $this->model->getCities(); 
		$data['tags'] = $this->model->getTags();
		$data['groups'] = $this->groupmodel->getGroups();  
		$data['input']= '0'; 
		$data['tag']='-1';
		$this->loadView('contacts',$data);	
	}

	/* filter contacts by group*/
	public function filterContacts(){ 
		$data = array();
		if($this->get('group_id')==0){
			$data['contacts'] = $this->model->getAllContacts();
		}  
		else{
			$input_group = array(); 
			ContactModel::$items=array(); 
			array_push($input_group ,$this->get('group_id')); 
			$ContactModel =new ContactModel(); 
			$this->model->inheritedGroups($input_group);
			array_push(ContactModel::$items,$this->get('group_id'));  
			$glist=implode(', ',array_unique(ContactModel::$items));  
			$data['contacts'] = $this->model->getContacts($glist);
		}

		$data['tags'] = $this->model->getTags();
		$data['city'] = $this->model->getCities(); 
		$data['groups'] = $this->groupmodel->getGroups(); 
		$data['input']= $this->get('group_id'); 
		$data['tag']='-1';
		$this->loadView('contacts',$data);
	}

	/* filter contacts by tags  */
	public function filterTaggedContacts(){ 
		$data = array();  
	 	$data['contacts'] = $this->model->getTaggedContacts($this->get('tag_id'));  
		$data['city'] = $this->model->getCities(); 
		$data['tags'] = $this->model->getTags();
		$data['groups'] = $this->groupmodel->getGroups();  
		$data['tag']= $this->get('tag_id'); 
		$data['input']= '0'; 
		$this->loadView('contacts',$data);
	} 

	/* add contact */
	public function addContact(){ 
		$data = array(); 
		$result=$this->model->addContact($this->post('name'),$this->post('firstName'),
		$this->post('email'),$this->post('street'),$this->post('city'),
		$this->post('zipcode'),$this->post('group')); 
		if($result){$data['msg']="Contact added successfully.";}
		else{$data['msg']="Issue with the data.";}
		$this->redirect('c=contact&m=index&group_id=0',$data); 
	}

	/* get contact details by id*/
	public function getContactDetails(){  
		$data=$this->model->getContact($this->post('contact_id'));  
		print_r(json_encode($data)); 
	}

	/* get tags by id*/
	public function getContactTags(){  
		$data=$this->model->getContactTags($this->post('contact_id'));  
		print_r(json_encode($data)); 
	}

	/* update contact details by id */
	public function updateContact(){ 
		$data = array(); 
		$result=$this->model->updateContact($this->post('contact_id'),$this->post('name'),$this->post('firstName'),
		$this->post('email'),$this->post('street'),$this->post('city'),$this->post('zipcode'),
		$this->post('group')); 
		if($result==1){$data['msg']="Contact updated successfully.";}
		else{$data['msg']="Issue with the data.";} 
		 $this->redirect('c=contact&m=index&group_id=0',$data); 
	}

	/* update contact tags by id */
	public function updateContactTag(){  
		$data = array(); 
		$tagList = implode(',', $this->post('ctags'));
		$result=$this->model->updateContactTag($this->post('tag_cid'),$tagList);  
		$this->redirect('c=contact&m=index',$data); 
	} 

	/* delete contact by id*/
	public function deleteContact(){  
		$result=$this->model->deleteContact($this->post('contact_id'),$this->post('group_id')); 
		echo $result; 
	}

	/* delete contact by id*/
	public function emailCheck(){  
		if($this->post('contact_id'))
			$result=$this->model->emailCheckOnEdit($this->post('email'),$this->post('group_id'),$this->post('contact_id'));
		else
			$result=$this->model->emailCheck($this->post('email'),$this->post('group_id'));
		echo $result; 
	}

	/* contacts xml feed */
	public function xmlFeed(){   
		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename="addressbook.xml"'); 
        $xml = new SimpleXMLElement('<addressbook></addressbook>'); 
        $data =$this->model->getContacts();  
		foreach($data as $row) {
		    $contact = $xml->addChild('contact'); 
            foreach ($row as $key => $value) {
                $contact->addChild($key, $value);
            }
		}
        print $xml->saveXML(); 
	}

	/* contacts JSON feed */
	public function jsonFeed(){ 
		header('Content-type: text/json');
		header('Content-Disposition: attachment; filename="addressbook.json"'); 
        $xml = new SimpleXMLElement('<addressbook></addressbook>');         
		$data =$this->model->getContacts();
		print_r(json_encode($data)); 
	} 
}
?>
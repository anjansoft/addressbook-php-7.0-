<?php
/**
 * Group controller 
 *
 * This class calls the model methods based on the form action
 * 
 * @package    controllers 
 * @author     anjanreddy <anjan111reddy@gmail.com>
 */
Class GroupController extends Controller {

	private $model=null;

	public function __construct(){ 
		$this->model = $this->loadModel('GroupModel'); 
		$GroupModel =new GroupModel();

		$this->cmodel = $this->loadModel('ContactModel'); 
		$ContactModel =new ContactModel();
	}
	
	/* list all groups */ 
	public function index(){  
		$data = array(); 
		$data['groups'] = $this->model->getGroups();  
		$this->loadView('groups',$data);	
	}

	/* add group */
	public function addGroup(){ 
		$data = array(); 
		$group_id=$this->model->addGroup($this->post('name')); 
		if($this->post('in_group')!="" && $group_id>0){ 
			//$groupList = implode(', ', $this->post('in_group'));  
			$result=$this->model->updateInheritedGroups($group_id,$this->post('in_group'));
			$data['msg']="group added successfully.";
		}
		else{$data['msg']="Issue with the data.";}
		$this->redirect('c=group&m=index',$data); 
	}

	/* get group details by id*/
	public function getGroupDetails(){  
		$data=$this->model->getGroup($this->post('group_id'));  
		print_r(json_encode($data)); 
	}

    /* get group details by group name*/
	public function checkGroup(){  
		$data=$this->model->checkGroup($this->post('group_name'));  
		print_r(json_encode($data)); 
    } 
    
	/* update group details by id */
	public function updateGroup(){ 
		$data = array(); 
		$result=$this->model->updategroup($this->post('group_id'),$this->post('name'));  
		if($this->post('in_group')!=""){
		    //$groupList = implode(', ', $this->post('in_group')); 
			$result=$this->model->updateInheritedGroups($this->post('group_id'),$this->post('in_group'));
		}
		$data['msg']="group updated successfully."; 
		$this->redirect('c=group&m=index',$data); 
	}

	/* delete group by id*/
	public function deleteGroup(){  
		$result=$this->model->deletegroup($this->post('group_id')); 
		$result=$this->cmodel->deleteContactsByGroupID($this->post('group_id')); 
		echo $result; 
	} 
 
}
?>
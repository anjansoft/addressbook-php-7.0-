<?php
/**
 * Group Model  
 * 
 * This model class is responsible for all database operations on groups 
 * 
 * @package    models 
 * @author     anjanreddy <anjan111reddy@gmail.com>
 */

Class GroupModel {

    private $db=null;

    public function __construct(){
        $this->db = Database::getInstance(); 
    }
   
    /* get all groups from contact_groups table */ 
	public function getGroups(){
		$mysqli = $this->db->getConnection();  
        $stmt = $mysqli->prepare("SELECT group_id,group_name from contact_groups"); 
        $stmt->execute();
        $result =$stmt->get_result()->fetch_all(MYSQLI_ASSOC); 
        $stmt->close(); 
        return $result;
	} 
    
    /* insert new group details into contact_groups table */ 
    public function addGroup($group_name) {  
        $mysqli = $this->db->getConnection();  
        $group_name=$mysqli->real_escape_string($group_name);  
        $stmt = $mysqli->prepare("INSERT INTO contact_groups (`group_name`) VALUES (?)");
        $stmt->bind_param("s",$group_name);
        $stmt->execute();
        $result=$mysqli->insert_id;
        $stmt->close(); 
        return $result;
    } 

     /* update inherited contacts */
    public function updateInheritedGroups($group_id,$inh_groups) {  
        $mysqli = $this->db->getConnection();  
        $group_id=$mysqli->real_escape_string($group_id);    

        $stmt = $mysqli->prepare("DELETE FROM contact_inherited where group_id = ?");
        $stmt->bind_param("i",$group_id);
        $stmt->execute();
        
        foreach($inh_groups as $inh_group){ 
        if($inh_group!=0) 
        {
        $stmt = $mysqli->prepare("INSERT INTO contact_inherited (group_id,inh_group_id) VALUES (?,?)");
        $stmt->bind_param("is",$group_id,$inh_group);
        $stmt->execute(); 
        }
        } 

        $result= $stmt->get_result();
        $stmt->close(); 
        return $result;  
    } 

    /* get group details by group_id from contact_groups table */
    public function getGroup($group_id){
        $mysqli = $this->db->getConnection();
        $group_id=$mysqli->real_escape_string($group_id);    
        $stmt = $mysqli->prepare("SELECT group_id,group_name,(SELECT GROUP_CONCAT(inh_group_id) FROM `contact_inherited` WHERE group_id=?) as inh_groups FROM contact_groups WHERE group_id=?"); 
        $stmt->bind_param("ii",$group_id,$group_id);
        $stmt->execute();
        $result =$stmt->get_result()->fetch_assoc(); 
        $stmt->close(); 
        return $result;
    }
    
     /* check group name exisistency in contact_groups table */
     public function checkGroup($group_name){
        $mysqli = $this->db->getConnection();
        $group_name=$mysqli->real_escape_string($group_name);    
        $stmt = $mysqli->prepare("SELECT group_id FROM contact_groups WHERE group_name=?"); 
        $stmt->bind_param("s",$group_name);
        $stmt->execute(); 
        $stmt->store_result(); 
        $result=$stmt->num_rows;
        $stmt->close(); 
        return $result;
    } 
    
    /* update group details by group_id in contact_groups table */
    public function updateGroup($group_id,$group_name) { 
        $mysqli = $this->db->getConnection();  
        $group_id=$mysqli->real_escape_string($group_id);
        $group_name=$mysqli->real_escape_string($group_name); 
        $stmt = $mysqli->prepare("update contact_groups set `group_name`=? where group_id=?");  
        $stmt->bind_param("si",$group_name, $group_id);
        $stmt->execute();
        $result=$mysqli->affected_rows;
        $stmt->close(); 
        return $result;
    } 

    /* delete group details by group_id from contact_groups table */
    public function deleteGroup($group_id) {  
        $mysqli = $this->db->getConnection();  
        $group_id=$mysqli->real_escape_string($group_id);  
        $stmt = $mysqli->prepare("DELETE FROM contact_groups where group_id = ?");
        $stmt->bind_param("i",$group_id);
        $stmt->execute();
        $result= $stmt->affected_rows;
        $stmt->close(); 
        return $result;  
    }  
 
}
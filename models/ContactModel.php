<?php
/**
 * Contact Model  
 * 
 * This model class is responsible for all database operations 
 * 
 * @package    models 
 * @author     anjanreddy <anjan111reddy@gmail.com>
 */

Class ContactModel {

    private $db=null; 
    public static $items=array();
    public static $rec=null;

    public function __construct(){
        $this->db = Database::getInstance(); 
    }
   
    /* get all contacts from user_contacts table */ 
	public function getContacts($group_id){  
        $mysqli = $this->db->getConnection(); 
        $group_id=$mysqli->real_escape_string($group_id); 
        $stmt = $mysqli->prepare("SELECT contact_id,group_name,user_contacts.name,first_name,email, 
        street,user_contacts.group_id,city.name as city,zipcode,tag FROM user_contacts 
        LEFT JOIN city on user_contacts.city_id=city.city_id 
        LEFT JOIN contact_groups on contact_groups.group_id=user_contacts.group_id
        WHERE user_contacts.contact_id 
        IN(SELECT max(contact_id) FROM `user_contacts` 
        WHERE group_id IN(".$group_id.") group by email order by contact_id desc)"); 
         
        $stmt->execute();
        $result =$stmt->get_result()->fetch_all(MYSQLI_ASSOC); 
        $stmt->close(); 
        return $result;
	}
    
    /* get all contacts from user_contacts table */ 
	public function getAllContacts(){ 
        $mysqli = $this->db->getConnection();
        $stmt = $mysqli->prepare("SELECT contact_id,group_name,user_contacts.name,first_name,email,street,user_contacts.group_id,city.name as city,zipcode,tag FROM user_contacts LEFT JOIN city on user_contacts.city_id=city.city_id LEFT JOIN contact_groups on contact_groups.group_id=user_contacts.group_id"); 
        $stmt->execute();
        $result =$stmt->get_result()->fetch_all(MYSQLI_ASSOC); 
        $stmt->close(); 
        return $result;
    }
    
    /* get all contacts from user_contacts table */ 
	public function getTaggedContacts($tag_id){  
        $mysqli = $this->db->getConnection(); 
        if($tag_id==0){
        $stmt = $mysqli->prepare("SELECT contact_id,user_contacts.name,first_name,email,street,
        user_contacts.group_id,city.name as city,zipcode,tag 
        FROM user_contacts LEFT JOIN city on user_contacts.city_id=city.city_id WHERE tag is NOT NULL AND tag!=''");
        }
        else{
        $stmt = $mysqli->prepare("SELECT contact_id,user_contacts.name,first_name,email,street,
        user_contacts.group_id,city.name as city,zipcode,tag 
        FROM user_contacts LEFT JOIN city on user_contacts.city_id=city.city_id WHERE ".$tag_id." IN (tag)"); 
        } 
        
        $stmt->execute();
        $result =$stmt->get_result()->fetch_all(MYSQLI_ASSOC); 
        $stmt->close(); 
        return $result;
    }

    /* insert new contact details into user_contacts table */ 
    public function addContact($name, $firstname, $email, $street, $city, $zipcode,$group_id) { 
        $mysqli = $this->db->getConnection();  
        $name=$mysqli->real_escape_string($name);
        $firstname=$mysqli->real_escape_string($firstname);
        $email=$mysqli->real_escape_string($email);
        $street=$mysqli->real_escape_string($street);
        $city=$mysqli->real_escape_string($city);
        $zipcode=$mysqli->real_escape_string($zipcode); 
        $group_id=$mysqli->real_escape_string($group_id);  
        $stmt = $mysqli->prepare("INSERT INTO user_contacts (`name`, first_name,email,street,city_id,zipcode,group_id)
         VALUES (?, ?,?,?,?,?,?)");
        $stmt->bind_param("ssssssi",$name, $firstname,$email,$street,$city,$zipcode,$group_id);
        $stmt->execute();
        $result=$mysqli->affected_rows;
        $stmt->close(); 
        return $result;
    } 

    /* get contact details by contact_id from user_contacts table */
    public function getContact($contact_id){
        $mysqli = $this->db->getConnection();
        $contact_id=$mysqli->real_escape_string($contact_id);    
        $stmt = $mysqli->prepare("SELECT contact_id,`name`,first_name,email,street,city_id,zipcode,group_id,tag FROM user_contacts  WHERE contact_id=?"); 
        $stmt->bind_param("i",$contact_id);
        $stmt->execute();
        $result =$stmt->get_result()->fetch_assoc(); 
        $stmt->close(); 
        return $result;
	}
    
    /* update contact details by contact_id in user_contacts table */
    public function updateContact($contact_id,$name, $firstname, $email, $street, $city, $zipcode,$group_id) { 
        $mysqli = $this->db->getConnection();  
        $contact_id=$mysqli->real_escape_string($contact_id);
        $name=$mysqli->real_escape_string($name);
        $firstname=$mysqli->real_escape_string($firstname);
        $email=$mysqli->real_escape_string($email);
        $street=$mysqli->real_escape_string($street);
        $city=$mysqli->real_escape_string($city);
        $zipcode=$mysqli->real_escape_string($zipcode); 
        $group_id=$mysqli->real_escape_string($group_id); 
        $stmt = $mysqli->prepare("update user_contacts set `name`=?, first_name=?,email=?,
        street=?,city_id=?,zipcode=? ,group_id=? where contact_id=?");  
        $stmt->bind_param("ssssssii",$name, $firstname,$email,$street,$city,$zipcode,$group_id,$contact_id);
        $stmt->execute();
        $result=$mysqli->affected_rows;
        $stmt->close(); 
        return $result;
    } 

    /* update contact details by contact_id in user_contacts table */
    public function updateContactTag($contact_id,$tags) {  
        $mysqli = $this->db->getConnection();  
        $contact_id=$mysqli->real_escape_string($contact_id); 
        $tags=$mysqli->real_escape_string($tags); 
        $stmt = $mysqli->prepare("update user_contacts set `tag`=? where contact_id=?");  
        $stmt->bind_param("si",$tags,$contact_id);
        $stmt->execute();
        $result=$mysqli->affected_rows;
        $stmt->close(); 
        return $result;
    }   

    /* update inherited contacts */
    public function updateInheritedGroupContacts($group_id,$inh_group_id) {  
        $mysqli = $this->db->getConnection();   
        $result = $mysqli->query("DELETE u from user_contacts u INNER JOIN user_contacts i ON u.email=i.email AND i.group_id=".$inh_group_id." AND u.group_id =".$group_id);
        $result = $mysqli->query("INSERT INTO user_contacts (`name`, first_name,email,street,city_id,zipcode,group_id,tag)
        select `name`, first_name,email,street,city_id,zipcode,".$group_id.",tag from user_contacts where group_id=".$inh_group_id);
        return $result;  
    } 

    /* delete contact details by contact_id from user_contacts table */
    public function deleteContact($contact_id,$group_id) {  
        $mysqli = $this->db->getConnection();   
        $sql="DELETE FROM user_contacts WHERE contact_id IN(
        SELECT i.contact_id from user_contacts u 
        INNER JOIN user_contacts i ON u.email=i.email 
        WHERE 
        i.group_id IN(SELECT GROUP_CONCAT(group_id) AS group_id FROM contact_inherited WHERE inh_group_id=".$group_id.") 
        AND u.contact_id=$contact_id
        )";  
        $result = $mysqli->query($sql); 
        $contact_id=$mysqli->real_escape_string($contact_id);  
        $stmt = $mysqli->prepare("DELETE FROM user_contacts where contact_id = ?");
        $stmt->bind_param("i",$contact_id);
        $stmt->execute();

        $result= $stmt->affected_rows;
        $stmt->close(); 
        return $result;  
    } 
    
     /* delete all contact  by group_id from user_contacts table */
    public function deleteContactsByGroupID($group_id) {  
        $mysqli = $this->db->getConnection();   
        $group_id=$mysqli->real_escape_string($group_id);  
        
        $stmt = $mysqli->prepare("DELETE FROM user_contacts where group_id = ?");
        $stmt->bind_param("i",$group_id);
        $stmt->execute();

        $stmt = $mysqli->prepare("DELETE FROM contact_inherited where group_id = ? OR inh_group_id= ? ");
        $stmt->bind_param("ii",$group_id,$group_id);
        $stmt->execute(); 
        $result= $stmt->affected_rows;
        $stmt->close(); 

        return $result;  
    }  

    /* get all city list from city table */
    public function getCities(){
		$mysqli = $this->db->getConnection();  
        $stmt = $mysqli->prepare("select * from city order by name ASC"); 
        $stmt->execute();
        $result =$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close(); 
        return $result;
    }

    /* get all tag list from tags table */
    public function getTags(){
		$mysqli = $this->db->getConnection();  
        $stmt = $mysqli->prepare("select * from tags order by tag_name ASC"); 
        $stmt->execute();
        $result =$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close(); 
        return $result;
    }

    /* get contact tags by contact_id*/
    public function getContactTags($contact_id){
        $mysqli = $this->db->getConnection();
        $contact_id=$mysqli->real_escape_string($contact_id);    
        $stmt = $mysqli->prepare("SELECT GROUP_CONCAT(tag) as tag from user_contacts WHERE contact_id=?"); 
        $stmt->bind_param("i",$contact_id);
        $stmt->execute();
        $result =$stmt->get_result()->fetch_assoc(); 
        $stmt->close(); 
        return $result;
    }

     /* check email exisistency in contact_groups by groyp id table */
    public function emailCheck($email,$group_id){
        $mysqli = $this->db->getConnection();
        $email=$mysqli->real_escape_string($email);   
        $group_id=$mysqli->real_escape_string($group_id); 
        $stmt = $mysqli->prepare("SELECT email FROM user_contacts WHERE email=? AND group_id=?"); 
        $stmt->bind_param("si",$email,$group_id);
        $stmt->execute(); 
        $stmt->store_result(); 
        $result=$stmt->num_rows;
        $stmt->close(); 
        return $result;
    } 

     /* Edit- check email exisistency in contact_groups by groyp id table */
    public function emailCheckOnEdit($email,$group_id,$contact_id){
        $mysqli = $this->db->getConnection();
        $email=$mysqli->real_escape_string($email);   
        $group_id=$mysqli->real_escape_string($group_id); 
        $contact_id=$mysqli->real_escape_string($contact_id); 
        $stmt = $mysqli->prepare("SELECT email FROM user_contacts WHERE email=? AND contact_id!=? AND group_id=?"); 
        $stmt->bind_param("sii",$email,$contact_id,$group_id);
        $stmt->execute(); 
        $stmt->store_result(); 
        $result=$stmt->num_rows;
        $stmt->close(); 
        return $result;
    }  

    //elements - groups
    public function inheritedGroups(array $groups) { 
        $branch = array();   
        foreach ($groups as $group) {  
            if ($group!=0) {   
                $grouplist= $this->recursiveGroupContacts($group);
                foreach($grouplist as $g) array_push(ContactModel::$items,$g); 
                if (count($grouplist)>0) $children =$this-> inheritedGroups($grouplist);  
                $branch[] = $group;
            } 
        } 
        return $branch;
    } 

    /* get all contacts from user_contacts table */ 
	public function recursiveGroupContacts($group_id){ 
        $mysqli = $this->db->getConnection();  
        $result = $mysqli->query("SELECT inh_group_id FROM  contact_inherited where group_id=".$group_id);
        $groups = $result->fetch_all(MYSQLI_NUM); 
        return array_column($groups, 0);
	}
}
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends CI_Model
{
    /**
     * Permission constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Find data.
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->db->get_where("permissions", array("id" => $id, "deleted_at" => null))->row(0);
    }
    public function findByGroupName($group_name)
    {
        return $this->db->get_where("permissions", array("group_name" => $group_name, "deleted_at" => null))->result();
    }
    /**
     * Read all data.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->db->get_where("permissions", array("deleted_at" => null))->result();
    }
    public function all_byrole($role_id=null)
    {    
         $this->db->select('*');  
         $this->db->from('permissions');
         $this->db->join('permission_roles', 'permission_roles.permission_id = permissions.id','left'); 
         if($role_id!=null){
            $this->db->where('role_id =', $role_id);
           }else{
            $this->db->group_by("group_name");
         }  
        return $this->db->get()->result_array();//print_r($this->db->last_query());exit();
    }
    public function all_perrole($role_id=null)
    {    
         $this->db->select('*');  
         $this->db->from('permissions');
         $this->db->join('permission_roles', 'permission_roles.permission_id = permissions.id'); 
         if($role_id!=null){
            $this->db->where('role_id =', $role_id);
           }else{
            $this->db->group_by("group_name");
        }  
        $this->db->order_by('group_name', 'ASC');
        
        return $this->db->get()->result_array();//print_r($this->db->last_query());exit();
    }
    /**
     * Insert Data.
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->db->insert('permissions', $data);
    }

    /**
     * Edit data.
     *
     * @param $data
     * @return mixed
     */
    public function edit($data)
    {
        return $this->db->update('permissions', $data, array('id' => $data['id']));
    }

    /**
     * Delete data.
     *
     * @param $id
     * @return int
     */
    public function deleteBy_role($role_id){
        return  $this->db->delete('permission_roles', array('role_id' => $role_id));
    } 
    public function delete($id)
    {
        $data['deleted_at'] = date("Y-m-d H:i:s");

        return $this->find($id) ? $this->db->update('permissions', $data, array('id' => $id)) : 0;
    }
    public function getLimitDepositByEmpoyee($deposit=0,$current_deposit=0) { 
        $xxreposit=['status'=>false,'depositlimit'=>0];
        $member_id=$this->encryption->decrypt($this->session->userID);
        $depositkey='MB_DepositByEmpoyee'.$member_id;
		$depositlimit  =$this->cache->get($depositkey);  
        if(!$depositlimit){ 
            $this->db->select('deposit_limit', FALSE); 
            $this->db->where("id",$member_id);                
            $depositlimit  = $this->db->get('employees')->row();  
            if(isset($depositlimit->deposit_limit)){ 
                $depositlimit =$depositlimit->deposit_limit;
                $this->cache->file->save($depositkey, $depositlimit,60*1);
            } 
		} 
      if($depositlimit <=0){ return $xxreposit;}
      $depositlimit=$depositlimit*1;
      $xxreposit['depositlimit']=$depositlimit;
      if($current_deposit>0){
        $xxreposit['status']=$depositlimit>=abs(($deposit+$current_deposit)); 
        return $xxreposit;
      }   
      $this->db->select('SUM(amount) AS total', FALSE); 
      $this->db->where("status = 1");            
      $this->db->where("admin_id",$member_id);         
      $this->db->where('DATE_FORMAT(trx_date, "%Y-%m-%d") = CURDATE()');       
      $deposit_total = $this->db->get('log_deposit')->row()->total; 
      $xxreposit['status']=$depositlimit>=abs(($deposit+$deposit_total));
      return $xxreposit; 
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth 
{
    protected $CI;

    public $user = null;
    public $userID = null;
    public $userName = null;
    public $password = null;
    public $roles = 0;  // [ public $roles = null ] codeIgniter where_in() omitted for null.
    public $permissions = null;
    public $login = false;
    public $error = array();

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        $this->CI->load->library('encryption');  
        $this->init();
    } 
    protected function init()
    {
        if ($this->CI->session->has_userdata("userID") && $this->CI->session->login) {
            $this->userID =$this->CI->encryption->decrypt($this->CI->session->userID);
            $this->userName = $this->CI->session->Username;
            $this->roles = $this->CI->session->level;
            $this->login = true; 
        } 
        return;
    }

    public function loginStatus()
    {
        return $this->login;
    }
    public function authenticate() {
        $this->CI->Model_function->LoginValidation();
        return true;
    }
    public function check($methods = 0)
    {
        if (is_array($methods) && count($methods)) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2))) {
                    return $this->authenticate();
                }
            }
        }
        return $this->authenticate();
    }
    public function userID()
    {
        return $this->userID;
    }
    public function roles()
    {
        return $this->roles;
    }
    public function permissions()
    {
        return $this->permissions;
    }

    protected function userWiseRoles()
    {   $key='MB_userWiseRoles'.$this->userID();
		$data =$this->CI->cache->get($key);  
		if(!$data){
			$data=$this->CI->db->get_where("roles_users", array("user_id" => $this->userID()))->result_array();
			$this->CI->cache->file->save($key, json_encode($data),60*1);
		}else{
		    $data=json_decode($data,true);
		}
        return $data;
        return array_map(function ($item) {
            return $item["role_id"];
        }, $data);
    }

  public function userRoles() 
    {    $key='MB_userRoles'.$this->userID();
		$data =$this->CI->cache->get($key);  
		if(!$data){
		    $data=$this->CI->db
            ->select("roles.*")
            ->from("roles")
            ->join("roles_users", "roles.id = roles_users.role_id", "inner")
            ->where(array("roles_users.user_id" => $this->userID(),"roles.status" => 1, "deleted_at" => null))
            ->get()->result_array();
			$this->CI->cache->file->save($key, json_encode($data),60*1);
		}else{
		  $data=json_decode($data,true);
		}
        return $data;
        return array_map(function ($item) {
            return $item["url_list"];
        }, $data);
    }
    public function userPermissions()
    {   $key='MB_userPermissions'.$this->userID();
		$data =$this->CI->cache->get($key);  
		if(!$data){
		 $data=$this->CI->db
        ->select("permissions.*")
        ->from("permissions")
        ->join("permission_roles", "permissions.id = permission_roles.permission_id", "inner")
        ->where_in("permission_roles.role_id", $this->roles())
        ->where(array("permissions.status" => 1, "deleted_at" => null))
        ->group_by("permission_roles.permission_id")
        ->get()->result_array();//print_r($this->CI->db->last_query());exit();
		 $this->CI->cache->file->save($key, json_encode($data), 60*1);
		}else{
		$data=json_decode($data,true);
		} 
        return $data;
        return array_map(function ($item) {
            return $item["url_list"];
        }, $data);
    }
    public function only($methods = array())
    {
        if (is_array($methods) && count($methods)) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2))) {
                    return $this->route_access();
                }
            }
        }

        return true;
    }
    public function except($methods = array())
    {
        if (is_array($methods) && count($methods)) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2))) {
                    return true;
                }
            }
        }

        return $this->route_access();
    }
    public function route_access()
    {
        $this->check(); 
        $routeName =uri_string();// (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2)) . "-" . $this->CI->uri->segment(1); 
         
        if($this->can($routeName))
            return true;
        if ($this->CI->input->is_ajax_request()) {
            if(strtolower($this->CI->uri->segment(2))=='check_have_session'){return true;}
            if($this->CI->uri->segment(1)=='admin'&&$this->CI->uri->segment(2)=='add_edit_member'){ 
                if($this->CI->input->method()=='post'){
                   $data = $this->CI->input->post(null, TRUE);
                   if($this->userID()==$this->CI->Model_function->get_decrypt($data['pg_ids'])){return true;}
                }
            }
            $response['type'] = 'error';
            $response['msg'] = 'error 403';
            $this->ResponeJson($response,403);  
        }
        return redirect('exceptions/custom_403', 'refresh');
    }

    public function hasRole($roles, $requireAll = false)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->checkRole($role) && !$requireAll)
                    return true;
                elseif (!$this->checkRole($role) && $requireAll) {
                    return false;
                }
            }
        }
        else {
            return $this->checkRole($roles);
        }
        // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
        // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
        // Return the value of $requireAll;
        return $requireAll;
    }
    public function checkRole($role)
    {
        return in_array($role, $this->userRoles());
    }
    public function can($permissions='', $requireAll = false)
    {   if($permissions==''){$permissions=uri_string();}
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ($this->canroute($permission) && !$requireAll)
                    return true;
                elseif (!$this->canroute($permission) && $requireAll) {
                    return false;
                }
            }
        }
        else {
            return $this->canroute($permissions);
        }
        // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
        // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
        // Return the value of $requireAll;
        return $requireAll;
    }
    public function canroute($route_permissions='')
    {   
        if ($route_permissions=='') {$route_permissions=uri_string();}
        $routepermissions=strtolower($route_permissions);
        $routepermissions=preg_replace('~://|/~', '_', $routepermissions);
        $routepermissions=preg_replace('/[\*]+/', '_star_', $routepermissions); 
        $key="MB_routepermis_$routepermissions".$this->userID();
		$data =$this->CI->cache->get($key);
		if (!$data && gettype($data)== 'boolean') {
            $data=$this->checkPermission($route_permissions);
            $data=($data?1:0);  
            $this->CI->cache->file->save($key, $data, 60*1); 
          } 
        return (boolean)$data==1; 
    } 
    
    public function checkPermission($permission)
    {   $permission=strtolower($permission);
        $arr=$this->userPermissions();// var_dump($arr);exit();   
        $exit_loop=false;
        $loop=sizeof($arr);
        for ($i=0; $i < $loop; $i++) { 
            if ($exit_loop) { break; }
            $exp=explode(',',$arr[$i]['url_list']);
            $loop2=sizeof($exp);
            for ($ii=0; $ii < $loop2; $ii++) { 
                if ($exit_loop) { break; }
                $permission_ur=strtolower($exp[$ii]);
                $re_uri = preg_replace('/\\\\\*/','*', preg_quote($permission_ur, '/'));
                $match = preg_match("/{$re_uri}/", $permission);
                $match = (!$match) ? $match : true; //(strtolower(uri_string()),$permission_ur,$match);
                if($match) { $exit_loop=true;return true; }
            }
        } 
       // var_dump($arr,$permission,$this->userPermissions());exit();   
     if($this->CI->uri->segment(1)=='admin'&&$this->CI->uri->segment(2)=='update'){ 
         if($this->CI->input->method()=='post'){
            $data = $this->CI->input->post(null, TRUE);
            if($this->userID()==$this->CI->Model_function->get_decrypt($data['pg_ids'])){return true;}
         }
      }  
      return false; 
    }
    public function __destruct()
	{
	// $this->CI->db->close();
	}
    public function ResponeJson($data=[],$status_header=200){
        if(is_object($data)){$data=(array)$data;}
        $this->CI->output
        ->set_status_header($status_header)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data))
        ->_display();exit(); 
    }

}
?>
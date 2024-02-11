<?php
class Model_Games extends MY_Model{   

	public function SaveData($provider='',$data='',$isnew=false){
		 try {     
               if($isnew){
                $this->InsertData($provider,$data); 
                }else{
                $this->UpdateData($provider,$data); 
              } 
			        if ($this->Data->error_code!=200) {
                 $this->Data->error_code=500;
                return $this->Data;
			      }  
		   } catch (Exception $e) {
			 $this->Data->error_code=500;
		  } 
		return $this->Data;
	} 
 public function SaveData2($provider='',$data=''){
    try {  
             $this->UpdateData($provider,$data); 
       if ($this->Data->error_code!=200) {
                $this->Data->error_code=500;
               return $this->Data;
       }  
      } catch (Exception $e) {
      $this->Data->error_code=500;
     } 
   return $this->Data;
 } 
	public function FindData($id=null){ 
             $row=[];$result_array=[]; 
             if($id!=null){
                $this->db->where('id =', $id);
            } 
            $sql = $this->db->get('pg_list_games');
            $num_rows=$sql->num_rows();
             if($num_rows>0){
              $result_array = $sql->result_array();
              $row = $sql->row();  
             }    
            $result['num_rows'] = $num_rows;
            $result['result_array'] = $result_array;
            $result['row'] = $row;
            return $result;
    }	
    
    
    public function FindDataProvider($id=null){ 
      $row=[];$result_array=[]; 
      if($id!=null){
        $this->db->where('id =', $id);
        $this->db->order_by('provider_id ASC');
      }else{ 
        $this->db->order_by('provider_id ASC');
       }   
       $sql = $this->db->get('lobby_control');
       $num_rows=$sql->num_rows();
      if($num_rows>0){
        $result_array = $sql->result_array();
        $row = $sql->row();  
      }    
     $result['num_rows'] = $num_rows;
     $result['result_array'] = $result_array;
     $result['row'] = $row;
     return $result;
  }
  public function FindMasterDatagamelist($id=null){ 
    $row=[];$result_array=[]; 
    if($id!=null){
      $this->db->where('id =', $id);
      $this->db->order_by('id ASC');
    }else{ 
      $this->db->order_by('id ASC');
     }   
     $sql = $this->db->get('autosystem_game_list');
     $num_rows=$sql->num_rows();
    if($num_rows>0){
      $result_array = $sql->result_array();
      $row = $sql->row();  
    }    
   $result['num_rows'] = $num_rows;
   $result['result_array'] = $result_array;
   $result['row'] = $row;
   return $result;
  }
  public function FindDatagamelist($id=null){ 
    $row=[];$result_array=[]; 
    if($id!=null){
      $this->db->where('game_id =', $id);
      $this->db->order_by('game_id ASC');
    }else{ 
      $this->db->order_by('game_id ASC');
     }   
     $sql = $this->db->get('autosystem_gamelist');
     $num_rows=$sql->num_rows();
    if($num_rows>0){
      $result_array = $sql->result_array();
      $row = $sql->row();  
    }    
   $result['num_rows'] = $num_rows;
   $result['result_array'] = $result_array;
   $result['row'] = $row;
   return $result;
  }
    public function FindData2($id=null,$isgroup=false){ 
            $row=[];$result_array=[]; 
           if($id!=null){
             $this->db->where('platformId =', $id);
             $this->db->order_by('slug ASC, name ASC');
             }else{ 
              if ($isgroup) {
                $this->db->group_by(['platformId']);
              } 
             $this->db->order_by('platformId ASC, slug ASC, name ASC');
            } 
             
           $sql = $this->db->get('autosystem_game_list'); 
            $num_rows=$sql->num_rows();
           if($num_rows>0){
             $result_array = $sql->result_array();
             $row = $sql->row();  
           }    
          $result['num_rows'] = $num_rows;
          $result['result_array'] = $result_array;
          $result['row'] = $row;
          return $result;
    }	 
    public function UpdateData($provider='',$Data=NULL){  
          try { 
               $this->db->where('id', $Data->id);  
                unset($Data->id); //remove id for update key 
               if($provider=='provider'){
                $this->db->where('provider_id', $Data->provider_id);  
                unset($Data->provider_code); 
                $edit =$this->db->update('lobby_control', $Data);
               }else if($provider=='gamelist'){ 
                $edit =$this->db->update('autosystem_gamelist', $Data); 
               }else if($provider=='pgs'){
                $this->db->where('id', $Data->id);   
                $edit =$this->db->update('pg_list_games', $Data); 
               }  
              if (!$edit) {
                $this->Data->error_code=500;return false;
             }
           } catch (Exception $e) {
            $this->Data->error_code=500;return false;
          }
        $this->Data->error_code=200; 
        return $this->Data;     
	}   
  public function InsertData($provider='',$Data=NULL){  
    try { 
         if($provider=='provider'){
            $edit =$this->db->insert('lobby_control', $Data); 
           }else if($provider=='gamelist'){
            $edit =$this->db->insert('autosystem_gamelist', $Data); 
          }else if($provider=='pgs'){
          $edit =$this->db->insert('pg_list_games', $Data); 
         }   
        if (!$edit) {
          $this->Data->error_code=500;return false;
       }
     } catch (Exception $e) {
      $this->Data->error_code=500;return false;
    }
  $this->Data->error_code=200; 
  return $this->Data;     
}  
	// หลังจากบันทึกข้อมูลให้ทำอะไร
	public function AfterSaveData(){ }	
 	// หลังจากบันทึกข้อมูลให้ทำอะไร
   public function AfterEdit(){
	  $this->AfterSaveData();	    
   }	
 	// หลังจากลบข้อมูลให้ทำอะไร
    public function AfterDelete(){ }
}
?>
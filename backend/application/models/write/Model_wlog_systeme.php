<?php
class Model_wlog_systeme extends MY_Model{   
  private $table='log_systeme';
	public function SaveData($data='',$isnew=false){
		 try {     
            if($isnew){
               $this->InsertData($data); 
            }else{
              $this->UpdateData($data); 
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
 public function UpdateOnly($data=''){
    try {  
             $this->UpdateData($data); 
           if ($this->Data->error_code!=200) {
                $this->Data->error_code=500;
               return $this->Data;
       }  
      } catch (Exception $e) {
      $this->Data->error_code=500;
     } 
     return $this->Data;
   }    
    public function UpdateData($Data=NULL){  
          try { 
               $this->db->where('id', $Data->id);  
                unset($Data->id); //remove id for update key 
                $edit =$this->db->update($this->table, $Data);  
               if (!$edit) {
                $this->Data->error_code=500;return false;
             }
           } catch (Exception $e) {
            $this->Data->error_code=500;return false;
          }
        $this->Data->error_code=200; 
        return $this->Data;     
	}   
  public function InsertData($Data=NULL){  
    try { 
          $edit =$this->db->insert($this->table, $Data);   
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
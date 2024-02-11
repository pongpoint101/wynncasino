
<?php
class Model_employees extends MY_Model {

	  public function FindBy($where_field,$where_value){
		$result_array = "";
		$row = [];  
        $this->db->where("$where_field", $where_value); 
        $sql = $this->db->get('employees');
        $num_rows=@$sql->num_rows(); 
        if($num_rows>0){
            $row =$sql->row();
        } 
		$result['num_rows'] = (@$num_rows>0?$num_rows:0);
		$result['result_array'] = $result_array;
		$result['row'] = $row;
		return $result;
	}
    public function SaveData($data){
        try { 
           $dupicate=$this->FindBy('username=',$data->username);
           if($dupicate['num_rows']>0){
               $this->EditById($data->username, $data); 
               }else{
               $this->Insert($data); 
             } 
             if ($this->Data->error_code!=200) {
               throw new Exception('save errro.'); 
             }  
          } catch (Exception $e) {
            $this->Data->error_code=500;$this->Data->error_msg=$e->getMessage();
         } 
       return $this->Data;
   }
   public function Insert($data){
       try { 
            $insert =$this->db->insert('employees', $data);
              if(!$insert){
             $this->Data->error_code=500;return false;
            } 
          $this->AfterSaveData();
         } catch (Exception $e) {
            $this->Data->error_code=500;$this->Data->error_msg=$e->getMessage();return $this->Data;
        }
         $this->Data->error_code=200; 
         return $this->Data;
   }
   public function EditById($id,$Data){
           try {
               $this->db->where('username', $id);  
               $edit =$this->db->update('employees', $Data);   
               if (!$edit) {
                   $this->Data->error_code=500; return $this->Data;
               }
               $this->AfterEdit();
             } catch (Exception $e) {
               $this->Data->error_code=500;$this->Data->error_msg=$e->getMessage();return $this->Data;
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
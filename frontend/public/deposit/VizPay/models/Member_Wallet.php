<?php
class Bc_Credit
{
    //DB stuff
    private $conn;
    private $table = 'member_wallet';

    //Properties
    public $amount;
    public $customerId;
 

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function deposite()
    {
        try{
              // Create query
            $query = "UPDATE {$this->table} 
                        SET main_wallet = main_wallet + :amount 
                        WHERE member_no = :customerId";
               
              // Prepare statement
            $stmt = $this->conn->prepare($query);

               // Clean data
            $this->amount = htmlspecialchars(strip_tags($this->amount));
            $this->customerId = htmlspecialchars(strip_tags($this->customerId));

             // Bind data
             $stmt->bindParam(':amount', $this->amount);
             $stmt->bindParam(':customerId', $this->customerId);
            
             // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }catch (Exception $e) {
            header('X-PHP-Response-Code: 500', true, 500);
            error_log($e->getMessage(), 0);
                echo json_encode(
                     array("Caught exception"=>$e->getMessage())
                );
                die();
        }
    }
    

}
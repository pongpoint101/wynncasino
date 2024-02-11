<?php
class Log_Deposite_Vizpay
{
    //DB stuff
    private $conn;
    private $table = 'log_deposit_vizpay';

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function check_trx($trx_id)
    {
        try {
            $trx_id = htmlspecialchars(strip_tags($trx_id));
            $wait_confirm = "WAIT_CONFIRM";
            $query = "SELECT txn_order_id FROM {$this->table} WHERE txn_order_id = :trx_id AND deposit_status = :wait_confirm";
             // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':trx_id', $trx_id);
            $stmt->bindParam(':wait_confirm', $wait_confirm);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return true;
            }


            return false;
        } catch (Exception $e) {
            header('X-PHP-Response-Code: 500', true, 500);
            error_log($e->getMessage(), 0);
            echo json_encode(
                array("Caught exception" => $e->getMessage())
            );
            die();
        }
    }

    public function confirm($trx_id)
    {
        try {
            $trx_id = htmlspecialchars(strip_tags($trx_id));
            $success = "SUCCESS";
            // Create query
            $query = "UPDATE {$this->table} 
                SET deposit_status = :success
                WHERE txn_order_id  = :trx_id";

            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':trx_id', $trx_id);
            $stmt->bindParam(':success', $success);
            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        } catch (Exception $e) {
            header('X-PHP-Response-Code: 500', true, 500);
            error_log($e->getMessage(), 0);
            echo json_encode(
                array("Caught exception" => $e->getMessage())
            );
            die();
        }
    }

}
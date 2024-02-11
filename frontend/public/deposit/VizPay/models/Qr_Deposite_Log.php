<?php
class Qr_Deposite_Log
{
    //DB stuff
    private $conn;
    private $table = 'log_deposit';

    //User Properties
    // private $trx_id;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insert($trx_id, $member_no, $amount)
    {
        try {
            $trx_id = htmlspecialchars(strip_tags($trx_id));;
            $member_no = htmlspecialchars(strip_tags($member_no));
            $amount = htmlspecialchars(strip_tags($amount));
            $channel = 15;
            $openration_type = $channel;
            $admin_name = "vpay_qrcode";
            $trx_date = date("Y-m-d");
            $trx_time = date("h:i:s");
            $status = 2;
            $remark = "QR Code";
            $remark_internal = $remark;
            //Create query
            $query = "INSERT INTO {$this->table} (member_no, amount, channel, openration_type , admin_name, trx_id, trx_date, trx_time, status, remark, remark_internal) VALUES (:member_no, :amount, :channel, :openration_type , :admin_name, :trx_id, :trx_date, :trx_time, :status, :remark, :remark_internal  )";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind data
            $stmt->bindParam(':trx_id', $trx_id);
            $stmt->bindParam(':member_no', $member_no);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':channel', $channel);
            $stmt->bindParam(':openration_type', $openration_type);
            $stmt->bindParam(':admin_name', $admin_name);
            $stmt->bindParam(':trx_date', $trx_date);
            $stmt->bindParam(':trx_time', $trx_time);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':remark', $remark);
            $stmt->bindParam(':remark_internal', $remark_internal);
            // Execute query
            if ($stmt->execute()) {
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
    public function update($trx_id, $member_no, $status)
    {
        try {
            $trx_id = htmlspecialchars(strip_tags($trx_id));
            $member_no = htmlspecialchars(strip_tags($member_no));
            $status = htmlspecialchars(strip_tags($status));
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

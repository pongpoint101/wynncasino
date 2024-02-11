<?php
require '../bootstart.php';
$promotion =  GetPromotionCate($_SESSION['member_no']);
$member_no = $_SESSION['member_no'];

?>
<div class="member-info mx-auto">
<div class="table-responsive">
    <table class="table table-bordered mb-0" id="tbWinLoss">
        <thead>
            <tr class="text-center">
                <th scope="col">Bonus/Promotion</th>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
        <?php if($promotion){ ?>
            <?php foreach($promotion as $row){ ?>
            <tr class="text-right">
                <td class="text-center"><?php echo $row->remark ?></td>
                <td><?php echo $row->amount ?></td>
            </tr>
            <?php } ?>
            <?php }else{ ?>
                <tr class="text-right">
                    <td class="text-center" colspan="3">........ไม่พบข้อมูล........</td>
                </tr>
            <?php } ?>
            
        </tbody>
    </table>
</div>

  
    
</div>
<script type="text/javascript">
    $(function() {

    });
</script>
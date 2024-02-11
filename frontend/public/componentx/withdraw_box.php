<?php 
defined('ROOT_APP') OR exit('No direct script access allowed');
?> 
<div class="modal fade" id="boxwidthdraw" tabindex="-1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content"> 
      <div class="modal-body">
      <table class="table table-borderless">
        <tbody class="table_h">
            <tr class="text-center"> 
                <th scope="col"><h3>ยอดเงินคงเหลือ</h3></th> 
            </tr>
            <tr class="text-center"> 
                <th scope="col"><h3 class="boxfont2">{{data_global.main_wallet}}</h3></th> 
                </tr>
            </tbody> 
        </table>
          <form action="#" method="post" data-parsley-validate="">
                <input type="hidden" name="task" value="withdraw">
                <input type="hidden" id="wd-member-fullname" :value="fullName">
                <input type="hidden" id="wd-to" name="wd-to" :value="fullBank">
                <input type="hidden" id="wd_amount_text" name="wd_amount_text" v-model="data_global.withdraw_balance">
                <input type="hidden" id="min_wd" :value="data_global.min_wd">
                <input type="hidden" id="wd_amount" v-model="data_global.withdraw_balance">
                <div class="table-responsive">
                    <table class="table withdraw_history_table"> 
                        <tbody>
                            <tr> 
                                <th scope="col">ชื่อบัญชี</th> 
                                <th scope="col">{{data_global.fname}} {{data_global.lname}}</th>  
                            </tr>
                            <tr> 
                                <th scope="col">โอนเข้าธนาคาร</th> 
                                <th scope="col">{{data_global.bank_name}} - {{data_global.bank_accountnumber}}</th>  
                            </tr>
                            <tr> 
                                <th scope="col">จำนวนเงินที่ถอนได้</th> 
                                <th scope="col">{{data_global.main_wallet}}</th> 
                            </tr>
                        </tbody>
                    </table> 
                </div>
                <div class="d-grid gap-2 pt-3 pb-3"> 
                    <button type="button" id="wdAction" class="btn btn-round btn-warning btn-block" value="แจ้งถอนเงิน" v-on:click="wdAction()" :disabled="data_wd.issubmit"><i class="fas fa-hand-holding-usd"></i> แจ้งถอนเงิน</button>
                </div>
            </form>
      </div> 
    </div>
  </div>
</div> 
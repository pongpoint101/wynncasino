<!-- ////////////////////////////////////////////////////////////////////////////-->
<footer class="footer footer-static footer-light navbar-border navbar-shadow">
	<p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
		<span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2021 <a class="text-bold-800 grey darken-2" href="javascript:void(0)">PG Game </a>, All rights reserved. </span>
		<span class="float-md-right d-block d-md-inline-blockd-none d-lg-block">Hand-crafted & Made with <i class="ft-heart pink"></i></span>
	</p>
</footer>
<div class="modal animated swing text-left" id="modal-success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel307" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content bg-white">
			<div class="modal-body">
				<div class="row">
					<div class="col-12 text-center pggmae-image-success">
						<img class="img-fluid width-200">
					</div>
				</div>
				<h2 class="text-center black">บันทึกข้อมูลสำเร็จ</h2>
				<h4 class="text-center black">บันทึกเรียบร้อยแล้ว</h4>
				<div class="row">
					<div class="col-12 text-center">
						<input type="button" class="btn btn-primary width-100 mt-2" data-dismiss="modal" value="OK">
					</div>
				</div>
	        </div>
	    </div>
	</div>
</div>

<div class="modal animated pulse text-left" id="modal-delete-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel37" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content  bg-hexagons" style="border-radius: 1rem !important;">
            <div class="modal-header">
                <h3 class="modal-title avatar-busy" id="myModalLabel37" style="color:#776c95;"><i class="la la-trash-o rounded-circle" style="padding: 0.35rem;color: #fff;font-size: 16PX;"></i> ยืนยันการลบข้อมูล</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mt-2">
            	<h6 class="text-center" style="color:#776c95;">คุณต้องการลบข้อมูล ? ยืนยันอีกครั้งเพื่อทำการลบข้อมูลนี้</h6>
        	</div>
            <div class="modal-footer border-0 text-center justify-content-center">
                <input type="button" style="border-radius: 0.7rem;" class="btn btn-primary width-100 submit-myway-form-user-del" value="ใช่">
                <input type="reset" style="border-radius: 0.7rem;" class="btn btn-outline-primary width-100" data-dismiss="modal" value="ไม่ใช่">
            </div>
        </div>
    </div>
</div>
<form onsubmit="return false;" class="myway-form-delete" method="post" action>
    <input type="hidden" class="delete-ids" name="ref_ids">
</form>
<!-- JS -->
<script src="<?php echo base_url('app-assets/vendors/js/vendors.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/data/jvector/visitor-data.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('js/jquery.form.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/js/core/app-menu.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/js/core/app.js')?>" type="text/javascript"></script>
  <script src="<?php echo base_url('app-assets/vendors/js/extensions/sweetalert.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/forms/toggle/bootstrap-switch.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/forms/toggle/switchery.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/js/scripts/customizer.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/forms/select/select2.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/pickers/pickadate/picker.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/pickers/pickadate/picker.date.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/pickers/pickadate/picker.time.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/pickers/pickadate/legacy.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/pickers/pickadate/translations/th_TH.js')?>" ></script>
<script src="<?php echo base_url('app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/pickers/daterange/daterangepicker.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/js/scripts/navs/navs.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/tables/datatable/datatables.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/tables/dataTables.buttons.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/tables/jszip.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('app-assets/vendors/js/tables/buttons.html5.min.js')?>" type="text/javascript"></script> 

 
<script type="text/javascript">
 var old_datawd=[]; 
 var old_datads=[];
 function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {   
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}
 function randomIntFromInterval(min, max) { // min and max included 
  return Math.floor(Math.random() * (max - min + 1) + min)
  } 
  function beep() {
       if ($(".ft-volume-1").length != 0) {
        var snd = new Audio("<?=base_url('assets/alert.mp3')?>");
        snd.play();
        }
      }
	  
	 function soundplay_withdraw_padding(pdata){
		var checkplay=true;if(pdata==null){ return false;}
		for (let index = 0; index < pdata.data.length; index++) {
			const element = pdata.data[index];
			if(element[2]!=old_datawd['index'+element[2]]&&checkplay){
				beep();checkplay=false;
			}
			old_datawd['index'+element[2]]=element[2]; 
		   } 
	   }
	   function soundplay_deposit_alert(pdata){
		var checkplay=true;
		for (let index = 0; index < pdata.data.length; index++) {
			const element = pdata.data[index];
			var indexid=element[2].replace(/[\D]/g, '');
			if(indexid!=old_datads['index'+indexid]&&checkplay){
				beep();checkplay=false;
				}
			 old_datads['index'+indexid]=indexid; 
		 } 
	   }
	$(function(){
		$.ajaxSetup({
			cache: false,
			error: function (x, status, error) {
				if (x.status == 403) {
					alert("ไม่มีสิทธิ์เข้าถึง Access Denied(403)"); 
					window.location='../home/index';
				 } 
			}
		}); 
		$(document).on("click","#soundalert",function() {
			if($("#soundalert .ft-volume-x").length>0){
				setCookie('soundalert','ft-volume-1',365);   	
			}
			if($("#soundalert .ft-volume-1").length>0){
				setCookie('soundalert','ft-volume-x',365);   	
			}
			$('#soundalert i').removeClass('ft-volume-1 ft-volume-x').addClass(getCookie('soundalert'));

        });

      if ($(".ft-volume-1").length != 0) {
		setTimeout(() => {
			check_withdraw_padding();	 
	    }, 1000*50); 
	  }

	  function check_withdraw_padding(){
		if('withdraw/pending'!='<?=uri_string();?>'){ 
		$.ajax({
				url: '<?= base_url("withdraw/list_withdraw_padding_json?latest=10") ?>',
				type: 'get', 
				dataType: 'json',
				success: function(res) {
					setTimeout(() => {
						check_withdraw_padding();	
					}, 1000*randomIntFromInterval(10, 15));
					if(res.data.length>0){
						soundplay_withdraw_padding(res);
					} 
				} 
			});
	     }
	  }  

		<?php 
		     $password_change=$this->session->userdata("password_change");
			 $member_level=$this->session->userdata("level");
		     if($password_change==null) { $password_change=date('Y-m-d H:i:s', strtotime('-2 month'));}
             $numday=date_diff(date_create(date('Y-m-d')),date_create(date($password_change)))->format("%R%a"); 
		 	 if((int)$numday<=0 && in_array($member_level,[1,2])){
			 ?>
			 alert('กรุณาเปลี่ยนรหัสผ่าน!');
			<?php
			}	
	        ?>
        $('#modal-delete-form').on('hidden.bs.modal', function (event) {
            $('.delete-ids').val("");
        });
        $('.submit-myway-form-user-del').click(function(){
        	$('.myway-form-delete').submit();
        });
		$('.menu-setting-game').click(function(){
			if ($('.ul-setting-game').hasClass('d-none') == true) {
				$('.ul-setting-game').removeClass('d-none');
			} else {
				$('.ul-setting-game').addClass('d-none');
			}
        });
        $('.menu-setting-all').click(function(){
			if ($('.ul-setting-all').hasClass('d-none') == true) {
				$('.ul-setting-all').removeClass('d-none');
			} else {
				$('.ul-setting-all').addClass('d-none');
			}
        });
        $('.menu-bank').click(function(){
			if ($('.ul-bank').hasClass('d-none') == true) {
				$('.ul-bank').removeClass('d-none');
			} else {
				$('.ul-bank').addClass('d-none');
			}
        });
	});
	function model_success_pggame(){
		$('.modal').modal('hide');
		var pggame_date = new Date();
		$('.pggmae-image-success').html('<img class="img-fluid width-200" src="<?php echo base_url('pg_image/success_pggame.gif')?>?'+pggame_date.getTime()+'">');
		$('#modal-success').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
		setTimeout(function(){
			$('.pggmae-image-success img').remove();
			$('.modal').modal('hide');
		},2000);
		return true;
	}
	function DeleteMainActive(ref_ids){
		$('.delete-ids').val(ref_ids);
		$('#modal-delete-form').modal({
            show: true
        });
	}

</script>
<script> 
       function Show_remark_detail(txt){ 
			 $('#txt_remark_detail').empty().html(txt);
			 $('#modalremark_detail').modal('show');
	  }
	</script> 
	<div class="modal fade modalremark_detail" id="modalremark_detail" tabindex="-1" role="dialog" aria-labelledby="modalremark_detail" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-sm">
		<div class="modal-content">
		<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">รายละเอียดหมายเหตุ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>	
		<div class="modal-body"> 
		  <p id="txt_remark_detail"></p>
		</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
      </div> 
		</div>
	</div>
	</div>

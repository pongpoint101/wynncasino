<?php 
$popupsize=sizeof($temp_popup);  
$popupexpcheck='<input type="checkbox" id="popupexpcheck" name="popupexpcheck" value="1" style="cursor: pointer;"> <label for="popupexpcheck">ปิดการแสดงผล 1 วัน</label> ';
$basejspopuphtml="if(getCookie('delayshowpopup')==''){ "; $latestDateUpdate=[];
if($popupsize>0){
  $name_popup_last_update='popup_last_update';$cookie_popup_last_update='';
   foreach ($temp_popup as $k => $v) {   
            $link_popup='#';$stypeopend=''; $popup_link_name='';$latestDateUpdate[]=$v['update_at']; 
            $popupcheckjs=" 
            if(result.value.popupexpcheck){
              setCookie(\"delayshowpopup\",1,24*60*(60*1000)); //  
            }";
            $stypeopend.=$popupcheckjs; 
            if($v['open_style_popup']!='close'){// ใส่ปุ่มลิงค์
              $link_popup='#'; 
                if(trim($v['link_popup'])!=''){ 
                $link_popup=$v['link_popup'];
                }
                $stypeopend.="window.open('$link_popup','{$v['open_style_popup']}').focus();"; 
                $popup_link_name="confirmButtonText: '{$v['popup_link_name']}', ";
            } 
           // show popup   style
            if($v['pop_style']==1){
                $html_link_tartget="'<img src=\"https://".SOURCE_CDN.'.'.get_domain()."/web/".$v['images']."\"><br>$popupexpcheck'";  
                $basejspopuphtml.=" 
                    await QueuePopup.fire(
                      {
                        $popup_link_name
                        html:$html_link_tartget
                      }
                      ).then((result) => { 
                      if (result.isConfirmed) {
                        $stypeopend 
                      } 
                    }); 
                    ";
             }else if($v['pop_style']==2){
              $html_link_tartget="'{$v['message']}<br>$popupexpcheck'";  
              $basejspopuphtml.="  
                  await QueuePopup.fire(
                    {
                      $popup_link_name
                      html:$html_link_tartget
                    }
                    ).then((result) => { 
                    if (result.isConfirmed) {
                      $stypeopend 
                    } 
                  }); 
                  ";
             }else if($v['pop_style']==3){
               $html_link_tartget="'<img src=\"https://".SOURCE_CDN.'.'.get_domain()."/web/".$v['images']."\"> <div>".$v['message']."</div> <br>$popupexpcheck'";  
               $basejspopuphtml.="  
                await QueuePopup.fire(
                  {
                    $popup_link_name
                    html:$html_link_tartget
                  }
                  ).then((result) => { 
                  if (result.isConfirmed) {
                    $stypeopend 
                  } 
                 }); 
                ";
         } 
    }  
}
$basejspopuphtml.="}// end getCookie('delayshowpopup')"; 
?>
<script>
  var latestDateTime='';
  <?php
   if(@sizeof($latestDateUpdate)>0){
     $latestDateTime = max(array_map('strtotime', $latestDateUpdate));
     $latestDateTime= date('Y-m-d H:i:s', $latestDateTime);
     ?>
     latestDateTime ='<?=$latestDateTime; ?>';
   <?php
   }
  ?> 
    $(function(){     
      var oldlatestDateTime=getCookie('popuplatest_localtime'); 
      var olddelayshowpopup=getCookie('delayshowpopup');   
      if(olddelayshowpopup!=''){// Shows that popups are disabled.
          if(latestDateTime!=oldlatestDateTime){// start showing popup again
            document.cookie = 'delayshowpopup'+'=; Max-Age=0';
         }
      }   
      window.ShowPopUp = async () => {
        const QueuePopup = Swal.mixin({ 
        showCloseButton: true,
        confirmButtonText: 'ตกลง', 
        position: 'center', customClass: 'popupheight',timer:20000000, 
        showClass: { backdrop: 'swal2-noanimation' },
        hideClass: { backdrop: 'swal2-noanimation' },
        willOpen: (willOpen) => { 
               if(getCookie('delayshowpopup')!=0){
                $("#popupexpcheck").prop('checked', true);
            } 
        },
        willClose: (willClose) => {
               if($('#popupexpcheck').is(':checked')){
                 setCookie("delayshowpopup",1,24*60*(60*1000)); //  
               }
        },
        preConfirm: function() {
            return new Promise((resolve, reject) => { 
                resolve({
                   popupexpcheck: $('#popupexpcheck').is(':checked')
                }); 
            });
          }
        });
       <?=$basejspopuphtml?> 
      };
      window.ShowPopUp(); 

      if(olddelayshowpopup==''||oldlatestDateTime==''||oldlatestDateTime==null&&latestDateTime!=''&&latestDateTime!=null){// ถ้าไม่เจอข้อมูล
         setCookie("popuplatest_localtime",latestDateTime,5*24*60*(60*1000)); 
         setCookie("delayshowpopup",0,1*60*(60*1000)); // 60 นาที
      }

});
  </script>
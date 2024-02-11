<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
          <li class="nav-item mr-auto">
            <a class="navbar-brand" href="<?php echo base_url() ?>">
              <i class="la la-tachometer pg-color-gray icon-main-pg"></i>
              
              <!-- <h3 class="brand-text pg-color-gray"><?= SITE_NAME_ID ?></h3> -->
              <h3 class="brand-text pg-color-gray"> BACKOFFICE</h3>
            </a>
          </li>
          <li class="nav-item d-none d-md-block float-right"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon ft-toggle-right d-none font-medium-3 white" data-ticon="ft-toggle-right"></i></a></li>
          <button class="navbar-toggler menu-icon d-md-none" type="button" id="toggle-buttonnav2" data-toggle="collapse" data-target="#sidebarnav2" aria-controls="sidebarnav2" aria-expanded="true" aria-label="Toggle navigation">
            <span class="navicon"><i class="ft-grid"></i></span>
          </button>
          <li class="nav-item d-md-none">
            <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a>
          </li>


        </ul>
      </div>
      <div class="navbar-container content">
        <div class="collapse navbar-collapse" id="navbar-mobile">
          <ul class="nav navbar-nav mr-auto float-left">
            <!-- <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li> -->
          </ul>
          <li class="navbar-toggler menu-icon toggle-buttonnav2" type="button" id="toggle-buttonnav2" data-toggle="collapse" data-target="#sidebarnav2" aria-controls="sidebarnav2" aria-expanded="true" aria-label="Toggle navigation">
            <span class="navicon"><i class="ft-grid"></i> </span>
          </li>
          <ul class="nav navbar-nav float-right">

            <li class="nav-item border-left border-right displayProfileMember d-none">
              <a class="nav-link gotoReportSection" href="#info">
                ข้อมูลสมาชิก
              </a>
            </li>
            <li class="nav-item border-left border-right displayProfileMember d-none">
              <a class="nav-link gotoReportSection" href="#aff">
                แนะนำเพื่อน
              </a>
            </li>
            <li class="nav-item border-left border-right displayProfileMember d-none">
              <a class="nav-link gotoReportSection" href="#deposit">
                ฝาก/รับเครดิต
              </a>
            </li>
            <li class="nav-item border-left border-right displayProfileMember d-none">
              <a class="nav-link gotoReportSection" href="#withdraw">
                ถอนเครดิต
              </a>
            </li>
            <li class="nav-item displayProfileMember mr-0">
              <a class="nav-link soundalert" href="#" id="soundalert">
                <!-- ft-volume-x ft-volume-1 -->
                <i class="ficon <?= (isset($_COOKIE['soundalert']) ? $_COOKIE['soundalert'] : 'ft-volume-x') ?>"></i>
              </a>
            </li>
            <?php if (canroute('admin/clodesysteme')) { ?>
              <li class="nav-item displayProfileMember mr-0" id="clodesysteme" onclick="clodesysteme()">
                <a class="nav-link soundalert" href="#" style="padding: 1.2rem 0.5rem;">
                  <i class="la la-exclamation-triangle" style="color:red;font-size:34px;" title="ปิดระบบการใช้งานระบบทั้งหมด"></i>
                </a>
              </li>
            <?php } ?>
            <li class="dropdown dropdown-user nav-item">
              <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                <span class="d-flex">Hello,
                  <span class="user-name text-bold-700 d-block"><?php echo $this->session->userdata('SurName'); ?> <i class="ft-chevron-down"></i></span>
                </span>
              </a>
              <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="<?php echo base_url('logout') ?>"><i class="ft-power"></i> Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>





  <div class="container">
    <div class="collapse navbar-collapse width bg-dark px-2" id="sidebarnav2">
      <ul class="navbar-nav mr-auto p-2 col align-self-center justify-content-center">
        <li class="nav-item r-menu">
          <!-- <a class="nav-link" href="#">การโยกเงิน</a> -->
          <!-- <a class="nav-link" href="#"> การ โยก/เติม เงินอัตโนมัติ</a> -->
          <!-- <a class="nav-link" href="#"> OTP ธนาคาร</a> -->
          <!-- <a class="nav-link" href="#">ตั้งค่าเกม</a> -->
          <?php if (canroute('games/*')) { ?>
            <!-- <li class="nav-item"> -->
            <a class="nav-link menu-setting-game">ตั้งค่าเกมส์ <i class="la la-angle-down"></i></a>
            <ul class="menu-content ul-setting-game d-none">
              <li class="MenuEnableprovidergames"><a class="menu-item MenuEnableprovidergames" href="<?php echo base_url('games/Listprovidergames') ?>" data-i18n="nav.lobbypgs.providergames">เปิด-ปิด ค่ายเกมส์</a></li>
              <li class="MenuEnablelobbygames"><a class="menu-item MenuEnablelobbygames" href="<?php echo base_url('games/Listgames') ?>" data-i18n="nav.lobbypgs.Listgames">เปิด-ปิด เกมส์</a></li>
            </ul>
            <!-- </li> -->
          <?php } ?>
          <a class="nav-link Menupopuplist" href="<?php echo base_url('website/popuplist') ?>">ประกาศ</a>
          <a class="nav-link Menu_reportmemberplay" href="<?php echo base_url('reports/reportwinlosemember?provi=filter') ?>">ประวัติการเล่นเกม</a>
          <!-- <a class="nav-link" href="#">กลุ่มลูกค้า</a> -->
          <a class="nav-link MenuPomotionMain" href="<?php echo base_url('promotion/promotionList') ?>">โปรโมชั่น</a>
          <!-- <a class="nav-link" href="#">Landing Pages</a> -->
          <!-- <a class="nav-link" href="#">ของรางวัล</a> -->
          <!-- <a class="nav-link" href="#">เซียน</a> -->
          <a class="nav-link MenuAdminMain" href="<?php echo base_url('admin/view') ?>">แอดมิน</a>
          <a class="nav-link MenuPermissions" href="<?php echo base_url('Permissions/view') ?>">สิทธิ์การใช้งาน</a>
          <!-- <a class="nav-link" href="#">พันธมิตร</a> -->


          <?php if (canroute('affiliate/view')) { ?>
            <a class="nav-link menu-setting-all">ตั้งค่าต่างๆ <i class="la la-angle-down"></i></a>
            <ul class="menu-content ul-setting-all d-none">
              <li class="MenuAffSetting"><a class="menu-item MenuAffSetting" href="<?php echo base_url('website/affsetting') ?>" data-i18n="nav.affiliate.setting">Affiliate</a></li>
              <li class="MenuWebsiteSetting"><a class="menu-item MenuWebsiteSetting" href="<?php echo base_url('website/view') ?>" data-i18n="nav.website.setting">เว็บไซต์</a></li>
              <li class="MenuCommSetting"><a class="menu-item MenuCommSetting" href="<?php echo base_url('website/commission') ?>" data-i18n="nav.commwebsite.setting">ตั้งค่าคอมมิชชั่น</a></li>
              <li class="MenucashbackSetting"><a class="menu-item MenucashbackSetting" href="<?php echo base_url('website/Cashback') ?>" data-i18n="nav.cashbackwebsite.setting">ตั้งค่าคืนยอดเสีย</a></li>
              <li class="Menupopuplist"><a class="menu-item Menupopuplist" href="<?php echo base_url('website/popuplist') ?>" data-i18n="nav.popupwebsite.setting">ตั้งค่าป๊อปอัพ</a></li>
              <li class="MenuHome"><a class="menu-item MenuHome" href="<?php echo base_url('website/home') ?>" data-i18n="nav.homewebsite.setting">ตั้งค่าหน้าหลัก</a></li>
              <!-- <li class="MenuNotifysetting"><a class="menu-item" href="<?php echo base_url('website/notifysetting') ?>" data-i18n="nav.notifysetting.setting">ตั้งค่าแจ้งเตือนผ่านไลน์</a></li> -->
            </ul>
          <?php } ?>

          <?php if (canroute('truewallet/view')) { ?>
          <a class="nav-link MenuTrueWalletMain" href="<?php echo base_url('truewallet/view') ?>">บัญชี TrueWallet</a>
          <?php } ?>
          <!-- <a class="nav-link" href="#">บัญขีธนาคารฝาก/ถอน (เอเย่นต์)</a> -->
          <!-- <a class="nav-link" href="#">SCB APPS</a> -->
          <!-- <a class="nav-link" href="#">DeviceID</a> -->
          <?php if (canroute('bank/*')) { ?>
            <a class="nav-link menu-bank" href="#">ธนาคาร <i class="la la-angle-down"></i></a>
            <ul class="menu-content ul-bank d-none">
              <li class="MenuBanklist"><a class="menu-item MenuBanklist" href="<?php echo base_url('bank/view') ?>" data-i18n="nav.withdraw.padding">รายการธนาคาร/statements</a></li>
              <li class="MenuBankhistory"><a class="menu-item MenuBankhistory" href="<?php echo base_url('bank/history') ?>" data-i18n="nav.affiliate.setting">รายงานดึงธนาคาร</a></li>
              <li class="MenuBankWithdraw"><a class="menu-item MenuBankWithdraw" href="<?php echo base_url('bank/withdraw') ?>" data-i18n="nav.withdraw">บัญชีถอน</a></li>
            </ul>
          <?php } ?>
          <!-- <a class="nav-link" href="#">ประวัติการแก้ไขข้อมูลลูกค้า</a> -->
          <!-- <a class="nav-link" href="#">Block Ip</a> -->
        </li>
      </ul>
    </div>
  </div>
  </nav>






  <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
      <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        <li class="nav-item MenuDashbord">
          <a href="javascript:void(0)"><i class="la la-home"></i><span class="menu-title" data-i18n="nav.dash.main">Dashboard </span></a>
          <ul class="menu-content">
            <?php if (canroute('dashbord/view')) { ?><li class="MenuDashbordDay"><a class="menu-item" href="<?php echo base_url('dashbord/view') ?>" data-i18n="nav.dashbord.today">สรุปวันนี้</a></li> <?php } ?>
            <?php if (canroute('dashbord/month')) { ?><li class="MenuDashbordMonth"><a class="menu-item" href="<?php echo base_url('dashbord/month') ?>" data-i18n="nav.dashbord.month">สรุปรายเดือน</a></li> <?php } ?>
          </ul>
        </li>
        <?php if (canroute('member/view')) { ?>
          <li class="nav-item MenuWithdrawMain">
            <a href="javascript:void(0)">
              <i class="la la-users"></i><span class="menu-title" data-i18n="nav.withdraw.main">ลูกค้า</span>
              <ul class="menu-content">
                <li class="MenuMemberMain"><a class="menu-item" href="<?php echo base_url('member/view') ?>" data-i18n="nav.withdraw.padding">รายการลูกค้า</a></li>
                <?php if (canroute('reports/top20_total_deposit_withdraw')) { ?>
                <li class="MenuMemberTop20"><a class="menu-item" href="<?php echo base_url('reports/top20_total_deposit_withdraw') ?>" data-i18n="nav.withdraw.padding">top 20 ยอดฝากสูงสุด</a></li>
                <?php } ?>
              </ul>
            </a>
          </li>
        <?php } ?>
        <?php if (canroute('deposit/view')) { ?><li class="nav-item MenuDepositMain"><a href="<?php echo base_url('deposit/view') ?>"><i class="la la-flash"></i><span class="menu-title" data-i18n="nav.templates.main">รายการฝาก</span></a></li><?php } ?>
        <?php if (canroute('withdraw/view')) { ?>
          <li class="nav-item MenuWithdrawMain">
            <a href="javascript:void(0)">
              <i class="la la-level-up"></i><span class="menu-title" data-i18n="nav.withdraw.main">รายการถอนเงิน</span>
              <ul class="menu-content">
                <li class="MenuWithdrawpending"><a class="menu-item" href="<?php echo base_url('withdraw/pending') ?>" data-i18n="nav.withdraw.padding">รอทำรายการ</a></li>
                <li class="MenuWithdrawlist"><a class="menu-item" href="<?php echo base_url('withdraw/view') ?>" data-i18n="nav.withdraw.history">ประวัติการถอนเงิน</a></li>
              </ul>
            </a>
          </li>
        <?php } ?>
        <?php if (canroute('reports/deposit_mannual')) { ?>
          <li class="nav-item">
            <a href="javascript:void(0)">
              <i class="la la-navicon"></i><span class="menu-title" data-i18n="nav.templates.main">รายการลดเครดิตร/เติมมือ</span>
            </a>
            <ul class="menu-content">
              <li class="MenuDeposit_mannual"><a class="menu-item" href="<?php echo base_url('reports/deposit_mannual') ?>" data-i18n="nav.withdraw.padding">ประวัติการเติมมือ</a></li>
              <li class="MenuDelete_mannual"><a class="menu-item" href="<?php echo base_url('reports/delete_mannual') ?>" data-i18n="nav.affiliate.setting">ประวัติการลบเครดิต</a></li>
            </ul>
          </li>
        <?php } ?>
        <?php if (canroute('bank/*')) { ?>
          <li class="nav-item">
            <a href="javascript:void(0)">
              <i class="la la-bank"></i><span class="menu-title" data-i18n="nav.templates.main">ธนาคาร</span>
            </a>
            <ul class="menu-content">
              <li class="MenuBanklist"><a class="menu-item" href="<?php echo base_url('bank/view') ?>" data-i18n="nav.withdraw.padding">รายการธนาคาร/statements</a></li>
              <li class="MenuBankhistory"><a class="menu-item" href="<?php echo base_url('bank/history') ?>" data-i18n="nav.affiliate.setting">รายงานดึงธนาคาร</a></li>
              <li class="MenuBankWithdraw"><a class="menu-item" href="<?php echo base_url('bank/withdraw') ?>" data-i18n="nav.withdraw">บัญชีถอน</a></li>
            </ul>
          </li>
        <?php } ?>
        <?php // if(canroute('reports/reportsummary')){
        ?>
        <li class="nav-item">
          <a href="javascript:void(0)">
            <i class="la la-briefcase"></i><span class="menu-title" data-i18n="nav.setting.main">กระเป๋าเงิน</span>
          </a>
          <ul class="menu-content">
            <li class="Menu_reportmemberplay"><a class="menu-item" href="<?php echo base_url('reports/reportwinlosemember?provi=filter') ?>" data-i18n="nav.withdraw.padding">ยอด แพ้/ชนะ การสร้างรายได้</a></li>
            <li class="MenuSumm"><a class="menu-item" href="<?php echo base_url('reports/reportsummary?provi=filter') ?>" data-i18n="nav.website.setting">สรุป ยอด ฝาก/ถอน (กระเป๋าเงิน)</a></li>
          </ul>
        </li>
        <?php  //} 
        ?>
        <li class="nav-item">
          <a href="javascript:void(0)">
            <i class="la la-undo"></i><span class="menu-title" data-i18n="nav.setting.main">คืนยอด turnover / เสีย</span>
          </a>
          <ul class="menu-content">
            <?php if (canroute('reports/reportcashback')) { ?>
            <li class="Menucashback"><a class="menu-item" href="<?php echo base_url('reports/reportcashback') ?>" data-i18n="nav.withdraw.padding">รายการคืนยอด turnover / เสีย</a></li>
            <?php } ?>
            <?php if (canroute('reports/reportsumcashback')) { ?>
            <li class="Menusumcashback"><a class="menu-item" href="<?php echo base_url('reports/reportsumcashback') ?>" data-i18n="nav.website.setting">สรุปรายการคืนยอด</a></li>
            <?php } ?>
          </ul>
        </li>
        <li class="nav-item MenuAffiliateMain">
            <a href="javascript:void(0)">
              <i class="la la-file"></i><span class="menu-title" data-i18n="nav.aff.main">สรุป</span>
            </a>
            <ul class="menu-content">
              <?php if (canroute('reports/total_deposit_withdraw')) { ?>
              <li class="MenuTotalDeposit"><a class="menu-item" href="<?php echo base_url('reports/total_deposit_withdraw') ?>" data-i18n="nav.aff.list">ยอด ฝาก/ถอน</a></li>
              <?php } ?>
              <!-- <li class="MenuTotalDeposit2"><a class="menu-item" href="" data-i18n="nav.aff.list">ยอด ฝาก/ถอน แยกตามบัญชี</a></li> -->
              <?php if (canroute('reports/win_loss')) { ?>
              <li class="MenuTotalWinLoss"><a class="menu-item" href="<?php echo base_url('reports/win_loss') ?>" data-i18n="nav.aff.list">ยอดแพ้ชนะ</a></li>
              <?php } ?>
              <!-- <li class="MenuTotalBonus"><a class="menu-item" href="" data-i18n="nav.aff.list">ยอดโบนัส</a></li> -->
              <?php if (canroute('reports/deposit_increase')) { ?>
              <li class="MenuTotalAddDeposit"><a class="menu-item" href="<?php echo base_url('reports/deposit_increase') ?>" data-i18n="nav.aff.list">ยอดเพิ่มรายการฝาก</a></li>
              <?php } ?>
              <!-- <li class="MenuTotalMoney"><a class="menu-item" href="" data-i18n="nav.aff.list">ยอดโยกเงิน</a></li> -->
              <?php if (canroute('reports/pro_cate')) { ?>
              <li class="MenuTotalPro"><a class="menu-item" href="<?php echo base_url('reports/pro_cate') ?>" data-i18n="nav.aff.list">แยกตามโปรโมชั่น</a></li>
              <?php } ?>
              <!-- <li class="MenuTotal"><a class="menu-item" href="" data-i18n="nav.aff.list">เซียนหาลูกค้า</a></li> -->
              <?php if (canroute('reports/user')) { ?>
              <li class="MenuTotalMember"><a class="menu-item" href="<?php echo base_url('reports/user') ?>" data-i18n="nav.aff.list">แยกตามลูกค้า</a></li>
              <?php } ?>
              <?php if (canroute('reports/deposit_member')) { ?>
              <li class="MenuTotalMemberDeposit"><a class="menu-item" href="<?php echo base_url('reports/deposit_member') ?>" data-i18n="nav.aff.list">การฝากของลูกค้า</a></li>
              <?php } ?>
              <!-- <li class="MenuAffiliatehistory"><a class="menu-item" href="<?php echo base_url('affiliate/history') ?>" data-i18n="nav.aff.list">จำนวนการชวนเพื่อน</a></li> -->
              <?php if (canroute('reports/admin_update_aff')) { ?>
              <li class="MenuTotalAdminUpAff"><a class="menu-item" href="<?php echo base_url('reports/admin_update_aff') ?>" data-i18n="nav.aff.list">ประวัติการแก้ไข aff</a></li>
              <?php } ?>
              <?php if (canroute('reports/admin_update_aff_team')) { ?>
              <li class="MenuTotalAdminUpAffTeam"><a class="menu-item" href="<?php echo base_url('reports/admin_update_aff_team') ?>" data-i18n="nav.aff.list">ประวัติการแก้ไข สายงาน aff</a></li>
              <?php } ?>
              <?php if (canroute('reports/admin_update_member')) { ?>
              <li class="MenuTotalAdminUpMem"><a class="menu-item" href="<?php echo base_url('reports/admin_update_member') ?>" data-i18n="nav.aff.list">ประวัติการแก้ไขข้อมูลลูกค้า</a></li>
              <?php } ?>
            </ul>
          </li>
        
        <?php if (canroute('affiliate/view')) { ?><li class="nav-item MenuAffiliateMain">
            <a href="javascript:void(0)">
              <i class="la la-share-alt"></i><span class="menu-title" data-i18n="nav.aff.main">แนะนำเพื่อน</span>
            </a>
            <ul class="menu-content">
              <li class="MenuAffiliatelist"><a class="menu-item" href="<?php echo base_url('affiliate/view') ?>" data-i18n="nav.aff.list">ประวัติการรับโบนัส</a></li>
              <li class="MenuAffiliatehistory"><a class="menu-item" href="<?php echo base_url('affiliate/history') ?>" data-i18n="nav.aff.list">ประวัติการแนะนำเพื่อน</a></li>
              <li class="MenuAffiliateincome"><a class="menu-item" href="<?php echo base_url('affiliate/moneyincom') ?>" data-i18n="nav.aff.moneyincom">รายได้จากสมาชิก</a></li>
              <li class="MenuAffiliateref_register"><a class="menu-item" href="<?php echo base_url('affiliate/refregister') ?>" data-i18n="nav.aff.refregister">สมาชิกจากผู้แนะนำ</a></li>
              <!-- <li class="MenuAffiliateranking"><a class="menu-item" href="<?php echo base_url('affiliate/ranking') ?>" data-i18n="nav.aff.list">อันดับการแนะนำเพื่อน</a></li> -->
              <li class="MenuAffiliateref_transferofwork"><a class="menu-item" href="<?php echo base_url('affiliate/transferofwork') ?>" data-i18n="nav.aff.transferofwork" title="จัดการสายงานแนะนำเพื่อน" >จัดการสายงาน</a></li>
              <li class="MenuAffiliateManage"><a class="menu-item" href="<?php echo base_url('affiliate/manage') ?>" data-i18n="nav.aff.list">จัดการรายการแนะนำเพื่อน</a></li>
            </ul>
          </li>
        <?php } ?>
        <?php // if(canroute('reports/reportsummary')){
        ?>
        <input type="hidden" id="tokenEmploy" value="<?php echo $this->session->userdata('token'); ?>">
       <!-- <li class="nav-item MenuMemberMain"><a href="javascript:void(0)" id="groupCustomer"><i class="la la-user"></i><span class="menu-title" >กลุ่มลูกค้า</span></a></li> -->
       <?php if(URL_PARTNER != ''){ ?>
       <li class="nav-item MenuMemberMain"><a href="<?php echo URL_PARTNER.'/auth?'.'token='.$this->session->userdata('token'); ?>" ><i class="la la-user"></i><span class="menu-title" >พาร์ทเนอร์</span></a></li>
       <?php } ?>

        <li class="nav-item">
          <a href="javascript:void(0)">
            <i class="la la-cog"></i><span class="menu-title" data-i18n="nav.setting.main">อื่นๆ</span>
          </a>
          <ul class="menu-content">
            <li class="Menuchangepossword"><a class="menu-item" href="<?php echo base_url('website/changepassword') ?>" data-i18n="nav.withdraw.padding">เปลี่ยนรหัสผ่าน</a></li>
            
          </ul>
        </li>
      </ul>
    </div>
  </div>
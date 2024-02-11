<?php
require '../bootstart.php';
require_once ROOT.'/public/actions/load_auto.php';
$WebSites = GetWebSites();
$com_l1 = (isset($WebSites['aff_comm_level_1']) ? $WebSites['aff_comm_level_1'] * 1 : 0);
$key = 'MB:AFF:' . $_SESSION['member_no'];
$sql = "SELECT id,af_code,turnover_all,commission_wallet,commission_money_all,aff_type FROM members WHERE id=?"; // AND status='1'";
$row = GetDataMem([$_SESSION['member_no']]);
$aff_rate1 = (isset($Website['aff_rate1']) ? $Website['aff_rate1'] : 0);
$aff_rate2 = (isset($Website['aff_rate2']) ? $Website['aff_rate2'] : 0);
$aff_type = (isset($row['aff_type']) ? $row['aff_type'] : 0);

$income_level1 = GetWinlossLevel1($_SESSION['member_no'],0);
$tot1 = isset($income_level1['total']['total']) ? $income_level1['total']['total'] : 0;
// $income_level1_total =number_format(($tot1*$WebSites['aff_rate1'])/100, 2, '.', ',');
$income_level1_total = ($tot1 * $WebSites['aff_rate1']) / 100;

$income_level2 = GetWinlossLevel2($_SESSION['member_no'],0);
$tot2 = isset($income_level2['total']['total']) ? $income_level2['total']['total'] : 0;
// $income_level2_total =number_format(($tot2*$WebSites['aff_rate2'])/100, 2, '.', ',');
$income_level2_total = ($tot2 * $WebSites['aff_rate2']) / 100;

$table = GetWinlossTable($_SESSION['member_no']);
// $calculateWinLoss =  GetCalculateWinLoss();
$member_no = $_SESSION['member_no'];

$income_level1_before = GetWinlossLevel1($_SESSION['member_no'],1);
$tot1_before = isset($income_level1_before['total']['total']) ? $income_level1_before['total']['total'] : 0;
$income_level1_before_total = ($tot1_before * $WebSites['aff_rate1']) / 100;

$income_level2_before = GetWinlossLevel2($_SESSION['member_no'],1);
$tot2_fore = isset($income_level2_before['total']['total']) ? $income_level2_before['total']['total'] : 0;
$income_level2_before_total = ($tot2_fore * $WebSites['aff_rate2']) / 100;

$log_win_loss = GetLogWinloss($member_no);


$sitetitle = 'แนะนำเพื่อน';
$assets_head = '<link rel="stylesheet" href="' . GetFullDomain() . '/assets/css/affiliate_links.css">';
include_once ROOT_APP . '/componentx/header.php';
?>
<style>
    #tbWinLoss tr.text-right td {
        text-align: right;
    }

    .promotion-btn {
        width: 220px;
        height: 32px;
        background: var(--table-text-color) 0% 0% no-repeat padding-box;
        border-radius: 22px;
        color: var(--white-text-color);
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        gap: 10px;
        font: normal normal normal 18px/28px Kanit;
    }

    .accout-head h1 {
        color: black;
        font: normal normal 600 24px/50px Kanit;
    }

    #Modal_Promotion .modal-header {
        background: #122032;
        border-color: #122032;
        color: white;
    }

    #Modal_Promotion .modal-header button {
        font-size: 20px;
        color: white;
    }

    #Modal_Promotion .modal-body,
    #Modal_Promotion .modal-footer {
        background: #122032;
        border-color: #122032;
    }

    .text-green {
        color: #1ebb09;
    }

    .text-red {
        color: #ff0505;
    }

    .text-blue {
        color: #0018ff;
    }
</style>
<section class="info-section" id="app" v-cloak>

    <div style="background-color: white;">
        <?php
        // echo "<pre>";
        // echo var_dump($WebSites['aff_use']);
        // echo "</pre>";
        ?>
    </div>
    <div class="member-info mx-auto">
        <div class="accout-info">
            <?php if ($aff_type == 2) {  ?>
                <h1>ระบบแนะนำเพื่อน Win Loss รับค่าคอมตลอดชีพ
                <?php } else { ?>
                    <h1>ระบบแนะนำเพื่อน <?= $com_l1 ?>% รับค่าคอมตลอดชีพ
                    <?php }  ?></h1>
        </div>
        <?php if ($aff_type == 2) {  ?>
            <!-- winloss -->
            <?php if (isset($_SESSION['member_no'])) { ?>
                <div class="inner-container mx-auto">
                    <div>
                        <div class="link-label">
                            <div>Link แนะนำเพื่อน</div>
                        </div>
                        <div class="link-box">{{data_aff_com.aff_url}}
                            <input type="text" style="position: absolute;left: -9999999px;" id="aff_url" :value="data_aff_com.aff_url">
                        </div>
                        <button class="copy-btn" onclick="copyAffURL()">
                            <img src="../assets/images/affiliate_links/Icon-copy.svg" alt="copy link">
                            <span>Copy</span>
                        </button>
                    </div>
                    <div>
                        <div class="link-label">
                            <div>แนะนำเพื่อน</div>
                            <div>(ลำดับที่ 1) <?php echo $WebSites['aff_rate1'] . '%'; ?></div>
                        </div>
                        <div class="link-box credit-box">
                            <span class="credit">{{data_aff_com.count_l1}}</span>
                            <span>คน</span>
                        </div>
                        <div class="link-box credit-box">
                            <span class="credit"><?php echo number_format($income_level1_total, 2, '.', ',') ?></span>
                            <span>บาท</span>
                        </div>
                    </div>
                    <div>
                        <div class="link-label">
                            <div>แนะนำเพื่อน</div>
                            <div>(ลำดับที่ 2) <?php echo $WebSites['aff_rate2'] . '%'; ?></div>
                        </div>
                        <div class="link-box credit-box">
                            <span class="credit">{{data_aff_com.count_l2}}</span>
                            <span>คน</span>
                        </div>

                        <div class="link-box credit-box">
                            <span class="credit"><?php echo number_format($income_level2_total, 2, '.', ',') ?></span>
                            <span>บาท</span>
                        </div>
                    </div>
                <?php if($log_win_loss < 0 ){ ?>
                    <div>
                        <div class="link-label">
                            <div>เครดิตรอบล่าสุด</div>
                        </div>
                        <div class="link-box credit-box">
                            <span class="credit"><?php echo number_format($log_win_loss, 2, '.', ',') ?> </span>
                            <span>เครดิต</span>
                        </div>
                    </div>
                <?php } ?>
                    <div>
                        <div class="link-label">
                            <div>จำนวนเครดิตแนะนำ</div>
                        </div>
                        <div class="link-box credit-box">
                            <input type="text" id="num_credit_af"  style="display: none;">
                            <span class="credit"><?php echo number_format(($income_level1_total + $income_level2_total)+($log_win_loss), 2, '.', ',') ?> </span>
                            <span>เครดิต</span>
                        </div>
                        <button class=" transfer-btn" v-on:click="claimAFF()">โอนเข้ากระเป๋าหลัก</button>
                    </div>
                    <div>
                        <div class="link-label">
                            <div>โบนัสโปรโมชั่น</div>
                        </div>
                        <button class="copy-btn" onclick="loadform(<?php echo $member_no; ?>)">
                            <span>ตรวจสอบ</span>
                        </button>
                    </div>
                    <div class="accout-head mt-2">
                        <h1>Income From Member Level 1 (<?php echo $WebSites['aff_rate1'] . '%'; ?>)</h1>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tbWinLoss">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">วันที่</th>
                                    <th scope="col">Deposit</th>
                                    <th scope="col">Withdraw</th>
                                    <th scope="col">W/L</th>
                                    <th scope="col">Bonus/Promotion</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if($income_level1['data']){ ?>
                                <?php foreach ($income_level1['data'] as $row) { ?>
                                    <tr class="text-right">
                                        <td class="text-center"><?php echo $row['date'] ?></td>
                                        <td class="text-green"><?php echo number_format($row['deposit'], 2, '.', ',') ?></td>
                                        <td class="text-red"><?php echo number_format($row['withdraw'], 2, '.', ',') ?></td>
                                        <?php
                                        if ($row['winloss'] > 0) {
                                            $text_green = 'text-green';
                                        } else {
                                            $text_green = '';
                                        }
                                        ?>
                                        <td <?php echo $text_green ?>><?php echo number_format($row['winloss'], 2, '.', ',') ?></td>
                                        <td><?php echo number_format($row['promotion'], 2, '.', ',') ?></td>
                                        <td class="text-blue"><?php echo number_format($row['total'], 2, '.', ',') ?></td>
                                    </tr>
                                <?php } ?>
                                <tr class="text-right">
                                    <?php
                                    if ($income_level1['total']['winloss'] > 0) {
                                        $text_green = 'text-green';
                                    } else {
                                        $text_green = '';
                                    }
                                    ?>
                                    
                                    <td colspan="2" class="text-green"><?php echo number_format($income_level1['total']['deposit'], 2, '.', ',') ?></td>
                                    <td class="text-red"><?php echo number_format($income_level1['total']['withdraw'], 2, '.', ',') ?></td>
                                    <td class="<?php echo $text_green ?>"><?php echo number_format($income_level1['total']['winloss'], 2, '.', ',') ?></td>
                                    <td><?php echo number_format($income_level1['total']['promotion'], 2, '.', ',') ?></td>
                                    <td class="text-blue"><?php echo number_format($income_level1['total']['total'], 2, '.', ',') ?></td>
                                </tr>
                                <tr class="text-right">
                                    <td class="text-center" colspan="5">Rate <?php echo $WebSites['aff_rate1']; ?>%</td>
                                    <td class="text-blue"><?php echo number_format(($income_level1['total']['total'] * $WebSites['aff_rate1']) / 100, 2, '.', ',')  ?></td>
                                </tr>
                            <?php }else{ ?>
                                <tr class="text-right">
                                    <td class="text-center" colspan="6">.....ไม่พบข้อมูล.....</td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="accout-head">
                        <h1>Income From Member Level 2 (<?php echo $WebSites['aff_rate2'] . '%'; ?>)</h1>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tbWinLoss">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">วันที่</th>
                                    <th scope="col">Deposit</th>
                                    <th scope="col">Withdraw</th>
                                    <th scope="col">W/L</th>
                                    <th scope="col">Bonus/Promotion</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if($income_level2['data']){ ?>
                                <?php foreach ($income_level2['data'] as $row) { ?>
                                    <tr class="text-right">
                                        <td class="text-center"><?php echo $row['date'] ?></td>
                                        <td class="text-green"><?php echo number_format($row['deposit'], 2, '.', ',') ?></td>
                                        <td class="text-red"><?php echo number_format($row['withdraw'], 2, '.', ',') ?></td>
                                        <?php
                                        if ($row['winloss'] > 0) {
                                            $text_green = 'text-green';
                                        } else {
                                            $text_green = '';
                                        }
                                        ?>
                                        <td class="<?php echo $text_green ?>"><?php echo number_format($row['winloss'], 2, '.', ',') ?></td>
                                        <td><?php echo number_format($row['promotion'], 2, '.', ',') ?></td>
                                        <td class="text-blue"><?php echo number_format($row['total'], 2, '.', ',') ?></td>
                                    </tr>
                                <?php } ?>
                                <tr class="text-right">
                                    <?php
                                    if ($income_level2['total']['winloss'] > 0) {
                                        $text_green = 'text-green';
                                    } else {
                                        $text_green = '';
                                    }
                                    ?>
                                    <td colspan="2" class="text-green"><?php echo number_format($income_level2['total']['deposit'], 2, '.', ',') ?></td>
                                    <td class="text-red"><?php echo number_format($income_level2['total']['withdraw'], 2, '.', ',') ?></td>
                                    <td class="<?php echo $text_green ?>"><?php echo number_format($income_level2['total']['winloss'], 2, '.', ',') ?></td>
                                    <td><?php echo number_format($income_level2['total']['promotion'], 2, '.', ',') ?></td>
                                    <td class="text-blue"><?php echo number_format($income_level2['total']['total'], 2, '.', ',') ?></td>
                                </tr>
                                <tr class="text-right">
                                    <td class="text-center" colspan="5">Rate <?php echo $WebSites['aff_rate2']; ?>%</td>
                                    <td class="text-blue"><?php echo number_format(($income_level2['total']['total'] * $WebSites['aff_rate2']) / 100, 2, '.', ',')  ?></td>
                                </tr>
                            <?php }else{ ?>
                            <tr class="text-right">
                                <td class="text-center" colspan="6">.....ไม่พบข้อมูล.....</td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>


                </div>
            <?php } ?>
            <div class="description-container mx-auto">
                <p>
                    สร้างรายได้ให้กับตัวท่านเองหลักแสนบาทต่อเดือน เพียงแนะนำ <?= strtoupper(get_domain()) ?> ให้เพื่อนๆ รู้จัก หรือแชร์ลงสื่อ Social ต่างๆ เมื่อมีคนกดสมัครเข้ามา คุณจะได้รับส่วนแบ่งจากยอดเดิมพันของสมาชิกที่สมัครผ่านลิงก์ของคุณ
                </p>
                <!-- <p>
                    ตัวอย่างเช่น <br> เพื่อน 1 คน มียอดเล่น 10,000 บาท ท่านจะได้ <?= (10000 * $com_l1) / 100 ?> บาท <br>
                    เพื่อน 10 คน มียอดเล่น 10,000 บาท ท่านจะได้ <?= (100000 * $com_l1) / 100 ?> บาท <br>
                    เพื่อน 100 คน มียอดเล่น 10,000 บาท ท่านจะได้ <?= number_format((1000000 * $com_l1 / 100)) ?> บาท
                </p> -->
                <p>
                    สามารถทำรายได้มากว่า 100,000 บาทต่อเดือนได้ง่ายๆเลยที่เดียว แจกจริง จ่ายเร็ว ตรวจสอบได้ทุกขั้นตอน ยิ่งแชร์มาก ยิ่งได้มาก ก๊อปลิงก์แล้วแชร์เลย !
                </p>
            </div>
        <?php } else { ?>
            <!-- turnover -->
            <?php if (isset($_SESSION['member_no'])) { ?>
                <div class="inner-container mx-auto">
                    <div>
                        <div class="link-label">
                            <div>Link แนะนำเพื่อน</div>
                        </div>
                        <div class="link-box">{{data_aff_com.aff_url}}
                            <input type="text" style="position: absolute;left: -9999999px;" id="aff_url" :value="data_aff_com.aff_url">
                        </div>
                        <button class="copy-btn" onclick="copyAffURL()">
                            <img src="../assets/images/affiliate_links/Icon-copy.svg" alt="copy link">
                            <span>Copy</span>
                        </button>
                    </div>
                    <div>
                        <div class="link-label">
                            <div>แนะนำเพื่อน</div>
                            <div>(ลำดับที่ 1)</div>
                        </div>
                        <div class="link-box">
                            {{data_aff_com.count_l1}}
                        </div>
                    </div>
                    <div>
                        <div class="link-label">
                            <div>แนะนำเพื่อน</div>
                            <div>(ลำดับที่ 2)</div>
                        </div>
                        <div class="link-box">
                            {{data_aff_com.count_l2}}
                        </div>
                    </div>
                    <div>
                        <div class="link-label">
                            <div>จำนวนเครดิตแนะนำ</div>

                        </div>
                        <div class="link-box credit-box">
                            <input type="text" id="num_credit_af" v-model="data_aff_com.total_comm" style="display: none;">
                            <span class="credit">{{data_aff_com.total_comm}}</span>
                            <span>เครดิต</span>
                        </div>
                        <button class=" transfer-btn" v-on:click="claimAFF()">โอนเข้ากระเป๋าหลัก</button>
                    </div>

                </div>
            <?php } ?>
            <div class="description-container mx-auto">
                <p>
                    ** ต้องมียอดแนะนำเพื่อนสะสม {{data_aff_com.min_aff_claim}}. ขึ้นไป กดรับได้ทุกวัน ** <br> สร้างรายได้ให้กับตัวท่านเองหลักแสนบาทต่อเดือน เพียงแนะนำ <?= strtoupper(get_domain()) ?> ให้เพื่อนๆ รู้จัก หรือแชร์ลงสื่อ Social ต่างๆ เมื่อมีคนกดสมัครเข้ามา คุณจะได้รับส่วนแบ่งจากยอดเดิมพันของสมาชิกที่สมัครผ่านลิงก์ของคุณ
                </p>
                <p>
                    ตัวอย่างเช่น <br> เพื่อน 1 คน มียอดเล่น 10,000 บาท ท่านจะได้ <?= (10000 * $com_l1) / 100 ?> บาท <br>
                    เพื่อน 10 คน มียอดเล่น 10,000 บาท ท่านจะได้ <?= (100000 * $com_l1) / 100 ?> บาท <br>
                    เพื่อน 100 คน มียอดเล่น 10,000 บาท ท่านจะได้ <?= number_format((1000000 * $com_l1 / 100)) ?> บาท
                </p>
                <p>
                    สามารถทำรายได้มากว่า 100,000 บาทต่อเดือนได้ง่ายๆเลยที่เดียว แจกจริง จ่ายเร็ว ตรวจสอบได้ทุกขั้นตอน ยิ่งแชร์มาก ยิ่งได้มาก ก๊อปลิงก์แล้วแชร์เลย !
                </p>
            </div>
        <?php } ?>

    </div>
    <div class="modal fade bd-example-modal-lg" id="Modal_Promotion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bonus / Promotion</h5>
                    <button type="button" class="close" data-dismiss="modal" onclick="closeModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h2 class="text-white">.....Loading.....</h2>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary promotion-btn w-100" onclick="closeModal()" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</section>

<input type="hidden" id="fullDomail" value="<?php echo GetFullDomain(); ?>">
<?php
$assets_footer = '<script src="' . GetFullDomain() . '/assets/js/navbar_control.js"></script>
                <script src="' . GetFullDomain() . '/assets/js/copy_link_btn_control.js"></script>';

include_once ROOT_APP . '/componentx/footer.php';
?>
<script type="text/javascript">
    $(function() {
        <?php
        if (isset($_SESSION['member_no'])) {
        ?>
            App.SetPageSection('affiliateSection');
        <?php
        }
        ?>
    });

    function closeModal() {
        $('#Modal_Promotion').modal('toggle');

        // .trigger('click')
    }

    function loadform($member_no) {
        var url = $('#fullDomail').val();
        var urlfrm = url + '/load_promotion/';
        $('#Modal_Promotion').modal('show').find('.modal-body').load(urlfrm);

    }

    function copyAffURL() {
        var copyText = document.getElementById("aff_url");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        Swal.fire({
            icon: "success",
            title: "คัดลอกแล้ว",
            showConfirmButton: false,
            timer: 1500,
        });
    }
</script>
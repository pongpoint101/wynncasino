var DataRegister = {
    issubmit: true,
    phone: "",
    password: "",
    password_confirmation: "",
    bank_account: "",
    bank_code: "",
    first_name: "",
    last_name: "",
    line_id: "",
    source: "",
    bankverify: false,
    aff_upline: "",
    choose_bank: '1',
    truewallet: '',
    truewallet_account: '',
    truewallet_is_register: 1
};

var data_global = {
    username: "loading..",
    fname: null,
    lname: null,
    min_wd: 0,
    max_withdraw: 0,
    max_withdraw_perday: 0,
    main_wallet: 0,
    withdraw_balance: 0,
    bank_name: null,
    bank_accountnumber: null,
    bank_code: null,
    aff_wallet_l1: 0,
    aff_wallet_l2: 0,
    cashback_wallet: 0,
    comm_claimed: 0,
    commission_wallet: 0,
    promo_id: 1,
    turnover: 0,
    turnover_expect: 0,
    turnover_now: 0,
    deposit: 0,
    withdraw: 0,
    min_aff_claim: 0,
    min_comm_claim: 0,
    min_accept_promo: 0,
    member_last_deposit: 0,
    ignore_zero_turnover: 0
};

var Fn_mixin = {
    methods: {
        SetPageSection: function(el) {
            var _this = this;
            //if(_this.PageSection==el){return;}
            if (el == "promoSection" || el == "promoB4LoginSection") {
                this.Changetabs(1);
            }
            switch (el) {
                case "depositSection":
                    _this.GetdataXhr(
                        "../actions/bank_account_transfer.php", {},
                        function(data) {
                            if (data == "error") {
                                swal.fire("error");
                                return;
                            }
                            _this.account_transfer = data;
                            _this.PageSection = el;
                            // _this.ShowElelement();
                        }
                    );
                    break;
                case "dwHistorySection":
                    _this.ShowWaitting("กรุณารอสักครู่", "กำลังโหลดข้อมูล");
                    _this.GetdataXhr(
                        "../actions/money_history.php", { money_type: "deposit" },
                        function(data) {
                            if (data == "error") {
                                swal.fire("error");
                                return;
                            }
                            if (typeof _this.alertwait === "object") {
                                _this.alertwait.close();
                            }
                            _this.history_money = data;
                            _this.PageSection = el;
                        }
                    );

                    break;
                case "dwCommission":
                    _this.ShowWaitting("กรุณารอสักครู่", "กำลังโหลดข้อมูล");

                    _this.GetdataXhr(
                        "../actions/commission_history.php", { com_type: "commission" },
                        function(data) {
                            if (data == "error") {
                                swal.fire("error");
                                return;
                            }
                            if (typeof _this.alertwait === "object") {
                                _this.alertwait.close();
                            }
                            _this.history_comm = data;
                            _this.PageSection = el;
                        }
                    );
                    break;
                case "affiliateSection":
                case "cashbackSection":
                    _this.loadbalanceinfo(el);
                    break;
                default:
                    _this.PageSection = el;
                    break;
            }
        },
        ShowWaitting(title, text) {
            this.alertwait = Swal.fire({
                title: title,
                text: text,
                timerProgressBar: true,
                showCancelButton: false,
                showConfirmButton: false,
                closeOnConfirm: false,
                allowOutsideClick: false,
                timer: 2000,
            });
        },
        loadbalanceinfo: function(el) {
            var _this = this;
            _this.ShowWaitting("กรุณารอสักครู่", "กำลังโหลดข้อมูล");
            _this.GetdataXhr("../actions/balanceinfo.php", {}, function(data) {
                if (data == "error") {
                    swal.fire("error");
                    return;
                }
                if (typeof _this.alertwait === "object") {
                    _this.alertwait.close();
                }
                _this.data_aff_com = data;
                if (el != "") {
                    _this.PageSection = el;
                }
            });
        },

        GetdataXhr: function(url, data, callback) {
            $.ajax({
                url: url,
                type: "get",
                data: data,
                contentType: "application/json",
                dataType: "json",
                cache: false,
                success: function(data) {
                    callback(data);
                },
                error: function() {
                    callback("error");
                },
            });
        },
        ReWardtabs: function(tab) {
            var _this = this;
            this.tab_wd = tab;
            this.boxloading = true;
            _this.GetdataXhr(
                "../actions/rank_reward.php?type=daily", {mdateselect:$('#mdateselect').val(),selected_type: tab, current_month: 1, current_year: 1 },
                function(data) {
                    if (data.error_code != 200) {
                        swal.fire("error");
                        return;
                    }
                    _this.history_money.datalist = data.datalist;
                    _this.boxloading = false;
                }
            );
        },
        Changedwtabs: function(tab) {
            var _this = this;
            this.tab_wd = tab;
            this.boxloading = true;
            _this.GetdataXhr(
                "../actions/money_history.php", { money_type: tab },
                function(data) {
                    if (data == "error") {
                        swal.fire("error");
                        return;
                    }
                    _this.history_money = data;

                    _this.boxloading = false;
                }
            );
        },
        Changecommtabs: function(tabtext) {
            var _this = this;
            this.tab_wd = tabtext;
            this.boxloading = true;
            _this.GetdataXhr(
                "../actions/commission_history.php", { com_type: tabtext },
                function(data) {
                    if (data == "error") {
                        swal.fire("error");
                        return;
                    }
                    _this.history_comm = data;
                    _this.boxloading = false;
                }
            );
        },
        getParameterByName: function(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return "";
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        },
    },
};
var App = App || {};
$(function() {
    App = new Vue({
        el: "#app",
        name: "app",
        mixins: [Fn_mixin],
        components: {},
        data: function data() {
            return {
                intervalid2: null,
                step: 1,
                switchelement: 1,
                alertwait: null,
                isbonus: false,
                boxloading: false,
                intervalid1: null,
                auth: window.auth,
                loginDetails: { username: null, password: null, issubmit: false },
                data_global: data_global,
                account_transfer: {
                    bank_transfer: [],
                    bank_member: [],
                    true_wallet: [],
                },
                history_money: [],
                history_comm: [],
                data_aff_com: {},
                data_wd: { issubmit: false, amount: 0 },
                data_af: { issubmit: false, amount: 0 },
                data_com: { issubmit: false, amount: 0 },
                tab_wd: "deposit",
                PageSection: "",
                navBottomSection: window.auth ?
                    "navBottomSection1" : "navBottomSection2",
                DataRegister: DataRegister,
            };
        },
        created: function() {},
        mounted: function() {
            this.Pullprofile();
            if (this.intervalid1 != null) {
                clearInterval(this.intervalid1);
            }
            this.intervalid1 = setInterval(() => {
                this.Pullprofile();
            }, 10 * 1000);
            this.deposit_next();
            if (this.intervalid2 != null) {
                clearInterval(this.intervalid2);
            }
            this.intervalid2 = setInterval(() => {
                this.deposit_next();
            }, 2 * 1000);
        },
        computed: {
            fullName: function() {
                return this.data_global.fname + " " + this.data_global.fname;
            },
            fullBank: function() {
                return (
                    this.data_global.bank_name +
                    " - " +
                    this.data_global.bank_accountnumber
                );
            },
        },

        methods: {
            deposit_next: function() {
                if (!(/\b(home)\b/gi.test(location.href) || /\b(deposit)\b/gi.test(location.href) ||
                        /\b(lobby)\b/gi.test(location.href))) { return false; }
                var _this = this;
                $.ajax({
                    url: "../actions/deposit_next.php?type=pull",
                    type: "get",
                    contentType: "application/json",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
                        if (!Swal.isVisible() && data.v_deposit_amount > 0 && getCookie("vnotify_deposit") == "") {
                            var money = data.v_deposit_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            Swal.fire({
                                text: "คุณมียอดฝาก " + money + " บาท",
                                icon: 'info',
                                showCancelButton: false,
                                confirmButtonColor: '#35bb40',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'ตกลง',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    _this.ShowWaitting("กรุณารอสักครู่", "กำลังลงทะเบียน");
                                    setCookie("vnotify_deposit", 1, 1 * 5 * (60 * 1000)); // 5 นาที 
                                    $.post("../actions/deposit_next.php", {}, function(response) {
                                            if (response.msg_error == 200) {
                                                Swal.fire('กรุณารอสักครู่ระบบกำลังดึงยอดฝาก')
                                            } else {
                                                var ttxt = 'ยอดฝากไม่ถูกต้องกรุณาติดต่อ พนักงาน!';
                                                if (response.msg_error == 203) {
                                                    ttxt = 'ไม่พบยอดฝากกรุณาติดต่อ พนักงาน!';
                                                }
                                                Swal.fire(ttxt);
                                            }
                                            if (typeof _this.alertwait === "object") {
                                                _this.alertwait.close();
                                            }
                                        }, "json" //html json
                                    );
                                }
                            });
                        }
                    },
                    error: function() {},
                });
            },
            coppy_alert_top: function(title, el_id) {
                var aux = document.createElement("input");
                aux.setAttribute("value", document.getElementById(el_id).innerHTML);
                document.body.appendChild(aux);
                aux.select();
                document.execCommand("copy");
                document.body.removeChild(aux);
                Swal.fire({
                    position: 'top-end',
                    width: 400,
                    icon: 'success',
                    title: title,
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            deposit_frequency: function(id) {
                var promo_id = 80 + (id * 1);
                this.get_bonus(promo_id);
            },
            get_bonus: function(id) {
                var _this = this;
                Swal.fire({
                    title: "ต้องการรับโบนัสนี้?",
                    text: "*** โปรดศึกษาเงื่อนไขโดยละเอียดหรือติดต่อ Admin ก่อนตกลงรับโบนัส ***",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ตกลง",
                    cancelButtonText: "ยกเลิก",
                }).then((result) => {
                    if (result.value) {
                        _this.isbonus = true;
                        _this.ShowWaitting("กรุณารอสักครู่", "กำลังตรวจสอบข้อมูล!");
                        $.post(
                            "../actions/promoclaim.php", { promo_id: id, is_promoclaim: 1 },
                            function(response) {
                                if (typeof _this.alertwait === "object") {
                                    _this.alertwait.close();
                                }
                                $("#xhtml").html(response);
                                _this.isbonus = false;
                            },
                            "html"
                        );
                    }
                });
            },
            InputReplaceNumber: function(vv) {
                let v = '' + vv;
                return parseInt(v.replace(/,/g, "")) || 0;
            },
            numberWithCommas: function(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            wdAction: function() {
                let _this = this;
                let swalobj = {
                    title: "ยอดเงินเครดิตคงเหลือ <br>" + _this.data_global.main_wallet + " บาท",
                    html: `<input type="tel" value='${_this.data_global.main_wallet}' id="input_wd" class="swal2-input" onkeyup="format_to_comma(this);" onchange="format_to_comma(this);" autocomplete="off" placeholder="ระบุจำนวนเงินที่ต้องการถอน">
                            <div style='color: red;font-size: small;'>
                            <h3 style='color: red!important;'>หมายเหตุ</h3>
                            <span>ถอนขั้นต่ำ ${_this.numberWithCommas(_this.data_global.min_wd)} บาท</span><br>
                            <span>ถอนเงิน ${_this.numberWithCommas(_this.data_global.max_withdraw)} บาท/ครั้ง</span><br>
                            <span>ถอนเงินได้สูงสุด ${_this.data_global.max_withdraw_perday} ครั้ง/ต่อวัน</span><br>
                            <span>*งดทำรายการถอนเงิน ตั้งแต่ เวลา 23.00-00.30 ของทุกวัน เนื่องจากจะเป็นช่วงระยะเวลาที่ธนาคารปรับปรุงอัพเดทประจำวัน</span>
                        </div>`,
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ตกลง",
                    cancelButtonText: "ยกเลิก",
                    focusConfirm: false,
                    preConfirm: () => {
                        const amount = _this.InputReplaceNumber(Swal.getPopup().querySelector('#input_wd').value);
                        const main_wallet = _this.InputReplaceNumber(_this.data_global.main_wallet);
                        if (amount <= 0) {
                            Swal.showValidationMessage(`กรุณาระบุยอดเงินที่ต้องการถอน!`);
                            Swal.getPopup().querySelector('#input_wd').focus();
                        }
                        if (main_wallet <= 0 || main_wallet < amount) {
                            Swal.showValidationMessage(`ยอดเงินเครดิตคงเหลือไม่พอที่จะถอน!`);
                            Swal.getPopup().querySelector('#input_wd').focus();
                        }
                        _this.data_wd.amount = amount;
                        return { amount: amount }
                    }
                };
                if (_this.data_global.promo_id != 0 || [16, 17, 18].indexOf(_this.data_global.member_last_deposit * 1) != -1) {
                    swalobj = {
                        title: "ท่านต้องการถอนเงิน " + _this.data_global.main_wallet + " บาท?",
                        html: `<div style='color: red;font-size: small;'>
                                <h3 style='color: red!important;'>หมายเหตุ</h3>
                                <span>ถอนขั้นต่ำ ${_this.numberWithCommas(_this.data_global.min_wd)} บาท</span><br>
                                <span>ถอนเงิน ${_this.numberWithCommas(_this.data_global.max_withdraw)} บาท/ครั้ง</span><br>
                                <span>ถอนเงินได้สูงสุด ${_this.data_global.max_withdraw_perday} ครั้ง/ต่อวัน</span><br>
                                <span>*งดทำรายการถอนเงิน ตั้งแต่ เวลา 23.00-00.30 ของทุกวัน เนื่องจากจะเป็นช่วงระยะเวลาที่ธนาคารปรับปรุงอัพเดทประจำวัน</span>
                         </div>`,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "ตกลง",
                        cancelButtonText: "ยกเลิก",
                    };
                    _this.data_wd.amount = _this.data_global.main_wallet;
                }
                Swal.fire(swalobj).then((result) => {
                    if (!result.isConfirmed) { return }
                    _this.data_wd.issubmit = true;
                    _this.ShowWaitting("กรุณารอสักครู่", "กำลังถอนเงิน!");
                    $.post("../actions/withdraw.php", _this.data_wd, function(response) {
                            _this.Pullprofile();
                            if (typeof _this.alertwait === "object") {
                                _this.alertwait.close();
                            }
                            $("#xhtml").html(response);
                            _this.data_wd.issubmit = false;
                        },
                        "html"
                    );
                });
            },
            claimCommAndReturn: function(comm_type) {
                var _this = this;
                //_this.data_wd.amount = parseInt(document.getElementById("wd_amount").value.replace(/,/g, ''));
                _this.ShowWaitting("กรุณารอสักครู่", "โอนเข้ากระเป๋าหลัก!");
                //_this.data_com_return.issubmit=true;

                //_this.data_com_return.comm_type=comm_type;

                $.post(
                    "../actions/com_and_returnloss.php", { comm_type: comm_type },
                    function(response) {
                        _this.GetdataXhr("../actions/balanceinfo.php", {}, function(data) {
                            if (data == "error") {
                                swal.fire("error");
                                return;
                            }
                            _this.loadbalanceinfo("");
                            if (typeof _this.alertwait === "object") {
                                _this.alertwait.close();
                            }
                            _this.history_comm = data;
                            $("#xhtml").html(response);
                            //_this.data_com_return.issubmit=false;
                            if (comm_type == 1) {
                                App.Changecommtabs("commission");
                            } else if (comm_type == 2) {
                                App.Changecommtabs("return_loss");
                            }
                        });
                    },
                    "html"
                );
            },

            claimAFF: function() {
                var _this = this;
                _this.data_af.issubmit = true;
                _this.data_af.amount = parseInt(
                    document.getElementById("num_credit_af").value.replace(/,/g, "")
                );
                _this.ShowWaitting("กรุณารอสักครู่", "โอนเข้ากระเป๋าหลัก!");
                $.post(
                    "../actions/affclaim.php",
                    _this.data_af,
                    function(response) {
                        _this.GetdataXhr("../actions/balanceinfo.php", {}, function(data) {
                            if (data == "error") {
                                swal.fire("error");
                                return;
                            }
                            _this.loadbalanceinfo("");
                            if (typeof _this.alertwait === "object") {
                                _this.alertwait.close();
                            }
                            _this.data_aff_com = data;
                            $("#xhtml").html(response);
                            _this.data_af.issubmit = false;
                        });
                    },
                    "html"
                );
            },
            Pullprofile: function() {
                if (!this.auth ||
                    /\b(login)\b/gi.test(location.href) ||
                    /\b(register)\b/gi.test(location.href)
                ) {
                    return;
                }
                var _this = this;
                $.ajax({
                    url: "../actions/Pullprofile.php",
                    type: "get",
                    contentType: "application/json",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
                        _this.data_global = data;
                    },
                    error: function() {
                        _this.data_global = data_global;
                    },
                });
            },
            Pullprofile_click: function() {
                if (!this.auth ||
                    /\b(login)\b/gi.test(location.href) ||
                    /\b(register)\b/gi.test(location.href)
                ) {
                    return;
                }
                var _this = this;
                _this.ShowWaitting("กรุณารอสักครู่", "โอนเข้ากระเป๋าหลัก!");
                $.ajax({
                    url: "../actions/Pullprofile.php",
                    type: "get",
                    contentType: "application/json",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
                        _this.data_global = data;
                    },
                    error: function() {
                        _this.data_global = data_global;
                    },
                });
            },
            keyLogin: function(event) {
                if (event.key == "Enter") {
                    this.$refs.btn_doLogin.click();
                }
            },
            checkForm: function(e) {
                e.preventDefault();
                this.checkLogin();
            },
            checkLogin: function() {
                var _this = this;
                if (
                    _this.loginDetails.username == null ||
                    _this.loginDetails.username.length <= 0
                ) {
                    Swal.fire("ข้อมูลไม่สมบูรณ์", "กรุณากรอก เบอร์โทรศัพท์", "error");
                    return false;
                } else if (
                    _this.loginDetails.password == null ||
                    _this.loginDetails.password.length <= 0
                ) {
                    Swal.fire("ข้อมูลไม่สมบูรณ์", "กรุณากรอก รหัสผ่าน", "error");
                    return false;
                }
                _this.loginDetails.issubmit = true;
                $.post(
                    "../actions/login.php",
                    _this.loginDetails,
                    function(response) {
                        $("#xhtml").html(response);
                        _this.loginDetails.issubmit = false;
                    },
                    "html"
                );
            },
            showalert: function(title, text, icon, callback) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    returnFocus: false,
                    allowOutsideClick: false,
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                }).then((result) => {
                    callback();
                });
            },
            check_register: function(type) {
                var _this = this;
                if (_this.step == 1) {
                    // _this.DataRegister.issubmit = true;
                    // _this.ShowWaitting("กรุณารอสักครู่", "กำลังตรวจสอบเบอร์โทรศัพท์");
                    // $.post("../actions/register.php",_this.DataRegister,
                    // function(response) {
                    //     $("#xhtml").html(response); 
                    //     _this.DataRegister.issubmit = false; 
                    //     if (typeof _this.alertwait === "object") {
                    //         _this.alertwait.close();
                    //     }
                    //  },
                    // "html"
                    // );
                    var regPattern = /^(0[1-9]{1})+([0-9]{8})+$/g;
                    if (!regPattern.test(_this.DataRegister.phone)) {
                        this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณากรอก เบอร์โทรศัพท์", "error", function() {
                            _this.$refs.phone.focus();
                        });
                        return;
                    }
                }
                if (_this.step == 2) {
                    if (_this.DataRegister.first_name == null || _this.DataRegister.first_name.length <= 0 && type == '+') {
                        if (_this.DataRegister.choose_bank == 1) {
                            this.showalert("ข้อมูลไม่สมบูรณ์", "ไม่พบข้อมูลชื่อในสมุดบัญชีธนาคาร", "error", function() {
                                _this.$refs.bank_code.focus();
                            });
                        } else {
                            this.showalert("ข้อมูลไม่สมบูรณ์", "ไม่พบข้อมูลชื่อในทรูมันนี่ วอลเล็ท", "error", function() {
                                _this.$refs.truewallet.focus();
                            });
                        }

                        return;
                    }
                    var regPattern3 = /^(0[1-9]{1})+([0-9]{8})+$/g;
                    if (!regPattern3.test(_this.DataRegister.truewallet) && type == '+' && _this.DataRegister.choose_bank == 2) {
                        this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณากรอกเบอร์ทรูมันนี่ วอลเล็ท", "error", function() {
                            _this.$refs.truewallet.focus();
                        });
                        return;
                    }
                }
                if (type == '+') { _this.step = _this.step + 1; }
                if (type == '-') { _this.step = _this.step - 1; }

            },
            register: function() {
                var _this = this;
                var regPattern4 = /^(0[1-9]{1})+([0-9]{8})+$/g;
                if (
                    _this.DataRegister.phone == null ||
                    _this.DataRegister.phone.length <= 0
                ) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณากรอก เบอร์โทรศัพท์", "error", function() {
                        _this.$refs.phone.focus();
                    });
                    return;
                } else if (
                    _this.DataRegister.bank_account == null ||
                    _this.DataRegister.bank_account.length <= 0 && this.DataRegister.choose_bank == 1
                ) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณากรอก เลขบัญชีธนาคาร (เฉพาะตัวเลข)", "error", function() {
                        _this.$refs.bank_account.focus();
                    });
                    return;
                } else if (
                    _this.DataRegister.bank_code == null ||
                    _this.DataRegister.bank_code.length <= 0 && this.DataRegister.choose_bank == 1
                ) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณาระบุธนาคาร", "error", function() {
                        _this.$refs.bank_code.focus();
                    });
                    return;
                } else if (
                    _this.DataRegister.first_name == null ||
                    _this.DataRegister.first_name.length <= 0
                ) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "ไม่พบข้อมูลชื่อในสมุดบัญชีธนาคาร", "error", function() {
                        _this.$refs.bank_code.focus();
                    });
                    return;
                } else if (
                    _this.DataRegister.last_name == null ||
                    _this.DataRegister.last_name.length <= 0
                ) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "ไม่พบข้อมูลนามสกุลในสมุดบัญชีธนาคาร", "error", function() {
                        _this.$refs.bank_code.focus();
                    });
                    return;
                } else if (
                    _this.DataRegister.line_id == null ||
                    _this.DataRegister.line_id.length <= 0
                ) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณากรอก ไลน์ไอดี", "error", function() {
                        _this.$refs.line_id.focus();
                    });
                    return;
                } else if (
                    _this.DataRegister.password == null ||
                    _this.DataRegister.password.length <= 0
                ) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณากรอก รหัสผ่าน", "error", function() {
                        _this.$refs.password.focus();
                    });
                    return;
                } else if (
                    _this.DataRegister.password_confirmation == null ||
                    _this.DataRegister.password_confirmation.length <= 0
                ) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณากรอก ยืนยื่นรหัสผ่าน", "error", function() {
                        _this.$refs.password_confirmation.focus();
                    });
                    return;
                } else if (_this.DataRegister.password != _this.DataRegister.password_confirmation) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "กรอก ยืนยื่นรหัสผ่านไม่ตรงกับรหัสผ่าน", "error", function() {
                        _this.$refs.password_confirmation.focus();
                    });
                    return;
                } else if (
                    _this.DataRegister.source == null ||
                    _this.DataRegister.source.length <= 0
                ) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณากรอก รู้จักเว็บเราจากที่ไหน?", "error", function() {
                        _this.$refs.source.focus();
                    });
                    return;
                } else if (!regPattern4.test(_this.DataRegister.truewallet) && this.DataRegister.choose_bank == 2) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณากรอก เบอร์ทรูมันนี่ วอลเล็ท", "error", function() {
                        _this.$refs.truewallet.focus();
                    });
                    return;
                }
                _this.DataRegister.issubmit = true;
                _this.ShowWaitting("กรุณารอสักครู่", "กำลังลงทะเบียน");
                $.post(
                    "../actions/register.php" + $("#queryStringHeader").val(),
                    _this.DataRegister,
                    function(response) {
                        $("#xhtml").html(response);
                        if (window.auth) {
                            setTimeout(function() {
                                window.location.href = window.location.protocol + "//" + window.location.hostname + "/home/";
                                _this.DataRegister = DataRegister;
                            }, 3000);
                        }
                        _this.DataRegister.issubmit = false;
                        _this.auth = window.auth;
                        _this.navBottomSection = _this.auth ?
                            "navBottomSection1" :
                            "navBottomSection2";
                        if (typeof _this.alertwait === "object") {
                            _this.alertwait.close();
                        }
                    },
                    "html"
                );
            },
            selectbank: function() {
                var _this = this;
                if (this.DataRegister.bank_account.length <= 5) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณากรอกเลขบัญชีธนาคาร", "error", function() {
                        _this.$refs.bank_account.focus();
                    });
                    return;
                }
                if (this.DataRegister.bank_code.length <= 0) {
                    this.showalert("ข้อมูลไม่สมบูรณ์", "กรุณาระบุธนาคาร", "error", function() {
                        _this.$refs.bank_code.focus();
                    });
                    return;
                }
                this.verifyBankAccount();
            },
            verifyBankAccount: function() {
                var timerInterval;
                var _this = this;
                Swal.fire({
                    title: "กำลังค้นหาข้อมูลบัญชี",
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    showCancelButton: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                var bank_code = _this.DataRegister.bank_code;
                var bank_acct = _this.DataRegister.bank_account;
                $.ajax({
                    type: "POST",
                    url: "../actions/VerifyBankAccount.php",
                    dataType: "json",
                    data: {
                        bank_code: bank_code,
                        bank_acct: bank_acct,
                        choose_bank: _this.DataRegister.choose_bank,
                        truewallet: _this.DataRegister.truewallet,
                        truewallet_account: _this.DataRegister.truewallet_account
                    },
                    success: function(data) {
                        var errorCode = data.code;
                        Swal.close();
                        if (errorCode == 0) {
                            _this.DataRegister.bankverify = true;
                            _this.DataRegister.first_name = data.firstName;
                            _this.DataRegister.last_name = data.lastName;
                            _this.DataRegister.issubmit = false;
                        } else {
                            _this.DataRegister.bankverify = false;
                            _this.DataRegister.first_name = "";
                            _this.DataRegister.last_name = "";
                            Swal.fire(
                                (_this.DataRegister.choose_bank == 1 ? "ไม่พบบัญชีธนาคาร" : "ไม่พบบัญชี ทรูมันนี่ วอลเล็ท"),
                                "กรุณาใส่ข้อมูลให้ถูกต้อง",
                                "error"
                            );
                        }
                    },
                    error: function(data) {
                        Swal.close();
                        Swal.fire(
                            "Server error",
                            "กรุณาลองอีกครั้ง...",
                            "error"
                        );
                    },
                });
            },
            cardGameBonus: function() {
                var _this = this;
                $.ajax({
                    type: "POST",
                    url: "../actions/bonus-cardgame.php",
                    dataType: "json",
                    data: {
                        task: "cardgame_bonus",
                    },
                    beforeSend: function() {
                        _this.ShowWaitting("กรุณารอสักครู่", "กำลังตรวจสอบข้อมูล!");
                    },
                    success: function(data) {
                        if (data.errCode != 0) {
                            var textDesc = data.errMsg;
                            Swal.fire({
                                icon: "error",
                                title: 'ไม่สามารถดำเนินการได้!',
                                text: textDesc,
                                showConfirmButton: false,
                                timer: 3000,
                            });
                            return;
                        }
                        if (data.uri_link) {
                            var hideLink = $('#xhtml_hidelink');
                            hideLink.attr("href", data.uri_link);
                            hideLink[0].click();
                            // window.open(data.uri_link, "_blank");
                        }
                    },
                    complete: function(data) {
                        if (typeof _this.alertwait === "object") {
                            _this.alertwait.close();
                        }
                    },
                });
            },
            luckywheelBonus: function() {
                var _this = this;
                $.ajax({
                    type: "POST",
                    url: "../actions/bonus-luckywheel.php",
                    dataType: "json",
                    data: {
                        task: "luckywheel_bonus",
                    },
                    beforeSend: function() {
                        _this.ShowWaitting("กรุณารอสักครู่", "กำลังตรวจสอบข้อมูล!");
                    },
                    success: function(data) {
                        if (data.errCode != 0) {
                            var textDesc = data.errMsg;
                            Swal.fire({
                                icon: "error",
                                title: 'ไม่สามารถดำเนินการได้!',
                                text: textDesc,
                                showConfirmButton: false,
                                timer: 3000,
                            });
                            return;
                        }
                        if (data.uri_link) {
                            var hideLink = $('#xhtml_hidelink');
                            hideLink.attr("href", data.uri_link);
                            hideLink[0].click();
                            // window.open(data.uri_link, "_blank");
                        }
                    },
                    complete: function(data) {
                        if (typeof _this.alertwait === "object") {
                            _this.alertwait.close();
                        }
                    },
                });
            },
            select_bank_deposit: function() {
                var regPattern2 = /^(0[1-9]{1})+([0-9]{8})+$/g;
                if (!regPattern2.test(this.DataRegister.truewallet)) {
                    this.DataRegister.truewallet = this.DataRegister.phone;
                }
                this.DataRegister.bankverify = false;
                this.DataRegister.bank_account = '';
                this.DataRegister.bank_code = '';
                this.DataRegister.first_name = '';
                this.DataRegister.last_name = '';
                if (this.DataRegister.choose_bank == 2) {
                    this.verifyBankAccount();
                }
            },
            selecttruewallet: function() {
                var _this = this;
                var regPattern5 = /^(0[1-9]{1})+([0-9]{8})+$/g;
                if (!regPattern5.test(_this.DataRegister.truewallet)) { return; }
                this.verifyBankAccount();
            }

        },
    });
});

function format_to_comma(input) {
    let num = input.value.replace(/\,/g, '');
    if (!isNaN(num)) {
        if (num > 9999999999) { num = '9999999999'; }
        if (num.indexOf('.') > -1) {
            num = num.split('.');
            num[0] = num[0].toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1,').split('').reverse().join('').replace(/^[\,]/, '');
            input.value = num[0];
        } else {
            input.value = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1,').split('').reverse().join('').replace(/^[\,]/, '')
        }
    } else {
        alert('ใส่ได้เฉพาะเลขเท่านั้น!');
        input.value = input.value.substring(0, input.value.length - 1);
    }
}
function getThaiMonth(month) {
    const thaiMonths = [
        'มกราคม', 'กุมภาพันธ์', 'มีนาคม',
        'เมษายน', 'พฤษภาคม', 'มิถุนายน',
        'กรกฎาคม', 'สิงหาคม', 'กันยายน',
        'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
    ];
    return thaiMonths[month];
}
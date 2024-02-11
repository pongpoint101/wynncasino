<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php echo $pg_header ?>
    <title><?php echo $pg_title ?></title>
    <style>
    </style>
</head>

<body class="vertical-layout vertical-menu-modern 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
    <?php echo $pg_menu ?>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">เเก้ไขรหัสผ่าน</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Home</a>
                                </li>
                                <li class="breadcrumb-item active">เเก้ไขรหัสผ่าน
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section>
                    <div class="row match-height">

                        <div class="col-xs-12 col-md-6">
                            <div class="card">

                                <div class="card-content collapse show">
                                    <div class="card-body">

                                        <form class="myway-form-member" id="myway-form-member" autocomplete="off" method="post" action>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="pg_password">Password</label>
                                                    <input type="password" id="password" class="form-control pg_password" name="password" autocomplete="nope">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <span class="form-error-text" style="color: #ff776d !important;"></span>
                                                <input type="button" class="btn btn-primary submit-member" value="บันทึก">
                                                <input type="button" class="btn btn-outline-light close-create-title" data-dismiss="modal" value="ยกเลิก">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </div>


    <?php echo $pg_footer ?>
    <script type="text/javascript">
        $(function() {
            $('.Menuchangepossword').addClass('active');
        });

        $('.submit-member').click(function(e) {
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('website/savechangepassword')?>',
                dataType: "json",
                data: {password:$('#password').val()},
                beforeSend: function() {
                    Myway_ShowLoader();
                },
                success: function(result, statusText, xhr, form) {
                    // console.log(result.Message);
                    if (result.Message == true) {
                        Myway_HideLoader();
                        model_success_pggame();
                    } else {
                        swal("มีบางอย่างผิดพลาด!", "กรุณาลองใหม่อีกครั้ง", "error");
                        Myway_HideLoader();
                    }
                }
            });

        });
    </script>
</body>

</html>
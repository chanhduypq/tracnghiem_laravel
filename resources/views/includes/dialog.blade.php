<?php 
use Illuminate\Support\Facades\Session;
?>
<div id="dialog-form" title="Đổi mật khẩu" style="display: none;">
    <form id="changePassForm">
        <div id="thongBao" style="padding-left: 100px;color: red;">  
        </div>
        <div style="float: left;text-align: right;">Nhập password cũ:</div>
        <div style="float: left;"><input id="password" type="password" name="oldPassword"/></div>
        <div style="clear: both;"></div>
        <div style="float: left;text-align: right;margin-top: 10px;">Nhập password mới:</div>
        <div style="float: left;margin-top: 10px;"><input type="password" name="newPassword" id="newPassword"/></div>
        <div style="clear: both;"></div>
        <div style="float: left;text-align: right;margin-top: 10px;">Nhập lại password mới:</div>
        <div style="float: left;margin-top: 10px;"><input type="password" name="confirmNewPassword" id="confirmNewPassword"/></div>
        <div style="clear: both;"></div>
    </form>
</div>
<div id="dialog-form-login" title="Login" style="display: none;">
    <form id="loginForm">
        <div id="thongBaologin" style="padding-left: 100px;color: red;">  
        </div>
        <div style="float: left;text-align: right;width: 30%;">email:</div>
        <div style="float: left;"><input id="username" type="text" name="username"/></div>
        <div style="clear: both;"></div>
        <div style="float: left;text-align: right;margin-top: 10px;width: 30%;">password:</div>
        <div style="float: left;margin-top: 10px;"><input type="password" name="password" id="password1"/></div>
        <div style="clear: both;"></div>  
        
    </form>
</div>
<script type="text/javascript">
    type = 0;
    jQuery(function ($) {
        $('#dialog-form-login').keypress(function(e) {
            if (e.keyCode == $.ui.keyCode.ENTER) {
              subLogin();
            }
        });
        
        $('#dialog-form').keypress(function(e) {
            if (e.keyCode == $.ui.keyCode.ENTER) {
              sub();
            }
        });
        
        function sub() {
            if (!validate())
                return;
<?php echo 'url="' . route('admin_index_ajaxchangepassword') . '";'; ?>
            jQuery.post(url, {'oldPassword': jQuery('input#password').val(), 'newPassword': jQuery('input#newPassword').val(),'_token':'<?php echo Session::token();?>' }, function (resp) {

                if (resp == '') {
                    alert('Thành công');
                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                } else if (resp == 'error') {

                    jQuery('div#thongBao').html('Nhập không đúng password cũ.');
                }

            });
        }
        function validate() {
            oldPassword = document.getElementById('password');
            newPassword = document.getElementById('newPassword');
            confirmNewPassword = document.getElementById('confirmNewPassword');
            if (oldPassword.value == null || oldPassword.value == "") {
                oldPassword.setAttribute('style', "border-color: red;");
                oldPassword.focus();
                return false;
            }
            if (newPassword.value == null || newPassword.value == "") {
                newPassword.setAttribute('style', "border-color: red;");
                newPassword.focus();
                return false;
            }
            if (newPassword.value.indexOf(" ", 0) != -1) {
                newPassword.setAttribute('style', "border-color: red;");
                newPassword.focus();
                return false;
            }
            if (confirmNewPassword.value == null || confirmNewPassword.value == "") {
                confirmNewPassword.setAttribute('style', "border-color: red;");
                confirmNewPassword.focus();
                return false;
            }
            if (newPassword.value != confirmNewPassword.value) {
                alert("Việc nhập password mới 2 lần không trùng nhau.");
                newPassword.setAttribute('style', "border-color: red;");
                confirmNewPassword.setAttribute('style', "border-color: red;");
                newPassword.focus();
                return false;
            }
            return true;
        }
        dialog = $("#dialog-form").dialog({
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "explode",
                duration: 1000
            },
            height: 400,
            width: 350,
            modal: true,
            buttons: {
                "Đổi": sub,
                "Hủy": function () {
                    dialog.dialog("close");
                }
            },
            close: function () {
                form[ 0 ].reset();
            }
        });

        form = dialog.find("form#changePassForm").on("submit", function (event) {
            event.preventDefault();
            sub();
        });

        function subLogin() {
            if (!validateLogin())
                return;
<?php echo 'url="' . route('index_login') . '";'; ?>
            jQuery.post(url, {'username': jQuery('input#username').val(), 'password': jQuery('input#password1').val(),'_token':'<?php echo Session::token();?>' }, function (resp) {

                if (resp == '') {
                    if (type == 0) {
                        window.location.reload();
                    } else if (type == 1) {
                        window.location = '<?php echo route("thi"); ?>';
                    } else if (type == 2) {
                        window.location = '<?php echo route("review"); ?>';
                    }


                } else if (resp == 'error') {

                    jQuery('div#thongBaologin').html('Thông tin đăng nhập không đúng. Vui lòng nhập lại.');
                }

            });
        }
        function validateLogin() {

            if ($.trim($("#username").val()) == '') {
                $("#username").attr('style', "border-color: red;");
                $("#username").focus();
                return false;
            }
            if ($.trim($("#username").val()).indexOf(" ", 0) != -1) {
                $("#username").attr('style', "border-color: red;");
                $("#username").focus();
                return false;
            }
            if ($.trim($("#password1").val()) == '') {
                $("#password1").attr('style', "border-color: red;");
                $("#password1").focus();
                return false;
            }

            return true;
        }
        dialogLogin = $("#dialog-form-login").dialog({
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "explode",
                duration: 1000
            },
            height: 400,
            width: 350,
            modal: true,
            buttons: {
                "Login": subLogin,
                "Hủy": function () {
                    dialogLogin.dialog("close");
                }
            },
            close: function () {
                formLogin[ 0 ].reset();
            }
        });

        formLogin = dialogLogin.find("form#loginForm").on("submit", function (event) {
            event.preventDefault();
            subLogin();
        });

        jQuery("#changePassword").click(function () {
            dialog.dialog("open");
        });
        jQuery("#a_login,li#thi,li#review").click(function () {
            if ($(this).attr('id') == 'a_login') {
                type = 0;
            } else if ($(this).attr('id') == 'thi') {
                type = 1;
            } else if ($(this).attr('id') == 'review') {
                type = 2;
            }
            dialogLogin.dialog("open");
        });
    });
</script>
<?php if (isset($_POST['register']) and isset($_POST['email']) and isset($_POST['password']) and isset($_POST['re_password'])){
    if ((int)$register_verify == 1){
        $mail_code = $_POST['mail_code'];
        if((strlen($mail_code)<4) or ($mail_code != $_SESSION['code'])){
            exit("<script>window.location.replace('./index.php?info=邮箱验证码错误！');</script>");
        }
    }
    if ($_POST['password'] != $_POST['re_password']){
        exit("<script>window.location.replace('./index.php?info=两次密码不一样！');</script>");
    }
    if ((strlen($_POST['email']) < 6) or (strlen($_POST['password']) < 6) ){
        exit("<script>window.location.replace('./index.php?info=邮箱或者密码格式错误！');</script>");
    }
    $email = $_POST['email'];
    $password = $_POST['password'];
    $select_new_user = "select * from user where email = '".$email."';";
    $ret = $db->query($select_new_user);
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
        $id = $row['id'];
    }
    if (isset($id)){
        echo "<script>window.location.replace('./index.php?info=用户已存在！');</script>";
    }else{
        $sql_add_user = "insert into user(id,email,password,is_admin,create_time) values(null ,'%s','%s',%d,'%s'); ";
        $sql_add_user = sprintf($sql_add_user,$_POST['email'],password_hash($_POST['password'], PASSWORD_DEFAULT),0,date("Y-m-d h:i:s"));
        $ret = $db->exec($sql_add_user);
        echo "<script>window.location.replace('./index.php?info=注册成功！');</script>";
    }
} ?>

<div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Sign up</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="index.php" method="post">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5" hidden>
                        <input type="text" id="orangeForm-name" class="form-control validate" name="register" value="1">
                        <label data-error="wrong" data-success="right" for="orangeForm-name">register</label>
                    </div>
                    <div class="md-form mb-5">
                        <input type="email" id="register_mail" class="form-control validate"  name="email">
                        <label data-error="wrong" data-success="right" for="register_mail">Your email</label>
                    </div>
                    <div class="md-form mb-4">
                        <input type="password" id="orangeForm-pass" class="form-control validate" maxlength="15"  name="password">
                        <label data-error="wrong" data-success="right" for="orangeForm-pass">Your password</label>
                    </div>
                    <div class="md-form mb-4">
                        <input type="password" id="orangeForm-repass" class="form-control validate" maxlength="15" name="re_password">
                        <label data-error="wrong" data-success="right" for="orangeForm-repass">Repeat password</label>
                    </div>
                    <div class="md-form mb-5" style="display: flex">
                        <input type="text" id="register_code" class="form-control validate" maxlength="4" name="code" style="margin: 0">
                        <img src="image.php" alt="code" id="image_register">
                    </div>
                    <script type="text/javascript">
                        $(function(){
                            $('#image_register').click(function(){
                                var t = Date.parse(new Date());
                                $('#image_register').prop({src: "image.php?t="+t,});
                                $('#image_reset').prop({src: "image.php?t="+t,});
                                try {
                                   $('#image_login').prop({src: "image.php?t="+t,});
                                }
                                catch(err){
                                     console.log(err);
                                }
                            })
                        });
                    </script>
                    <?php if ($register_verify==1){?>
                    <div class="md-form mb-5" style="display: flex">
                        <input type="text" id="code" class="form-control validate" name="mail_code" style="margin: 0">
                        <button type="button" class="btn btn-email" id="send_email"><i class="fas fa-envelope pr-1"></i></button>
                    </div>
                    <?php } ?>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-deep-orange " type="submit">Sign up</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#send_email').click(function(event){
            event.preventDefault();
            var s = $('#register_mail').prop('value');
            var p = $('#register_code').prop('value');
            if(s.length<5){
                mdui.alert("请输入正确的邮箱地址！");
                return
            }
            if(p.length<4){
                mdui.alert("请输入正确的图片验证码！");
                return
            }
            $('#send_email').prop({'disabled':true});
            var tmp = 60;
            var s = setInterval(function(){
                if (tmp > 0) {
                    tmp-=1;
                    $('#send_email').html(tmp+'s后重发');
                    $('#send_email').prop({'disabled':true});
                    console.log(tmp);
                }else{
                    clearInterval(s);
                    $('#send_email').html('<i class="fas fa-envelope pr-1"></i>');
                    $('#send_email').prop({'disabled':false});
                }
            },1000);
            $.ajax({
                url: 'api/api.php',
                type: 'POST',
                dataType: 'json',
                data:{'sendmail':1,'mail':$("#register_mail").prop('value'),'img_code':$("#register_code").prop('value')}
            })
                .done(function(data) {
                    if(data.code==200){
                        mdui.alert(data.resp);
                    }else{
                        clearInterval(s);
                        $('#send_email').html('<i class="fas fa-envelope pr-1"></i>');
                        $('#send_email').prop({'disabled':false});
                        mdui.alert(data.resp);
                    }
                })
                .fail(function() {
                    clearInterval(s);
                    $('#send_email').html('<i class="fas fa-envelope pr-1"></i>');
                    $('#send_email').prop({'disabled':false});
                    mdui.alert('服务器超时，请换一个邮箱然后重试！');
                });
        })
    });
</script>
<div class="text-center float-left">
    <a href="" class="btn btn-default btn-rounded mb-4 purple-gradient" data-toggle="modal" data-target="#modalRegisterForm">注册</a>
</div>

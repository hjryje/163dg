<?php
include "./tools/post_func.php";
if (isset($_POST['login']) and isset($_POST['email']) and isset($_POST['password'])){
    $is_bot=false;
    if ($login_verify==1){
        if (strlen($_POST['g-recaptcha-response'])<10){
            echo "<script>window.location.replace('./index.php?info=人机给爷爬！');</script>";
            exit;
        }
        $post_data = array(
            'secret' => $secret,
            'response' => $_POST["g-recaptcha-response"]
        );
        $recaptcha_json_result = send_post('https://www.recaptcha.net/recaptcha/api/siteverify', $post_data)[0];
        $recaptcha_result = $recaptcha_json_result;
        $is_bot = $recaptcha_result['success'];
        $msg = "人机给爷爬！";
    }
    if ($login_verify==2){
        if (isset($_POST['code'])){
            if ($_POST['code'] == $_SESSION['img_code']){
                $is_bot = true;
            }else{
                $msg = "图片验证码错误！";
            }
        }else{
            $msg = "请输入图片验证码";
        }
    }
    if ($login_verify==0){
        $is_bot = true;
    }
    if ($is_bot==true){
        $select_user = "select * from user where email = '%s';";
        $select_user = sprintf($select_user,$_POST['email']);
        $ret = $db->query($select_user);
        while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
            $password = $row['password'];
            $id = $row['id'];
            $is_admin = $row['is_admin'];
        }
        if (isset($password)){
            if (password_verify($_POST['password'], $password)){
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['id'] = $id;
                echo "<script>window.location.replace('./index.php?info=登录成功！');</script>";
            }else{
                echo "<script>window.location.replace('./index.php?info=密码错误！');</script>";
            }
        }else{
            echo "<script>window.location.replace('./index.php?info=用户不存在！');</script>";
        }
    }else{
        echo "<script>window.location.replace('./index.php?info='.$msg);</script>";
        exit;
    }
}
?>
<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Sign in</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mx-md-5" action="index.php" method="post">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5" hidden>
                        <input type="text" id="hidden" class="form-control validate" name="login" value="1">
                        <label data-error="wrong" data-success="right" for="hidden">hidden</label>
                    </div>
                    <div class="md-form mb-5">
                        <input type="email" id="defaultForm-email" class="form-control validate" name="email">
                        <label data-error="wrong" data-success="right" for="defaultForm-email">Your email</label>
                    </div>
                    <div class="md-form mb-4">
                        <input type="password" id="defaultForm-pass" class="form-control validate" name="password">
                        <label data-error="wrong" data-success="right" for="defaultForm-pass">Your password</label>
                    </div>
                    <?php if ($login_verify==1){?>
                    <div class="g-recaptcha" data-sitekey="<?php echo $sitekey;?>"></div>
                    <?php }elseif($login_verify==2){?>
                    <div class="md-form mb-5" style="display: flex">
                        <input type="text" id="login_code" class="form-control validate" maxlength="4" name="code" style="margin: 0">
                        <img src="image.php" alt="code" id="image_login">
                    </div>
                    <script type="text/javascript">
                        $(function(){
                            $('#image_login').click(function(){
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
                    <?php }?>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-default" type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://www.recaptcha.net/recaptcha/api.js" async defer></script>
<div class="text-center float-left">
    <a href="" class="btn btn-default btn-rounded mb-4 peach-gradient" data-toggle="modal" data-target="#modalLoginForm">登录</a>
</div>

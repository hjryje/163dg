
<?php
if (isset($_POST['reset_form']) and isset($_POST['repassword']) and isset($_POST['password'])){
    if ($_POST['repassword'] != $_POST['password']){
        exit("<script>window.location.replace('./index.php?reset_html=1&email={$_POST['email']}&code={$_POST['code']}&info=两次密码不一致！');</script>");
    }else{
        $sql_update_user = sprintf("update user set password ='%s' where email = '%s';", password_hash($_POST['password'],PASSWORD_DEFAULT) , $_POST['email']);
        $ret = $db->exec($sql_update_user);
        $delete_reset_code = "delete from reset_code where email = '".$_POST['email']."';";
        $ret = $db->exec($delete_reset_code);
        exit("<script>window.location.replace('./index.php?info=修改密码成功！');</script>");
    }
}
if (isset($_GET['reset_html']) and isset($_GET['email']) and isset($_GET['code'])){
    $select_reset_sql="select * from reset_code where email = '".$_GET['email']."' and code = '".$_GET['code']."';";
    $ret = $db->query($select_reset_sql);
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
        $select_reset_id = $row['id'];
    }
    if (isset($select_reset_id)){
        echo "";
    }else{
        exit("<script>window.location.replace('./index.php?info=链接已经过期！');</script>");
    }
}else{
    exit("<script>window.location.replace('./index.php');</script>");
}
?>
<div class="container">
    <div class="card w-100 mb-3">
        <div class="card-body">
            <h5 class="card-title">重置密码</h5>
            <form class="mx-md-5" action="index.php?reset_html=1&email=<?php echo $_GET['email']?>$code=<?php echo $_GET['code']?>" method="post">
                <div class="input-group mb-3" hidden>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">密码</span>
                    </div>
                    <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="reset_form" value="1">
                </div>
                <div class="input-group mb-3" hidden>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">密码</span>
                    </div>
                    <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="email" value="<?php echo $_GET['email']?>">
                </div>
                <div class="input-group mb-3" hidden>
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">密码</span>
                    </div>
                    <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="code" value="<?php echo $_GET['code']?>">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">密码</span>
                    </div>
                    <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="password">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">确认密码</span>
                    </div>
                    <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="repassword">
                </div>
                <button class="btn aqua-gradient" type="submit">修改</button>
            </form>
        </div>
    </div>
</div>

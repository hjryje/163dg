<?php
session_start();
$is_install = 0;
$is_login = 0;
if (file_exists("database/wangyi.db")){
    $is_install = 1;
    class MyDB extends SQLite3
    {
        function __construct()
        {
            $this->open('database/wangyi.db');
        }
    }
    $db = new MyDB();
    $select_web_name_sql = "select * from config where id = 0;";
    $ret = $db->query($select_web_name_sql);
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
        $web_title = $row['web_title'];
        $web_name = $row['web_name'];
        $api = $row['api'];
        $web_url = $row['web_url'];
        $notice = $row['notice'];
        $register_verify= $row['register_verify'];
        $login_verify = $row['login_verify'];
        $sitekey = $row['sitekey'];
        $secret = $row['secret'];
        $send_info = $row['send_info'];
        $smtp_host = $row['Host'];
        $smtp_username = $row['Username'];
        $smtp_password = $row['Password'];
        $smtp_port = $row['Port'];
        $smtp_sender = $row['setFrom'];
    }
    //是否登录
    if (isset($_SESSION['id'])) {
        $is_login = 1;
        $select_user = "select * from user where id = %d;";
        $select_user = sprintf($select_user,(int)$_SESSION['id']);
        $ret = $db->query($select_user);
        while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
            $email = $row['email'];
            $is_admin = $row['is_admin'];
            $accept_email = $row['accept_email'];
            $create_time = $row['create_time'];
            $activation_time =  $row['activation_time'];
            $wangyi_num = $row['wangyi_num'];
            $wangyi_password = $row['wangyi_password'];
            $wangyi_style = $row['wangyi_style'];
            $days = $row['days'];
        }
        if (isset($_GET['logout'])){
            $is_login = 0;
            session_unset();//free all session variable
            session_destroy();//销毁一个会话中的全部数据
            setcookie(session_name(),'',time()-3600);//销毁与客户端的卡号
            echo "<script>window.location.replace('./index.php?info=注销登录成功!');</script>";
        }

    }else{
        $is_login = 0;
    }
}else{
    $web_name = "网易云打卡平台";
    $web_title = "网易云打卡平台";
}

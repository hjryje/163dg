<?php
error_reporting(0);
session_start();
header('Content-Type:application/json; charset=utf-8');
include "../tools/sendmail.php";
include "../tools/post_func.php";
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('../database/wangyi.db');
    }
}
$db = new MyDB();
if (isset($_POST['make_kami'])){
    for ($x=0; $x<(int)$_POST['num']; $x++) {
        $sql_make_kami = "insert into kami values(null ,'%s',%d,null ,1);";
        $sql_make_kami = sprintf($sql_make_kami,md5(md5(time().mt_rand(0,1000))),(int)$_POST['day']);
        $db->exec($sql_make_kami);
        usleep(100);
    }
    echo json_encode(array('code'=>'200','resp'=>'生成卡密成功！'));
}
if($_GET['ex_day']){
    if(isset($_SESSION['id']) and $_SESSION['id']==0){
        $sql_get_valid_kami = "select * from kami where is_valid=1 and day=".$_GET['ex_day'].";";
        $ret = $db->query($sql_get_valid_kami);
        $data = array();
        $con="";
        while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
            $con=$con.$row['kami']."\n";
        }
        echo json_encode(array('code'=>'200','resp'=>$con));
    }else{
        echo json_encode(array('code'=>'201','resp'=>'爬！','s'=>$_SESSION['id']));
    }
}
if (isset($_POST['del'])){
    $id = $_POST['id'];
    $sql_select_user = "select * from user where id = ".$id.";";
    $ret = $db->query($sql_select_user);
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){$is_admin = $row['is_admin'];}
    if ($is_admin==0){
        $sql_delete_user = "delete from user where id = ".$id.";";
        $sql_delete_kami = "delete from kami where user_id = ".$id.";";
        $db->exec($sql_delete_kami);
        $db->exec($sql_delete_user);
        echo json_encode(array('resp'=>'200','data'=>"删除成功"));
    }else{
        echo json_encode(array('resp'=>'200','data'=>"该用户是网站管理"));
    }
}
if (isset($_POST['del_kami'])){
    $id = $_POST['id'];
    $sql_select_user = "select * from kami where id = ".$id.";";
    $ret = $db->query($sql_select_user);
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){$kami = $row['kami'];}
    if (isset($kami)){
        $sql_delete_kami = "delete from kami where id = ".$id.";";
        $db->exec($sql_delete_kami);
        echo json_encode(array('resp'=>'200','data'=>"删除成功"));
    }else{
        echo json_encode(array('resp'=>'200','data'=>"卡密并不存在"));
    }
}
if (isset($_POST['wangyi_num']) and isset($_POST['wangyi_password']) and isset($_POST['style'])) {
    $sql_select_api = "select * from config where id =0;";
    $ret = $db->query($sql_select_api);
    $password = md5($_POST['wangyi_password']);
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $api = $row['api'];
    }
    $url_list = explode("\n",$api);
    foreach ($url_list as $key => $value){
        $url_str = str_replace(array("\r\n", "\r", "\n"), "", $value);
        $data = ['do'=>'check'];
        $http_status = send_post($url_str,$data)[1];
        if ($http_status[0]=='HTTP/1.1 200 OK'){
            $url = $url_str;
            break;
        }
    }
    if ((int)$_POST['style']==0) {
        $data = ['do'=>'email','uin'=>$_POST['wangyi_num'],'pwd'=>$password,'r'=>randFloat()];
        $login_s = send_post($url,$data)[0];
    } else {
        $data = ['do'=>'login','uin'=>$_POST['wangyi_num'],'countrycode'=>substr($_POST['style'],1,100),'pwd'=>$password,'r'=>randFloat()];
        $login_s = send_post($url,$data)[0];
    }
    if (isset($login_s)) {
        if ($login_s['code'] == '200') {
            $update_user_insert_wangyi = "update user set wangyi_num = '%s',wangyi_password = '%s' , wangyi_style='%s' where id = %d;";
            $update_user_insert_wangyi = sprintf($update_user_insert_wangyi, $_POST['wangyi_num'], $password, $_POST['style'] ,(int)$_POST['id']);
            $db->exec($update_user_insert_wangyi);
            echo json_encode(array('resp'=>'200','url_list'=>$url_list,'api'=>$url,'data'=>"网易云账号保存成功！",'ex'=>$login_s));
        }elseif ($login_s['code'] == '501'){
            echo json_encode(array('resp'=>'501','api'=>$url,'j'=>$login_s,'data'=>"163邮箱还是手机号？",'p'=>$data));
        } else {
            echo json_encode(array('resp'=>'201','api'=>$url,'j'=>$login_s,'data'=>$login_s['message']));
        };
    } else {
        echo json_encode(array('resp'=>'200','data'=>"网络错误！请检查网站设置的网易云打卡接口！"));
    }
}
if (isset($_POST['bind_kami'])){
    $kami = $_POST['bind_kami'];
    if (isset($_POST['accept_email'])){
        $accept_email = 1;
    }else{
        $accept_email = 0;
    }
    $select_kami = "select * from kami where kami = '".$kami."';";
    $ret = $db->query($select_kami);
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
        $is_valid = $row['is_valid'];
        $day = $row['day'];
    }
    $select_user_is_active = "select * from user where id = ".$_POST['id'].";";
    $ret = $db->query($select_user_is_active);
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
        $activation_time = $row['activation_time'];
        $days = $row['days'];
    }
    if (isset($is_valid)){
        if ($is_valid ==0){
            echo json_encode(array('resp'=>'200','data'=>"卡密已被使用!"));
        }else{
            if ($days==0){
                $activation_time=date("Y-m-d h:i:s");
            }
            $d = (int)$day+(int)$days;
            $update_kami = "update kami set user_id = %d , is_valid = 0 where kami  = '%s';";
            $update_kami = sprintf($update_kami,(int)$_POST['id'],$kami);
            $update_user_accept_email = "update user set days = ".$d.", is_active=1, "." accept_email = ".$accept_email.",activation_time = '".$activation_time."' where id = ".$_POST['id'].";";
            $db->exec($update_user_accept_email);
            $db->exec($update_kami);
            echo json_encode(array('resp'=>'200','data'=>"卡密激活成功！"));
        }
    }else{
        echo json_encode(array('resp'=>'200','data'=>"卡密不正确！"));
    }
}
if (isset($_POST['d'])){
    $d = $_POST['d'];
    if ($d=="t"){
        $accept_email = 1;
        $data = "您已经开启打卡通知";
    }else{
        $accept_email = 0;
        $data = "您已经关闭打卡通知";
    }
    $update_user_accept_email = "update user set accept_email = ".$accept_email."  where id = ".$_POST['id'].";";
    $db->exec($update_user_accept_email);
    echo json_encode(array('resp'=>'200','data'=>$data));
}
if (isset($_POST['sendmail'])){
    $mailaddr = $_POST['mail'];
    $p = $_POST['img_code'];
    $sql_select_api = "select * from config where id =0;";
    $ret = $db->query($sql_select_api);
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
        $host = $row['Host'];
        $username = $row['Username'];
        $password = $row['Password'];
        $port = $row['Port'];
        $setfrom = $row['setFrom'];
        $web_url = $row['web_url'];
        $web_name = $row['web_name'];
    }
    if($_SESSION['img_code']!=$p){
        $arr = array('code'=>201,'resp'=>"图片验证码错误",'code'=>$_SESSION['img_code']);
        exit(json_encode($arr));
    }
    if ((!$_SESSION['issend']) || ((time()-$_SESSION['issend'])>60)) {
        # code...
        if (isset($_POST['reset'])){
            try {
                //Server settings
                $select_user = "select * from user where email = '{$mailaddr}';";
                $ret = $db->query($select_user);
                while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
                    $id = $row['id'];
                }
                if (isset($id)) {
                    $t = date("Y-m-d h:i:s");
                    $reset_code = (int)time();
                    $insert_reset = "insert into reset_code values(null,'".$mailaddr."','".$reset_code."','".$t."');";
                    $db->exec($insert_reset);
                    $subject = $web_name."重置密码";
                    $body = "重置密码链接(一天内有效) : ".$web_url."?reset_html=1&email=".$mailaddr."&code=".$reset_code;
                    ob_start();
                    $cache = send_email($host,$username,$password,$port,$setfrom,$mailaddr,$subject,$body);
                    ob_get_clean();
                    ob_end_flush();
                    $_SESSION['issend'] = time();
                    $arr = array('code'=>200,'resp'=>"发送成功！");
                    exit(json_encode($arr));
                }else{
                    $arr = array('code'=>200,'resp'=>"用户不存在！");
                    exit(json_encode($arr));
                }
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$e->getMessage()}";
            }
        }else{
            try {
                $code = "";
                for ($i=0; $i < 6 ; $i++) {
                    # code...
                    $code.=chr(rand(97,122));
                }
                $_SESSION['code'] = $code;
                $subject = "验证码";
                $body = "验证码 : ".$code;
                ob_start();
                $cache = send_email($host,$username,$password,$port,$setfrom,$mailaddr,$subject,$body);
                ob_get_clean();
                ob_end_flush();
                $_SESSION['issend'] = time();
                $arr = array('code'=>200,'resp'=>"发送成功！");
                exit(json_encode($arr));
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$e->getMessage()}";
            }
        }
    }else{
        echo json_encode(array('code'=>200,'resp'=>"频繁！"));
    }
}
if(isset($_GET['info'])){
    $select_web_name_sql = "select * from config where id = 0;";
    $ret = $db->query($select_web_name_sql);
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
        $api = $row['api'];
    }
    if (strlen($_GET['wangyi_num'])<5){
        exit(json_encode(array('code'=>'500','resp'=>'未绑定网易账号！')));
    }
    $select_user = "select * from user where wangyi_num = '{$_GET['wangyi_num']}';";
    $ret = $db->query($select_user);
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
        $wangyi_password = $row['wangyi_password'];
        $wangyi_style = $row['wangyi_style'];
    }
    $url_list = explode("\n",$api);
    foreach ($url_list as $key => $value){
        $url_str = str_replace(array("\r\n", "\r", "\n"), "", $value);
        $data = ['do'=>'check'];
        $http_status = send_post($url_str,$data)[1];
        if ($http_status[0]=='HTTP/1.1 200 OK'){
            $url = $url_str;
            break;
        }
    }
    $wangyi_num=$_GET['wangyi_num'];
    if ($url != null and strlen($wangyi_num)>5){
        if ((int)$wangyi_style==0) {
            $data = ['do'=>'email','uin'=>$wangyi_num,'pwd'=>$wangyi_password,'r'=>randFloat()];
            $login_s = send_post($url,$data,$timeout=10)[0];
        } else {
            $data = ['do'=>'login','uin'=>$wangyi_num,'countrycode'=>substr($wangyi_style,1,100),'pwd'=>$wangyi_password,'r'=>randFloat()];
            $login_s = send_post($url,$data,$timeout=10)[0];
        }
        if ($login_s['code']==200){
            exit(json_encode(array('code'=>'200','resp'=>'网易云登陆成功！','data'=>$login_s,'style'=>$wangyi_style)));
        }else{
            exit(json_encode(array('code'=>'500','api'=>$url,'data'=>$data,'resp'=>'网易云登陆失败！ 代码：'.$login_s['code'].'消息：'.$login_s['msg'])));
        }
    }elseif($url ==null){
        exit(json_encode(array('code'=>'500','resp'=>'API错误！请网站管理尽快修复！')));
    }
}
$db->close();

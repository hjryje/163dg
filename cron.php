<!--核心文件请勿擅自改动-->
<?php
include "tools/post_func.php";
include 'smtp/src/Exception.php';
include 'smtp/src/PHPMailer.php';
include 'smtp/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function send_email($host,$username,$password,$port,$setfrom,$mailaddr,$subject,$body){
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 1;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = $host;                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $username;                     // SMTP username
    $mail->Password   = $password;                               // SMTP password
    $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = $port;
    $mail->setFrom($setfrom, $setfrom);
    $mail->addAddress($mailaddr);     // Add a recipient
    $mail->Charset='UTF-8';
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->send();
    return $body;
}
$limit_user_num = 5;
$grade = [10,40,70,130,200,400,1000,3000,8000,20000];
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('./database/wangyi.db');
    }
}
$db = new MyDB();
$select_reset_code = "select * from reset_code";
$ret = $db->query($select_reset_code);
$reset_array = array();
while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    $reset_id = $row['id'];
    $reset_time = $row['create_time'];
    array_push($reset_array,['id'=>$reset_id,'time'=>$reset_time]);
}
if (count($reset_array)>0){
    foreach ($reset_array as $k => $v){
        $l = date("Y-m-d h:i:s",strtotime("{$v['time']} +1 day"));
        if (strtotime(date("Y-m-d h:i:s"))>strtotime($l)){
            $d = "delete from reset_code where id = ".$v['id']." ;";
            $ret = $db->exec($d);
        }
    }
}
$sql_select_api = "select * from config where id =0;";
$ret = $db->query($sql_select_api);
while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    $api = $row['api'];
    $web_title = $row['web_title'];
    $web_name = $row['web_name'];
    $web_url = $row['web_url'];
    $send_info = $row['send_info'];
    $smtp_host = $row['Host'];
    $smtp_username = $row['Username'];
    $smtp_password = $row['Password'];
    $smtp_port = $row['Port'];
    $smtp_sender = $row['setFrom'];
}
$sql_select_admin = "select * from user where is_admin =1;";
$ret = $db->query($sql_select_admin);
while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    $admin_email = $row['email'];
}
// 校验API
echo date("Y-m-d h:i:s")."-打卡脚本启动...\n".date("Y-m-d h:i:s")."-开始校验API...\n";
$url_list = explode("\n",$api);
if (strlen($api)<1){
    exit(date("Y-m-d h:i:s")."-未配置API,打卡终止！\n");
}
$url_ok_list = array();
foreach ($url_list as $key => $value){
    $url_str = str_replace(array("\r\n", "\r", "\n"), "", $value);
    $data = ['do'=>'check','r'=>"".randFloat()];
    $http_status = send_post($url_str,$data);
    if ($http_status[1][0]=='HTTP/1.1 200 OK'){
        array_push($url_ok_list,$url_str);
        echo(date("Y-m-d h:i:s")."-API可用-".$url_str."\n");
    }else{
        echo(date("Y-m-d h:i:s")."-API不可用-".$url_str."\n");
    }
    sleep(5);
}
if (count($url_ok_list)<1){
    try {
        ob_start();
        $subject=$web_name."定时任务通知！";
        $body = "API全部凉凉，请尽快修复！\n 地址：".$web_url;
        $cache = send_email($smtp_host,$smtp_username,$smtp_password,$smtp_port,$smtp_sender,$admin_email,$subject,$body);
        ob_get_clean();
        ob_end_flush();
    }catch (Exception $e) {
        echo date("Y-m-d h:i:s").$e->getMessage();
        echo("\n");
        echo(date("Y-m-d h:i:s")."-未配置邮箱，发送通知信息失败！\n");
        // die(); // 终止异常
    }
    exit(date("Y-m-d h:i:s")."-无可用API 打卡终止\n");
}else{
    echo(date("Y-m-d h:i:s")."-开始打卡！\n");
    $users_array = array();
    $select_active_users = "select * from user where is_active = 1";
    $ret = $db->query($select_active_users);
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
        $id = $row['id'];
        $wangyi_num = $row['wangyi_num'];
        $wangyi_password = $row['wangyi_password'];
        $activation_time = $row['activation_time'];
        $email = $row['email'];
        $accept_email = $row['accept_email'];
        $wangyi_style = $row['wangyi_style'];
        $days = $row['days'];
        $user = ['wangyi_style'=>$wangyi_style,'days'=>$days,'accept_email'=>$accept_email,'email'=>$email,'id'=>$id,'wangyi_num'=>$wangyi_num,'wangyi_password'=>$wangyi_password,'activation_time'=>$activation_time];
        array_push($users_array,$user);
    }
    $need_daka_user_array = array();
    // var_dump($users_array);
    foreach ($users_array as $key => $user){
        $select_info = "select * from info where user_id = ".$user['id']." and is_sign=1 and is_daka=1 and strftime('%m-%d','now','localtime') = strftime('%m-%d',create_time);";
        $ret = $db->query($select_info);
        $i=0;
        while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
            $info_user_id = $row['id'];
            $is_sign =  $row['is_sign'];
            $is_daka =  $row['is_daka'];
            $remark = $row['remark'];
            $i=$i+1;
        }
        if ($i==0){
            array_push($need_daka_user_array,$user);
        }
        unset($info_user_id,$is_sign,$is_daka,$remark,$i);
    }
    // var_dump($need_daka_user_array);
    // exit();
    if (count($need_daka_user_array)<1){
        exit(date("Y-m-d h:i:s")."-暂无需要打卡用户 打卡终止\n");
    }else{
        if (count($need_daka_user_array)>$limit_user_num){
            $limit_users_array = array();
            $tmp = array_rand($need_daka_user_array, $limit_user_num);
            foreach ($tmp as $k => $v){
                array_push($limit_users_array,$need_daka_user_array[$v]);
            }
        }else{
            $limit_users_array = $need_daka_user_array;
        }
        // 当前要打卡的用户
        var_dump($limit_users_array);
        foreach ($limit_users_array as $key => $value) {
            $now_mail = $value['email'];
            $now_wangyi_num = $value['wangyi_num'];
            $now_user_id = (int)$value['id'];
            $now_wangyi_style = $value['wangyi_style'];
            $now_wangyi_password = $value['wangyi_password'];
            $accept_email = $value['accept_email'];
            $activation_time = $value['activation_time'];
            $days = $value['days'];
            $last_date = date("Y-m-d h:i:s",strtotime("{$activation_time} +{$days} day"));
            if (strtotime(date("Y-m-d h:i:s"))>strtotime($last_date)){
                // 卡密过期
                $update_user ="update user set is_active =0,accept_email=0,activation_time='%s',days=0 where id=%d;";
                $update_user=sprintf($update_user,date("Y-m-d h:i:s"),$now_user_id);
                $db->exec($update_user);
                $insert_login_error_info = "insert into info values(null ,%d,0,0,'%s','%s');";
                $insert_login_error_info = sprintf($insert_login_error_info,$now_user_id,"卡密过期",date("Y-m-d h:i:s"));
                $db->exec($insert_login_error_info);
                echo(date("Y-m-d h:i:s")."-{$now_mail}卡密过期！发送邮箱通知...\n");
                try {
                    ob_start();
                    $subject=$web_name."打卡通知！";
                    $body = "您的卡密已到期！\n 地址：".$web_url;
                    $cache = send_email($smtp_host,$smtp_username,$smtp_password,$smtp_port,$smtp_sender,$now_mail,$subject,$body);
                    ob_get_clean();
                    ob_end_flush();
                }catch (Exception $e) {
                    echo date("Y-m-d h:i:s").$e->getMessage();
                    echo("\n");
                    echo(date("Y-m-d h:i:s")."-未配置邮箱，发送通知信息失败！\n");
                    // die(); // 终止异常
                }
            }else{
                // 正常打卡
                start_daka:
                echo(date("Y-m-d h:i:s")."-{$now_mail}开始打卡-网易云账号：{$now_wangyi_num}\n");
                $url = $url_ok_list[array_rand($url_ok_list,1)];
                echo(date("Y-m-d h:i:s")."-获取可用API：{$url}\n");
                if ((int)$now_wangyi_style==0) {
                    $data = ['do'=>'email','uin'=>$now_wangyi_num,'pwd'=>$now_wangyi_password,'r'=>"".randFloat()];
                    $login_s = send_post($url,$data);
                } else {
                    $data = ['do'=>'login','uin'=>$now_wangyi_num,'countrycode'=>substr($now_wangyi_style,1,100),'pwd'=>$now_wangyi_password,'r'=>"".randFloat()];
                    $login_s = send_post($url,$data);
                }
                sleep(1);
                if ($login_s[0]['code']=="200"){
                    echo(date("Y-m-d h:i:s")."-{$now_wangyi_num}登陆成功，正在获取cookies...\n");
                    foreach ($login_s[1] as $login_k => $login_v){
                        if(strpos($login_v,"MUSIC_U")){
                            $cookie = $login_v;
                        }
                    }
                    if (!isset($cookie)) {
                        echo(date("Y-m-d h:i:s")."-{$now_wangyi_num}获取cookies失败，重新登陆...\n");
                        try {
                            ob_start();
                            $subject=$web_name."定时任务通知！";
                            $body = "API：".$url."疑似被网易BAN，请尽快修复！\n 地址：".$web_url;
                            $cache = send_email($smtp_host,$smtp_username,$smtp_password,$smtp_port,$smtp_sender,$admin_email,$subject,$body);
                            ob_get_clean();
                            ob_end_flush();
                        }catch (Exception $e) {
                            echo date("Y-m-d h:i:s").$e->getMessage();
                            echo("\n");
                            echo(date("Y-m-d h:i:s")."-未配置邮箱，发送通知信息失败！\n");
                            // die(); // 终止异常
                        }
                        echo(date("Y-m-d h:i:s")."-API疑似被网易BAN:".$url."！\n");
                        $url_ok_list = array_diff($url_ok_list, [$url,]);
                        if (count($url_ok_list)<1){
                            exit(date("Y-m-d h:i:s")."-无可用API打卡终止\n");
                        }
                        goto start_daka;
                    }
                    preg_match("/set\-cookie:([^\r\n]*)/i", $cookie, $matches);
                    unset($cookie);
                    $c = explode('=', $matches[1]);
                    $cookies =  $c[0].'='.$c[1];
                    $data = ['do'=>'sign','r'=>randFloat()];
                    $sign_s = send_post($url,$data,$cookies,$timeout=120);
                    if ($sign_s[0]['code']==200){
                        $remark = "签到成功";
                    }elseif ($sign_s[0]['code']==301){
                        try {
                            ob_start();
                            $subject=$web_name."定时任务通知！";
                            $body = "API：".$url."疑似被网易BAN，请尽快修复！\n 地址：".$web_url;
                            $cache = send_email($smtp_host,$smtp_username,$smtp_password,$smtp_port,$smtp_sender,$admin_email,$subject,$body);
                            ob_get_clean();
                            ob_end_flush();
                        }catch (Exception $e) {
                            echo date("Y-m-d h:i:s").$e->getMessage();
                            echo("\n");
                            echo(date("Y-m-d h:i:s")."-未配置邮箱，发送通知信息失败！\n");
                            // die(); // 终止异常
                        }
                        echo(date("Y-m-d h:i:s")."-API疑似被网易BAN:".$url."！\n");
                        $url_ok_list = array_diff($url_ok_list, [$url,]);
                        if (count($url_ok_list)<1){
                            exit(date("Y-m-d h:i:s")."-无可用API打卡终止\n");
                        }
                        goto start_daka;
                    }else{
                        $remark = "重复签到";
                    }
                    sleep(1);
                    echo(date("Y-m-d h:i:s")."-{$now_wangyi_num}{$remark}\n");
                    $x = 10;
                    $t = 0;
                    $uid = $login_s[0]['account']['id'];
                    $data = ['do'=>'detail','uid'=>$uid,'r'=>randFloat()];
                    $detail = send_post($url,$data,$cookies,$timeout=120);
                    $level = $detail[0]['level'];
                    $start_listenSongs = $detail[0]['listenSongs'];
                    while ($x>0){
                        $t = $t +1;
                        $data = ['do'=>'detail','uid'=>$uid,'r'=>randFloat()];
                        $detail = send_post($url,$data,$cookies,$timeout=120);
                        $listenSongs = $detail[0]['listenSongs'];
                        $data = ['do'=>'daka','r'=>randFloat()];
                        $daka_s = send_post($url,$data,$cookies,$timeout=120);
                        if ($daka_s[0]['code']==200){
                            echo(date("Y-m-d h:i:s")."-{$now_wangyi_num}第".(11-$x)."次打卡...\n");
                            $x=$x-1;
                        }else{
                            echo(date("Y-m-d h:i:s")."-{$now_wangyi_num}第".(11-$x)."次打卡重试...\n");
                        }
                        if (($listenSongs-$start_listenSongs)==300){
                            echo(date("Y-m-d h:i:s")."-{$now_wangyi_num}打卡300首歌...\n");
                            break;
                        }
                        if ($t>30){
                            break;
                        }
                        sleep(10);
                    }
                    echo(date("Y-m-d h:i:s")."-{$now_mail}打卡完成，发送邮箱通知...\n");
                    $data = ['do'=>'detail','uid'=>$uid,'r'=>randFloat()];
                    $detail_s = send_post($url,$data,$cookies,$timeout=120);
                    $level = $detail_s[0]['level'];
                    $listenSongs = $detail_s[0]['listenSongs'];
                    $name = $login_s[0]['profile']['nickname'];
                    $remark = $remark."打卡".($listenSongs-$start_listenSongs)."首歌";
                    $insert_login_error_info = "insert into info values(null ,%d,1,1,'%s','%s');";
                    $insert_login_error_info = sprintf($insert_login_error_info,$now_user_id,$remark,date("Y-m-d h:i:s"));
                    $db->exec($insert_login_error_info);
                    foreach ($grade as $lkey => $lvalue){
                        if($level < 10){
                            if($listenSongs < 20000){
                                if ($listenSongs < $lvalue){
                                    $tip = '还需听歌'.($lvalue-$listenSongs).'首即可升级';
                                    break;
                                }
                            }else{
                                $tip = '你已经听够20000首歌曲,如果登录天数达到800天即可满级';
                            }
                        }else{
                            $tip = '恭喜你已经满级!';
                        }
                    }
                    $day = ceil((20000 - $listenSongs)/300);
                    $remark = "今日打卡备注：".$remark."\n<br>账户名称：".$name."\n<br>当前等级：".$level."\n<br>累计播放：".$listenSongs."首\n<br>升级提示：".$tip."\n<br>今日共打卡:".$t."次\n<br>今日共播放：".($listenSongs-$start_listenSongs)."首\n<br>还需要打卡：".$day."天\n<br>打卡网站：".$web_url;
                    echo("\n".$remark."\n");
                    if ($send_info==1 and $accept_email==1){
                        try {
                            ob_start();
                            $subject=$web_name."打卡通知！";
                            $body = $remark;
                            $cache = send_email($smtp_host,$smtp_username,$smtp_password,$smtp_port,$smtp_sender,$now_mail,$subject,$body);
                            ob_get_clean();
                            ob_end_flush();
                            echo(date("Y-m-d h:i:s")."-{$now_mail}邮箱通知已发送\n");
                        }catch (Exception $e) {
                            echo date("Y-m-d h:i:s").$e->getMessage();
                            echo("\n");
                            echo(date("Y-m-d h:i:s")."-未配置邮箱，发送通知信息失败！\n");
                            // die(); // 终止异常
                        }
                    }
                    sleep(10);
                }else{
                    $e_code = $login_s[0]['code'];
                    $e_msg = $login_s[0]['msg'];
                    echo(date("Y-m-d h:i:s")."-{$now_wangyi_num}登陆失败，失败码：{$e_code}，信息：{$e_msg}\n");
                    $insert_login_error_info = "insert into info values(null ,%d,0,0,'%s','%s');";
                    $insert_login_error_info = sprintf($insert_login_error_info,$now_user_id,"登陆失败".$login_s[0]['code'].$login_s[0]['message'],date("Y-m-d h:i:s"));
                    $db->exec($insert_login_error_info);
                    echo(date("Y-m-d h:i:s")."-API疑似被网易BAN:".$url."！\n");
                    $url_ok_list = array_diff($url_ok_list, [$url,]);
                    if (count($url_ok_list)<1){
                        exit(date("Y-m-d h:i:s")."-无可用API打卡终止\n");
                    }
                    goto start_daka;
                }

            }
        }
    }
}
$db->close();
exit(date("Y-m-d h:i:s")."-脚本执行完毕\n");


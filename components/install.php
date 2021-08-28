<?php
if (isset($_POST['install']) and isset($_POST['email']) and isset($_POST['password']) and isset($_POST['re_password'])){
    class MyDB extends SQLite3
    {
        function __construct()
        {
            $this->open('database/wangyi.db');
        }
    }
    $db = new MyDB();
    $sql_user = <<<EOF
create table user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email varchar(25) unique default '' ,
    password varchar(100) default '',
    wangyi_num varchar(25) default '',
    wangyi_style varchar(5) default '0',
    wangyi_password varchar(100) default '',
    is_admin bit default 0,
    accept_email bit default 0,
    create_time datetime DEFAULT CURRENT_TIMESTAMP,
    is_active bit default 0,
    activation_time datetime DEFAULT null,
    days int unsigned default 0
)
EOF;
    $sql_config = <<<EOF
create table config (
    id INTEGER  PRIMARY KEY AUTOINCREMENT ,
    web_url varchar(50) default '',
    web_title varchar(25) default '',
    web_name varchar(25) default '',
    api varchar(500) default '',
    register_verify bit default 0,
    login_verify TINYINT default 0,
    sitekey varchar(50) default '',
    secret varchar(50) default '',
    send_info bit default 0,
    notice varchar(500) default '',
    Host varchar(20) default '',
    Username varchar(20) default '',
    Password varchar(100) default '',
    Port int unsigned default 465,
    setFrom varchar(20) default ''
)
EOF;
    $sql_kami = <<<EOF
create table kami(
    id INTEGER  PRIMARY KEY AUTOINCREMENT ,
    kami varchar(100) unique default '',
    day int unsigned default 0,
    user_id int unsigned ,
    is_valid bit default 1
)
EOF;
    $sql_info = <<<EOF
create table info(
    id INTEGER  PRIMARY KEY AUTOINCREMENT ,
    user_id int unsigned ,
    is_sign bit default 0,
    is_daka bit default 0,
    remark varchar(100) default '',
    create_time datetime DEFAULT CURRENT_TIMESTAMP
)
EOF;
    $sql_reset = <<<EOF
create table reset_code(
    id INTEGER  PRIMARY KEY AUTOINCREMENT ,
    email varchar(25) default '' ,
    code varchar(10) default '',
    create_time datetime DEFAULT CURRENT_TIMESTAMP
)
EOF;

    $sql_setadmin = "insert into user(id,email,password,is_admin,create_time) values(0,'%s','%s',%d,'%s'); ";
    $sql_setadmin = sprintf($sql_setadmin,$_POST['email'],password_hash($_POST['password'], PASSWORD_DEFAULT),1,date("Y-m-d h:i:s"));
    $sql_setconfig = "insert into config(id,web_title,web_name) values(0,'网易云打卡','网易云打卡');";
    $ret = $db->exec($sql_user);
    $ret = $db->exec($sql_config);
    $ret = $db->exec($sql_kami);
    $ret = $db->exec($sql_info);
    $ret = $db->exec($sql_reset);
    $ret = $db->exec($sql_setadmin);
    $ret = $db->exec($sql_setconfig);
    $db->close();
    exit("<script>window.location.replace('./index.php?info=安装成功！');</script>");
}
?>
<div class="modal fade" id="modalLaunchForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">开始安装</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mx-md-5" action="index.php" method="post">
                <div class="modal-body mx-3">
                    <div class="md-form mb-5" hidden>
                        <input type="text" id="launch-hidden" class="form-control validate" minlength="5" name="install" value="1">
                        <label data-error="wrong" data-success="right" for="launch-hidden">hidden</label>
                    </div>
                    <div class="md-form mb-5">
                        <input type="email" id="launch-email" class="form-control validate" minlength="5" name="email">
                        <label data-error="wrong" data-success="right" for="launch-email">管理员邮箱</label>
                    </div>

                    <div class="md-form mb-4">
                        <input type="password" id="launch-pass" class="form-control validate" minlength="6" name="password">
                        <label data-error="wrong" data-success="right" for="launch-pass">管理员密码</label>
                    </div>

                    <div class="md-form mb-4">
                        <input type="password" id="launch-repass" class="form-control validate" minlength="6" name="re_password">
                        <label data-error="wrong" data-success="right" for="launch-repass">重复密码</label>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-default" id="launch" type="submit">安装</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="text-center">
    <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalLaunchForm">点此安装</a>
</div>


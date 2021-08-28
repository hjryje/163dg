<?php
if (isset($_POST['config_change'])){
    if (isset($_POST['register_verify'])){
        $register_verify = 1;
    }else{
        $register_verify = 0;
    }
    if (isset($_POST['send_info'])){
        $send_info = 1;
    }else{
        $send_info = 0;
    }
    $update_config = "update config set web_title='%s',web_name='%s',web_url='%s',api='%s',notice='%s',register_verify=%d,login_verify=%d,send_info=%d,sitekey='%s',secret='%s',Host='%s',Username='%s',Password='%s',Port='%s',setFrom='%s'  where id=0;";
    $update_config = sprintf($update_config,$_POST['web_title'],$_POST['web_name'],$_POST['web_url'],$_POST['api'],$_POST['notice'],(int)$register_verify,(int)$_POST['login_verify'],(int)$send_info,$_POST['sitekey'],$_POST['secret'],$_POST['host'],$_POST['username'],$_POST['password'],$_POST['port'],$_POST['sender']);
    $ret = $db->exec($update_config);
    echo "<script>window.location.replace('./index.php?admin=1&info=修改系统设置成功！');</script>";
}
?>
<div class="mdui-dialog" id="exampleDialog" >
    <div class="mdui-dialog-title" id="title"></div>
    <div class="mdui-dialog-content" style="text-align: left"><p style="display: inline"></p><p style="display: inline" id="wangyi_num"></p></div>
    <div class="mdui-dialog-actions">
        <button class="mdui-btn mdui-ripple" mdui-dialog-close>取消</button>
        <button class="mdui-btn mdui-ripple" mdui-dialog-confirm>删除</button>
    </div>
</div>

<div class="container">
    <div class="card card-cascade wider reverse my-4 wow animate__backInUp">
        <div class="card-body card-body-cascade text-center">
            <h4 class="card-title ndigo-text font-weight-bold "><i class="fas fa-address-card"></i> 用户管理</h4>
            <hr>
            <!-- Subtitle -->
            <table id="dt-filter-select" class="table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="th-sm">ID
                    </th>
                    <th class="th-sm">邮箱
                    </th>
                    <th class="th-sm">网易账号
                    </th>
                    <th class="th-sm">国别
                    </th>
                    <th class="th-sm">账号类型
                    </th>
                    <th class="th-sm">接受通知
                    </th>
                    <th class="th-sm">注册日期
                    </th>
                    <th class="th-sm">是否激活
                    </th>
                    <th class="th-sm">激活日期
                    </th>
                    <th class="th-sm">到期日期
                    </th>
                    <th class="th-sm">操作
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $data = array();
                $select_users = "select * from user order by id desc ;";
                $ret = $db->query($select_users);
                while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
                    $id = $row['id'];
                    $email = $row['email'];
                    if (strlen($row['wangyi_num'])>1){
                        $wangyi_num = $row['wangyi_num'];
                        if ($row['wangyi_style']==0){
                            $wangyi_style="邮箱";
                        }else{
                            $wangyi_style = ''.$row['wangyi_style'];
                        }
                    }else{
                        $wangyi_num = "未绑定";
                        $wangyi_style = "未绑定";
                    }
                    if ($row['is_admin']==1){
                        $is_admin = "网站管理";
                    }else{
                        $is_admin = "普通用户";
                    }
                    if ($row['accept_email']==1){
                        $accept_email = "接受";
                    }else{
                        $accept_email = "拒绝";
                    }
                    $create_time = $row['create_time'];
                    if ($row['is_active']==1){
                        $is_active = "激活";
                        $activation_time =  $row['activation_time'];
                        $last_day = date("Y-m-d h:i:s",strtotime("{$activation_time} +{$row['days']} day"));
                    }else{
                        $is_active = "未激活";
                        $activation_time = "未激活";
                        $last_day = "未激活";
                    }
                    echo "<tr  id='del-".$id."' >";
                    echo "<td>{$id}</td><td>{$email}</td><td>{$wangyi_num}</td><td>{$wangyi_style}</td><td>{$is_admin}</td><td>{$accept_email}</td><td>{$create_time}</td><td>{$is_active}</td><td>{$activation_time}</td><td>{$last_day}</td><td><a class=\"btn-floating btn-sm btn-secondary\" id='del${id}'><i class=\"fas fa-archive\"></i></a></td>";
                    echo "</tr>";
                    echo <<<EOF
<script>
$(function(){
    $("#del${id}").click(function() {
        $("#title").html('是否删除用户：'+'${email}');
        $("#wangyi_num").html('网易账号：'+'${wangyi_num}');
        var inst = new mdui.Dialog('#exampleDialog');
        inst.open();
        var dialog = document.getElementById('exampleDialog');
        dialog.addEventListener('confirm.mdui.dialog', function () {
          $.ajax({
                url: 'api/api.php',
                type: 'POST',
                dataType: 'json',
                data:{'del':1,'id':${id} }
            })
            .done(function(data) {
                mdui.alert(data['data']);
            })
            .fail(function() {
                mdui.alert('服务器超时，请重试！');
            });
        });
    })
});
</script>
EOF;

                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="th-sm">ID
                    </th>
                    <th class="th-sm">邮箱
                    </th>
                    <th class="th-sm">网易账号
                    </th>
                    <th class="th-sm">国别
                    </th>
                    <th class="th-sm">账号类型
                    </th>
                    <th class="th-sm">接受通知
                    </th>
                    <th class="th-sm">注册日期
                    </th>
                    <th class="th-sm">是否激活
                    </th>
                    <th class="th-sm">激活日期
                    </th>
                    <th class="th-sm">到期日期
                    </th>
                    <th class="th-sm">操作
                    </th>
                </tr>
                </tfoot>
            </table>
            <style>
                .dt-filter-selectWrapper {
                    max-width: 600px;
                    margin: 0 auto;
                }
                #dt-filter-select th, td {
                    white-space: nowrap;
                }

                table.dataTable thead .sorting:after,
                table.dataTable thead .sorting:before,
                table.dataTable thead .sorting_asc:after,
                table.dataTable thead .sorting_asc:before,
                table.dataTable thead .sorting_asc_disabled:after,
                table.dataTable thead .sorting_asc_disabled:before,
                table.dataTable thead .sorting_desc:after,
                table.dataTable thead .sorting_desc:before,
                table.dataTable thead .sorting_desc_disabled:after,
                table.dataTable thead .sorting_desc_disabled:before {
                    bottom: .5em;
                }
            </style>
            <script>
                // Basic example
                $(document).ready(function () {
                    $('#dt-filter-select').DataTable({
                        "searching": true, // false to disable search (or any other option)
                        "scrollX": true
                    });
                    $('.dataTables_length').addClass('bs-select');
                });
            </script>

        </div>
    </div>
    <div class="card card-cascade wider reverse my-4 wow animate__backInUp">
        <!-- Card content -->
        <div class="card-body card-body-cascade text-center">

            <!-- Title -->
            <h4 class="card-title ndigo-text font-weight-bold "><i class="fas fa-cogs"></i> 网站设置</h4>
            <!-- Text -->
            <hr>
            <form action="index.php?admin=1" method="post">
                <!-- Material input -->
                <div class="md-form" hidden>
                    <input  type="text" id="inputPrefilledEx1" class="form-control"  name="config_change" value="1">
                    <label for="inputPrefilledEx1">Example label</label>
                </div>
                <!-- Material input -->
                <div class="md-form">
                    <input name="web_title" value="<?php echo $web_title;?>" type="text" id="inputPrefilledEx2" class="form-control">
                    <label for="inputPrefilledEx2">网站标题</label>
                </div>
                <!-- Material input -->
                <div class="md-form">
                    <input name="web_name" value="<?php echo $web_name;?>" type="text" id="inputPrefilledEx3" class="form-control">
                    <label for="inputPrefilledEx3">网站名称</label>
                </div>
                <div class="md-form">
                    <input name="web_url" value="<?php echo $web_url;?>" type="text" id="inputPrefilledEx2web_url" class="form-control">
                    <label for="inputPrefilledEx2web_url">网站地址</label>
                </div>
                <!-- Material input -->
                <div class="md-form">
                    <textarea id="form7" class="md-textarea form-control" rows="3" name="api"><?php echo $api;?></textarea>
                    <label for="form7">API(一行一个)</label>
                </div>
                <!-- Material input -->
                <div class="float-left custom-control switch">
                    <label>
                        注册验证:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Off
                        <input type="checkbox" name="register_verify" value="1" <?php if ($register_verify==1){echo 'checked';} ?>>
                        <span class="lever"></span> On
                    </label>
                </div>
                <p>&nbsp;</p>
                <!-- Default checked -->
                <div class="custom-control float-left">
                    <!-- Default inline 1-->
                    <p style="display: inline;padding-left: 0;margin-right: 20px">登陆验证:</p>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="defaultInline1" name="login_verify" value="0" <?php if ($login_verify==0){echo 'checked';} ?>>
                        <label class="custom-control-label" for="defaultInline1">关闭</label>
                    </div>

                    <!-- Default inline 2-->
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="defaultInline2" name="login_verify" value="1" <?php if ($login_verify==1){echo 'checked';} ?>>
                        <label class="custom-control-label" for="defaultInline2">reCAPTCHA</label>
                    </div>

                    <!-- Default inline 3-->
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="defaultInline3" name="login_verify" value="2" <?php if ($login_verify==2){echo 'checked';} ?>>
                        <label class="custom-control-label" for="defaultInline3">图片验证</label>
                    </div>
                </div>
                <p>&nbsp;</p>
                <div class="float-left custom-control switch">
                    <label>
                        发送打卡通知:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Off
                        <input type="checkbox" name="send_info" value="1" <?php if ($send_info==1){echo 'checked';} ?>>
                        <span class="lever"></span> On
                    </label>
                </div>
                <br>
                <div class="md-form">
                    <input name="sitekey" value="<?php echo $sitekey;?>" type="text" id="inputPrefilledEx5" class="form-control">
                    <label for="inputPrefilledEx5">reCAPTCHA 网站密钥</label>
                </div>
                <div class="md-form">
                    <input name="secret" value="<?php echo $secret;?>" type="text" id="inputPrefilledEx5" class="form-control">
                    <label for="inputPrefilledEx5">reCAPTCHA 客户端密钥</label>
                </div>
                <div class="md-form">
                    <textarea id="form7" class="md-textarea form-control" rows="3" name="notice"><?php echo $notice;?></textarea>
                    <label for="form7">头部公告</label>
                </div>
                <div class="md-form">
                    <input name="host" value="<?php echo $smtp_host;?>" type="text" id="inputPrefilledEx5" class="form-control">
                    <label for="inputPrefilledEx5">SMTP服务器</label>
                </div>
                <div class="md-form">
                    <input name="port" value="<?php echo $smtp_port;?>" type="text" id="inputPrefilledEx6" class="form-control">
                    <label for="inputPrefilledEx6">SMTP端口</label>
                </div>
                <div class="md-form">
                    <input name="auth" value="ssl" disabled type="text" id="inputPrefilledEx7" class="form-control">
                    <label for="inputPrefilledEx7">SMTP验证</label>
                </div>
                <div class="md-form">
                    <input name="username" value="<?php echo $smtp_username;?>" type="text" id="inputPrefilledEx8" class="form-control">
                    <label for="inputPrefilledEx8">SMTP用户名</label>
                </div>
                <div class="md-form">
                    <input name="password" value="<?php echo $smtp_password;?>" type="text" id="inputPrefilledEx9" class="form-control">
                    <label for="inputPrefilledEx9">SMTP授权码</label>
                </div>
                <div class="md-form">
                    <input name="sender" value="<?php echo $smtp_sender;?>" type="text" id="inputPrefilledEx10" class="form-control">
                    <label for="inputPrefilledEx10">SMTP发件箱</label>
                </div>
                <button type="submit" class="btn btn-default btn-lg btn-block">提交修改</button>
            </form>

        </div>
    </div>
    <div class="card card-cascade wider reverse my-4 wow animate__backInUp">
        <!-- Card content -->
        <div class="card-body card-body-cascade text-center">
            <!-- Title -->
            <!-- Title -->
            <h4 class="card-title ndigo-text font-weight-bold "><i class="fas fa-passport"></i> 卡密管理</h4>
            <!-- Text -->
            <hr>
            <div class="md-form input-group mb-3">
                <p for="num" style="padding:0 10px;margin: 0;line-height: 40px">数量</p>
                <input class="quantity" min="1" name="num" value="1" type="number" max="20" id="num">
                <p for="day" style="padding:0 10px;margin: 0;line-height: 40px">时长/天</p>
                <input class="quantity" min="1" name="day" value="1" type="number" max="365" id="day">
                &nbsp;
                <div class="input-group-append">
                    <button class="btn btn-md btn-secondary m-0 px-3" type="button" id="make_kami">生成</button>
                </div>
                &nbsp;
                <p for="day" style="padding:0 10px;margin: 0;line-height: 40px">按有效期导出/天</p>
                <input class="quantity" min="1" name="day" value="1" type="number" max="365" id="ex_day">
                &nbsp;
                <div class="input-group-append">
                    <button class="btn btn-md btn-warning m-0 px-3" type="button" id="export_btn">导出</button>
                </div>
            </div>
            <hr>
            <script type="text/javascript">
                function download(filename, text) {
                    var element = document.createElement('a');
                    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
                    element.setAttribute('download', filename);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                }
                $(function(){
                    $('#export_btn').click(function(event){
                        event.preventDefault();
                        console.log("---");
                        $('#export_btn').prop({'disabled':true});
                        $('#export_btn').html('<div class="text-center">\n' +
                            '  <div class="spinner-border" role="status">\n' +
                            '    <span class="sr-only">Loading...</span>\n' +
                            '  </div>\n' +
                            '</div>');
                        $.ajax({
                            url: 'api/api.php',
                            type: 'GET',
                            dataType: 'json',
                            data:{'ex_day':document.getElementById('ex_day').value}
                        })
                        .done(function(data) {
                            console.log(data);
                            if(data.code==200){
                                if(data.resp.length>10){
                                    download("kami_"+document.getElementById('ex_day').value+".txt",data.resp);
                                }
                                else{
                                    mdui.alert("无卡密！");
                                }
                            }else{
                                mdui.alert(data.resp);
                            }
                            $('#export_btn').html('导出');
                            $('#export_btn').prop({'disabled':false});
                        })
                        .fail(function() {
                            mdui.alert('服务器超时，请重试！');
                            $('#export_btn').html('导出');
                            $('#export_btn').prop({'disabled':false});
                        });
                    })
                    $('#make_kami').click(function(event){
                        event.preventDefault();
                        $('#make_kami').prop({'disabled':true});
                        $('#make_kami').html('<div class="text-center">\n' +
                            '  <div class="spinner-border" role="status">\n' +
                            '    <span class="sr-only">Loading...</span>\n' +
                            '  </div>\n' +
                            '</div>');
                        $.ajax({
                            url: 'api/api.php',
                            type: 'POST',
                            dataType: 'json',
                            data:{'make_kami':1,'day':document.getElementById('day').value,'num':document.getElementById('num').value}
                        })
                            .done(function(data) {
                                if(data.code==200){
                                    $("#ps").html(data.resp);
                                    $('#myModal').modal('show');
                                }else{
                                    $("#ps").html(data.resp);
                                    $('#myModal').modal('show');
                                }
                                $('#make_kami').html('生成');
                                $('#make_kami').prop({'disabled':false});
                            })
                            .fail(function() {
                                $('#make_kami').html('生成');
                                $('#make_kami').prop({'disabled':false});
                                mdui.alert('服务器超时，请重试！');
                            });
                    })
                });
            </script>
            <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="th-sm">ID
                    </th>
                    <th class="th-sm">卡密
                    </th>
                    <th class="th-sm">时效/天
                    </th>
                    <th class="th-sm">绑定用户
                    </th>
                    <th class="th-sm">有效
                    </th>
                    <th class="th-sm">操作
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql_get_kami = "select * from kami order by is_valid and id asc;";
                $ret = $db->query($sql_get_kami);
                $data = array();
                while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
                    $id = $row['id'];
                    $kami = $row['kami'];
                    $day = $row['day'];
                    if ($row['is_valid']==1){
                        $user_id="未使用";
                        $is_valid="未使用";
                    }else{
                        $user_id = $row['user_id'];
                        $is_valid="已使用";
                    }
                    echo "<tr><td>${id}</td><td>${kami}</td><td>${day}</td><td>${user_id}</td><td>${is_valid}</td><td><a class=\"btn-floating btn-sm btn-secondary\" id='delkami${id}'><i class=\"fas fa-archive\"></i></a></td></tr>";
                    echo <<<EOF
<script>
$(function(){
    $("#delkami${id}").click(function() {
        $("#title").html('是否删除卡密：'+'${id}');
        $("#wangyi_num").html('删除后不会影响已经绑定卡密的用户！');
        var inst = new mdui.Dialog('#exampleDialog');
        inst.open();
        var dialog = document.getElementById('exampleDialog');
        dialog.addEventListener('confirm.mdui.dialog', function () {
          $.ajax({
                url: 'api/api.php',
                type: 'POST',
                dataType: 'json',
                data:{'del_kami':1,'id':${id} }
            })
            .done(function(data) {
                mdui.alert(data['data']);
            })
            .fail(function() {
                mdui.alert('服务器超时，请重试！');
            });
        });
    })
});
</script>
EOF;
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="th-sm">ID
                    </th>
                    <th class="th-sm">卡密
                    </th>
                    <th class="th-sm">时效/天
                    </th>
                    <th class="th-sm">绑定用户
                    </th>
                    <th class="th-sm">有效
                    </th>
                    <th class="th-sm">操作
                    </th>
                </tr>
                </tfoot>
            </table>
            <style>
                table.dataTable thead .sorting:after,
                table.dataTable thead .sorting:before,
                table.dataTable thead .sorting_asc:after,
                table.dataTable thead .sorting_asc:before,
                table.dataTable thead .sorting_asc_disabled:after,
                table.dataTable thead .sorting_asc_disabled:before,
                table.dataTable thead .sorting_desc:after,
                table.dataTable thead .sorting_desc:before,
                table.dataTable thead .sorting_desc_disabled:after,
                table.dataTable thead .sorting_desc_disabled:before {
                    bottom: .5em;
                }
            </style>
            <script>
                $(document).ready(function () {
                    $('#dtBasicExample').DataTable({
                        "scrollX": true
                    });
                    $('.dataTables_length').addClass('bs-select');
                });
            </script>
        </div>
    </div>
    <div class="card card-cascade wider reverse my-4 wow animate__backInUp">
        <!-- Card content -->
        <div class="card-body card-body-cascade text-center">

            <!-- Title -->
            <h4 class="card-title ndigo-text font-weight-bold "><i class="fas fa-info-circle"></i> 打卡日志</h4>
            <!-- Text -->
            <hr>

            <table id="dtBasicExample1" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="th-sm">ID
                    </th>
                    <th class="th-sm">是否已经签到
                    </th>
                    <th class="th-sm">是否已经打卡
                    </th>
                    <th class="th-sm">日志备注
                    </th>
                    <th class="th-sm">日期
                    </th>
                    <th class="th-sm">用户ID
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $select_info = "select * from info order by create_time desc;";
                $ret = $db->query($select_info);
                while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
                    $id = $row['id'];
                    $user_id = $row['user_id'];
                    $is_sign = $row['is_sign'];
                    $is_daka = $row['is_daka'];
                    $remark = $row['remark'];
                    $create_time = $row['create_time'];
                    echo "<tr><td>{$id}</td><td>{$is_sign}</td><td>{$is_daka}</td><td>{$remark}</td><td>{$create_time}</td><td>{$user_id}</td></tr>";
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="th-sm">ID
                    </th>
                    <th class="th-sm">是否已经签到
                    </th>
                    <th class="th-sm">是否已经打卡
                    </th>
                    <th class="th-sm">日志备注
                    </th>
                    <th class="th-sm">日期
                    </th>
                    <th class="th-sm">用户ID
                    </th>
                </tr>
                </tfoot>
            </table>
            <style>
                table.dataTable thead .sorting:after,
                table.dataTable thead .sorting:before,
                table.dataTable thead .sorting_asc:after,
                table.dataTable thead .sorting_asc:before,
                table.dataTable thead .sorting_asc_disabled:after,
                table.dataTable thead .sorting_asc_disabled:before,
                table.dataTable thead .sorting_desc:after,
                table.dataTable thead .sorting_desc:before,
                table.dataTable thead .sorting_desc_disabled:after,
                table.dataTable thead .sorting_desc_disabled:before {
                    bottom: .5em;
                }
            </style>
            <script>
                $(document).ready(function () {
                    $('#dtBasicExample1').DataTable({
                        "searching": true, // false to disable search (or any other option)
                        "scrollX": true
                    });
                    $('.dataTables_length').addClass('bs-select');
                });
            </script>
        </div>
    </div>
</div>

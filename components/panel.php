<?php
error_reporting(0);
?>
<div class="container">
    <div class="card w-100 mb-3">
        <div class="card-body">
            <h5 class="card-title">网易云绑定 <small style="color: red" id="small_info"></small></h5>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="inputGroupSelect01">类型</label>
                </div>
                <select class="browser-default custom-select" id="inputGroupSelect01" name="style">
                    <option id="op0" value="0" selected>163邮箱登录</option>
                </select>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">网易账号</span>
                </div>
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="wangyimun" value="<?php
                if (strlen($wangyi_num)>5){
                    echo $wangyi_num;
                }
                ?>">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">网易密码</span>
                </div>
                <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="wangyipwd" placeholder="本站不会明文保存您的密码">
            </div>
            <button class="btn aqua-gradient" id="bind">绑定</button>
            <script type="text/javascript">
                $(function(){
                    $.ajax({
                        url: 'countrycode.json',
                        type: 'GET',
                        dataType: 'json'
                    })
                        .done(function(data) {
                            var $htm = $('#inputGroupSelect01').html();
                            for(var p in data){
                                $htm = $htm + '<option id="op'+data[p].phone_code.slice(1,100)+'" value="'+data[p].phone_code+'">'+"手机号登录 - "+data[p].cn+"</option>";
                            }
                            $('#inputGroupSelect01').html($htm);
                        })
                        .fail(function() {
                            $("#mod_body").html('服务器超时，请重试！');
                            $('#exampleModalCenter').modal('show');
                        });
                });
            </script>
            <script type="text/javascript">
                $(function(){
                    $('#bind').click(function(event){
                        event.preventDefault();
                        $('#bind').prop({'disabled':true});
                        $('#bind').html('<div class="text-center">\n' +
                            '  <div class="spinner-border" role="status">\n' +
                            '    <span class="sr-only">Loading...</span>\n' +
                            '  </div>\n' +
                            '</div>');
                        var s = $('#wangyimun').prop('value');
                        var p = $('#wangyipwd').prop('value');
                        var t = $('#inputGroupSelect01').prop('value');
                        if(s.length<5){
                            mdui.alert("请输入正确的账号！");
                            $('#bind').html("绑定");
                            $('#bind').prop({'disabled':false});
                            return
                        }
                        if(p.length<5){
                            mdui.alert("请输入正确的密码！");
                            $('#bind').html("绑定");
                            $('#bind').prop({'disabled':false});
                            return
                        }
                        $.ajax({
                            url: 'api/api.php',
                            type: 'POST',
                            dataType: 'json',
                            data:{'id':<?php echo $_SESSION['id'];?>,'wangyi_num':s,'wangyi_password':p,'style':t}
                        })
                            .done(function(data) {
                                if(data.resp==200){
                                    $("#ps").html(data.data);
                                    $('#myModal').modal('show');
                                    $('#bind').html("绑定");
                                    $('#bind').prop({'disabled':false});
                                }else{
                                    mdui.alert(data.data, function(){
                                        console.log(data.data);
                                    });
                                    $('#bind').html("绑定");
                                    $('#bind').prop({'disabled':false});
                                }
                            })
                            .fail(function() {
                                $('#bind').html("绑定");
                                $('#bind').prop({'disabled':false});
                                mdui.alert('服务器超时，请重试！');
                            });
                    })
                });
            </script>
        </div>
    </div>
    <div class="card w-100 mb-3">
        <div class="card-body">
            <h5 class="card-title">卡密激活 <?php if ($days>0){
                    $last_date = date("Y-m-d h:i:s",strtotime("{$activation_time} +{$days} day"));
                    ?>
                    <small style="color: red;">已激活-将于<?php echo $last_date;?>到期</small>
                <?php } ?></h5>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">卡密</span>
                </div>
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="bind_kami" placeholder="XXXX-XXXX-XXXX" name="bind_kami">
            </div>
            <div class="input-group mb-3">
                <div class="custom-control custom-switch" style="margin-left: 20px">
                    <input type="checkbox" class="custom-control-input" id="accept_email" value="1" <?php if($accept_email==1){echo 'checked';}?>>
                    <label class="custom-control-label" for="accept_email">接受邮件打卡通知</label>
                </div>
            </div>
            <button class="btn blue-gradient" id="kami_active">激活</button>
        </div>
        <script type="text/javascript">
            $(function(){
                $("#accept_email").change(function() {
                    if($("#accept_email").is(':checked') ){
                        var d = "t";
                    }else{
                        var d = "f";
                    }
                    $.ajax({
                        url: 'api/api.php',
                        type: 'POST',
                        dataType: 'json',
                        data:{'d':d,'id':'<?php echo $_SESSION['id'];?>'},
                        async:true
                    })
                        .done(function(data) {
                            mdui.alert(data.data);
                        })
                        .fail(function() {
                            mdui.alert('服务器超时，请重试！');
                        });
                });
                $('#kami_active').click(function(event){
                    event.preventDefault();
                    $('#kami_active').prop({'disabled':true});
                    $('#kami_active').html('<div class="text-center">\n' +
                        '  <div class="spinner-border" role="status">\n' +
                        '    <span class="sr-only">Loading...</span>\n' +
                        '  </div>\n' +
                        '</div>');
                    var s = $('#bind_kami').prop('value');
                    var p = $('#accept_email').prop('value');
                    if(s.length<10){
                        mdui.alert("请输入正确的卡密！");
                        $('#kami_active').html("激活");
                        $('#kami_active').prop({'disabled':false});
                        return
                    }
                    if(document.getElementById("accept_email").checked){
                        var data={
                            'id':<?php echo $_SESSION['id'];?>,
                            'bind_kami':s,
                            'accept_email':p
                        }
                    }
                    else{
                        var data={
                            'id':<?php echo $_SESSION['id'];?>,
                            'bind_kami':s,
                        }
                    }
                    $('#kami_active').prop({'disabled':true});
                    $.ajax({
                        url: 'api/api.php',
                        type: 'POST',
                        dataType: 'json',
                        data:data
                    })
                        .done(function(data) {
                            if(data.resp==200){
                                $("#ps").html(data.data);
                                $('#myModal').modal('show');
                                $('#kami_active').html("激活");
                                $('#kami_active').prop({'disabled':false});
                            }else{
                                $('#kami_active').html("激活");
                                $('#kami_active').prop({'disabled':false});
                                mdui.alert(data.data);
                            }
                        })
                        .fail(function() {
                            $('#kami_active').html("激活");
                            $('#kami_active').prop({'disabled':false});
                            mdui.alert('服务器超时，请重试！');
                        });
                })
            });
        </script>
    </div>
    <div class="d-flex justify-content-center" style="margin-top: 20px;" id="loading">
        <div class="spinner-grow text-secondary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="card testimonial-card" id="user_panel" style="display: none">
        <!--Bacground color-->
        <img id="user_backimg" class="card-img-top" src="https://api.72.rs/images/api.php?t=2" alt="Card image cap">
        <!--Avatar-->
        <div class="avatar mx-auto white">
            <img  id="user_avatar" src="https://q.qlogo.cn/g?b=qq&nk=420688441&s=100" class="rounded-circle">
        </div>

        <div class="card-body">
            <!--Name-->
            <h4 id="user_nickname" class="card-title">未绑定账号或密码错误</h4>
            <hr>
            <!--Quotation-->
            <p id="signature"><i class="fas fa-quote-left"></i> Description: 未绑定账号或密码错误</p>

            <button type="button" class="btn btn-dribbble waves-effect waves-light" id="get_info">
                <i class="fab fa-dribbble left"></i> 查看日志</button>
        </div>
        <div class="mdui-dialog" id="paneldialog">
            <div class="mdui-dialog-title">提示：</div>
            <div class="mdui-dialog-content" id="tip"></div>
            <div class="mdui-dialog-actions">
                <button class="mdui-btn mdui-ripple" mdui-dialog-confirm>知道了</button>
            </div>
        </div>
        <script>
            $(function(){

                $('#get_info').click(function(){
                    var inst = new mdui.Dialog('#paneldialog');
                    var msg =  "<?php
                        $select_info = "select * from info where user_id = ".$_SESSION['id']."  order by create_time desc limit 10;";
                        $ret = $db->query($select_info);
                        echo "<table class='table'><thead><tr><th>用户ID</th><th>是否已经签到</th><th>是否已经打卡</th><th>日志备注</th><th>日期</th></tr></thead><tbody>";
                        while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
                            $id = $row['id'];
                            $user_id = $row['user_id'];
                            $remark = $row['remark'];
                            $create_time = $row['create_time'];
                            if ($row['is_sign']==1){
                                $is_sign="已签到";
                            }else{
                                $is_sign="未签到";
                            }
                            if ($row['is_daka']==1){
                                $is_daka="已打卡";
                            }else{
                                $is_daka="未打卡";
                            }
                            echo "<tr><td>{$user_id}</td><td>{$is_sign}</td><td>{$is_daka}</td><td>{$remark}</td><td>{$create_time}</td></tr>";
                        }
                        echo "</tbody></table>";
                        ?>";
                    $('#tip').html(msg);
                    inst.open();
                });
            });
        </script>
    </div>
    <script type="text/javascript">
        $(function(){
            $.ajax({
                url: 'api/api.php',
                type: 'GET',
                dataType: 'json',
                data:{'info':1,'wangyi_num':'<?php echo $wangyi_num;?>'}
            })
                .done(function(data) {
                    console.log(data);
                    if (data.code==200){
                        try{$("#user_backimg").prop({'src':data.data.profile.backgroundUrl});}catch(e){console.log(e)}
                        try{$("#user_avatar").prop({'src':data.data.profile.avatarUrl});}catch(e){console.log(e)}
                        try{$("#user_nickname").html(data.data.account.userName);}catch(e){console.log(e)}
                        try{$("#user_nickname").html(data.data.profile.nickname);}catch(e){console.log(e)}
                        try{$("#signature").html('<i class="fas fa-quote-left"></i> Description: '+"");}catch(e){console.log(e)}
                        try{$("#signature").html('<i class="fas fa-quote-left"></i> Description: '+data.data.profile.signature);}catch(e){console.log(e)}
                        console.log(Number(data.style));
                        $('#op0').prop({selected: "false",});
                        $('#op'+Number(data.style)).prop({selected: "true",});
                        $("#small_info").html("已绑定账号");
                        $("#user_panel").css('display','block');
                    }else{
                        $("#small_info").html("未绑定账号或者密码错误");
                    }
                    $("#loading").remove();
                    $("#mod_body").html(data.resp);
                    $('#exampleModalCenter').modal('show');
                })
                .fail(function() {
                    $("#loading").remove();
                    $("#mod_body").html('服务器超时，请重试！');
                    $('#exampleModalCenter').modal('show');
                });
        });
    </script>
</div>

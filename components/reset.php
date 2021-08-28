<div class="modal fade" id="reset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Reset</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body mx-3">
                    <div class="md-form mb-5" hidden>
                        <input type="text" id="hidden" class="form-control validate" name="reset" value="1">
                        <label data-error="wrong" data-success="right" for="hidden">hidden</label>
                    </div>
                    <div class="md-form mb-5">
                        <input type="email" id="reset-email" class="form-control validate" name="email">
                        <label data-error="wrong" data-success="right" for="reset-email">Your email</label>
                    </div>
                    <div class="md-form mb-5" style="display: flex">
                        <input type="text" id="reset_code" class="form-control validate" maxlength="4" name="code" style="margin: 0">
                        <img src="image.php" alt="code" id="image_reset">
                    </div>
                    <script type="text/javascript">
                        $(function(){
                            var t = Date.parse(new Date());
                            $('#image_register').prop({src: "image.php?t="+t,});
                            $('#image_reset').prop({src: "image.php?t="+t,});
                            try {
                               $('#image_login').prop({src: "image.php?t="+t,});
                            }
                            catch(err){
                                 console.log(err);
                            }
                            $('#image_reset').click(function(){
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
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-default" id="reset_btn">发送</button>
                </div>
            <script type="text/javascript">
                $(function(){
                    $('#reset_btn').click(function(event){
                        event.preventDefault();
                        var s = $('#reset-email').prop('value');
                        var p = $('#reset_code').prop('value');
                        if(s.length<5){
                            mdui.alert("请输入正确的邮箱地址！");
                            return
                        }
                        if(p.length<4){
                            mdui.alert("请输入正确的图片验证码！");
                            return
                        }
                        $('#reset_btn').prop({'disabled':true});
                        var tmp = 60;
                        var s = setInterval(function(){
                            if (tmp > 0) {
                                tmp-=1;
                                $('#reset_btn').html(tmp+'s后重发');
                                $('#reset_btn').prop({'disabled':true});
                                console.log(tmp);
                            }else{
                                clearInterval(s);
                                $('#reset_btn').html('<i class="fas fa-envelope pr-1"></i>');
                                $('#reset_btn').prop({'disabled':false});
                            }
                        },1000);
                        $.ajax({
                            url: 'api/api.php',
                            type: 'POST',
                            dataType: 'json',
                            data:{'sendmail':1,'reset':1,'mail':$("#reset-email").prop('value'),'img_code':$("#reset_code").prop('value')}
                        })
                            .done(function(data) {
                                if(data.code==200){
                                    mdui.alert(data.resp);
                                }else{
                                    clearInterval(s);
                                    $('#reset_btn').html('<i class="fas fa-envelope pr-1"></i>');
                                    $('#reset_btn').prop({'disabled':false});
                                    mdui.alert(data.resp);
                                }
                            })
                            .fail(function() {
                                clearInterval(s);
                                $('#reset_btn').html('<i class="fas fa-envelope pr-1"></i>');
                                $('#reset_btn').prop({'disabled':false});
                                mdui.alert('服务器超时！');
                            });
                    })
                });
            </script>
        </div>
    </div>
</div>
<a href="javascript:;" style="float: right;margin-top: 20px" data-toggle="modal" data-target="#reset">忘记密码？</a>

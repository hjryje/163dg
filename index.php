<?php include "components/load_db.php" ?>
<html lang="zh-cn" class="full-height">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="网易云,云签到,打卡,听歌,300首,自动,netease,自动打卡签到听歌300首,源码,开源" />
    <meta name="keywords" content="网易云,云签到,打卡,听歌,300首,自动,netease,自动打卡签到听歌300首,源码,开源" />
    <title><?php echo $web_title;?></title>
    <!-- MDB icon -->
    <link rel="icon" href="https://s1.music.126.net/style/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="static/fontawesome/css/all.css">
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <!-- Material Design Bootstrap -->
    <link rel="stylesheet" href="static/css/mdb.min.css">
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/css/mdui.min.css"
            integrity="sha384-cLRrMq39HOZdvE0j6yBojO4+1PrHfB7a9l5qLcmRm/fiWXYY+CndJPmyu5FV/9Tw"
            crossorigin="anonymous"
    />
    <link href="static/css/Artalk.css" rel="stylesheet">
    <script src="static/js/Artalk.js"></script>
    <!-- Font Awesome -->
    <script type="text/javascript" src="static/js/jquery.min.js"></script>
    <script type="text/javascript" src="static/js/mdui.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="static/js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="static/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="static/js/mdb.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <!-- Your custom styles (optional) -->
    <link rel="stylesheet" href="static/css/style.css">
</head>

<body>
<?php include "components/nav.php";?>
<?php if (isset($_GET['info'])){?>
    <div class="alert alert-warning alert-dismissible fade show fixed-top" role="alert">
        <strong>注意!</strong> <?php echo $_GET['info']?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php }?>
<div class="modal fade bottom" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

    <!-- Add class .modal-frame and then add class .modal-bottom (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-frame modal-bottom" role="document">


        <div class="modal-content">
            <div class="modal-body">
                <div class="row d-flex justify-content-center align-items-center">

                    <p class="pt-3 pr-2" id="ps">
                    </p>
                    <button type="button" class="btn btn-primary" id="reload">我知道了</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $("#reload").click(function () {
            location.reload();
        })
    });
</script>
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">

    <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">提示</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mod_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">知道了</button>
            </div>
        </div>
    </div>
</div>
<!--Main Layout-->
<main class="text-center py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                if (isset($_GET['comment'])){
                    include "components/comment.php";
                    exit("</div></div></div></main></body></html>");
                }elseif (isset($_GET['download'])){
                    include "components/download.php";
                    exit("</div></div></div></main></body></html>");
                }
                ?>
                <?php if ($is_install==0){
                    // 未安装
                    include "components/install.php";
                }else{ ?>
                    <?php
                    // 已安装
                    if (isset($_GET['reset_html'])){
                        include "components/reset_html.php";
                        exit("</div></div></div></main></body></html>");
                    }
                    if ($is_login==0){
                        //未登录
                        include "components/login.php";
                        include "components/register.php";
                        include "components/reset.php";
                        ?>
                    <?php }else{
                        // 已登录
                        if (isset($_GET['admin'])){
                            if ($is_admin==1){
                                include "components/admin.php";
                            }else{
                                exit("<script>window.location.replace('./index.php');</script></div></div></div></main></body></html>");
                            }
                        }else{
                            include "components/panel.php";
                        }
                        ?>
                    <?php }?>
                <?php }?>
            </div>
        </div>
    </div>

</main>

</body>


</html>

<!--Main Navigation-->
<header>

    <nav class="navbar fixed-top navbar-expand-lg navbar-dark scrolling-navbar">
        <a class="navbar-brand" href="./index.php"><strong><?php echo $web_name;?></strong></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item  <?php if(isset($_GET['admin']) or isset($_GET['comment'])){}else{?>active<?php } ?>">
                    <a class="nav-link" href="./index.php">主页
                        <?php if(isset($_GET['admin']) or isset($_GET['comment'])){}else{?>
                            <span class="sr-only">(current)</span>
                        <?php } ?>
                    </a>
                </li>
                <?php if ($is_admin==1){?>
                    <li class="nav-item  <?php if(isset($_GET['admin'])){?>active<?php } ?>">
                        <a class="nav-link" href="index.php?admin=1">管理员面板
                            <?php if(isset($_GET['admin'])){?>
                                <span class="sr-only">(current)</span>
                            <?php } ?>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item  <?php if(isset($_GET['comment'])){?>active<?php } ?>">
                    <a class="nav-link" href="index.php?comment">讨论
                        <?php if(isset($_GET['comment'])){?>
                            <span class="sr-only">(current)</span>
                        <?php } ?>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item ">
                    <a class="nav-link" href="http://www.xtbkw.cn/">
                        <i class="fab fa-github"></i>小天博客
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://yd.x06.xyz/">
                        <i class="fas fa-ad"></i> 天鸿运动宝</a>
                </li>
                <?php if ($is_login==1){?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> <?php echo $_SESSION['email'];?> </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-info" aria-labelledby="navbarDropdownMenuLink-4">
                            <!--                    <a class="dropdown-item" href="#">My account</a>-->
                            <a class="dropdown-item" href="index.php?logout=1">Log out</a>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <div class="view intro-2" style="">
        <div class="full-bg-img">
            <div class="mask rgba-purple-light flex-center">
                <div class="container text-center white-text wow fadeInUp">
                    <?php if (strlen($notice)<1){?>
                    <h2>网易云音乐</h2>
                    <br>
                    <h5>这是一个网易云音乐在线打卡签到平台</h5>
                    <p>每天自动打卡签到，听歌300首，无需人工操作</p>
                    <br>
                    <p>有问题请联系QQ：1665938639 </p>
                    <?php }else{ echo $notice; }?>
                </div>
            </div>
        </div>
    </div>

</header>



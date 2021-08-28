<div class="container">
    <div>
        <h4 style="display: inline-block">网易云打卡平台源码</h4><small style="vertical-align: top" class="badge badge-secondary">V1.0</small>
    </div>
    <div style="text-align: left;margin-top: 20px">
        <p>源码下载：<a href="https://d.storming.ltd/netease/" target="_blank">网易云打卡平台源码</a>&nbsp;&nbsp;版本V1.0&nbsp;&nbsp;<span style="color: red">2020/12/13 </span> 重大更新。<span style="color: red">修复了图片验证码的BUG，修复了打错账号的BUG</span>。</p>
        <p>版本说明：此版本与之前网上公开的版本并不兼容！（ 欢迎各位大佬找BUG</p>
        <p> PS:&nbsp;本人竟然是一名QQ音乐忠实用户&nbsp;&nbsp;<img src="https://i.loli.net/2019/02/01/5c53daa84f24a.png" alt="微笑默叹以为妙绝"></p>
        <hr>
        <h6>教程</h6>
        <br>
        <p>1.下载源码解压到网站根目录，然后访问你的网站，会自动安装</p>
        <p>2.配置定时任务，时间建议20分钟</p>
        <div style="padding-left: 20px;">
            <samp>
                cd 网站目录
                <br>
                <br>
                <small style="color: red"># 这里是换行</small>
                <br>
                <br>
                php cron.php
            </samp>
        </div>
        <br>
        <p>图示：</p>
        <img src="https://s3.ax1x.com/2020/11/14/DPQ5hF.png" class="img-fluid" alt="Responsive image" width="75%">
        <br>
        <br>
        <br>
        <p>定时任务日志里出现<div style="padding-left: 20px;"><pre>2020-12-08 03:32:13-打卡脚本启动...</pre></div>即可</p>
        <br>
        <p>3.关于设置里的reCAPTCHA <small style="color: red"> 2 </small>请在<a href="https://www.google.com/recaptcha" target="_blank">这里获取密钥(这个链接需要翻墙)</a> ，不开启reCAPTCHA则不用理会</p>
        <p>4.关于防止沙雕网友下载数据库(😎），请自行配置waf或者伪静态，伪静态规则如下（网址替换成你自己的）</p>
        <div style="padding-left: 20px;">
            <samp>
                rewrite .*db$  http://brain.storming.ltd/?info=滚!!!臭傻逼！下载尼玛数据库呢！ break;
            </samp>
        </div>
        <br>
        <p>5.API接口（请自建接口）<a href="https://zaincheung.gitee.io/netease-cloud/#/api/" target="_blank">这里获取API源码</a></p>
        <p>6.用国外小鸡儿搭网站记得修改时区和时间，改成北京时间。</p>
        <p>7.后台展示：</p>
        <br>
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" style="width:75%;">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img class="d-block w-100" src="https://i.loli.net/2020/12/09/aYZez74TwuNy6GK.png"
                alt="First slide">
            </div>
            <div class="carousel-item">
              <img class="d-block w-100" src="https://i.loli.net/2020/12/09/yjg9rlVI5cUCGEZ.png"
                alt="Second slide">
            </div>
            <div class="carousel-item">
              <img class="d-block w-100" src="https://i.loli.net/2020/12/09/aYZez74TwuNy6GK.png"
                alt="Third slide">
            </div>
            <div class="carousel-item">
              <img class="d-block w-100" src="https://i.loli.net/2020/12/09/ZOsgpbuDa1zjQcU.png"
                alt="Third slide">
            </div>
            <div class="carousel-item">
              <img class="d-block w-100" src="https://i.loli.net/2020/12/09/UMAQicj3yP8GfvS.png"
                alt="Third slide">
            </div>
            <div class="carousel-item">
              <img class="d-block w-100" src="https://i.loli.net/2020/12/09/uQO7d5gpsrwhz3k.png"
                alt="Third slide">
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        <div style="height:250px;">&nbsp;</div>
    </div>

</div>

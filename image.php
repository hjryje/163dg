<?php
include "image/src/CaptchaBuilderInterface.php";
include "image/src/CaptchaBuilder.php";
session_start();
use Minho\Captcha\CaptchaBuilder;

$captch = new CaptchaBuilder();

$captch->initialize([
    'width' => 120,     // 宽度
    'height' => 40,     // 高度
    'line' => false,    // 直线
    'curve' => true,    // 曲线
    'noise' => 1,       // 噪点背景
    'fonts' => []       // 字体
]);
$captch->create();
$_SESSION['img_code'] = $captch->getText();
$captch->output(1);


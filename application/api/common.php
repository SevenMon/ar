<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 数字验证码
 * @author hejianyu
 */
function numberCaptcha($length = 6)
{
    (int)$min = substr(10000000000, 0, $length);
    (int)$max = substr(99999999999, 0, $length);
    return rand($min, $max);
}

/**
 * 生成订单号
 * @author whh
 */
function orderNum()
{
    return chr(rand(65, 90)) . date('YmdHis', time()) . numberCaptcha(4);
}

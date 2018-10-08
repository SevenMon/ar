<?php /*a:1:{s:70:"C:\phpStudy\PHPTutorial\WWW\ar\application\login\view\index\index.html";i:1538966109;}*/ ?>
<!DOCTYPE html>
<html >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>YOUHOO</title>

    <!-- Bootstrap -->
    <link href="/ui/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/ui/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/ui/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="/ui/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="/ui/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="login">
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form method="post" action="<?php echo url('login/index/checkLogin'); ?>">
                    <h1>登录页面</h1>
                    <div>
                        <input name="username" type="text" class="form-control" id="username" placeholder="用户名" required="required" />
                    </div>
                    <div>
                        <input name="password" type="password" class="form-control" id="password" placeholder="密码" required="required"/>
                    </div>
                    <div>
                        <?php if($erromsg != ''): ?><p style="color: rgba(231,76,60,.88)"><?php echo htmlentities($erromsg); ?></p><?php endif; ?>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default">登录</button>
<!--                        <a class="reset_pass" href="#">Lost your password?</a>-->
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">

                        <div class="clearfix"></div>
                        <div>
                            <h1> YOUHOO</h1>
                            
                        </div>
                    </div>
                </form>
            </section>
        </div>


    </div>
</div>
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>

</body>
</html>

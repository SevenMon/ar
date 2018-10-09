<?php /*a:4:{s:76:"C:\phpStudy\PHPTutorial\WWW\ar\application\admin\view\projects\add_page.html";i:1539051853;s:65:"C:\phpStudy\PHPTutorial\WWW\ar\application\admin\view\layout.html";i:1539051853;s:72:"C:\phpStudy\PHPTutorial\WWW\ar\application\admin\view\public\header.html";i:1539051853;s:72:"C:\phpStudy\PHPTutorial\WWW\ar\application\admin\view\public\footer.html";i:1539051853;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico"/>

    <title>YOUHOO</title>

    <!-- Bootstrap -->
    <link href="/ui/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/ui/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/ui/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="/ui/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="/ui/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="/ui/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="/ui/vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="/ui/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="/ui/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>

    <link href="/ui/bootstrapdatetime/css/bootstrap-datetimepicker.min.css" rel="stylesheet">


    <!-- Dropzone.js -->
    <link href="/ui/vendors/dropzone/dist/min/dropzone.min.css" rel="stylesheet">
    <!-- imgupload -->
    <link href="/ui/imgupload/upload.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="/ui/build/css/custom.min.css" rel="stylesheet">
    <link href="/ui/docs/css/jquery-confirm.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="/ui/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/ui/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- imgupload.js -->
    <script src="/ui/imgupload/jQuery.upload.mini.js"></script>
    <script src="/ui/docs/js/jquery-confirm.min.js"></script>
	<!-- 弹出成 -->
	<link rel="stylesheet" href="/ui/build/css/jquery-confirm.min.css"/>
	<script type="text/javascript" src="/ui/build/js/jquery-confirm.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/ui/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/ui/ueditor/ueditor.all.min.js"> </script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="/ui/ueditor/lang/zh-cn/zh-cn.js"></script>
    
    <script src="/ui/vendors/moment/min/moment.min.js"></script>

    <script src="/ui/bootstrapdatetime/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/ui/bootstrapdatetime/js/bootstrap-datetimepicker.zh-CN.js"></script>


    <script type="text/javascript">
        // 全局变量
        var ueditor_config = {
            // 服务器URL地址
            server_url: "<?php echo url('Api/Ueditor/index'); ?>",
            // 高级配置
            senior_toolbars: [
                [
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'forecolor', 'backcolor', '|',
                    'fontsize', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'simpleupload', '|', 'insertimage',
                    'insertorderedlist', 'insertunorderedlist', '|',
                    //全屏
                    'fullscreen', 'source'
                ]
            ],
            senior_toolbars1: [
                [
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'forecolor', 'backcolor', '|',
                    'fontsize', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'simpleupload', '|', 'insertimage',
                    'insertorderedlist', 'insertunorderedlist', '|', 'insertvideo',
                    //全屏
                    'fullscreen', 'source'
                ]
            ],
            // 基本配置
            basic_toolbars: [
                [
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'forecolor', 'backcolor', '|',
                    'fontsize', '|',
                    'insertorderedlist', 'insertunorderedlist', '|',
                    //全屏
                    'source'
                ]
            ]
        };

        // 上传uploadify 插件配置
        var uploadify_config = {
            sessionId: "<?php echo cookie('id'); ?>",
            picTypeExts: '*.gif; *.jpg; *.png',
            fileTypeExts: '*.doc; *.docx; *.pdf; *.zip; *.rar; *.ext; *.xlsx; *.xls;',
            swf: "__UPLOAD__/uploadify.swf",
            buttonImage: "/admin/img/uploadPictureBtn.png",
            buttonFile: "/admin/img/uploadFileBtn.png"
        };
    </script>
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="index.html" class="site_title"> <span>YOUHOO</span></a>
                </div>

                <div class="clearfix"></div>
                <br/>

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <!--<h3>General</h3>-->
                        <ul class="nav side-menu">
                            <?php foreach (app\admin\model\Menu::manageLeft($top_menu_active, $left_menu_active) as $data){ ?>
                            <li class="<?php echo htmlentities($data['active']); ?>"><a><i class="fa fa-home"></i> <?php echo htmlentities($data['title']); ?> <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: <?php echo htmlentities($data['display']); ?>">
                                    <?php foreach ($data['children'] as $left){ ?>
                                    <li class="<?php echo htmlentities($left['css']); ?>">
                                        <a href="<?php echo url('/' . implode('/', explode('_', $left['url'])));?>" >
                                            <?php echo $left['label'];?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                               aria-expanded="false">admin
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="<?php echo url('login/Index/logout'); ?>"><i class="fa fa-sign-out pull-right"></i> 登出</a></li>
                            </ul>
                        </li>

                        <li role="presentation" class="dropdown">

                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>添加项目</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form data-parsley-validate="" data-parsley-required="" class="form-horizontal form-label-left" novalidate="" action="<?php echo url('admin/Projects/add'); ?>" method="post">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">名称 <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="title" id="title" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">项目内容 <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="content" required="required" class="form-control" name="content"></textarea>
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gameId">选择游戏<span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <select class="form-control" id="gameId" name="gameId">
                                <?php if(is_array($gameList) || $gameList instanceof \think\Collection || $gameList instanceof \think\Paginator): $k = 0; $__LIST__ = $gameList;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['title']); ?></option>
                                <?php endforeach; endif; else: echo "暂时没有数据" ;endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="partnerId">选择合作商<span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <select class="form-control" id="partnerId" name="partnerId">
                                <?php if(is_array($partnerList) || $partnerList instanceof \think\Collection || $partnerList instanceof \think\Paginator): $k = 0; $__LIST__ = $partnerList;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['name']); ?></option>
                                <?php endforeach; endif; else: echo "暂时没有数据" ;endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="brandId">根据品牌商选商品 <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <select class="form-control" id="brandId" name="brandId" onchange="changetype(this.options[this.options.selectedIndex].value)">
                                <?php if(is_array($brandList) || $brandList instanceof \think\Collection || $brandList instanceof \think\Paginator): $k = 0; $__LIST__ = $brandList;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['name']); ?></option>
                                <?php endforeach; endif; else: echo "暂时没有数据" ;endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <select class="form-control" id="waresId" name="waresId">
                                <?php if(is_array($waresslist) || $waresslist instanceof \think\Collection || $waresslist instanceof \think\Paginator): $k = 0; $__LIST__ = $waresslist;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['name']); ?></option>
                                <?php endforeach; endif; else: echo "暂时没有数据" ;endif; ?>
                            </select>
                        </div>
                    </div>

                    <!--<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">中奖领取方式 <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6" id="receive_type">
                            <script id="receive_content" type="text/plain" style="width:1024px;height:500px;">

                            </script>
                        </div>
                    </div>-->


                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button class="btn btn-primary" type="button" onclick="window.history.back(-1); ">返回</button>
                            <button class="btn btn-primary" type="reset">重置</button>
                            <button type="submit" class="btn btn-success">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    /*var ue = UE.getEditor('receive_content', {
        //authHeight : false
        toolbars : ueditor_config.senior_toolbars,
        initialFrameWidth : 800,
        initialFrameHeight : 300,
        enableAutoSave : false,
        saveInterval : 0,
        autoSyncData : false,
        //emotionLocalization : true,
        wordCount : true,
        elementPathEnabled : false,
        autoFloatEnabled : true,
        serverUrl : ueditor_config.server_url
    });*/
    function changetype(brandId) {
        var url = "<?php echo url('admin/Waress/getListByBrand'); ?>";
        $.ajax({
            url:url+ "?brand_id="+brandId,
            dataType:'json',
            async:true,
            success:function(res){
                var html = '';
                html += '';
                if(res.data.length > 0){
                    for (var i=0;i<res.data.length;i++)
                    {
                        html += '<option value="'+res.data[i].id+'">'+res.data[i].name+'</option>';
                    }
                }
                $('#waresId').html(html);
            }
        });
    }
</script>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <!--<footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>-->
        <!-- /footer content -->
    </div>
</div>

<!-- FastClick -->
<script src="/ui/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="/ui/vendors/nprogress/nprogress.js"></script>
<!-- Chart.js -->
<script src="/ui/vendors/Chart.js/dist/Chart.min.js"></script>
<!-- gauge.js -->
<script src="/ui/vendors/gauge.js/dist/gauge.min.js"></script>
<!-- bootstrap-progressbar -->
<script src="/ui/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<!-- iCheck -->
<script src="/ui/vendors/iCheck/icheck.min.js"></script>
<!-- Skycons -->
<script src="/ui/vendors/skycons/skycons.js"></script>
<!-- Flot -->
<script src="/ui/vendors/Flot/jquery.flot.js"></script>
<script src="/ui/vendors/Flot/jquery.flot.pie.js"></script>
<script src="/ui/vendors/Flot/jquery.flot.time.js"></script>
<script src="/ui/vendors/Flot/jquery.flot.stack.js"></script>
<script src="/ui/vendors/Flot/jquery.flot.resize.js"></script>
<!-- Flot plugins -->
<script src="/ui/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
<script src="/ui/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
<script src="/ui/vendors/flot.curvedlines/curvedLines.js"></script>
<!-- DateJS -->
<script src="/ui/vendors/DateJS/build/date.js"></script>
<!-- JQVMap -->
<script src="/ui/vendors/jqvmap/dist/jquery.vmap.js"></script>
<script src="/ui/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
<script src="/ui/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="/ui/vendors/moment/min/moment.min.js"></script>
<script src="/ui/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- bootstrap-wysiwyg -->
<script src="/ui/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
<script src="/ui/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
<script src="/ui/vendors/google-code-prettify/src/prettify.js"></script>

<!-- jQuery Tags Input -->
<script src="/ui/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
<!-- Switchery -->
<script src="/ui/vendors/switchery/dist/switchery.min.js"></script>
<!-- Select2 -->
<script src="/ui/vendors/select2/dist/js/select2.full.min.js"></script>
<!-- Parsley -->
<script src="/ui/vendors/parsleyjs/dist/parsley.js"></script>
<script src="/ui/vendors/parsleyjs/dist/i18n/zh_cn.js"></script>
<!-- Autosize -->
<script src="/ui/vendors/autosize/dist/autosize.min.js"></script>
<!-- jQuery autocomplete -->
<script src="/ui/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
<!-- starrr -->
<script src="/ui/vendors/starrr/dist/starrr.js"></script>
<!-- Dropzone.js -->
<script src="/ui/vendors/dropzone/dist/min/dropzone.min.js"></script>



<!-- Custom Theme Scripts -->
<script src="/ui/build/js/custom.js"></script>

</body>
</html>


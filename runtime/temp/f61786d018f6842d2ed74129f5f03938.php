<?php /*a:4:{s:71:"C:\phpStudy\PHPTutorial\WWW\ar\application\admin\view\gamear\index.html";i:1538990926;s:65:"C:\phpStudy\PHPTutorial\WWW\ar\application\admin\view\layout.html";i:1538966109;s:72:"C:\phpStudy\PHPTutorial\WWW\ar\application\admin\view\public\header.html";i:1538966109;s:72:"C:\phpStudy\PHPTutorial\WWW\ar\application\admin\view\public\footer.html";i:1538966109;}*/ ?>
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
            <style>
    .playnumber input{
        width: 150px;
        display: inline-block;
    }
    .defaultnumber{
        padding-top: 8px;
        line-height: 19px;
    }
    #adddata{
        margin-top: -7px;
    }
    .timeitem{
        margin-top: 20px;
    }
    .timeitem .btn{
        margin-bottom: 5px;
    }
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>ar扫描游戏次数设置</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form class="form-horizontal form-label-left" id="form">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">每日可玩次数设置<span class="required">*</span>
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <div class="defaultnumber">
                                <span class="red">默认情况下，用户可以无限次扫描进行收集,可按时间添加可玩次数设置！</span>
                                <button type="button" class="btn btn-primary btn-xs" id="adddata">添加</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8 col-sm-8 col-xs-12 playnumber col-md-offset-2">
                            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                                <div class="timeitem">
                                    开始：<input readonly class="controls input-append date form_datetime form-control" name="start_time" id="start_time_<?php echo htmlentities($k); ?>" type="text" value="<?php echo htmlentities($vo['start_time']); ?>" data-date="2018-09-01" data-date-format="yyyy mm dd" data-link-field="dtp_input1">
                                    结束：<input readonly class="controls input-append date form_datetime form-control" name="end_time" id="end_time_<?php echo htmlentities($k); ?>" type="text" value="<?php echo htmlentities($vo['end_time']); ?>" data-date="2018-09-01" data-date-format="yyyy mm dd" data-link-field="dtp_input1">
                                    次数：<input class="controls input-append date form_datetime form-control" name="play_number" id="play_number_<?php echo htmlentities($k); ?>" type="number" value="<?php echo htmlentities($vo['play_number']); ?>" >
                                    <button type="button" class="btn btn-danger" onclick="deleteItem($(this))">删除</button>
                                </div>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button class="btn btn-primary" type="button" onclick="window.history.back(-1); ">返回</button>
                            <button type="button" class="btn btn-success" id="submit">保存</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="x_title">
                <h2>ar扫描游戏规则编辑</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form class="form-horizontal form-label-left" id="ruleform">

                    <div class="form-group">
                        <div class="col-md-8 col-sm-8 col-xs-12 playnumber col-md-offset-2">
                            
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button class="btn btn-primary" type="button" onclick="window.history.back(-1); ">返回</button>
                            <button type="button" class="btn btn-success" id="rulesubmit">保存</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    $(function () {
        var list = '<?php echo json_encode($list); ?>';
        list = $.parseJSON( list );
        for(var key in list){
            $('#start_time_'+(parseInt(key)+1)).datetimepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayBtn: true,
                todayHighlight: true,
                showMeridian: true,
                pickerPosition: "bottom-left",
                startView: 2,
                minView: 2,
                language: 'zh-CN',
            });
            $('#end_time_'+(parseInt(key)+1)).datetimepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayBtn: true,
                todayHighlight: true,
                showMeridian: true,
                pickerPosition: "bottom-left",
                startView: 2,
                minView: 2,
                language: 'zh-CN',
            });
        }

        $('#adddata').click(function () {
            var count = $('.playnumber').children().length;

            var str = '<div class="timeitem">\n' +
                '开始：<input readonly class="controls input-append date form_datetime form-control" name="start_time" id="start_time_'+(count+1)+'" type="text" value="" data-date="2018-09-01" data-date-format="yyyy mm dd" data-link-field="dtp_input1">\n' +
                '结束：<input readonly class="controls input-append date form_datetime form-control" name="end_time" id="end_time_'+(count+1)+'" type="text" value="" data-date="2018-09-01" data-date-format="yyyy mm dd" data-link-field="dtp_input1">\n' +
                '次数：<input class="controls input-append date form_datetime form-control" name="play_number" id="play_number_'+(count+1)+'" type="number" value="" >\n' +
                '<button type="button" class="btn btn-danger" onclick="deleteItem($(this))">删除</button>\n' +
                '</div>';
            $('.playnumber').append(str);
            $('#start_time_'+(count+1)).datetimepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayBtn: true,
                todayHighlight: true,
                showMeridian: true,
                pickerPosition: "bottom-left",
                startView: 2,
                minView: 2,
                language: 'zh-CN',
            });
            $('#end_time_'+(count+1)).datetimepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayBtn: true,
                todayHighlight: true,
                showMeridian: true,
                pickerPosition: "bottom-left",
                startView: 2,
                minView: 2,
                language: 'zh-CN',
            });
        });
        $('#submit').click(function () {
            var check = true;
            //console.log($('#form').serializeArray());
            var data = $('#form').serializeArray();
            var tempdate = [];
            for(var i = 0;i < data.length;i++){
                if(i % 3 == 0){
                    tempdate.push({'start_time':data[i]['value'],'end_time':data[i+1]['value']});
                }
                if(data[i]['value'] == ''){
                    $.alert('请补充完整数据！');
                    check = false;
                    return false;
                }
            }
            if(!check){
                return false;
            }
            console.log(tempdate);

            var checkx = true;
            for(var i = 0;i < tempdate.length;i++){
                //判断开始不能大于结束
                console.log(tempdate[i]['start_time']);
                console.log(tempdate[i]['end_time']);
                if(tempdate[i]['start_time'] >= tempdate[i]['end_time']){
                    $.alert('开始时间不能大于等于结束时间！');
                    checkx = false;
                    return false;
                }

                for(var j = i - 1;j >= 0;j--){
                    if(
                        !((tempdate[j]['start_time'] >= tempdate[i]['start_time']
                        && tempdate[j]['start_time'] >= tempdate[i]['end_time'])
                       ||(tempdate[j]['end_time'] <= tempdate[i]['start_time']
                            && tempdate[j]['end_time'] <= tempdate[i]['end_time']))
                    ){
                        $.alert('每个时间段不能与其他时间段重合！');
                        checkx = false;
                        return false;
                    }
                }
                if(!checkx){
                    return false;
                }
            }
            if(!checkx){
                return false;
            }

            var url = "<?php echo url('admin/Gamear/ajaxUpdateParam'); ?>";
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data:{data:data},
                success:function(res){
                    if(res.code == 1){
                        $.alert({
                            title: '系统提示',
                            content: '保存成功',
                            icon: 'fa fa-comment',
                            type: 'green',
                        });
                    }else {
                        $.alert({
                            title: '系统提示',
                            content: '保存失败',
                            icon: 'fa fa-comment',
                            type: 'green',
                        });
                    }
                    console.log(res);
                },
                error:function () {
                    $.alert({
                        title: '系统提示',
                        content: '网络错误，请稍后再试！',
                        icon: 'fa fa-comment',
                        type: 'green',
                    });
                }
            });

        });

    });
    function deleteItem(obj) {
        obj.parent().remove();
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




// 改变原生checkbox 与 radio
iCheckInput();

// 上传网站LOGO
var url = $("#pictureBtn").attr('url');

$(function(){

    // validate验证 ===================================
    $('#addLabelForm').validate({
        //ajax提交信息
        submitHandler:function(form){
            form.submit();
        },
        errorClass : 'common-input-error',
        errorPlacement : function(error, element){
            $(element).removeClass('common-input-success').addClass('common-input-error').next('span').removeClass('warn-info-success').addClass('warn-info-error').html(error);
        },
        success : function(error, element){
            $(error).parent('span').removeClass('warn-info-error').addClass('warn-info-success').html('').prev('input.common-input').removeClass('common-input-error').addClass('common-input-success');
        }
    });


    //菜单名称验证
    $('#stro_name').rules('add',{
        required : true,
        rangelength : [2,20],
        messages : {
            required : '店铺名称不能为空',
            rangelength : '店铺名称长度为2~20'
        }
    });

    //店铺地不能为空
    $('#stro_address').rules('add',{
        required : true,
        messages : {
            required : '店铺地不能为空',
        }
    });

    //负责人必填
    $('#manager').rules('add',{
        required : true,
        messages : {
            required : '负责人必填',
        }
    });





    //联系电话必填
    $('#stro_tel').rules('add',{
        required : true,
        messages : {
            required : '联系电话必填',
        }
    });

    //账号必填
    $('#username').rules('add',{
        required : true,
        messages : {
            required : '账号必填',
        }
    });

    //密码必填
    $('#password').rules('add',{
        required : true,
        rangelength:[6,20],
        messages : {
            required : '密码必填',
            rangelength : '密码长度6~20',
        }
    });


    $('#password2').rules('add',{
        required : true,
        equalTo:"#password",
        messages : {
            required : '确认密码必填',
            equalTo : '两次密码不一致',
        }
    });



    // 手机号码验证
    $.validator.addMethod("isMobile", function(value, element) {
        var length = value.length;
        var mobile = /^1[34578]\d{9}$/;
        return this.optional(element) || (length == 11 && mobile.test(value));
    }, "请正确填写您的手机号码");


});
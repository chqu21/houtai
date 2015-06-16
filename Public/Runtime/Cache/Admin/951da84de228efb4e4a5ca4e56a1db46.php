<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"course_onecourse_edit_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminteacherlistEditDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
	$("#course_onecourse_edit_dialog_form_password").formValidator({empty:true,onShow:"不修改密码请留空",onFocus:"密码应该为6-20位之间"}).inputValidator({min:6,max:20,onError:"密码应该为6-20位之间"});
	$("#course_onecourse_edit_dialog_form_pwdconfirm").formValidator({onShow:"不修改密码请留空",onFocus:"请输入确认密码"}).compareValidator({desID:"course_onecourse_edit_dialog_form_password",operateor:"=",onError:"输入两次密码不一致"});
	$("#course_onecourse_edit_dialog_form_email").formValidator({onShow:"请输入E-mail",onFocus:"请输入E-mail"}).regexValidator({regExp:"email",dataType:"enum",onError:"E-mail格式错误"}).ajaxValidator({
		type : "post",
		url : "<?php echo U('Admin/public_checkEmail');?>",
		data : {email: function(){return $("#course_onecourse_edit_dialog_form_email").val()}, default: '<?php echo ($info["email"]); ?>'},
		datatype : "json",
		async:'false',
		success : function(data){
			var json = $.parseJSON(data);
            return json.status == 1 ? false : true;
		},
		onError : "该邮箱已经存在",
		onWait : "请稍候..."
	});
	$("#course_onecourse_edit_dialog_form_realname").formValidator({onShow:"请输入真实姓名",onFocus:"真实姓名应该为2-20位之间"}).inputValidator({min:2,max:20,empty:{leftEmpty:false,rightEmpty:false,emptyError:"姓名两边不能有空格"},onError:"真实姓名应该为2-20位之间"});
	$("#course_onecourse_edit_dialog_form_role").formValidator({onShow:"请选择角色",onFocus:"请选择角色"}).inputValidator({min:0,onError:"请选择角色"});
})
function adminteacherlistEditDialogFormSubmit(){
	$.post('<?php echo U('Course/courseEdit?id='.$info['course_id']);?>', $("#course_onecourse_edit_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			$('#course_onecourse_edit_dialog').dialog('close');
			adminMemberRefresh();
		}
	})
}
</script>
<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
    table.gridtable {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:#333333;
        border-width: 0px;
        border-color: #666666;
        border-collapse: collapse;
        margin-left: 15px;
    }
    table.gridtable th {
        border-width: 0px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
    }
    table.gridtable td {
        border-width: 0px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
    }
</style>

<form id="course_onecourse_edit_dialog_form">
<table class="gridtable">
    <tr>
        <td width="80">老师姓名：</td>
        <td><input type="text" name="info[teacher_name]" value="<?php echo ($info["teacher_name"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td width="80">课程名：</td>
        <td><input type="text" name="info[course_name]" value="<?php echo ($info["course_name"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>上课方式：</td>
        <td>
            <select id="class_method_id" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[class_method_id]" style="height:25px">
              <option value="1" <?php if($info["class_method_id"] == '1'): ?>selected<?php endif; ?>>一对一(学生上门)</option>
              <option value="2" <?php if($info["class_method_id"] == '2'): ?>selected<?php endif; ?>>一对一(教师外出)</option>
            </select>
        </td>
        <td></td>
    </tr>
    <tr>
        <td>课程类别：</td>
        <td>
            <select id="course_category" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[course_category]" style="height:25px">

                <option value="艺术" <?php if($info["course_category"] == '艺术'): ?>selected<?php endif; ?>>艺术</option>

                <option value="体育" <?php if($info["course_category"] == '体育'): ?>selected<?php endif; ?>>体育</option>
                <option value="高中" <?php if($info["course_category"] == '高中'): ?>selected<?php endif; ?>>高中</option>
                <option value="初中" <?php if($info["course_category"] == '初中'): ?>selected<?php endif; ?>>初中</option>
                <option value="小学" <?php if($info["course_category"] == '小学'): ?>selected<?php endif; ?>>小学</option>
                <option value="兴趣" <?php if($info["course_category"] == '兴趣'): ?>selected<?php endif; ?>>兴趣</option>
                <option value="学前" <?php if($info["course_category"] == '学前'): ?>selected<?php endif; ?>>学前</option>
            </select>
        </td>
        <td></td>
    </tr>
	<tr>
		<td>开始时间：</td>
        <td><input type="text" name="info[begin_time]"  class="easyui-datebox" value="<?php echo ($info["begin_time"]); ?>" style="width:180px;height:22px" /></td>
		<td></td>
	</tr>
    <tr>
        <td>结束时间：</td>
        <td><input type="text" name="info[end_time]" class="easyui-datebox" value="<?php echo ($info["end_time"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>是否显示：</td>
        <td>
            <?php if($info["display"] == 0): ?><input type="radio" value="0"  name="info[display]" checked="checked">&nbsp;&nbsp;未显示</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1"  name="info[display]">&nbsp;&nbsp;已显示</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php else: ?>
                <input type="radio" value="0" name="info[display]">&nbsp;&nbsp;未显示</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1" name="info[display]" checked="checked">&nbsp;&nbsp;已显示</input>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
    </tr>
	<tr>
		<td>总人数：</td>
        <td><input type="text" name="info[person_num]" value="<?php echo ($info["person_num"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
	</tr>
    <tr>
        <td>报名人数：</td>
        <td><input type="text" name="info[registration_number]"  value="<?php echo ($info["registration_number"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>区域：</td>
        <td><input type="text" name="info[area]"  value="<?php echo ($info["area"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>排序数字：</td>
        <td><input type="text" name="info[sort_num]"  value="<?php echo ($info["sort_num"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>价格：</td>
        <td><input type="text" name="info[price]"  value="<?php echo ($info["price"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>上课地址：</td>
        <td><input type="text" name="info[class_address]"  value="<?php echo ($info["class_address"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>课时数：</td>
        <td><input type="text" name="info[course_time]"  value="<?php echo ($info["course_time"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
</table>
</form>
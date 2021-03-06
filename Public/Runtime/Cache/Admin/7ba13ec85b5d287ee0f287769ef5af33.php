<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"admin_studentlist_edit_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminteacherlistEditDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
	$("#admin_studentlist_edit_dialog_form_password").formValidator({empty:true,onShow:"不修改密码请留空",onFocus:"密码应该为6-20位之间"}).inputValidator({min:6,max:20,onError:"密码应该为6-20位之间"});
	$("#admin_studentlist_edit_dialog_form_pwdconfirm").formValidator({onShow:"不修改密码请留空",onFocus:"请输入确认密码"}).compareValidator({desID:"admin_studentlist_edit_dialog_form_password",operateor:"=",onError:"输入两次密码不一致"});
	$("#admin_studentlist_edit_dialog_form_email").formValidator({onShow:"请输入E-mail",onFocus:"请输入E-mail"}).regexValidator({regExp:"email",dataType:"enum",onError:"E-mail格式错误"}).ajaxValidator({
		type : "post",
		url : "<?php echo U('Admin/public_checkEmail');?>",
		data : {email: function(){return $("#admin_studentlist_edit_dialog_form_email").val()}, default: '<?php echo ($info["email"]); ?>'},
		datatype : "json",
		async:'false',
		success : function(data){
			var json = $.parseJSON(data);
            return json.status == 1 ? false : true;
		},
		onError : "该邮箱已经存在",
		onWait : "请稍候..."
	});
	$("#admin_studentlist_edit_dialog_form_realname").formValidator({onShow:"请输入真实姓名",onFocus:"真实姓名应该为2-20位之间"}).inputValidator({min:2,max:20,empty:{leftEmpty:false,rightEmpty:false,emptyError:"姓名两边不能有空格"},onError:"真实姓名应该为2-20位之间"});
	$("#admin_studentlist_edit_dialog_form_role").formValidator({onShow:"请选择角色",onFocus:"请选择角色"}).inputValidator({min:0,onError:"请选择角色"});
})
function adminteacherlistEditDialogFormSubmit(){
	$.post('<?php echo U('Student/studentEdit?id='.$info['member_id']);?>', $("#admin_studentlist_edit_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			$('#admin_studentlist_edit_dialog').dialog('close');
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

<form id="admin_studentlist_edit_dialog_form">
<table class="gridtable">
	<tr>
		<td width="80">学生姓名：</td>
		<td><input type="text" name="info[real_name]" readonly value="<?php echo ($info["teacher_name"]); ?>" style="width:180px;height:22px" /></td>
		<td></td>
	</tr>
    <tr>
        <td>手机号：</td>
        <td><input id="admin_studentlist_add_dialog_form_mobile" type="text" name="info[mobile]" value="<?php echo ($info["mobile"]); ?>" style="width:180px;height:22px" /></td>
        <td><div id="admin_studentlist_add_dialog_form_mobileTip"></div></td>
    </tr>
    <tr>
        <td>密码：</td>
        <td><input id="admin_studentlist_add_dialog_form_password" type="password" name="info[password]" value="<?php echo ($info["password"]); ?>" style="width:180px;height:22px" /></td>
        <td><div id="admin_studentlist_add_dialog_form_roleTip"></div></td>
    </tr>
</table>
</form>
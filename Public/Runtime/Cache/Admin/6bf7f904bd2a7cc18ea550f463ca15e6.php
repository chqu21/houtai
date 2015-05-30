<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"admin_teacher_addAppraise_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminTeacherAddAppraiseDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
	$("#admin_teacherlist_edit_dialog_form_password").formValidator({empty:true,onShow:"不修改密码请留空",onFocus:"密码应该为6-20位之间"}).inputValidator({min:6,max:20,onError:"密码应该为6-20位之间"});
	$("#admin_teacherlist_edit_dialog_form_pwdconfirm").formValidator({onShow:"不修改密码请留空",onFocus:"请输入确认密码"}).compareValidator({desID:"admin_teacherlist_edit_dialog_form_password",operateor:"=",onError:"输入两次密码不一致"});
	$("#admin_teacherlist_edit_dialog_form_email").formValidator({onShow:"请输入E-mail",onFocus:"请输入E-mail"}).regexValidator({regExp:"email",dataType:"enum",onError:"E-mail格式错误"}).ajaxValidator({
		type : "post",
		url : "<?php echo U('Admin/public_checkEmail');?>",
		data : {email: function(){return $("#admin_teacherlist_edit_dialog_form_email").val()}, default: '<?php echo ($info["email"]); ?>'},
		datatype : "json",
		async:'false',
		success : function(data){
			var json = $.parseJSON(data);
            return json.status == 1 ? false : true;
		},
		onError : "该邮箱已经存在",
		onWait : "请稍候..."
	});
	$("#admin_teacherlist_edit_dialog_form_realname").formValidator({onShow:"请输入真实姓名",onFocus:"真实姓名应该为2-20位之间"}).inputValidator({min:2,max:20,empty:{leftEmpty:false,rightEmpty:false,emptyError:"姓名两边不能有空格"},onError:"真实姓名应该为2-20位之间"});
	$("#admin_teacherlist_edit_dialog_form_role").formValidator({onShow:"请选择角色",onFocus:"请选择角色"}).inputValidator({min:0,onError:"请选择角色"});
})
function adminTeacherAddAppraiseDialogFormSubmit(){
	$.post('<?php echo U('Teacher/addAppraise?id='.$info['teacher_id']);?>', $("#admin_teacher_addAppraise_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			$('#admin_teacher_addAppraise_dialog_form').dialog('close');
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

<form id="admin_teacher_addAppraise_dialog_form">

<table class="gridtable">
    <tr>
        <td>老师姓名：</td>
        <td><input id="admin_addAppraise_add_dialog_form_teacher_name" type="text" readonly name="info[teacher_name]" value="<?php echo ($teacherName); ?>" style="width:280px;height:22px" />
            <input id="admin_addAppraise_add_dialog_form_teacher_id" type="hidden" name="info[teacher_id]" value="<?php echo ($teacherId); ?>" style="width:180px;height:22px" />
        </td>
        <td>评价人:</td>
        <td><input id="admin_addAppraise_add_dialog_form_member_name" type="text" name="info[member_name]" style="width:100px;height:22px" /></td>
        <td><div id="admin_addAppraise_add_dialog_form_membernameTip"></div></td>
    </tr>
	<tr>
		<td width="80">环境与设施：</td>
        <td><textarea id="admin_addAppraise_add_dialog_form_type1_content" type="text" name="info[type1][content]" style="width:280px;height:42px" /></textarea></td>
        <td>评分：</td>
        <td><input id="admin_addAppraise_add_dialog_form_type1_score" type="text" name="info[type1][score]" style="width:100px;height:22px" /></td>
        <td><div id="admin_addAppraise_add_dialog_form_realnameTip"></div></td>
	</tr>
	<tr>
		<td>教学过程：</td>
        <td><textarea id="admin_addAppraise_add_dialog_form_type2_content" type="text" name="info[type2][content]" style="width:280px;height:42px" /></textarea></td>
        <td>评分：</td>
        <td><input id="admin_addAppraise_add_dialog_form_type2_score" type="text" name="info[type2][score]" style="width:100px;height:22px" /></td>
	</tr>
    <tr>
        <td>教学效果：</td>
        <td><textarea id="admin_addAppraise_add_dialog_form_type3_content" type="text" name="info[type3][content]" style="width:280px;height:42px" /></textarea></td>
        <td>评分：</td>
        <td><input id="admin_addAppraise_add_dialog_form_type3_score" type="text" name="info[type3][score]" style="width:100px;height:22px" /></td>
    </tr>
	<tr>
		<td>服务质量：</td>
        <td><textarea id="admin_addAppraise_add_dialog_form_type4_content" type="text" name="info[type4][content]" style="width:280px;height:42px" /></textarea></td>
        <td>评分：</td>
        <td><input id="admin_addAppraise_add_dialog_form_type4_score" type="text" name="info[type4][score]" style="width:100px;height:22px" /></td>
	</tr>
	<tr>
		<td>作业与答疑：</td>
        <td><textarea id="admin_addAppraise_add_dialog_form_type5_content" type="text" name="info[type5][content]" style="width:280px;height:42px" /></textarea></td>
        <td>评分：</td>
        <td><input id="admin_addAppraise_add_dialog_form_type5_score" type="text" name="info[type5][score]" style="width:100px;height:22px" /></td>
        <td><div id="admin_addAppraise_add_dialog_form_type5Tip"></div></td>
	</tr>
</table>
</form>
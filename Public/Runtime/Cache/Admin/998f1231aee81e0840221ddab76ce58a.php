<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"admin_time_add_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminteacherlistAddDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
	$("#admin_teacherlist_add_dialog_form_username").formValidator({onShow:"请输入用户名",onFocus:"用户名应该为2-20位之间"}).inputValidator({min:2,max:20,onError:"用户名应该为2-20位之间"}).ajaxValidator({
		type : "post",
		url : "<?php echo U('Admin/public_checkName');?>",
		data : {name:function(){return $("#admin_teacherlist_add_dialog_form_username").val()} },
		datatype : "json",
		async:'false',
		success : function(data){
			var json = $.parseJSON(data);
            return json.status == 1 ? false : true;
		},
		onError : "用户名已存在",
		onWait : "请稍候..."
	});
	$("#admin_teacherlist_add_dialog_form_password").formValidator({onShow:"请输入密码",onFocus:"密码应该为6-20位之间"}).inputValidator({min:6,max:20,onError:"密码应该为6-20位之间"});
	$("#admin_teacherlist_add_dialog_form_pwdconfirm").formValidator({onShow:"请输入确认密码",onFocus:"请输入确认密码"}).compareValidator({desID:"admin_teacherlist_add_dialog_form_password",operateor:"=",onError:"输入两次密码不一致"});
	$("#admin_teacherlist_add_dialog_form_email").formValidator({onShow:"请输入E-mail",onFocus:"请输入E-mail"}).regexValidator({regExp:"email",dataType:"enum",onError:"E-mail格式错误"});
	$("#admin_teacherlist_add_dialog_form_realname").formValidator({onShow:"请输入真实姓名",onFocus:"真实姓名应该为2-20位之间"}).inputValidator({min:2,max:20,empty:{leftEmpty:false,rightEmpty:false,emptyError:"姓名两边不能有空格"},onError:"真实姓名应该为2-20位之间"});
	$("#admin_teacherlist_add_dialog_form_role").formValidator({onShow:"请选择角色",onFocus:"请选择角色"}).inputValidator({min:0,onError:"请选择角色"});
})
function adminteacherlistAddDialogFormSubmit(){
	$.post('<?php echo U('Teacher/timeAdd');?>', $("#admin_time_add_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			$('#admin_time_add_dialog').dialog('close');
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
<form id="admin_time_add_dialog_form">
<table class="gridtable">
	<tr>
		<td width="80">教师id：</td>
		<td><input id="admin_time_add_dialog_form_teacherid" type="text" name="info[teacher_id]" style="width:180px;height:22px" /></td>
        <td>所有老师：</td>
        <td>
            <select id="admin_time_add_dialog_form_teacherAll" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[teacherAll]" style="height:25px">
                <option value="1">是</option>
                <option value="0">否</option>
            </select>
        </td>
        <td><div id="admin_time_add_dialog_form_teacheridTip"></div></td>
	</tr>
	<tr>
		<td>日期：</td>
		<td><input id="admin_time_add_dialog_form_class_date" class="easyui-datebox" name="info[class_date]" style="width:180px;height:22px" /></td>
		<td><div id="admin_time_add_dialog_form_classdateTip"></div></td>
	</tr>
	<tr>
		<td>时间段：</td>
		<td colspan="4">
                <label><input type="checkbox" name="info[class_time][]" value="07:00-07:30">07:00-07:30</label>
                <label><input type="checkbox" name="info[class_time][]" value="07:30-08:00">07:30-08:00</label>
                <label><input type="checkbox" name="info[class_time][]" value="08:00-08:30">08:00-08:30</label>
                <label><input type="checkbox" name="info[class_time][]" value="08:30-09:00">08:30-09:00</label><br/>
                <label><input type="checkbox" name="info[class_time][]" value="09:00-09:30">09:00-09:30</label>
                <label><input type="checkbox" name="info[class_time][]" value="09:30-10:00">09:30-10:00</label>
                <label><input type="checkbox" name="info[class_time][]" value="10:00-10:30">10:00-10:30</label>
                <label><input type="checkbox" name="info[class_time][]" value="11:00-11:30">11:00-11:30</label><br/>
                <label><input type="checkbox" name="info[class_time][]" value="11:30-12:00">11:30-12:00</label>
                <label><input type="checkbox" name="info[class_time][]" value="12:00-12:30">12:00-12:30</label>
                <label><input type="checkbox" name="info[class_time][]" value="12:30-13:00">12:30-13:00</label>
                <label><input type="checkbox" name="info[class_time][]" value="13:00-13:30">13:00-13:30</label><br/>
                <label><input type="checkbox" name="info[class_time][]" value="13:30-14:00">13:30-14:00</label>
                <label><input type="checkbox" name="info[class_time][]" value="14:00-14:30">14:00-14:30</label>
                <label><input type="checkbox" name="info[class_time][]" value="14:30-15:00">14:30-15:00</label>
                <label><input type="checkbox" name="info[class_time][]" value="15:00-15:30">15:00-15:30</label><br/>
                <label><input type="checkbox" name="info[class_time][]" value="16:00-16:30">16:00-16:30</label>
                <label><input type="checkbox" name="info[class_time][]" value="16:30-17:00">16:30-17:00</label>
                <label><input type="checkbox" name="info[class_time][]" value="17:00-17:30">17:00-17:30</label>
                <label><input type="checkbox" name="info[class_time][]" value="17:30-18:00">17:30-18:00</label><br/>
                <label><input type="checkbox" name="info[class_time][]" value="18:00-18:30">18:00-18:30</label>
                <label><input type="checkbox" name="info[class_time][]" value="18:30-19:00">18:30-19:00</label>
                <label><input type="checkbox" name="info[class_time][]" value="19:00-19:30">19:00-19:30</label>
                <label><input type="checkbox" name="info[class_time][]" value="20:00-20:30">20:00-20:30</label><br/>
                <label><input type="checkbox" name="info[class_time][]" value="20:30-21:00">20:30-21:00</label>
                <label><input type="checkbox" name="info[class_time][]" value="21:00-21:30">21:00-21:30</label>
                <label><input type="checkbox" name="info[class_time][]" value="21:30-22:00">21:30-22:00</label>
		</td>
		<td><div id="admin_time_add_dialog_form_pwdconfirmTip"></div></td>
	</tr>
	<tr>
		<td>全时间段：</td>
		<td>
			<select id="admin_time_add_dialog_form_classTimeAll" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[classTimeAll]" style="height:25px">
				<option value="1">是</option>
                <option value="0">否</option>
			</select>
		</td>
		<td><div id="admin_time_add_dialog_form_roleTip"></div></td>
	</tr>
</table>
</form>
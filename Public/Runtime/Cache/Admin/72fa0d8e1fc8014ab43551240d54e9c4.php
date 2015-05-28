<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"admin_teacherlist_add_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminteacherlistAddDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
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
	$.post('<?php echo U('Teacher/teacherAdd');?>', $("#admin_teacherlist_add_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			$('#admin_teacherlist_add_dialog').dialog('close');
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
<form id="admin_teacherlist_add_dialog_form">
<table class="gridtable">
	<tr>
		<td width="80">教师姓名：</td>
		<td><input id="admin_teacherlist_add_dialog_form_teachername" type="text" name="info[teacher_name]" style="width:180px;height:22px" /></td>
		<td><div id="admin_teacherlist_add_dialog_form_usernameTip"></div></td>
	</tr>
	<tr>
		<td>密码：</td>
		<td><input id="admin_teacherlist_add_dialog_form_password" type="password" name="info[password]" style="width:180px;height:22px" /></td>
		<td><div id="admin_teacherlist_add_dialog_form_passwordTip"></div></td>
	</tr>
	<tr>
		<td>确认密码：</td>
		<td><input id="admin_teacherlist_add_dialog_form_pwdconfirm" type="password" name="info[pwdconfirm]" style="width:180px;height:22px" /></td>
		<td><div id="admin_teacherlist_add_dialog_form_pwdconfirmTip"></div></td>
	</tr>
    <!--
	<tr>
		<td>E-mail：</td>
		<td><input id="admin_teacherlist_add_dialog_form_email" type="text" name="info[email]" style="width:180px;height:22px" /></td>
		<td><div id="admin_teacherlist_add_dialog_form_emailTip"></div></td>
	</tr>
	-->
	<tr>
		<td>真实姓名：</td>
		<td><input id="admin_teacherlist_add_dialog_form_realname" type="text" name="info[real_name]" style="width:180px;height:22px" /></td>
		<td><div id="admin_teacherlist_add_dialog_form_realnameTip"></div></td>
	</tr>
    <tr>
        <td>手机号：</td>
        <td><input id="admin_teacherlist_add_dialog_form_mobile" type="text" name="info[mobile]" style="width:180px;height:22px" /></td>
        <td><div id="admin_teacherlist_add_dialog_form_mobileTip"></div></td>
    </tr>
	<tr>
		<td>教师类型：</td>
		<td>
			<select id="admin_teacherlist_add_dialog_form_teacherType" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[teacher_type]" style="height:25px">
				<option value="专职教师">专职教师</option>
                <option value="教师工作室">教师工作室</option>
                <option value="机构老师">机构老师</option>
                <option value="学校老师">学校老师</option>
			</select>
		</td>
		<td><div id="admin_teacherlist_add_dialog_form_roleTip"></div></td>
	</tr>
    <tr>
        <td>教龄：</td>
        <td><input type="text" name="teaching_age" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>姓别：</td>
        <td>
        <input type="radio" value="男" name="sex">&nbsp;&nbsp;男</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="女" name="sex" checked="checked">&nbsp;&nbsp;女</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    <tr>
        <td>身份认证：</td>
        <td>
        <input type="radio" value="0" name="certification_flag">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="1" name="certification_flag" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    <tr>
        <td>学历认证：</td>
        <td>
        <input type="radio" value="0" name="education_flag">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="1" name="education_flag" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    <tr>
        <td>教师资格认证：</td>
        <td>
        <input type="radio" value="0" name="teacher_certification_flag">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="1" name="teacher_certification_flag" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    <tr>
        <td>是否推荐：</td>
        <td>
        <input type="radio" value="0" name="recommand_flag">&nbsp;&nbsp;未推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="1" name="recommand_flag" checked="checked">&nbsp;&nbsp;已推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    </tr>
    <tr>
        <td>是否显示：</td>
        <td>
        <input type="radio" value="0" name="display">&nbsp;&nbsp;未显示</input>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="1" name="display" checked="checked">&nbsp;&nbsp;已显示</input>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    <tr>
        <td>获得的荣誉：</td>
        <td><input type="text" name="honor" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>科目：</td>
        <td><input type="text" name="discipline" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>年级：</td>
        <td><input type="text" name="grade"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>课程分类：</td>
        <td><input type="text" name="course_category"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>排序数字：</td>
        <td><input type="text" name="sort_num"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>价格：</td>
        <td><input type="text" name="price"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>总评分：</td>
        <td><input type="text" name="score" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>名言警句：</td>
        <td><input type="text" name="catchphrase"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>学生数：</td>
        <td><input type="text" name="students_number" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>课时数：</td>
        <td><input type="text" name="class_hours_number" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>上课方式：</td>
        <td><input type="text" name="class_method" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
</table>
</form>
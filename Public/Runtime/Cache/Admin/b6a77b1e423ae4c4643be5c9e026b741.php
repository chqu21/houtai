<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"admin_memberlist_edit_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminMemberlistEditDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
	$("#admin_memberlist_edit_dialog_form_password").formValidator({empty:true,onShow:"不修改密码请留空",onFocus:"密码应该为6-20位之间"}).inputValidator({min:6,max:20,onError:"密码应该为6-20位之间"});
	$("#admin_memberlist_edit_dialog_form_pwdconfirm").formValidator({onShow:"不修改密码请留空",onFocus:"请输入确认密码"}).compareValidator({desID:"admin_memberlist_edit_dialog_form_password",operateor:"=",onError:"输入两次密码不一致"});
	$("#admin_memberlist_edit_dialog_form_email").formValidator({onShow:"请输入E-mail",onFocus:"请输入E-mail"}).regexValidator({regExp:"email",dataType:"enum",onError:"E-mail格式错误"}).ajaxValidator({
		type : "post",
		url : "<?php echo U('Admin/public_checkEmail');?>",
		data : {email: function(){return $("#admin_memberlist_edit_dialog_form_email").val()}, default: '<?php echo ($info["email"]); ?>'},
		datatype : "json",
		async:'false',
		success : function(data){
			var json = $.parseJSON(data);
            return json.status == 1 ? false : true;
		},
		onError : "该邮箱已经存在",
		onWait : "请稍候..."
	});
	$("#admin_memberlist_edit_dialog_form_realname").formValidator({onShow:"请输入真实姓名",onFocus:"真实姓名应该为2-20位之间"}).inputValidator({min:2,max:20,empty:{leftEmpty:false,rightEmpty:false,emptyError:"姓名两边不能有空格"},onError:"真实姓名应该为2-20位之间"});
	$("#admin_memberlist_edit_dialog_form_role").formValidator({onShow:"请选择角色",onFocus:"请选择角色"}).inputValidator({min:0,onError:"请选择角色"});
})
function adminMemberlistEditDialogFormSubmit(){
	$.post('<?php echo U('Teacher/teacherEdit?id='.$info['teacher_id']);?>', $("#admin_memberlist_edit_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			$('#admin_memberlist_edit_dialog').dialog('close');
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

<form id="admin_memberlist_edit_dialog_form">
<table class="gridtable">
	<tr>
		<td width="80">教师姓名：</td>
		<td><input type="text" value="<?php echo ($info["teacher_name"]); ?>" readonly style="width:180px;height:22px" /></td>
		<td></td>
	</tr>
	<tr>
		<td>教龄：</td>
        <td><input type="text" value="<?php echo ($info["teaching_age"]); ?>" readonly style="width:180px;height:22px" /></td>
		<td></td>
	</tr>
    <tr>
        <td>姓别：</td>
        <td>
            <?php if($info["sex"] == 男): ?><input type="radio" value="男"  name="sex" checked="checked">&nbsp;&nbsp;男</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="女"  name="sex">&nbsp;&nbsp;女</input>
                <?php else: ?>
                <input type="radio" value="男" name="sex">&nbsp;&nbsp;男</input>
                <input type="radio" value="女" name="sex" checked="checked">&nbsp;&nbsp;女</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
    </tr>
	<tr>
		<td>身份认证：</td>
        <td>
            <?php if($info["certification_flag"] == 0): ?><input type="radio" value="0"  name="certification_flag" checked="checked">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1"  name="certification_flag">&nbsp;&nbsp;已认证</input>
                <?php else: ?>
                <input type="radio" value="0" name="certification_flag">&nbsp;&nbsp;未认证</input>
                <input type="radio" value="1" name="certification_flag" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
	</tr>
	<tr>
		<td>学历认证：</td>
        <td>
            <?php if($info["education_flag"] == 0): ?><input type="radio" value="0"  name="education_flag" checked="checked">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1"  name="education_flag">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php else: ?>
                <input type="radio" value="0" name="education_flag">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1" name="education_flag" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
	</tr>
	<tr>
		<td>教师资格认证：</td>
        <td>
        <?php if($info["teacher_certification_flag"] == 0): ?><input type="radio" value="0"  name="teacher_certification_flag" checked="checked">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" value="1"  name="teacher_certification_flag">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        <?php else: ?>
            <input type="radio" value="0" name="teacher_certification_flag">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" value="1" name="teacher_certification_flag" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
	</tr>
    <tr>
        <td>是否推荐：</td>
        <td>
            <?php if($info["recommand_flag"] == 0): ?><input type="radio" value="0"  name="recommand_flag" checked="checked">&nbsp;&nbsp;未推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1"  name="recommand_flag">&nbsp;&nbsp;已推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php else: ?>
                <input type="radio" value="0" name="recommand_flag">&nbsp;&nbsp;未推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1" name="recommand_flag" checked="checked">&nbsp;&nbsp;已推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
    </tr>
    </tr>
    <tr>
        <td>是否显示：</td>
        <td>
            <?php if($info["display"] == 0): ?><input type="radio" value="0"  name="display" checked="checked">&nbsp;&nbsp;未显示</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1"  name="display">&nbsp;&nbsp;已显示</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php else: ?>
                <input type="radio" value="0" name="display">&nbsp;&nbsp;未显示</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1" name="display" checked="checked">&nbsp;&nbsp;已显示</input>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
    </tr>
	<tr>
		<td>获得的荣誉：</td>
        <td><input type="text" name="teaching_age" value="<?php echo ($info["teaching_age"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
	</tr>
    <tr>
        <td>科目：</td>
        <td><input type="text" name="discipline"  value="<?php echo ($info["discipline"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>年级：</td>
        <td><input type="text" name="grade"  value="<?php echo ($info["grade"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>课程分类：</td>
        <td><input type="text" name="course_category"  value="<?php echo ($info["course_category"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>排序数字：</td>
        <td><input type="text" name="sort_num"  value="<?php echo ($info["sort_num"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>价格：</td>
        <td><input type="text" name="price"  value="<?php echo ($info["price"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>总评分：</td>
        <td><input type="text" name="score"  value="<?php echo ($info["score"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>名言警句：</td>
        <td><input type="text" name="catchphrase"  value="<?php echo ($info["catchphrase"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>学生数：</td>
        <td><input type="text" name="students_number"  value="<?php echo ($info["students_number"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>课时数：</td>
        <td><input type="text" name="class_hours_number"  value="<?php echo ($info["class_hours_number"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>上课方式：</td>
        <td><input type="text" name="class_method"  value="<?php echo ($info["class_method"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
</table>
</form>
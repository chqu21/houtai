<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"admin_teacherlist_edit_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminteacherlistEditDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
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
function adminteacherlistEditDialogFormSubmit(){
	$.post('<?php echo U('Teacher/teacherEdit?id='.$info['teacher_id']);?>', $("#admin_teacherlist_edit_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			$('#admin_teacherlist_edit_dialog').dialog('close');
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

<form id="admin_teacherlist_edit_dialog_form">
<table class="gridtable">
    <tr>
        <td width="80">真实姓名：</td>
        <td><input type="text" name="info[real_name]" value="<?php echo ($info["teacher_name"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td width="80">显示姓名：</td>
        <td><input type="text" name="info[teacher_name]" value="<?php echo ($info["teacher_name"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>手机号：</td>
        <td><input id="admin_teacherlist_add_dialog_form_mobile" type="text" name="info[mobile]" value="<?php echo ($info["mobile"]); ?>" style="width:180px;height:22px" /></td>
        <td><div id="admin_teacherlist_add_dialog_form_mobileTip"></div></td>
    </tr>
    <tr>
        <td>教师类型：</td>
        <td>
            <select id="admin_teacherlist_add_dialog_form_teacherType" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[teacher_type]" style="height:25px">
                <?php if($info["teacher_type"] == '专职教师'): ?><option value="专职教师" selected = "selected">专职教师</option>
                        <option value="教师工作室">教师工作室</option>
                        <option value="机构老师">机构老师</option>
                        <option value="学校老师">学校老师</option>
                    <?php elseif($info["teacher_type"] == '教师工作室'): ?>
                        <option value="专职教师">专职教师</option>
                        <option value="教师工作室" selected = "selected">教师工作室</option>
                        <option value="机构老师">机构老师</option>
                        <option value="学校老师">学校老师</option>
                    <?php elseif($info["teacher_type"] == '机构老师'): ?>
                        <option value="专职教师">专职教师</option>
                        <option value="教师工作室">教师工作室</option>
                        <option value="机构老师" selected = "selected">机构老师</option>
                        <option value="学校老师">学校老师</option>
                    <?php elseif($info["teacher_type"] == '学校老师'): ?>
                        <option value="专职教师">专职教师</option>
                        <option value="教师工作室">教师工作室</option>
                        <option value="机构老师">机构老师</option>
                        <option value="学校老师" selected = "selected">学校老师</option><?php endif; ?>
            </select>
        </td>
        <td><div id="admin_teacherlist_add_dialog_form_roleTip"></div></td>
    </tr>
    <tr>
        <td>上课方式：</td>
        <td>
            <select id="class_method" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[class_method]" style="height:25px">
                        <option value="1"  <?php if($info["class_method"] == '1'): ?>selected<?php endif; ?>>一对一(学生上门)</option>
                        <option value="2" <?php if($info["class_method"] == '2'): ?>selected<?php endif; ?>>一对一(教师外出)</option>
                        <option value="3" <?php if($info["class_method"] == '3'): ?>selected<?php endif; ?>>小组课（2～5人,学生上门）</option>
                        <option value="4" <?php if($info["class_method"] == '4'): ?>selected<?php endif; ?>>小班课（6～10人,学生上门）</option>
                        <option value="5" <?php if($info["class_method"] == '5'): ?>selected<?php endif; ?>>大班课（10人以上,学生上门)</option>
            </select>
        </td>
        <td></td>
    </tr>
    <tr>
        <td>课程分类：</td>
        <td>
            <select id="course_category" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[course_category]" style="height:25px">
                <option value="艺术"  <?php if($info["course_category"] == '艺术'): ?>selected<?php endif; ?>>艺术</option>
                <option value="体育"  <?php if($info["course_category"] == '体育'): ?>selected<?php endif; ?>>体育</option>
                <option value="高中"  <?php if($info["course_category"] == '高中'): ?>selected<?php endif; ?>>高中</option>
                <option value="初中"  <?php if($info["course_category"] == '初中'): ?>selected<?php endif; ?>>初中</option>
                <option value="小学"  <?php if($info["course_category"] == '小学'): ?>selected<?php endif; ?>>小学</option>
                <option value="兴趣"  <?php if($info["course_category"] == '兴趣'): ?>selected<?php endif; ?>>兴趣</option>
                <option value="学前"  <?php if($info["course_category"] == '学前'): ?>selected<?php endif; ?>>学前</option>
            </select>
        </td>
        <td></td>
    </tr>
	<tr>
		<td>教龄：</td>
        <td><input type="text" name="info[teaching_age]" value="<?php echo ($info["teaching_age"]); ?>" style="width:180px;height:22px" /></td>
		<td></td>
	</tr>
    <tr>
        <td>姓别：</td>
        <td>
            <?php if($info["sex"] == 男): ?><input type="radio" value="男"  name="info[sex]" checked="checked">&nbsp;&nbsp;男</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="女"  name="info[sex]">&nbsp;&nbsp;女</input>
                <?php else: ?>
                <input type="radio" value="男" name="info[sex]">&nbsp;&nbsp;男</input>
                <input type="radio" value="女" name="info[sex]" checked="checked">&nbsp;&nbsp;女</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
    </tr>
	<tr>
		<td>身份认证：</td>
        <td>
            <?php if($info["certification_flag"] == 0): ?><input type="radio" value="0"  name="info[certification_flag]" checked="checked">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1"  name="info[certification_flag]">&nbsp;&nbsp;已认证</input>
                <?php else: ?>
                <input type="radio" value="0" name="info[certification_flag]">&nbsp;&nbsp;未认证</input>
                <input type="radio" value="1" name="info[certification_flag]" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
	</tr>
	<tr>
		<td>学历认证：</td>
        <td>
            <?php if($info["education_flag"] == 0): ?><input type="radio" value="0"  name="info[education_flag]" checked="checked">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1"  name="info[education_flag]">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php else: ?>
                <input type="radio" value="0" name="info[education_flag]">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1" name="info[education_flag]" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
	</tr>
	<tr>
		<td>教师资格认证：</td>
        <td>
        <?php if($info["teacher_certification_flag"] == 0): ?><input type="radio" value="0"  name="info[teacher_certification_flag]" checked="checked">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" value="1"  name="info[teacher_certification_flag]">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        <?php else: ?>
            <input type="radio" value="0" name="info[teacher_certification_flag]">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" value="1" name="info[teacher_certification_flag]" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
	</tr>
    <tr>
        <td>是否推荐：</td>
        <td>
            <?php if($info["recommand_flag"] == 0): ?><input type="radio" value="0"  name="info[recommand_flag]" checked="checked">&nbsp;&nbsp;未推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1"  name="info[recommand_flag]">&nbsp;&nbsp;已推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php else: ?>
                <input type="radio" value="0" name="info[recommand_flag]">&nbsp;&nbsp;未推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="1" name="info[recommand_flag]" checked="checked">&nbsp;&nbsp;已推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
        </td>
        <td></td>
    </tr>
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
		<td>获得的荣誉：</td>
        <td><input type="text" name="info[honor]" value="<?php echo ($info["honor"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
	</tr>
    <tr>
        <td>科目：</td>
        <td><input type="text" name="info[discipline]"  value="<?php echo ($info["discipline"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>年级：</td>
        <td><input type="text" name="info[grade]"  value="<?php echo ($info["grade"]); ?>"  style="width:180px;height:22px" /></td>
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
        <td>总评分：</td>
        <td><input type="text" name="info[score]"  value="<?php echo ($info["score"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>名言警句：</td>
        <td><input type="text" name="info[catchphrase]"  value="<?php echo ($info["catchphrase"]); ?>"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>学生数：</td>
        <td><input type="text" name="info[students_number]"  value="<?php echo ($info["students_number"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>课时数：</td>
        <td><input type="text" name="info[class_hours_number]"  value="<?php echo ($info["class_hours_number"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>好评率：</td>
        <td><input type="text" name="info[praise_rate]"  value="<?php echo ($info["praise_rate"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>续课率：</td>
        <td><input type="text" name="info[continue_rate]"  value="<?php echo ($info["continue_rate"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
</table>
</form>
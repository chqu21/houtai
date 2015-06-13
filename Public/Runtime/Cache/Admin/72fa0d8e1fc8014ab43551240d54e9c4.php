<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"admin_teacherlist_add_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminteacherlistAddDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
    $("#admin_teacherlist_add_dialog_form_mobile").formValidator({onShow:"输入正确手机号",onFocus:"输入正确手机号"}).regexValidator({regExp:"mobile",dataType:"enum",onError:"手机号码格式不正确"}).ajaxValidator({
		type : "post",
		url : "<?php echo U('Teacher/checkMobile');?>",
		data : {mobile:function(){return $("#admin_teacherlist_add_dialog_form_mobile").val()} },
		datatype : "json",
		async:'false',
		success : function(data){
			var json = $.parseJSON(data);
            return json.status == 1 ? false : true;
		},
		onError : "手机号已存在",
		onWait : "请稍候..."
	});

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
    <!--
	<tr>
		<td>E-mail：</td>
		<td><input id="admin_teacherlist_add_dialog_form_email" type="text" name="info[email]" style="width:180px;height:22px" /></td>
		<td><div id="admin_teacherlist_add_dialog_form_emailTip"></div></td>
	</tr>
	-->
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
        <td>上课方式：</td>
        <td>
            <select id="class_method" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[class_method]" style="height:25px">
                <option value="1">一对一(学生上门)</option>
                <option value="2">一对一(教师外出)</option>
                <option value="3">小组课（2～5人,学生上门）</option>
                <option value="4">小班课（6～10人,学生上门）</option>
                <option value="5">大班课（10人以上,学生上门)</option>
            </select>
        </td>
        <td></td>
    </tr>
    <tr>
        <td>课程分类：</td>
        <td>
            <select id="course_category" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[course_category]" style="height:25px">
                <option value="艺术">艺术</option>
                <option value="体育">体育</option>
                <option value="高中">高中</option>
                <option value="初中">初中</option>
                <option value="小学">小学</option>
                <option value="兴趣">兴趣</option>
                <option value="学前">学前</option>
            </select>
        </td>
        <td></td>
    </tr>
    <tr>
        <td>教龄：</td>
        <td><input type="text" name="info[teaching_age]" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>姓别：</td>
        <td>
        <input type="radio" value="男" name="info[sex]">&nbsp;&nbsp;男</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="女" name="info[sex]" checked="checked">&nbsp;&nbsp;女</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    <tr>
        <td>身份认证：</td>
        <td>
        <input type="radio" value="0" name="info[certification_flag]">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="1" name="info[certification_flag]" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    <tr>
        <td>学历认证：</td>
        <td>
        <input type="radio" value="0" name="info[education_flag]">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="1" name="info[education_flag]" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    <tr>
        <td>教师资格认证：</td>
        <td>
        <input type="radio" value="0" name="info[teacher_certification_flag]">&nbsp;&nbsp;未认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="1" name="info[teacher_certification_flag]" checked="checked">&nbsp;&nbsp;已认证</input>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    <tr>
        <td>是否推荐：</td>
        <td>
        <input type="radio" value="0" name="info[recommand_flag]">&nbsp;&nbsp;未推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="1" name="info[recommand_flag]" checked="checked">&nbsp;&nbsp;已推荐</input>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    </tr>
    <tr>
        <td>是否显示：</td>
        <td>
        <input type="radio" value="0" name="info[display]">&nbsp;&nbsp;未显示</input>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="1" name="info[display]" checked="checked">&nbsp;&nbsp;已显示</input>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    <tr>
        <td>获得的荣誉：</td>
        <td><input type="text" name="info[honor]" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>科目：</td>
        <td><input type="text" name="info[discipline]" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>年级：</td>
        <td><input type="text" name="info[grade]"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>排序数字：</td>
        <td><input type="text" name="info[sort_num]"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>价格：</td>
        <td><input type="text" name="info[price]"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>名言警句：</td>
        <td><input type="text" name="info[catchphrase]"  style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>区域：</td>
        <td><input type="text" name="info[area]" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td>学生数：</td>
        <td><input type="text" name="info[students_number]" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
</table>
</form>
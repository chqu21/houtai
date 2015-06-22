<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"admin_teacher_addAppraise_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminTeacherAddAppraiseDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});

})
function adminTeacherAddAppraiseDialogFormSubmit(){
	$.post('<?php echo U('Teacher/addAppraise?id='.$teacherId);?>', $("#admin_teacher_addAppraise_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
            $('#admin_teacher_addAppraise_dialog').dialog('close');
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
        <td>添加时间：</td>
        <td><input type="text" name="info[raw_add_time]" class="easyui-datebox" panelHeight="auto" style="width:100px"></td>
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
        <!--
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
	-->
</table>
</form>
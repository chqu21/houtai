<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"admin_addAppraise_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminAddAppraiseDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});

})
function adminAddAppraiseDialogFormSubmit(){
	$.post('<?php echo U('Forum/appraiseAdd?id='.$subjectId);?>', $("#admin_addAppraise_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
            $('#admin_appraise_add_dialog').dialog('close');
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

<form id="admin_addAppraise_dialog_form">

<table class="gridtable">
    <tr>
        <td>添加时间：</td>
        <td><input type="text" name="info[raw_add_time]" class="easyui-datebox" panelHeight="auto" style="width:100px"></td>
    </tr>
    <tr>
        <td>评价人:</td>
        <td><input id="admin_addAppraise_add_dialog_form_member_name" type="text" name="info[member_name]" style="width:100px;height:22px" /></td>
    </tr>
	<tr>
		<td width="80">内容：</td>
        <td><textarea id="admin_addAppraise_add_dialog_form_type2_content" type="text" name="info[content]" style="width:280px;height:42px" /></textarea></td>
	</tr>
</table>
</form>
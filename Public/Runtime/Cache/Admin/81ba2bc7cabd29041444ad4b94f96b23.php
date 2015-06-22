<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"admin_editAppraise_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminappraiseEditDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
});
function adminappraiseEditDialogFormSubmit(){
	$.post('<?php echo U('Appraise/appraiseEdit?id='.$info['comments_id']);?>', $("#admin_editAppraise_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			$('#admin_appraiselist_edit_dialog').dialog('close');
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

<form id="admin_editAppraise_dialog_form">

    <table class="gridtable">
        <tr>
            <td>添加时间：</td>
            <td><input type="text" name="info[raw_add_time]" class="easyui-datebox" panelHeight="auto" style="width:150px"  value="<?php echo ($info["raw_add_time"]); ?>"></td>
            <td>评价人:</td>
            <td><input id="admin_addAppraise_add_dialog_form_member_name" type="text" name="info[member_name]" style="width:100px;height:22px"  value="<?php echo ($info["member_name"]); ?>" /></td>
            <td><div id="admin_addAppraise_add_dialog_form_membernameTip"></div></td>
        </tr>
        <tr>
            <td width="80"><?php echo ($info["type"]); ?></td>
            <td><textarea style="width:280px;height:42px" name="info[comments]"><?php echo ($info["comments"]); ?></textarea></td>
            <td>评分：</td>
            <td><input id="admin_addAppraise_add_dialog_form_type1_score" type="text" name="info[score]"  value="<?php echo ($info["score"]); ?>" style="width:100px;height:22px" /></td>
            <td><div id="admin_addAppraise_add_dialog_form_realnameTip"></div></td>
        </tr>
        <tr>
            <td>上课方式：</td>
            <td>
                <select id="class_method_id" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[class_method_id]" style="height:25px">
                    <option value="1" <if condition="$info.class_method_id eq '1'">一对一(学生上门)</option>
                    <option value="2" <if condition="$info.class_method_id eq '2'">一对一(教师外出)</option>
                    <option value="3" <if condition="$info.class_method_id eq '3'">小组课（2～5人,学生上门）</option>
                    <option value="4" <if condition="$info.class_method_id eq '4'">小班课（6～10人,学生上门）</option>
                    <option value="5" <if condition="$info.class_method_id eq '5'">大班课（10人以上,学生上门)</option>
                </select>
            </td>
            <td>课时数：</td>
            <td><input type="text" name="info[class_time]"  value="<?php echo ($info["class_time"]); ?>" style="width:100px;height:22px" /></td>
        </tr>
    </table>
</form>
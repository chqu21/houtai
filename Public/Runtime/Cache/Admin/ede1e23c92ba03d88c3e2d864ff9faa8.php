<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
$(function(){
	$.formValidator.initConfig({formID:"course_courselist_add_dialog_form",onError:function(msg){/*$.messager.alert('错误提示', msg, 'error');*/},onSuccess:adminteacherlistAddDialogFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
})
function adminteacherlistAddDialogFormSubmit(){
    console.log('dse');
	$.post('<?php echo U('Course/courseAdd?id='.$info['teacher_id']);?>', $("#course_courselist_add_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			$('#course_onecourse_edit_dialog').dialog('close');
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

<form id="course_courselist_add_dialog_form">
<table class="gridtable">
    <tr>
        <td width="180">开课类型：</td>
        <td></td>
    </tr>
    <tr>
        <td width="180">一对一(学生上门):</td>
        <td><input type="checkbox" name="info[class_method_id][]" value="1" style="width:180px;height:22px" <?php echo ($info["check1"]); ?>/></td>
    </tr>
    <tr>
        <td width="180">一对一(教师外出):</td>
        <td><input type="checkbox" name="info[class_method_id][]" value="2" style="width:180px;height:22px" <?php echo ($info["check2"]); ?>/></td>
    </tr>
    <tr>
        <td width="180">小组课（2～5人,学生上门）:</td>
        <td><input type="checkbox" name="info[class_method_id][]" value="3" style="width:180px;height:22px" <?php echo ($info["check3"]); ?>/></td>
    </tr>
    <tr>
        <td width="180">小班课（6～10人,学生上门）:</td>
        <td><input type="checkbox" name="info[class_method_id][]" value="4" style="width:180px;height:22px" <?php echo ($info["check4"]); ?> /></td>
    </tr>
    <tr>
        <td width="180">大班课（10人以上,学生上门):</td>
        <td><input type="checkbox" name="info[class_method_id][]" value="5" style="width:180px;height:22px" <?php echo ($info["check5"]); ?> /></td>
    </tr>
</table>
</form>
<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
    $(function() {
        $.formValidator.initConfig({
            formID: "admin_postionList_add_dialog_form",
            onError: function (msg) {/*$.messager.alert('错误提示', msg, 'error');*/
            },
            onSuccess: adminpostionListAddDialogFormSubmit,
            submitAfterAjaxPrompt: '有数据正在异步验证，请稍等...',
            inIframe: true
        });
    });
function adminpostionListAddDialogFormSubmit(){
	$.post('<?php echo U('Ad/addPostion');?>', $("#admin_postionList_add_dialog_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			$('#admin_postionList_add_dialog').dialog('close');
            adminAdRefresh();
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
<form id="admin_postionList_add_dialog_form">
<table class="gridtable">
	<tr>
		<td width="80">广告类型：</td>
        <td>
                <input type="radio" value="1"  name="info[type]" checked="checked">&nbsp;&nbsp;幻灯片</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" value="2"  name="info[type]">&nbsp;&nbsp;单张图片</input>
        </td>
        <td></td>
	</tr>
	<tr>
		<td>广告位置：</td>
        <td>
            <input type="text"   name="info[postion]" checked="checked">&nbsp;&nbsp;</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
	</tr>
    <tr>
        <td>宽度：</td>
        <td>
            <input type="text"   name="info[width]" checked="checked" value="">&nbsp;&nbsp;</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
    <tr>
        <td>高度：</td>
        <td>
            <input type="text"   name="info[height]" checked="checked" value="">&nbsp;&nbsp;</input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td></td>
    </tr>
</table>
</form>
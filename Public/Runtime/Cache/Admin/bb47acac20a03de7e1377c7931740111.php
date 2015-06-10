<?php if (!defined('THINK_PATH')) exit();?><script src="/Public/static/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/Public/static/css/admin/uploadify/uploadify.css">
<script type="text/javascript">
 $(function() {
        $.formValidator.initConfig({
            formID: "admin_adList_edit_dialog_form",
            onError: function (msg) {/*$.messager.alert('错误提示', msg, 'error');*/
            },
            onSuccess: adminadListEditDialogFormSubmit,
            submitAfterAjaxPrompt: '有数据正在异步验证，请稍等...',
            inIframe: true
        });

    $('#file_upload').uploadify({
        'formData'     : {
            'timestamp' : "<?php echo ($info["timestamp"]); ?>",
            'token'     : "<?php echo ($info["token"]); ?>"
        },
        'auto': false, //非自动上传模式。
        'buttonText' : '选择图片',
        'swf'      : '/flash/uploadify.swf',
        'uploader' : "/admin/Ad/adImage?id=<?php echo ($info["postion_id"]); ?>",
        'onUploadSuccess' : function(file, data, response)     //上传一次
        {
            var p = eval('('+data+ ')');
            $("#img_upload").val(p.pic);
            $.post('<?php echo U('Ad/adEdit?id='.$info['ad_id']);?>', $("#admin_adList_edit_dialog_form").serialize(), function(res){
            if(!res.status){
                $.messager.alert('提示信息', res.info, 'error');
            }else{
                $.messager.alert('提示信息', res.info, 'info');
                $('#admin_adList_edit_dialog').dialog('close');
                adminAdRefresh();
            }
          });
        }
    });
});


function adminadListEditDialogFormSubmit(){
    $("#submitBtn").click(function(){     //上传按钮
        $('#file_upload').uploadify('upload', '*');
        $("#submitBtn").attr("disabled",true).attr("enabled",false);
    });
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

<form id="admin_adList_edit_dialog_form">
<table class="gridtable">
	<tr>
		<td width="80">位置：</td>
		<td><input type="text" name="info[postion]" readonly value="<?php echo ($info["postion"]); ?>" style="width:180px;height:22px" /></td>
		<td></td>
	</tr>
    <tr>
        <td width="80">标题：</td>
        <td><input type="text" id="title"  name="info[title]" value="<?php echo ($info["postion_id"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
    <tr>
        <td width="80">url：</td>
        <td><input type="text" id="url"  name="info[url]" value="<?php echo ($info["url"]); ?>" style="width:180px;height:22px" /></td>
        <td></td>
    </tr>
	<tr>
		<td>更新图片：</td>
        <td><input id="file_upload" name="file_upload" type="file" multiple="true"><input id="img_upload" name="info[img_upload]" type="hidden"></td>
		<td></td>
	</tr>
</table>
</form>
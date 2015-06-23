<?php if (!defined('THINK_PATH')) exit();?>
<table id="forum_subjectlist_datagrid" class="easyui-datagrid" data-options='<?php $dataOptions = array_merge(array ( 'border' => false, 'fit' => true, 'fitColumns' => true, 'rownumbers' => true, 'singleSelect' => true, 'pagination' => true, 'pageList' => array ( 0 => 20, 1 => 30, 2 => 50, 3 => 80, 4 => 100, ), 'pageSize' => '20', ), $datagrid["options"]);if(isset($dataOptions['toolbar']) && substr($dataOptions['toolbar'],0,1) != '#'): unset($dataOptions['toolbar']); endif; echo trim(json_encode($dataOptions), '{}[]').((isset($datagrid["options"]['toolbar']) && substr($datagrid["options"]['toolbar'],0,1) != '#')?',"toolbar":'.$datagrid["options"]['toolbar']:null); ?>' style=""><thead><tr><?php if(is_array($datagrid["fields"])):foreach ($datagrid["fields"] as $key=>$arr):if(isset($arr['formatter'])):unset($arr['formatter']);endif;echo "<th data-options='".trim(json_encode($arr), '{}[]').(isset($datagrid["fields"][$key]['formatter'])?",\"formatter\":".$datagrid["fields"][$key]['formatter']:null)."'>".$key."</th>";endforeach;endif; ?></tr></thead></table>
<div id="admin_subjectlist_datagrid_toolbar" style="padding:5px;height:auto">
    <form>
        主题:
        <input type="text" name="search[subject]" class="easyui-text" panelHeight="auto" style="width:100px">
        </input>&nbsp;&nbsp;
        发布者ID:
        <input type="text" name="search[member_id]" class="easyui-text" panelHeight="auto" style="width:100px">
        </input>&nbsp;&nbsp;
        作者:
        <input type="text" name="search[author]" class="easyui-text" panelHeight="auto" style="width:100px">
        </input>&nbsp;&nbsp;
        日期:
        <input type="text" name="search[raw_add_time]" class="easyui-datebox" panelHeight="auto" style="width:100px">
        </input>&nbsp;&nbsp;
        <a href="javascript:;" onclick="adminSubjectSearch(this);" class="easyui-linkbutton" iconCls="icons-map-magnifier">搜索</a>&nbsp;&nbsp;
        <a href="javascript:;" onclick="adminMemberRefresh();" class="easyui-linkbutton" iconCls="icons-arrow-arrow_refresh">刷新</a>
    </form>
</div>
<!-- 编辑评价 -->
<div id="admin_subjectlist_edit_dialog" class="easyui-dialog" title="编辑评价" data-options="modal:true,closed:true,iconCls:'icons-application-application_edit',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#admin_editSubject_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#admin_subjectlist_edit_dialog').dialog('close');}}]" style="width:750px;height:600px;"></div>


<script type="text/javascript">
$("#isNewMember").click(function(){
    if ($("#isNewMember").is(':checked')){
        $("#isNewMember").val('1');//boolea 控制是否选中
    }else{
        $("#isNewMember").val('0');//boolea 控制是否选中
    }

});

//搜索
function adminSubjectSearch(that){
    var queryParams = $('#forum_subjectlist_datagrid').datagrid('options').queryParams;
    $.each($(that).parent('form').serializeArray(), function() {
            queryParams[this['name']] = this['value'];
    });

    $('#forum_subjectlist_datagrid').datagrid('reload');
}

//时间格式化
function adminMemberListTimeFormatter(val){
	return val != '1970-01-01 08:00:00' ? val : '';
}
//操作格式化
function adminSubjectListOperateFormatter(val){
	var btn = [];
	btn.push('<a href="javascript:;" onclick="adminSubjectEdit('+val+')">编辑</a>');
    btn.push('<a href="javascript:;" onclick="adminMemberDelete('+val+')">删除</a>');
	return btn.join(' | ');
}

//刷新
function adminMemberRefresh(){
	$('#forum_subjectlist_datagrid').datagrid('reload');
}

//编辑
function adminSubjectEdit(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择学生', 'error');
		return false;
	}
	var url = '<?php echo U('forum/subjectEdit');?>';
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	$('#admin_subjectlist_edit_dialog').dialog({href:url});
	$('#admin_subjectlist_edit_dialog').dialog('open');
}

//删除
function adminMemberDelete(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择学生', 'error');
		return false;
	}
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post('<?php echo U('Appraise/appraiseDelete');?>', {id: id}, function(res){
			if(!res.status){
				$.messager.alert('提示信息', res.info, 'error');
			}else{
				$.messager.alert('提示信息', res.info, 'info');
				adminMemberRefresh();
			}
		}, 'json');
	});
}
</script>
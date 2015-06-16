<?php if (!defined('THINK_PATH')) exit();?>
<table id="course_onecourse_datagrid" class="easyui-datagrid" data-options='<?php $dataOptions = array_merge(array ( 'border' => false, 'fit' => true, 'fitColumns' => true, 'rownumbers' => true, 'singleSelect' => true, 'pagination' => true, 'pageList' => array ( 0 => 20, 1 => 30, 2 => 50, 3 => 80, 4 => 100, ), 'pageSize' => '20', ), $datagrid["options"]);if(isset($dataOptions['toolbar']) && substr($dataOptions['toolbar'],0,1) != '#'): unset($dataOptions['toolbar']); endif; echo trim(json_encode($dataOptions), '{}[]').((isset($datagrid["options"]['toolbar']) && substr($datagrid["options"]['toolbar'],0,1) != '#')?',"toolbar":'.$datagrid["options"]['toolbar']:null); ?>' style=""><thead><tr><?php if(is_array($datagrid["fields"])):foreach ($datagrid["fields"] as $key=>$arr):if(isset($arr['formatter'])):unset($arr['formatter']);endif;echo "<th data-options='".trim(json_encode($arr), '{}[]').(isset($datagrid["fields"][$key]['formatter'])?",\"formatter\":".$datagrid["fields"][$key]['formatter']:null)."'>".$key."</th>";endforeach;endif; ?></tr></thead></table>
<div id="course_onecourse_datagrid_toolbar" style="padding:5px;height:auto">
    <form>
        老师姓名:
        <input type="text" name="search[teacher_name]" class="easyui-text" panelHeight="auto" style="width:100px">
        </input>&nbsp;&nbsp;
        课程名:
        <input type="text" name="search[course_name]" class="easyui-text" panelHeight="auto" style="width:100px">
        </input>&nbsp;&nbsp;
        <a href="javascript:;" onclick="adminStudentSearch(this);" class="easyui-linkbutton" iconCls="icons-map-magnifier">搜索</a>&nbsp;&nbsp;
        <a href="javascript:;" onclick="adminMemberRefresh();" class="easyui-linkbutton" iconCls="icons-arrow-arrow_refresh">刷新</a>
    </form>
</div>
<!-- 添加学生 -->
<div id="course_onecourse_add_dialog" class="easyui-dialog" title="添加学生" data-options="modal:true,closed:true,iconCls:'icons-application-application_add',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#course_onecourse_add_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#course_onecourse_add_dialog').dialog('close');}}]" style="width:750px;height:550px;"></div>

<!-- 编辑课程 -->
<div id="course_onecourse_edit_dialog" class="easyui-dialog" title="编辑学生" data-options="modal:true,closed:true,iconCls:'icons-application-application_edit',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#course_onecourse_edit_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#course_onecourse_edit_dialog').dialog('close');}}]" style="width:750px;height:550px;"></div>

<script type="text/javascript">

//搜索
function adminStudentSearch(that){
    var queryParams = $('#course_onecourse_datagrid').datagrid('options').queryParams;
    $.each($(that).parent('form').serializeArray(), function() {
            queryParams[this['name']] = this['value'];
    });

    $('#course_onecourse_datagrid').datagrid('reload');
}

//操作格式化
function adminMemberListOperateFormatter(val){
	var btn = [];
    btn.push('<a href="javascript:;" onclick="adminMemberEdit('+val+')">编辑</a>');
    btn.push('<a href="javascript:;" onclick="adminMemberDelete('+val+')">删除</a>');
	return btn.join(' | ');
}

//刷新
function adminMemberRefresh(){
	$('#course_onecourse_datagrid').datagrid('reload');
}
//添加
function adminMemberAdd(){
	$('#course_onecourse_add_dialog').dialog({href:'<?php echo U('Course/courseAdd');?>'});
	$('#course_onecourse_add_dialog').dialog('open');
}
//编辑
function adminMemberEdit(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择学生', 'error');
		return false;
	}
	var url = '<?php echo U('Course/courseEdit');?>';
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	$('#course_onecourse_edit_dialog').dialog({href:url});
	$('#course_onecourse_edit_dialog').dialog('open');
}


//删除
function adminMemberDelete(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择学生', 'error');
		return false;
	}
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post('<?php echo U('Course/courseDelete');?>', {id: id}, function(res){
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
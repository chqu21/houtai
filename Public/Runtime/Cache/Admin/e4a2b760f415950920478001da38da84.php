<?php if (!defined('THINK_PATH')) exit();?>
<table id="ad_adlist_datagrid" class="easyui-datagrid" data-options='<?php $dataOptions = array_merge(array ( 'border' => false, 'fit' => true, 'fitColumns' => true, 'rownumbers' => true, 'singleSelect' => true, 'pagination' => true, 'pageList' => array ( 0 => 20, 1 => 30, 2 => 50, 3 => 80, 4 => 100, ), 'pageSize' => '20', ), $datagrid["options"]);if(isset($dataOptions['toolbar']) && substr($dataOptions['toolbar'],0,1) != '#'): unset($dataOptions['toolbar']); endif; echo trim(json_encode($dataOptions), '{}[]').((isset($datagrid["options"]['toolbar']) && substr($datagrid["options"]['toolbar'],0,1) != '#')?',"toolbar":'.$datagrid["options"]['toolbar']:null); ?>' style=""><thead><tr><?php if(is_array($datagrid["fields"])):foreach ($datagrid["fields"] as $key=>$arr):if(isset($arr['formatter'])):unset($arr['formatter']);endif;echo "<th data-options='".trim(json_encode($arr), '{}[]').(isset($datagrid["fields"][$key]['formatter'])?",\"formatter\":".$datagrid["fields"][$key]['formatter']:null)."'>".$key."</th>";endforeach;endif; ?></tr></thead></table>
<div id="admin_adList_datagrid_toolbar" style="padding:5px;height:auto">
</div>
<!-- 添加广告 -->
<div id="admin_adList_add_dialog" class="easyui-dialog" title="添加广告位" data-options="modal:true,closed:true,iconCls:'icons-application-application_add',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#admin_adList_add_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#admin_adList_add_dialog').dialog('close');}}]" style="width:450px;height:350px;"></div>

<!-- 编辑广告 -->
<div id="admin_adList_edit_dialog" class="easyui-dialog" title="编辑广告图" data-options="modal:true,closed:true,iconCls:'icons-application-application_edit',buttons:[{id:'submitBtn',text:'确定',iconCls:'icons-other-tick',handler:function(){$('#admin_adList_edit_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#admin_adList_edit_dialog').dialog('close');}}]" style="width:450px;height:350px;"></div>


<script type="text/javascript">
var teacher_adList_datagrid_id = 'teacher_adList_datagrid';
//搜索
function adminTeacherSearch(that){
    var queryParams = $('#'+teacher_adList_datagrid_id).datagrid('options').queryParams;
    $.each($(that).parent('form').serializeArray(), function() {
        queryParams[this['name']] = this['value'];
    });
    $('#'+teacher_adList_datagrid_id).datagrid('reload');
}
//图片格式化
function adImgFormatter(val){
    return imgStr =  "<img width=100 height=100 src='"+val+"'/>";
}
//时间格式化
function adminMemberListTimeFormatter(val){
	return val != '1970-01-01 08:00:00' ? val : '';
}
//操作格式化
function adminAdListOperateFormatter(val){
	var btn = [];
    btn.push('<a href="javascript:;" onclick="adminAdEdit('+val+')">编辑</a>');
	btn.push('<a href="javascript:;" onclick="adminAdDelete('+val+')">删除</a>');

	return btn.join(' | ');
}

//刷新
function adminAdRefresh(){
	$('#ad_adlist_datagrid').datagrid('reload');
}
//添加
function addPostion(){
	$('#admin_adList_add_dialog').dialog({href:'<?php echo U('Ad/addPostion');?>'});
	$('#admin_adList_add_dialog').dialog('open');
}
//编辑
function adminAdEdit(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择广告', 'error');
		return false;
	}
	var url = '<?php echo U('Ad/adEdit');?>';
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	$('#admin_adList_edit_dialog').dialog({href:url});
	$('#admin_adList_edit_dialog').dialog('open');
}



//删除
function adminAdDelete(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择老师', 'error');
		return false;
	}
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post('<?php echo U('Ad/adDelete');?>', {id: id}, function(res){
			if(!res.status){
				$.messager.alert('提示信息', res.info, 'error');
			}else{
				$.messager.alert('提示信息', res.info, 'info');
                adminAdRefresh();
			}
		}, 'json');
	});
}

//工具栏
var admin_adList_datagrid_toolbar = [
    { text: '刷新', iconCls: 'icons-arrow-arrow_refresh', handler: adminAdRefresh },
    { text: '排序', iconCls: 'icons-arrow-arrow_down', handler: adListFormatter }
];

//排序格式化
function adListFormatter(val, arr){
    return '<input class="adList_input" type="text" name="order['+arr['id']+']" value="'+ val +'" size="2" style="text-align:center">';
}

//排序
function adListOrder(){
    $.post('<?php echo U('System/menuOrder');?>', $('.adList_input').serialize(), function(res){
        if(!res.status){
            $.messager.alert('提示信息', res.info, 'error');
        }else{
            $.messager.alert('提示信息', res.info, 'info');
            systemMenuRefresh();
        }
    }, 'json');
}
</script>
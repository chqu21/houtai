<?php if (!defined('THINK_PATH')) exit();?>
<table id="appraise_appraiselist_datagrid" class="easyui-datagrid" data-options='<?php $dataOptions = array_merge(array ( 'border' => false, 'fit' => true, 'fitColumns' => true, 'rownumbers' => true, 'singleSelect' => true, 'pagination' => true, 'pageList' => array ( 0 => 20, 1 => 30, 2 => 50, 3 => 80, 4 => 100, ), 'pageSize' => '20', ), $datagrid["options"]);if(isset($dataOptions['toolbar']) && substr($dataOptions['toolbar'],0,1) != '#'): unset($dataOptions['toolbar']); endif; echo trim(json_encode($dataOptions), '{}[]').((isset($datagrid["options"]['toolbar']) && substr($datagrid["options"]['toolbar'],0,1) != '#')?',"toolbar":'.$datagrid["options"]['toolbar']:null); ?>' style=""><thead><tr><?php if(is_array($datagrid["fields"])):foreach ($datagrid["fields"] as $key=>$arr):if(isset($arr['formatter'])):unset($arr['formatter']);endif;echo "<th data-options='".trim(json_encode($arr), '{}[]').(isset($datagrid["fields"][$key]['formatter'])?",\"formatter\":".$datagrid["fields"][$key]['formatter']:null)."'>".$key."</th>";endforeach;endif; ?></tr></thead></table>
<div id="admin_appraiselist_datagrid_toolbar" style="padding:5px;height:auto">
    <form>
        老师ID:
        <input type="text" name="search[teacher_id]" class="easyui-text" panelHeight="auto" style="width:100px">
        </input>&nbsp;&nbsp;
        老师姓名:
        <input type="text" name="search[teacher_name]" class="easyui-text" panelHeight="auto" style="width:100px">
        </input>&nbsp;&nbsp;
        会员名:
        <input type="text" name="search[member_name]" class="easyui-text" panelHeight="auto" style="width:100px">
        </input>&nbsp;&nbsp;
        日期:
        <input type="text" name="search[raw_add_time]" class="easyui-datebox" panelHeight="auto" style="width:100px">
        </input>&nbsp;&nbsp;
        <a href="javascript:;" onclick="adminAppraiseSearch(this);" class="easyui-linkbutton" iconCls="icons-map-magnifier">搜索</a>&nbsp;&nbsp;
        <a href="javascript:;" onclick="adminMemberRefresh();" class="easyui-linkbutton" iconCls="icons-arrow-arrow_refresh">刷新</a>
    </form>
</div>
<!-- 编辑评价 -->
<div id="admin_appraiselist_edit_dialog" class="easyui-dialog" title="编辑评价" data-options="modal:true,closed:true,iconCls:'icons-application-application_edit',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#admin_editAppraise_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#admin_appraiselist_edit_dialog').dialog('close');}}]" style="width:750px;height:550px;"></div>


<script type="text/javascript">
$("#isNewMember").click(function(){
    if ($("#isNewMember").is(':checked')){
        $("#isNewMember").val('1');//boolea 控制是否选中
    }else{
        $("#isNewMember").val('0');//boolea 控制是否选中
    }

});
   function sendSms(){
        var checkedItems = $('#appraise_appraiselist_datagrid').datagrid('getChecked');
       console.dir(checkedItems);
        var names = [];
       $.each(checkedItems, function(index, item){
           names.push(item.order_id);
       });
       console.log(names.join(","));
    };
        var appraise_appraiselist_datagrid_id = 'appraise_appraiselist_datagrid';
//搜索
function adminAppraiseSearch(that){
    var queryParams = $('#'+appraise_appraiselist_datagrid_id).datagrid('options').queryParams;
    $.each($(that).parent('form').serializeArray(), function() {
            queryParams[this['name']] = this['value'];
    });

    $('#'+appraise_appraiselist_datagrid_id).datagrid('reload');
}

//时间格式化
function adminMemberListTimeFormatter(val){
	return val != '1970-01-01 08:00:00' ? val : '';
}
//操作格式化
function adminAppraiseListOperateFormatter(val){
	var btn = [];
	btn.push('<a href="javascript:;" onclick="adminAppraiseEdit('+val+')">编辑</a>');
    btn.push('<a href="javascript:;" onclick="adminMemberDelete('+val+')">删除</a>');
	return btn.join(' | ');
}

//刷新
function adminMemberRefresh(){
	$('#appraise_appraiselist_datagrid').datagrid('reload');
}

//编辑
function adminAppraiseEdit(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择学生', 'error');
		return false;
	}
	var url = '<?php echo U('Appraise/appraiseEdit');?>';
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	$('#admin_appraiselist_edit_dialog').dialog({href:url});
	$('#admin_appraiselist_edit_dialog').dialog('open');
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
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<title>404 Not Found</title>
</head>
<body style="font-family:'微软雅黑';color:#333;margin:0;padding:0">
<div class="easyui-panel error" data-options="fit:true,title:'<?php if(isset($_GET['menuid'])): ?>访问出错了<?php endif; ?>',border:false" style="font-size:16px;padding:12px 48px">
	<div style="font-size:100px;font-weight:normal;line-height:120px;margin-bottom:12px">:(</div>
	<h1 style="font-size:32px;line-height:48px">页面不存在或已经删除，请与管理员联系！</h1>
</div>
</body>
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
 <head>
  <title>thinkphp + flash 上传头像组件</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="Keywords" content="thinkphp头像上传预览，ThinkPHP剪切预览头像" />
  <meta name="Description" content="thinkphp头像上传组件是使用flash+thinkphp技术实现的，适用于与大多数的SNS、微博网站，qq空间等社交网站" />
  <style type="text/css" media="screen">
  html, body { height:100%; background-color: #ffffff;}
  #flashContent { width:100%; height:100%; }
  </style>
  
 <script type="text/javascript">
   function uploadevent(status){
        status += '';
     switch(status){
     case '1':
		var time = new Date().getTime();
		document.getElementById('avatar_priview').innerHTML = "头像1 : <img src='./upload/1.png?" + time + "'/> <br/> 头像2: <img src='./upload/2.png?" + time + "'/><br/> 头像3: <img src='./upload/3.png?" + time + "'/>" ;
		
	break;
     break;
     case '-1':
	  window.location.reload();
     break;
     default:
     window.location.reload();
    } 
   }
  </script>
 </head>
 <body>
  <div id="altContent">

<input  id="username" type="hidden" value="20130101232565" />

<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
WIDTH="850" HEIGHT="450" id="myMovieName">
<PARAM NAME=movie VALUE="../flash/avatar.swf">
<PARAM NAME=quality VALUE=high>
<PARAM NAME=bgcolor VALUE=#FFFFFF>
<param name="flashvars" value="imgUrl=<?php echo ($headPhoto["bigPic"]); ?>&uploadUrl=/index.php?s=/admin/student/upFile/sId/<?php echo ($teacherId); ?>&uploadSrc=true&pCut=170|130&pData=170|130|130|130|20|20&pSize=170|130|130|130|20|20" />
<EMBED src="../flash/avatar.swf" quality=high bgcolor=#FFFFFF WIDTH="850" HEIGHT="450" wmode="transparent" flashVars="imgUrl=<?php echo ($headPhoto["bigPic"]); ?>&uploadUrl=/index.php?s=/admin/student/upFile/sId/<?php echo ($teacherId); ?>&uploadSrc=true&pCut=170|130&pData=170|130|130|130|20|20&pSize=170|130|130|130|20|20"
NAME="myMovieName" ALIGN="" TYPE="application/x-shockwave-flash" allowScriptAccess="always"
PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer">
</EMBED>
</OBJECT>
 

  </div>

  <div id="avatar_priview"></div>

</body>
</html>
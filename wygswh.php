<?php
require '../../config/config.php';
require '../../config/config_db_wy.php';
require '../../pub/fun_tool.php';
extract($_POST);
extract($_GET);
$sxsj=isset($sxsj) ? $sxsj : date("Y-m").'-01';
if($cx=='search' && $zdbm!=''){
   $sql="select * from ydserver.gs_wy where bm='$zdbm'";
   $rs=$pdo_wy->query($sql)->fetchAll(PDO::FETCH_ASSOC);
   if(empty($rs)){
      echo "<script>alert('不存在该社区/自助终端');window.location='wygswh.php';</script>";
   }
}
$shengstr=get_province($rs[0]['sheng']);
$citystr=get_city($rs[0]['sheng'],$rs[0]['shi']);
$countystr=get_county($rs[0]['shi'],$rs[0]['xq']);
$gsstr=get_wygs($rs[0]['shi'],$rs[0]['sjdw']);
$zdstr=get_zdgs($rs[0]['xq'],$zdbm);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>公司设置</title>
<link type="text/css" href="./../../css/<?php echo $theme;?>/jquery-ui-1.8.custom.css" rel="stylesheet" />
<link href="./../../css/global.css" rel="stylesheet" type="text/css" />
<link href="./../../css/main.css" rel="stylesheet" type="text/css" />
<script src="./../../js/jquery-1.4.2.min.js" type="text/javascript"> </script>
<script src="./../../js/jquery.form.js" type="text/javascript"> </script>
<script src="./../../js/jquery-ui-1.8.1.custom.min.js" type="text/javascript"></script>
<script src="../../My97DatePicker/WdatePicker.js" type="text/javascript" ></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#mbxz").hide();
	$("#mbdr").hide();
	//设置表单的样式
	$("input").not(".oprate_type").addClass("ui-widget-content ui-corner-all").css({});
	$("fieldset>form>div>label").css({"font-weight":"bold","color":"#1D5987"});
	$("input + label,input[type='submit']").css({"cursor":"pointer"});
	$(":text").focus(function(){
	    //$(this).select();
		$(this).addClass('ui-state-highlight');
	}).blur(function(){
		$(this).removeClass('ui-state-highlight');	
	});
	$("#xsheng").change(function(){
		$.get('./wygswh_ajax.php',{act:'get_shi',sheng:$(this).val()},function(data){

			if(data!=0){
			   $("#xshi").html();
			   $("#xshi").html(data);
			}else{
			   $("#xshi").html("<option value='0'>-请选择市-</option>");
			}
			$("#xxq").html("<option value='0'>-请选择-</option>");
			$("#zzzd").html("<option value='0'>-请选择-</option>");
			$("#zdbm").val('');
		},'html');
	 });
	 $("#xshi").change(function(){
		$.get('./wygswh_ajax.php',{act:'get_xq',shi:$(this).val()},function(data){
		   if(data!=0){
			  $("#xxq").html();
			  $("#xxq").html(data);
		   }else{
		      $("#xxq").html("<option value='0'>-请选择-</option>");
		   }
		   $("#zzzd").html("<option value='0'>-请选择-</option>");
		   $("#zdbm").val('');
		},'html');
	 });
	 $("#xxq").change(function(){
	     $.get('./wygswh_ajax.php',{act:'get_zzzd',xq:$(this).val()},function(data){
			if(data!=0){
			   $("#zzzd").html();
			   $("#zzzd").html(data);
		    }else{
			   $("#zzzd").html("<option value='0'>-请选择-</option>");
			   $("#zdbm").val('');
			}
		},'html');
	 });
	 $("#zzzd").change(function(){
	    if($(this).val()>0){
		   $("#zdbm").val($(this).val());
		}else{
		   $("#zdbm").val('');
		}
	 });
	 $("#sheng").change(function(){
		$.get('./wygswh_ajax.php',{act:'get_shi',sheng:$(this).val()},function(data){
			if(data!=0){
			   $("#shi").html();
			   $("#shi").html(data);
			}else{
			   $("#shi").html("<option value='0'>-请选择市-</option>");
			}
			$("#xq").html("<option value='0'>-请选择-</option>");
		    $("#gs").html("<option value='0'>-请选择公司-</option>");
		},'html');
		$("#sjdw").val('');
		$("#bm").val('');
	 });
	 $("#shi").change(function(){
	     $.get('./wygswh_ajax.php',{act:'get_xq',shi:$(this).val()},function(data){
			if(data!=0){
				$("#xq").html();
				$("#xq").html(data);
			}else{
			    $("#xq").html("<option value='0'>-请选择-</option>");
			}
			$("#gs").html("<option value='0'>-请选择公司-</option>");
		 },'html');
		 $("#sjdw").val('');
		 $("#bm").val('');
	 });
	 $("#xq").change(function(){
		 $.get('./wygswh_ajax.php',{act:'get_gs',countyid:$(this).val()},function(data){
			if(data!=0){
			   $("#gs").html();
			   $("#gs").html(data);
			}else{
			   $("#gs").html("<option value='0'>-请选择公司-</option>");
			}
			$("#sjdw").val('');
		 },'html');
		 $("#sjdw").val('');
		 $("#bm").val('');
	 });
	 $("#gs").change(function(){
	     var lb=$("#gslb").val();
		 var gs=$(this).val();
		 var flag=$("#flag").val();
		 if(gs>0){
		    $("#sjdw").val($(this).val());
		 }else{
		    $("#sjdw").val('');
			$("#bm").val('');
		 }
         if(gs>0 && lb>0 && flag=='add'){
		     $.get('wygswh_ajax.php',{act:'get_wybm',gsbm:gs,lb:lb},function(data){
				 if(data){
					if(lb==82){ 
						var s=data.wybm.substr(0,6);
						var ss=data.wybm.substr(6,6);
						$("#bm").val(s);
						$("#zdh").val(ss);
						$("#fzr").val(data.fzr);
						$("#tel").val(data.dh);
					}else{
					    $("#bm").val(data.wybm);
						$("#fzr").val(data.fzr);
						$("#tel").val(data.dh);
					}
				 }else{
					$("#bm").val('');
				 }
			 },'json');
		 }	
	 });
	 $("#dia").dialog({
			autoOpen: false,
		 //autoOpen: ture,
			show: 'blind',
			title:'',
			width:400,
			position:'center',
			modal:true
			//hide: 'explode'	
		});
	 $("#mbxz").click(function(){
			 window.location='./hzzzexcel.php?lb='+$(this).attr('tmid');
		});
	   $("#mbdr").click(function(){
		   $("#dia").dialog("open");
		});
	 $("#gslb").change(function(){
	    var gs=$("#gs").val();
		var wybm=$("#wybm").val();
		var lb=$(this).val();
		$("#mbxz").attr('tmid',lb);
		$("#mbdr").attr('tmid',lb);
		if(lb==23){
		   $(".shequ").hide();
		   $("#sswy").show();
		   $("#mbxz").show();
		   $("#mbdr").show();
		   $("#sjqbm").show();
		   $("#ssqmc").show();
		   $("#zdh").hide();
		   $("#skl").show();
		   $("#ljf").hide();
		   $("#appkey").hide();
		   $('#sclb').val(23);	
		}else if(lb==81){
		   $(".shequ").show();
		   $("#sswy").hide();
		   $("#mbxz").hide();
		   $("#mbdr").hide();
		   $("#sjqbm").hide();
		   $("#ssqmc").hide();
		   $("#zdh").hide();
		   $("#skl").hide();
		   $("#ljf").hide();
		   $("#erp").hide();
		}else if(lb==82){
		   $(".shequ").show();
		   $("#sswy").show();
		   $("#mbxz").show();
		   $("#mbdr").show();
		   $("#sjqbm").hide();
		   $("#ssqmc").show();
		   $("#zdh").show();
		   $("#skl").hide();
		   $("#ljf").show();	
           $("#erp").show();
           $('#sclb').val(82);		   
		}else{
		   $(".shequ").show();
		   $("#sswy").hide();
		   $("#mbxz").hide();
		   $("#mbdr").hide();
		   $("#sjqbm").hide();
		   $("#ssqmc").hide();
		   $("#zdh").hide();
		   $("#skl").hide();
		   $("#ljf").hide();
		   $("#erp").hide();
		   return false;
		}
		if(gs!='' && lb!='0'){
		     $.get('wygswh_ajax.php',{act:'get_wybm',gsbm:gs,lb:lb},function(data){
				 if(data){
					 var obj = JSON.parse(data);
					 if(lb==82){
						 var s=obj.wybm.substr(0,6);
						 var ss=obj.wybm.substr(6,6);
						 $("#bm").val(s);
						 $("#zdh").val(ss);
					 }else{
						 $("#bm").val(obj.wybm);
						 $("#fzr").val(obj.fzr);
						 $("#tel").val(obj.dh);
					 }
				 }else{
					$("#bm").val('');
				 }
			 },'html');
		 }else{
		    $("#bm").val('');
		 } 
	 });
	 $("#bm").focus(function(event){
	    var szgs=$("#gs").val();
		var lb=$("#gslb").val();
		var wybm=$("#wybm").val();
		var bm=$(this).val();
		if(lb=='0'){
		   alert('请先选择类别');
		   $("#bm").val('');
		   $("#bm").blur();
		   event.stopPropagation();
		   return false;
		}
		if(szgs=='0'){
		   alert('请先选择所属公司');
		   $("#bm").val('');
		   $("#bm").blur();
		   event.stopPropagation();
		   return false;
		}
	 });
	 $("#bm").keyup(function(){
	    return false;
	 });
	  $("#bm").keydown(function(){
	    return false;
	 });
	var bool;
	 $("#sjdw").blur(function(){
	     var gsbm=$(this).val();
		 var lb=$("#gslb").val();
		 var szgs=$("#gs").val();
		 var flag=$("#flag").val();
		 if(gsbm!=''){
		    if(!/^\d{6}$/.test(gsbm)){
			   alert('所属上级公司编码为6位数字');
			   return false;
			}else{
			   if(szgs>0){
			      if(gsbm!=szgs){
				     alert('上级公司和所属公司必须相同');
					 $("#sjdw").val('');
					 return false;
				  }
			   }

			   $.get('wygswh_ajax.php',{act:'check_gsbm',gsbm:gsbm},function(data){
				   bool = data.num;
			      if(data.num==1){
				     $("#sjdwmc").text(data.msg.mc);
					  $("#fzr").val(data.msg.fzr);
					  $("#tel").val(data.msg.dh);
				  }else{
				     alert(data.msg);
					 return false;
				  }
			   },'json');
			   if(flag=='add' && bool == 1){
				   if(lb>0){
					  $.get('wygswh_ajax.php',{act:'get_wybm',gsbm:gsbm,lb:lb},function(data){
						 if(data){
							$("#bm").val(data.wybm);
						 }else{
							$("#bm").val('');
						 }
					  },'json');
				   }
			   }
			}
		 }
	 });
	 $("#wybm").blur(function(){
	    var bm=$(this).val();
		var lb=$("#gslb").val();
		var flag=$("#flag").val();;
		if(bm!=''){
		   if(!/^[0-9a-zA-Z]{13,}$/.test(bm)){
		      alert('所属物业公司编码为至少13位的数字或字母');
			  return false;
		   }else{
		      $.get('wygswh_ajax.php',{act:'check_wybm',bm:bm},function(data){
			     if(data!='0'){
				    alert(data);
					return false;
				 }
			  },'html');
		   }
		}
	 });
	 $("#tel").blur(function(){
	    var tel=$(this).val();
		if(tel!=''){
		   if(!/(^\d{11}$)|(^(\d{3}|\d{4}|\d{5})-\d{7}$)|(^(\d{3}|\d{4}|\d{5})-\d{8}$)|(^(\d{7}|\d{8})$)/.test(tel)){
		      alert('联系电话格式错误');
			  return false;
		   }
		}
	 });
     $("#password").blur(function(){
	    var psw=$(this).val();
		if(psw!=''){
		   if(!/^[0-9a-zA-Z]{6,20}$/.test(psw)){
		      alert('内部事务口令为至少6位的字母或数字的组合');
			  $("#password").val('');
		   }
		}
	 });
	 $("#bz").blur(function(){
	    var bz=$(this).val();
		len=bz.length;
		if(len>150){
		   alert('备注请控制在150个字符以内');
		}
	 });
	  $("#appkey").blur(function(){
	    var appkey=$(this).val();
		appkey_len=appkey.length;
		if(appkey!=''){
			if(!/^[0-9a-zA-Z]+$/.test(appkey)){
			   alert('ERP授权码为小于32位的字母或数字的组合');
			   $("#appkey").val('');
			}else if(appkey_len>32){
			   alert('ERP授权码为小于32位的字母或数字的组合!');
			   $("#appkey").val('');
			}
		}
	 });
	 $("#save").click(function(){
	     var sheng=$("#sheng").val();
		 var shi=$("#shi").val();
		 var xq=$("#xq").val();
		 var gs=$("#gs").val();
	     var gsbm=$("#gs").val();
		 var lb=$("#gslb").val();
		 var wybm=$("#bm").val();
		 var zdh=$("#zdh").val();
		 var mc=$.trim($("#mc").val());
		 var sjdw=$("#sjdw").val();
		 var wy=$("#wybm").val();
		 var jqbm=$("#jqbm").val();
		 var address=$.trim($("#address").val());
		 var fzr=$.trim($("#fzr").val());
		 var tel=$.trim($("#tel").val());
		 var password=$("#password").val();
		 var password01=$("#password01").val();
		 var sxsj=$("#sxsj").val();
		 var bz=$("#bz").val();
		 var id=$("#id").val();
		 var appkey=$("#appkey").val();
		 if(lb=='0'){
		    alert('请选择社区/自助终端类别');
			return false;
		 }
			 if(xq=='0'){
				alert('请选择县/区');
				return false;
			 }
			 if(gsbm=='0'){
				alert('请选择所属公司');
				return false;
			 }
			 if(sjdw==''){
		        alert('请选择所属上级公司');
			    return false;
		     }else{
		        if(!/^\d{6}$/.test(sjdw)){
			        alert('所属上级公司编码为6位数字');
			    }else{
			        if(gsbm!=sjdw){
			           alert('所属公司和上级公司必须相同');
				       return false;
			        }
			        $.get('wygswh_ajax.php',{act:'check_gsbm',gsbm:sjdw},function(data){
			           if(data.num==0){
				          alert(data.msg);
					      return false;
				       }
			        },'json');
			   }
		    }
		 if(lb==82){
			 if(wy==''){
				alert('请输入所属物业公司编码');
				return false;
			 }else{
				if(!/^[0-9a-zA-Z]{6,}$/.test(wy)){
				  alert('所属物业公司编码为数字或字母');
				  return false;
				}
			 }
		 }
		 if(lb==23){
		    if(wybm==''){
			    alert('请输入社区/自助终端编码');
			    return false;
		    }else{
			   if(!/^\d{12}$/.test(wybm)){
				   alert('社区/自助终端编码为12位数字');
				   return false;
			    }
	        }
			if(jqbm==''){
			    alert('请输入机器编码');
				return false;
			}
			if(password01==''){
		        alert('机器口令不能为空');
			    return false;
		    }else{
		        if(!/^[0-9a-zA-Z]{6,20}$/.test(password01)){
			       alert('内部事务口令为至少6位的字母或数字的组合');
				   return false;
			    }
		    }
		}else{

			 if(wybm==''){
				alert('请输入社区/自助终端编码');
				return false;
			 }else{
				if(!/^\d{6}$/.test(wybm)){
					alert('社区/自助终端编码为12位数字');
					return false;
				}
			 }
			 if(zdh && zdh == ''){
				alert('请输入社区/自助终端编码');
				return false;
			 }else{
				if(zdh  && !/^\d{6}$/.test(zdh)){
					alert('社区/自助终端编码为6位数字');
					return false;
				}
			 }
		 }
		 if(address==''){
		    alert('请输入服务地址');
			return false;
		 }
		 if(lb==81 || lb==82){
		     if(mc && mc==''){
		        alert('请输入社区名称');
			    return false;
		     }else{
				if(tel && !/(^\d{11}$)|(^(\d{3}|\d{4})-\d{7}$)|(^(\d{3}|\d{4})-\d{8}$)|(^(\d{7}|\d{8})$)/.test(tel)){
				   alert('联系电话格式错误');
				   return false;
				}
			 }
			 if(password==''){
		        alert('内部事务口令不能为空');
			    return false;
		     }else{
		        if(!/^[0-9a-zA-Z]{6,20}$/.test(password)){
			       alert('内部事务口令为至少6位的字母或数字的组合');
				   return false;
			    }
		     }
		 }
		 if(sxsj && sxsj==''){
		    alert('请输入生效日期');
			return false;
		 }
		 var qstr=$("#myform").serialize();
		 qstr=qstr+"&gslb="+lb+"&sheng="+sheng+"&shi="+shi+"&gs="+gs+"&xq="+xq;
		 $.get('./wygswh_ajax.php',qstr,function(data){
			if(data.num==1){
			    alert(data.msg);
				window.location="wygswh.php";
			}else{
			    alert(data.msg);
				return false;
			}	
		},'json');
	 });
	 $("#subform").submit(function(){
		if($("#zdbm").val()==''){
			alert('请选择社区/自助终端');
			return false;
		}else{
			if(!/^\d{12}$/.test($("#zdbm").val())){
				alert('社区/自助终端编码格式错误');
				return false;
			}
		}
	 });
	 $('#tj').click(function(){
		var name=$('#file').val();
		r=str.match(/^[a-zA_Z0-9\u4e00-\u9fa5]+\.(xls|xlsx)$/g)
		if(name==''){
			alert('对不起,请选择.xls文件上传');
			return false;
			}
		 //if(!r && typeof(r)!="undefined" && r!=0)
		 if(r==null)
			{
				alert('对不起,请选择.xls或者.xlsx文件上传');
				return false;
			}
		}) 
});
</script>
</head>
<body>
<fieldset>
<legend><label class="ui-widget-header ui-corner-tl ui-corner-br">当前位置:省市公司维护☞社区/自助终端维护</label></legend>
<p class="intro1"><span class='intro'>提示：</span><span style="color:#ff0000;padding-left: 5px;">*</span>为必填项,输入社区/自助终端编码点击查询可以对社区/自助终端进行修改，添加请直接输入必填信息（先选择类别，然后选择所属公司或者所属物业，自动生成12位数的编码,社区编码后6位从000001开始递增，自助终端后6位从600000开始递增）</p>
<div id="main_column" style='width:98%'>
<div class="">
<div class="form_block">
<div id="ctl00_CP1_UpdatePanel1">

<table id="Table3" cellspacing="1" cellpadding="3" width="100%" border="0" bordercolor="#CDE3F9">
	<colgroup>
		<col width='15%' />
		<col />
	</colgroup>
	<tr>
		<td class="SmallPartHeader" colspan="2">社区/自助终端设置>></td>
	</tr>
	<form name="subform" id="subform" action="wygswh.php" method="post">
	<tr class="o_gangwei">
		<td class="ItemName">社区/自助终端查询：</td>
		<td>
			省<select id='xsheng' name='xsheng' style="width: 100px;"><?php echo $shengstr;?></select>&nbsp;
			市<select id='xshi' name='xshi' style="width: 100px;">
			    <?php if(!empty($rs)){ echo $citystr;}else{ ?><option value='0'>-请选择市-</option><?php } ?>
			  </select>&nbsp;
			县/区<select name="xxq" id="xxq">
			      <?php if(!empty($rs)){ echo $countystr;}else{ ?><option value='0'>-请选择-</option><?php } ?>
				</select>&nbsp;
			社区/自助终端<select id='zzzd' name='zzzd' style="width:150px;" >
			                <?php if(!empty($rs)){ echo $zdstr;}else{ ?><option value='0'>-请选择-</option><?php } ?>
						 </select>&nbsp;
			社区/自助终端编码<input type="text" name="zdbm" id="zdbm" maxlength="12"  style="width:80px;" value="<?php echo $rs[0]['bm']; ?>" />&nbsp;
			<input type="submit" name="sub" id="sub" value="查询" />
			<input type="hidden" name="cx" id="cx" value="search" />
		</td>
	</tr>
	</form>
	<tr>
		<td  class="ItemName">社区/自助终端信息维护</td>
		<td>&nbsp;</td>
	</tr>
	<form name="myform" id="myform" action="#" method='post' style='margin:0'>
	<input type="hidden" name="act" id="act" value="save" />
    <input type="hidden" name="id" id="id" value="<?php echo $rs[0]['id'];?>" />
	<input type="hidden" name="flag" id="flag" value="<?php if(!empty($rs)){ echo 'xg';}else{ echo 'add';} ?>" />
	<tr class="o_gangwei">
		<td class="ItemName"><span class='required_mark'>*</span>类别：</td>
		<td>
			<select id='gslb' name='gslb' <?php if(!empty($rs)){ echo "disabled='disabled'";} ?>>
			   <option value='0' >-选择类别-</option>
			   <option value="81" <?php if($rs[0]['lb']==81){ echo 'selected';} ?> >自建社区</option>
			   <option value="82" <?php if($rs[0]['lb']==82){ echo 'selected';} ?> >合作社区</option>
			   <option value="23" <?php if($rs[0]['lb']==23){ echo 'selected';} ?> >自助终端</option>
			</select>&nbsp;
			<input type="button" tmid='' name="mbxz" id="mbxz" style="color:red" value="模板下载" />&nbsp;<input type="button" name="mbdr" tmid='' id="mbdr"  style="color:red" value="模板导入" />
		</td>
	</tr>
	<tr class="o_gangwei">
		<td class="ItemName"><span class='required_mark'>*</span>所属省市公司：</td>
		<td>
			省：<select id='sheng' name='sheng' style="width: 100px;" <?php if(!empty($rs)){ echo "disabled='disabled'";} ?> ><?php echo $shengstr;?></select>&nbsp;
			市：<select id='shi' name='shi' style="width: 100px;" <?php if(!empty($rs)){ echo "disabled='disabled'";} ?> >
			           <?php if(!empty($rs)){ echo $citystr;}else{ ?><option value=0''>-请选择-</option><?php } ?>
			    </select>&nbsp;
			县/区：
			<select name="xq" id="xq" <?php if(!empty($rs)){ echo "disabled='disabled'";} ?> >
			    <?php if(!empty($rs)){ echo $countystr;}else{ ?><option value='0'>-请选择-</option><?php } ?>
			</select>&nbsp;
			所属公司：<select id='gs' name='gs' style="width: 250px;" <?php if(!empty($rs)){ echo "disabled='disabled'";} ?> >
			             <?php if(!empty($rs)){ echo $gsstr;}else{ ?><option value='0'>-请选择公司-</option><?php } ?>
					  </select>&nbsp;
		</td>
	</tr>
	<tr class="o_gangwei" id="sswy" style="display:<?php if($rs[0]['lb']==81){ echo 'none';} ?>">
		<td class="ItemName"><span class='required_mark'>*</span>所属物业公司编码：</td>
		<td>
			<input type="text" id='wybm' name='wybm' value="<?php echo $rs[0]['wybm']; ?>" <?php if(!empty($rs)){ echo 'readonly';} ?> />
		</td>
	</tr>
	<tr class="o_gangwei">
		<td class="ItemName"><span class='required_mark'>*</span>社区/自助终端编码：</td>
		<td>
			<input id='bm' name='bm' maxlength='6' readonly="readonly"  value="<?php if($rs[0]['lb']!=82){echo $rs[0]['bm'];}else{ $aa=substr($rs[0]['bm'],0,6);echo $aa; }?>" />
            
            <?php if($rs[0]['lb']==82){ 
			     echo "-";
			 ?>
                <input id="zdh" name="zdh" maxlength="6" readonly="readonly"  value="<?php echo substr($rs[0]['bm'],6,6);?>"/>
			<?php }elseif(($rs[0]['lb']==81) || ($rs[0]['lb']==23)){
			
			      }else{	
			?>
                 <a id="ljf">-</a>
		         <input id="zdh" name="zdh" maxlength="6"  value=""/> 
			<?php } ?>
               
           
		</td>
	</tr>
	<tr class="o_gangwei" id="ssqmc" >
		<td class="ItemName"><span class='required_mark'>*</span>社区/自助终端名称：</td>
		<td>
			<input type="text" id='mc' name='mc' style="width:350px;" maxlength="40" value="<?php echo $rs[0]['mc']; ?>" />
		</td>
	</tr>
	
	<tr class="o_gangwei">
		<td class="ItemName"><span class='required_mark'>*</span>上级公司：</td>
		<td>
			<input type="text" id='sjdw' name='sjdw' maxlength="6" value="<?php echo $rs[0]['sjdw']; ?>" <?php if(!empty($rs)){ echo 'readonly';} ?> />&nbsp;<span id="sjdwmc"></span>
		</td>
	</tr>
	<tr class="o_gangwei" id="sjqbm" style="display:<?php if($rs[0]['lb']!=23){ echo 'none';} ?>">
	    <td class="ItemName"><span class="required_mark">*</span>机器编码：</td>
		<td>
		    <input type="text" id='jqbm' name='jqbm' value="<?php echo $rs[0]['jqbm']; ?>"<?php if(!empty($rs)){echo 'readonly';} ?> />
		</td>	
	</tr>
	<tr class="o_gangwei">
		<td class="ItemName"><span class='required_mark'>*</span>服务地址：</td>
		<td>
		   <input type="text" name="address" id="address" style="width:350px;" value="<?php echo $rs[0]['address']; ?>" />&nbsp;填写详细地址
		</td>
	</tr>
	<tr class="o_gangwei shequ" style="display:<?php if($rs[0]['lb']==23){ echo 'none';} ?>">
		<td class="ItemName"><span class='required_mark'>*</span>负责人：</td>
		<td>
		   <input type="text" name="fzr" id="fzr" value="<?php echo $rs[0]['fzr']; ?>" />
		</td>
	</tr>
	<tr class="o_gangwei shequ" style="display:<?php if($rs[0]['lb']==23){ echo 'none';} ?>">
		<td class="ItemName"><span class='required_mark'></span>联系电话：</td>
		<td>
		   <input type="text" name="tel" id="tel" value="<?php echo $rs[0]['fzrdh']; ?>" />
		</td>
	</tr>
	<tr class="o_gangwei" id='skl'{ echo 'none';} ?>>
		  <td class="ItemName"><span class='required_mark'>*</span> 机器口令：</td>
		<td>
			<input id='password01' name='password01' value="<?php echo $rs[0]['kl']; ?>" />
		</td>
	</tr>
	<tr class="o_gangwei shequ" style="display:<?php if($rs[0]['lb']==23){ echo 'none';} ?>">
	     <td class="ItemName"><span class='required_mark'>*</span>内部事务口令：</td>
		 <td>
			<input id='password' name='password' value="<?php echo $rs[0]['kl']; ?>" />
		</td>
	</tr>	
	<tr class="o_gangwei shequ" style="display:<?php if($rs[0]['lb']==23){ echo 'none';} ?>">
		<td class="ItemName"><span class='required_mark'>*</span>网上查询权限：</td>
		<td>
			<select id='wscxqx' name='wscxqx' style="width: 150px;">
				<option value='3' <?php if($rs[0]['wscxqx']==3){ echo 'selected';} ?>>可以查询</option>
				<option value='0' <?php if($rs[0]['wscxqx']=='0'){ echo 'selected';} ?>>不能查询</option>
			</select>
		</td>
	</tr>
	<tr class="o_gangwei shequ" style="display:<?php if($rs[0]['lb']==23){ echo 'none';} ?>">
		<td class="ItemName"><span class='required_mark'>*</span>韵达事务权限：</td>
		<td>
			<select id='ydsw' name='ydsw' style="width: 150px;">
				<option value='0' <?php if($rs[0]['ydswqx']=='0'){ echo 'selected';} ?>>全部</option>
				<option value='1' <?php if($rs[0]['ydswqx']=='1'){ echo 'selected';} ?>>不能留言</option>
			</select>
		</td>
	</tr>
	<tr class="o_gangwei">
		<td class="ItemName"><span class='required_mark'>*</span>窗口状态：</td>
		<td>
			<select id='gszt' name='gszt'>
				<option value='1' <?php if($rs[0]['zt']==1){ echo 'selected';} ?>>正常使用</option>
				<!--<option value='2' <?php if($rs[0]['zt']==2){ echo 'selected';} ?>>预备关闭</option>
				<option value='3' <?php if($rs[0]['zt']==3){ echo 'selected';} ?>>欠费关闭</option>-->
				<option value='0' <?php if($rs[0]['zt']=='0'){ echo 'selected';} ?>>暂停</option>
				
			</select>
		</td>
	</tr>
	<tr class="o_gangwei">
		<td class="ItemName"><span class='required_mark'>*</span>生效日期：</td>
		<td>
			<input type='text' size='15' name='sxsj' id='sxsj' onFocus="WdatePicker()" value="<?php if(!empty($rs)){ echo $rs[0]['sxsj'];}else{ echo $sxsj; }?>" />
		</td>
	</tr>
	<tr class="o_gangwei" id="erp" style="display:<?php if($rs[0]['lb']!=82){ echo 'none';} ?>">
	    <td class="ItemName">ERP授权码</td>
		<td>
		   <input type='text' size='32' name='appkey' id='appkey' value="<?php if(!empty($rs)){echo $rs[0]['appkey'];}else{echo $appkey;}?>"/>
		   <span class='required_mark'>&nbsp;(使用韵达系统的不用填写，使用其他系统接入的必须填写)</span>
		</td>
	</tr>
	<tr class="o_gangwei">
		<td class="ItemName">备注</td>
		<td>
		   <textarea name="bz" id="bz" style="width:250px; height:80px" ><?php echo $rs[0]['bz']; ?></textarea>
		</td>
	</tr>
	<tr class="o_gwfz need">
		<td class="ItemName"></td>
		<td>
			<input type="button" name="save" id="save" value="保存" />
		</td>
	</tr>
	</form>
</table>
</div>
</div>
</div>
</div>
<div id='dia' align="center">请正确选择您要上传的文件，区分自助终端还是合作社区.<br/>
<form name="myform" id="myform" action="../../Excel/operaexcel.php" method="post" enctype="multipart/form-data" ><br/>
<input type="file" name='file' id='file' /><br/>
<input type="hidden" name="sclb" id="sclb" value='' ></input>
<input type="submit" id="tj" value="提交" align="center"></input>
<input type="reset" id="qx" value="重置" align="center"></input><form></div>	
</fieldset>
</body>
</html>
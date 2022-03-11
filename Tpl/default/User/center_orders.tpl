<php>$item_orders = ff_mysql_orders('uid:'.$user_id.';limit:30;page_is:true;page_id:orders;page_p:'.$user_page.';order:order_id;sort:desc');
$page = ff_url_page('user/center',array('action'=>'orders','p'=>'FFLINK'),true,'orders',4);
$totalpages = ff_page_count('orders', 'totalpages');
</php><!DOCTYPE html>
<html lang="zh-cn">
<head>
<include file="User:header" />
<title>订单管理_{$site_name}</title>
<meta name="keywords" content="{$site_name}用户中心">
<meta name="description" content="欢迎回到{$site_name}用户中心">
</head>
<body class="user-center">
<include file="User:center_nav" />
<div class="container ff-bg">
<div class="row">
  <div class="col-xs-12 ff-col">
    <div class="page-header">
      <h4><span class="glyphicon glyphicon-menu-right text-green"></span> 我的订单管理</h4>
    </div>
		<div class="table-responsive">
			<table class="table table-bordered table-responsive text-center">
			<thead>
				<tr>
					<th class="text-center">订单名称</th>
					<th class="text-center">订单号</th>
					<th class="text-center">下单日期</th>
					<th class="text-center">支付状态</th>
					<th class="text-center">订单状态</th>
				</tr>
			</thead>
			<tbody>
			 <volist name="item_orders" id="feifei">
				<tr>
					<td>{$feifei.order_info}</td>
					<td>{$feifei.order_sign}</td>
					<td>{$feifei.order_addtime|date='Y-m-d H:i:s',###}</td>
					<td><switch name="feifei.order_ispay"><case value="2"><font color="green">已付款</font></case><default/>未支付</switch></td>
					<td><switch name="feifei.order_status"><case value="1"><font color="green">已确认</font></case><case value="2">已取消</case><case value="3">无效</case><case value="4">退货</case><default/>未确认</switch></td>
				</tr>
				</volist>
			</tbody>
			</table>
		</div>
  </div>
  <!-- -->
  <gt name="totalpages" value="1">
    <div class="clearfix"></div>
    <div class="col-xs-12 ff-col text-center">
      <ul class="pagination pagination-lg hidden-xs">
        {$page}
      </ul>
      <ul class="pager visible-xs">
      	<gt name="user_page" value="1">
          <li><a id="ff-prev" href="{:ff_url('user/center', array('action'=>'buy','p'=>($user_page-1)), true)}">上一页</a></li>
        </gt>
        <lt name="user_page" value="$totalpages">
          <li><a id="ff-next" href="{:ff_url('user/center', array('action'=>'buy','p'=>($user_page+1)), true)}">下一页</a></li>
        </lt>
       </ul> 
    </div>
  </gt>
</div><!--row end -->
</div>
<include file="User:footer" />
</body>
</html>
<ul class="nav navbar-nav ff-nav">
  <volist name=":ff_mysql_nav('field:*;limit:0;order:nav_pid asc,nav_oid;sort:asc;cache_name:default;cache_time:default')" id="feifei">
  <notempty name="feifei.nav_son">
		<volist name="feifei.nav_son" id="feifeison">
		<eq name="feifeison.nav_target" value="1">
			<li class="visible-xs nav-{$feifeison.nav_tips}"><a href="{:ff_url_nav($feifeison['nav_link'],$feifeison['nav_tips'])}" target="_blank">{$feifeison.nav_title}</a></li>
		 <else/>
			<li class="visible-xs nav-{$feifeison.nav_tips}"><a href="{:ff_url_nav($feifeison['nav_link'],$feifeison['nav_tips'])}">{$feifeison.nav_title}</a></li>
		 </eq>
		</volist>
		<!-- -->
    <li class="dropdown hidden-xs">
      <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">{$feifei.nav_title}<b class="caret"></b></a>
      <ul class="dropdown-menu">
        <volist name="feifei.nav_son" id="feifeison">
        <eq name="feifeison.nav_target" value="1">
          <li class="nav-{$feifeison.nav_tips}"><a href="{:ff_url_nav($feifeison['nav_link'],$feifeison['nav_tips'])}" target="_blank">{$feifeison.nav_title}</a></li>
         <else/>
          <li class="nav-{$feifeison.nav_tips}"><a href="{:ff_url_nav($feifeison['nav_link'],$feifeison['nav_tips'])}">{$feifeison.nav_title}</a></li>
         </eq>
        </volist>
				<li class="visible-sm"><a href="{:ff_url('search/index')}"><span class="glyphicon glyphicon-search"></span> 搜索</a></li>
				<li class="visible-sm"><a href="{:ff_url('user/center')}"><span class="glyphicon glyphicon-user"></span> 用户</a></li>
      </ul>
    </li>
  <else/>
     <eq name="feifei.nav_target" value="1">
      <li class="nav-{$feifei.nav_tips}"><a href="{:ff_url_nav($feifei['nav_link'],$feifei['nav_tips'])}" target="_blank">{$feifei.nav_title}</a></li>
    <else/>
      <li class="nav-{$feifei.nav_tips}"><a href="{:ff_url_nav($feifei['nav_link'],$feifei['nav_tips'])}">{$feifei.nav_title}</a></li>
    </eq>
  </notempty>
  </volist>
</ul>
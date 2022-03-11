<div class="container nav-feifei">
  <ul class="list-unstyled nav-feifei-toggle ff-row">
    <li class="col-xs-6 text-nowrap ff-col site-name">
    	<neq name="model" value="index">
    		<a class="ff-goback" href="javascript:;"><span class="glyphicon glyphicon-chevron-left"></span></a>
      </neq>
    	<a href="{$root}"><span class="glyphicon glyphicon-home"></span> {$site_name}</a>
    </li>
    <li class="col-xs-2 ff-col site-ico text-center">
      <a href="javascript:;" data-toggle="collapse" data-target="#navbar-feifeicms"><span class="glyphicon glyphicon-list-alt"></span><br />导航</a>
    </li>
    <li class="col-xs-2 ff-col site-ico text-center">
      <a href="{:ff_url('search/index')}"><span class="glyphicon glyphicon-search"></span><br />搜索</a>
    </li>
    <li class="col-xs-2 ff-col site-ico text-center">
    	<gt name="site_user_id" value="0">
      <a href="{:ff_url('user/center')}"><span class="glyphicon glyphicon-user"></span><br />我的</a>
      <else/>
      <a class="user-login-modal ff-user" href="{:ff_url('user/login')}" data-href="{:ff_url('user/center')}"><span class="glyphicon glyphicon-user"></span><br />登录</a>
      </gt>
    </li>
  </ul>
</div> 
<div class="container nav-feifei">
  <ul class="list-unstyled collapse nav-feifei-collapse" id="navbar-feifeicms">
    <volist name=":ff_mysql_nav('field:*;limit:0;cache_name:default;cache_time:default;order:nav_pid asc,nav_oid;sort:asc')" id="feifei">
    <notempty name="feifei.nav_son">
        <volist name="feifei.nav_son" id="feifeison">
        <eq name="feifeison.nav_target" value="1">
          <li><a href="{$feifeison.nav_link|ff_url_nav}" target="_blank">{$feifeison.nav_title}</a></li>
         <else/>
          <li><a href="{$feifeison.nav_link|ff_url_nav}">{$feifeison.nav_title}</a></li>
         </eq>
        </volist>
    <else/>
       <eq name="feifei.nav_target" value="1">
        <li><a href="{$feifei.nav_link|ff_url_nav}" target="_blank">{$feifei.nav_title}</a></li>
      <else/>
        <li><a href="{$feifei.nav_link|ff_url_nav}">{$feifei.nav_title}</a></li>
      </eq>
    </notempty>
    </volist>
  </ul>
</div>
<div class="clearfix mb-1"></div>
<div class="container ff-bg">
<ul class="list-inline nav-gallery" data-dir="#nav-{$list_dir}">
  <volist name=":ff_mysql_nav('field:*;limit:0;cache_name:default;cache_time:default;order:nav_pid asc,nav_oid;sort:asc')" id="feifei">
  <notempty name="feifei.nav_son">
    <volist name="feifei.nav_son" id="feifeison">
    <eq name="feifeison.nav_target" value="1">
      <li class="gallery-cell" id="nav-{$feifeison.nav_tips}"><a href="{$feifeison.nav_link|ff_url_nav}" target="_blank">{$feifeison.nav_title}</a></li>
     <else/>
      <li class="gallery-cell" id="nav-{$feifeison.nav_tips}"><a href="{$feifeison.nav_link|ff_url_nav}">{$feifeison.nav_title}</a></li>
     </eq>
    </volist>
  <else/>
     <eq name="feifei.nav_target" value="1">
      <li class="gallery-cell" id="nav-{$feifei.nav_tips}"><a href="{$feifei.nav_link|ff_url_nav}" target="_blank">{$feifei.nav_title}</a></li>
    <else/>
      <li class="gallery-cell" id="nav-{$feifei.nav_tips}"><a href="{$feifei.nav_link|ff_url_nav}">{$feifei.nav_title}</a></li>
    </eq>
  </notempty>
  </volist>
</ul>
</div>
<div class="clearfix mb-1"></div>
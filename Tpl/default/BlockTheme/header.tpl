<nav class="navbar navbar-inverse" role="navigation" data-dir=".nav-{$list_dir}">
  <div class="container">
  	<div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-feifeicms">
        <span class="glyphicon glyphicon-align-justify"></span>
      </button>
      <gt name="site_user_id" value="0">
    		<a class="navbar-toggle btn" href="{:ff_url('user/center')}">
          <span class="glyphicon glyphicon-user"></span>
        </a>
      <else/>
      	<a class="navbar-toggle btn ff-user user-login-modal" href="{:ff_url('user/login')}" data-href="{:ff_url('user/center')}">
          <span class="glyphicon glyphicon-user"></span>
        </a>
      </gt>
      <a class="navbar-toggle btn" href="{:ff_url('search/index')}">
        <span class="glyphicon glyphicon-search"></span>
      </a>
			<neq name="model" value="index">
    		<a class="navbar-toggle btn ff-goback visible-xs" href="javascript:;"><span class="glyphicon glyphicon-chevron-left"></span></a>
      </neq>
      <a class="navbar-brand" href="{$root}">{$site_name}</a>
    </div>
    <div class="collapse navbar-collapse navbar-left" id="navbar-feifeicms">
    	<include file="./Tpl/base/bootstrap3/nav_default" />
    </div>
		<ul class="nav navbar-nav navbar-right visible-md visible-lg">
			<li class="visible-md">
				<a class="px-2" href="{:ff_url('search/index')}"><span class="glyphicon glyphicon-search text-white"></span></a>
			</li>		
			<li class="visible-md visible-lg">
				<a class="ff-record-get px-2" href="javascript:;" data-toggle="popover" data-container="body" data-html="true" data-trigger="manual" data-placement="bottom" data-content="loading..."><span class="glyphicon glyphicon-record text-white"></span></a>
			</li>
			<li class="visible-md visible-lg">
				<gt name="site_user_id" value="0">
					<a class="pl-2" href="{:ff_url('user/center')}" data-toggle="tooltip" data-placement="bottom" title="我的用户中心"><span class="glyphicon glyphicon-user text-green"></span></a>
				<else/>
					<a class="ff-user user-login-modal pl-2" href="{:ff_url('user/login')}" data-href="{:ff_url('user/center')}" data-toggle="tooltip" data-placement="bottom" title="点击登录"><span class="glyphicon glyphicon-user text-white"></span></a>
				</gt>
			</li>		
		</ul>
		<form class="navbar-form navbar-right ff-search visible-lg pl-0 pr-2" action="{$root}index.php?s=vod-search" method="post" data-sid="{$site_sid}" data-limit="{:C('ui_search_limit')}" data-action="{:ff_url(ff_sid2module(ff_default($site_sid,1)).'/search',array('wd'=>'FFWD'), true)}">
			<div class="input-group input-group-sm">
				<input type="text" class="form-control ff-wd" id="ff-wd" name="wd" placeholder="关键字">
				<div class="input-group-btn">
					<button type="submit" class="btn btn-success">
						<span class="glyphicon glyphicon-search"></span>
					</button>
				</div>
			</div>
		</form>	
  </div><!-- /.container -->
</nav><!-- /.navbar -->
<form class="form-horizontal ff-search" action="{$root}index.php?s=vod-search" method="post" data-sid="{$site_sid}" data-limit="{:C('ui_search_limit')}" data-action="{:ff_url('vod/search',array('wd'=>'FFWD'), true)}">
  <div class="input-group">
    <div class="input-group-btn">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        <span class="title"></span>
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a href="javascript:;" data-action="{:ff_url('vod/search',array('name'=>'FFWD'), true)}">视频</a></li>
			  <li><a href="javascript:;" data-action="{:ff_url('news/search',array('name'=>'FFWD'), true)}">资讯</a></li>
				<li><a href="javascript:;" data-action="{:ff_url('special/search',array('name'=>'FFWD'), true)}">专题</a></li>
				<li><a href="javascript:;" data-action="{:ff_url('star/search',array('name'=>'FFWD'), true)}">明星</a></li>
				<li><a href="javascript:;" data-action="{:ff_url('role/search',array('name'=>'FFWD'), true)}">角色</a></li>
      </ul>
    </div>
    <input type="text" class="form-control ff-wd" name="wd" placeholder="Search">
    <div class="input-group-btn">
      <button type="submit" class="btn btn-default btn-success">搜索</button>
    </div>
  </div>   
</form>
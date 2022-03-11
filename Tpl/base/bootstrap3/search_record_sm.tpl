<form class="navbar-form navbar-right ff-search" action="{$root}index.php?s=vod-search-name" method="post" data-sid="{$site_sid}" data-limit="{:C('ui_search_limit')}" data-action="{:ff_url('vod/search',array('wd'=>'FFWD'), true)}">
  <div class="input-group input-group-sm">
    <gt name="Think.config.ui_record" value="0">
    <div class="input-group-addon">
    	<a class="ff-record-get" href="javascript:;" data-toggle="popover" data-container="body" data-html="true" data-trigger="manual" data-placement="bottom" data-content="loading...">
      <span class="glyphicon glyphicon-record text-green"></span>
      </a>
    </div>
    </gt>
    <eq name="site_sid" value="2">
      <input type="text" class="form-control ff-wd" id="ff-wd" name="wd" placeholder="请输入关键字" value="{$search_wd}">
      <div class="input-group-btn">
        <button type="submit" class="btn btn-default">
        <span class="glyphicon glyphicon-search"></span>
        </button>
      </div>
    <else/>
      <input type="text" class="form-control ff-wd" id="ff-wd" name="wd" placeholder="请输入影片名称" value="{$search_wd}">
      <div class="input-group-btn">
        <button type="submit" class="btn btn-default">
        <span class="glyphicon glyphicon-search"></span>
        </button>
      </div>
    </eq>
  </div>
</form>
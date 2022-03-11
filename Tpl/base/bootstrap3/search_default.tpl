<form class="form-horizontal ff-search" data-sid="{$site_sid}" data-limit="{:C('ui_search_limit')}" role="search" action="{$root}index.php?s=vod-search-name" method="post">
  <div class="form-group">
    <div class="col-xs-7" style="padding-right:0;">
    <input type="text" class="form-control ff-wd" name="wd" placeholder="Search">
    </div>
    <div class="col-xs-5 text-right" style="padding-left:0;">
      <button type="submit" class="btn btn-default" data-action="{:ff_url('news/search',array('name'=>'FFWD'), true)}">搜资讯</button>
      <button type="submit" class="btn btn-default btn-success" data-action="{:ff_url('vod/search',array('name'=>'FFWD'), true)}">搜视频</button>
    </div>
  </div>
</form>
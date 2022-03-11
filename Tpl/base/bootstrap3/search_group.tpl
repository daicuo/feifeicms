<form class="form-horizontal ff-search" action="{$root}index.php?s=vod-search-name" method="post" data-sid="{$site_sid}" data-limit="{:C('ui_search_limit')}">
  <div class="input-group">
    <input type="text" class="form-control ff-wd" name="wd" placeholder="Search">
    <div class="input-group-btn">
    <button type="submit" class="btn btn-default" data-action="{:ff_url('news/search',array('name'=>'FFWD'), true)}">搜资讯</button>
    <button type="submit" class="btn btn-default btn-success" data-action="{:ff_url('vod/search',array('name'=>'FFWD'), true)}">搜视频</button></div>
    </div>
</form>
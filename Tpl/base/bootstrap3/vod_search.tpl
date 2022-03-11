<form class="form-horizontal ff-search" action="{$root}index.php?s=vod-search" method="post" data-sid="1" data-limit="{:C('ui_search_limit')}" data-action="{:ff_url('vod/search',array('wd'=>'FFWD'), true)}">
  <div class="input-group">
    <input type="text" class="form-control ff-wd" name="wd" placeholder="片名、演员">
    <div class="input-group-btn">
      <button type="submit" class="btn btn-default">
        <span class="glyphicon glyphicon-search text-green"></span>
      </button>
    </div>
  </div>
</form>
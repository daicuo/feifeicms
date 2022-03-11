<form class="form-horizontal ff-search" action="{$root}index.php?s=role-search" method="post" data-sid="9" data-limit="{:C('ui_search_limit')}" data-action="{:ff_url('role/search',array('wd'=>'FFWD'), true)}">
  <div class="input-group">
    <input type="text" class="form-control ff-wd" name="wd" placeholder="关键字">
    <div class="input-group-btn">
      <button type="submit" class="btn btn-default">
        <span class="glyphicon glyphicon-search text-green"></span>
      </button>
    </div>
  </div>
</form>
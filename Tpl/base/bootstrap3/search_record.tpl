<form class="form-horizontal ff-search" data-sid="{$site_sid}" data-limit="{:C('ui_search_limit')}" action="{$root}index.php?s=vod-search" method="post">
  <div class="input-group">
    <gt name="Think.config.ui_record" value="0">
    <div class="input-group-addon">
      <a class="ff-record-get" href="javascript:;" data-toggle="popover" data-container="body" data-html="true" data-trigger="manual" data-placement="bottom" data-content="loading...">
      <span class="glyphicon glyphicon-record"></span>
      </a>
    </div>
    </gt>
    <input type="text" class="form-control ff-wd" id="ff-wd" name="wd" placeholder="Search">
    <div class="input-group-btn">
      <button type="submit" class="btn btn-default">
        <span class="glyphicon glyphicon-search"></span>
      </button>
    </div>
  </div>
</form>
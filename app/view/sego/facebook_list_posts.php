<div class="container">
    <div class="col-md-12">

      <div class="new-client col-md-12 active">
        <?php echo $client_name; ?> - Published posts (for item <?php echo $item_id; ?>)
      </div>

      <div class="row queued-post">
        <div class="col-md-2">Date</div>
        <div class="col-md-2">Content</div>
        <div class="col-md-2">Likes</div>
      </div>
      <?php foreach($query as $k => $v){ ?>
          <div class="row queued-post">
            <div class="col-md-2"><?php echo date('m-d-y',$v['post_date']); ?></div>
            <div class="col-md-2"><?php echo $v['content']; ?></div>
            <div class="col-md-2"><?php echo $v['stats']['likes']; ?></div>
          </div>
      <?php } ?>

      <button id="update-stats" onclick="updateStats('FACEBOOK', '<?php echo $item_id; ?>')">Update stats</button>

    </div>
</div>



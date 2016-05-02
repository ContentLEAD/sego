<div class="container">
    <div class="col-md-12">

      <div class="new-client col-md-12 active">
        <?php echo $client_name; ?> - Published posts (for item <?php echo $item_id; ?>)
      </div>

      <div class="row queued-post">
        <div class="col-md-1">Date</div>
        <div class="col-md-6">Content</div>
        <div class="col-md-1">Likes</div>
        <div class="col-md-1">Shares</div>
        <div class="col-md-1">Comments</div>

      </div>

      <?php foreach($query as $k => $v){ ?>
          <div class="row queued-post">
            <div class="col-md-1"><?php echo date('m-d-y',$v['post_date']); ?></div>
            <div class="col-md-6"><?php echo $v['content']; ?></div>
            <div class="col-md-1"><?php echo $v['stats']['likes']; ?></div>
            <div class="col-md-1"><?php echo $v['stats']['shares']; ?></div>
            <div class="col-md-1"><?php echo $v['stats']['comments']; ?></div>


          </div>
      <?php } ?>

      <button id="update-stats" onclick="updateStats('FACEBOOK', '<?php echo $item_id; ?>')">Update stats</button>

    </div>
</div>



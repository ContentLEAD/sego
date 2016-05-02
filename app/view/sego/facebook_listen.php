<div class="container">
    <div class="col-md-12">

      <div class="new-client col-md-12 active">
        <?php echo $client_name; ?> - Published posts
      </div>

      <div class="row queued-post">
        <div class="col-md-2">Date</div>
        <div class="col-md-6">Content</div>
        <div class="col-md-1">Likes</div>
        <div class="col-md-1">Shares</div>
        <div class="col-md-1">Comments</div>

      </div>

      <?php foreach($fbPosts as $fbPost){ ?>

        <?php if( array_key_exists('likes', $fbPost )) {
          $likes = count($fbPost['likes']);
        } else {
          $likes = 0;
        }

        if( array_key_exists('shares', $fbPost )) {
          $shares = count($fbPost['shares']);
        } else {
          $shares = 0;
        }

        if( array_key_exists('comments', $fbPost )) {
          $comments = $fbPost['comments'];
          $comments = $comments['data'];
        } else {
          $comments = [];
        }


        ?>

          <div class="row queued-post">
            <div class="col-md-2"><?php echo $fbPost['created_time']; ?></div>
            <div class="col-md-6"><?php echo $fbPost['message']; ?></div>
            <div class="col-md-1"><?php echo $likes; ?></div>
            <div class="col-md-1"><?php echo $shares; ?></div>
          </div>
          <?php foreach ($comments as $comment) { ?>

            <?php
/*
            echo '<pre>';
            var_dump($comment);
            echo '</pre>';
*/
            if( array_key_exists('comments', $comment )) {
              $commentsChildren = $comment['comments'];
              $commentsChildren = $commentsChildren['data'];
            } else {
              $commentsChildren = [];
            }



            ?>

            <div class="row queued-post" style="background:pink; margin-left: 20px;">


              <div class="col-md-2"><?php echo $comment['created_time']; ?></div>
              <div class="col-md-6"><?php echo $comment['message']; ?></div>
              <div class="col-md-1">From: <?php echo $comment['from']['name']; ?></div>
              <div class="col-md-2">Reply <?php echo $comment['id']; ?></div>
            </div>

            <?php foreach($commentsChildren as $commentChild) { ?>

              <div class="row queued-post" style="background:pink; margin-left: 40px;">


                <div class="col-md-2"><?php echo $commentChild['created_time']; ?></div>
                <div class="col-md-6"><?php echo $commentChild['message']; ?></div>
                <div class="col-md-1">From: <?php echo $commentChild['from']['name']; ?></div>
                <div class="col-md-2">Reply <?php echo $commentChild['id']; ?></div>
              </div>

            <?php } ?>

          <?php } ?>

      <?php } ?>



    </div>
</div>



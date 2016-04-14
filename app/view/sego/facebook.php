<div class="container">
    <div class="col-md-12">
      <!--
        <h2>Facebook Auth</h2>
        <?= $signin; ?>
-->

      <?php
     //  $sfid = $_SESSION['sfid'];
     //   var_dump($sfid);

      ?>
<!--
      <form action="/index.php/sego/facebook_set_page" method="POST">
        <select id="pageID" name="pageID">
          <option value="null">Select a facebook page</option>
          <?php foreach($accounts as $account) {
            echo '<option value="' . $account['id'] . '">' . $account['name'] . '</option>';
          } ?>
        </select>
        <br>
        <input name="sfid" type="hidden" value="<?php echo $sfid; ?>"></input>
        <input name="page_name" type="hidden" value="<?php echo $account['name']; ?>"></input>
        <input type="submit" value="Submit">
      </form>
-->

      <select id="pageID" name="pageID">
        <option value="null">Select a facebook page</option>
        <?php foreach($accounts as $account) {
          echo '<option value="' . $account['id'] . '">' . $account['name'] . '</option>';
        } ?>
      </select>
      <div id="sfid-hidden" style="display:none;"><?php echo $sfid; ?></div>


      <button id="submit-button-<?php echo $sfid; ?>">Submit</button>

      <script>
        var elementName = '#submit-button-' + $('#sfid-hidden').html();
        $(elementName).click(function() {
          $.ajax({
            url: '/index.php/sego/facebook_set_page',
            method: 'POST',
            data: {
              pageID: $('#pageID').val(),
              sfid: $('#sfid-hidden').html(),
              page_name: $('#pageID option:selected').text()
            },
            success: function() {
              console.log( 'dun it worked dun');
            }
          });
        })
      </script>


    </div>
</div>



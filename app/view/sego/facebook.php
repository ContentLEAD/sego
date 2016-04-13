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

      <form action="/index.php/sego/facebook_set_page" method="POST">
        <select name="pageID">
          <option value="null">Select a facebook page</option>
          <?php foreach($accounts as $account) {
            echo '<option value="' . $account['id'] . '">' . $account['name'] . '</option>';
          } ?>
        </select>
        <br>
        <input name="sfid" type="hidden" value="<?php echo $sfid; ?>"></input>
        <input type="submit" value="Submit">
      </form>




    </div>
</div>



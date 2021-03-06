<?php
//
//
//   echo '<pre>';
//    var_dump($overdue);


?>

<div class="container">
    <div class="col-md-4 col-xs-4" id="left-bar">
        <div class="row widget">
            <div class="row">
                <div class="col-md-12">
                    <input type="text" placeholder="search" id="search" class="form-control"/>
                </div>
            </div>
            <div class="row sel toggler" id="task-toggle">
                <div class="col-md-8">
                    <h4><i class="fa fa-envelope"></i>&nbsp;Tasks</h4>
                </div>
                <div class="col-md-4 text-right"><span class="badge"><?= count($tasks)?></span></div>
            </div>
            <div class="row toggle" data-toggle="task-toggle">
                <div class="col-md-12 col-xs-12">
                    <?php if(!empty($tasks)) :?>
                    <?php foreach($tasks as $t => $v):?>
                        <div class="task row" id="task_<?= $v['id'] ?>" data-task='<?= json_encode($v) ; ?>' data-due='<?= date('m-d-y',$v['due_date'])  ?>' data-created= '<?= date('m-d-y',$v['created_date'])  ?>'>
                            <div class="col-xs-8">
                            <?= substr($v['subject'],0,30);  ?>
                            </div>
                            <div class="col-xs-4">
                            <?= date('m-d-y',$v['due_date'])  ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row sel toggler" id="overdue-toggle">
                <div class="col-md-8">
                    <h4><i class="fa fa-exclamation"></i>&nbsp;Overdue Items</h4>
                </div>
                <div class="col-md-4 text-right"><span class="badge"><?= count($overdue)?></span></div>
            </div>
            <div class="row toggle" data-toggle="overdue-toggle">
                <div clas="col-md-12 col-xs-12" >
                     <?php foreach($overdue as $t => $v):?>
                        <div class="row overdue" data-overdue="<?=$v['id']?>" style="margin-left:0px;margin-right:0px;">
                            <div class="col-md-12"><?=$v['name']?></div>
                            <div class="col-md-12"><?=$v['network']?>&nbsp;<?=$v['action']?></div>
                        </div>
                     <?php endforeach;?>
                </div>
            </div>
            <div class="row sel toggler" id="view-toggle">
                <div class="col-md-8">
                    <h4><i class="fa fa-desktop"></i>&nbsp;Views</h4>
                </div>
            </div>
            <div class="row toggle" data-toggle="view-toggle">
                <div clas="col-md-12 col-xs-12" >
                    <?php if($is_admin) :?>
                    <a href="/index.php/board/network_view/admin" style="display:block;padding:10px;">Network</a>
                    <a href="/index.php/board/admin" style="display:block;padding:10px;">Clients</a>
                    <a href="/index.php/posts/index" style="display:block;padding:10px;">Posts</a>
                    <?php else :?>
                    <a href="/index.php/board/network_view" style="display:block;padding:10px;">Network</a>
                    <a href="/index.php/board/index/" style="display:block;padding:10px;">Clients</a>
                    <a href="/index.php/posts/index/" style="display:block;padding:10px;">Posts</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div id="facebook-login-status">
            <!-- Provide facebook login link if not logged in to general account -->
            <!-- <a display="_blank" href="/index.php/sego/facebook_loader/">Connect</a> -->
            <?php //var_dump($fbInfo);
            if ( !empty($fbInfo['signin']) ) {
                echo $fbInfo['signin'];
            }
            ?>
        </div>
    </div>
    <div class="col-md-8 col-xs-8">
        <div class="row widget task-reader">
            <div class="col-md-12 text-right close-task" style="margin-bottom:10px;color:red;">
                <span class="badge"><i class="fa fa-times"></i>&nbsp;Back</span>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <h3 id="task-subject"></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="task-note">
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <strong>Due Date</strong><br />
               <span id="task-due-date"></span><br />
                 <strong>Client Name</strong><br />
                <span id="task-client-name"></span><br />
                 <strong>Created By</strong><br />
                <span id="task-created-by"></span><br />
                 <strong>Created Date</strong><br />
                <span id="task-created-date"></span>
            </div>
            <div class="col-md-6" >
                <button class="btn btn-xs btn-primary task-closer" data-task-id=""><i class="fa fa-check"></i>&nbsp;Complete Task</button>
            </div>
        </div>
        <div class="row widget client-list">
        <?php foreach($client as $client_id => $v) :?>
            <div class="row new-client-full-div" data-balance="<?= $v['client_time_remaining'];?>" style="margin-left:0px;margin-right:0px;">
                <!--client block-->
                <div data-id="<?= $client_id;?>" class="new-client col-md-12">
                    <div class="col-md-7 col-xs-7">
                        <?= $v['name'];?>
                    </div>
                    <div class="col-md-4 col-xs-4 text-right">
                        <?php if ($v['backlog']) :?>
                        &nbsp;<i class="fa fa-exclamation-triangle "></i>&nbsp;
                        <?php endif;?>
                        <?php if (!empty($v['tasks'])) :?>
                        &nbsp;<i class="fa fa-envelope-o "></i>&nbsp;
                        <?php endif;?>
                    </div>
                    <div class="col-md-1 col-xs-1 text-right" data-balance-counter="<?= $client_id;?>">
                        <?= $v['client_time_remaining'];?>
                    </div>
                </div>
                <!--client order-->
                <div class="new-client-order" data-order="<?= $client_id;?>">
                    <!--OPTIONS-->
                    <div class="row options" >
                        <div class="col-md-6 col-xs-6">
                        <?php if ($v['backlog']) :?>
                            <div style="color:red; width:100%;">
                                <a href="javascript:void" class="fb" data-fb="item/backlogger/"><i class="fa fa-exclamation-triangle "></i>&nbsp;Client has <?= $v['backlog'] ?> minutes of unassigned backlog<a/>
                            </div>
                        <?php endif;?>
                        </div>
                        <div class="col-md-2 col-xs-2 text-right fb " data-fb="item/add_item/"><i class=" fa fa-plus-circle"></i>&nbsp;Item</div>
                        <div class="col-md-2 col-xs-2 text-right new-task " data-task-client-id="<?= $client_id;?>"><i class=" fa fa-plus-circle"></i>&nbsp;Task</div>
                        <div class="col-md-2 col-xs-2 text-right client-info" data-info-client-id="<?= $client_id;?>"><i class="fa fa-info-circle"></i>&nbsp;Info</div>
                    </div>
                    <?php if (isset($v['items'])) :?>
                    <!--ITEMS-->
                    <div class="col-md-12" style="padding-left:6px;padding-right:6px;">
                        <div class="progress">
                            <div id="progress_bar_<?=$client_id?>"class="progress-bar progress-bar-striped" data-total-done="<?=$v['client_time_delivered'];?>" data-total-due="<?=$v['client_time_owed'];?>" role="progressbar" aria-valuenow="<?=$v['client_time_percent'];?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$v['client_time_percent'];?>%;">
                                <?=$v['client_time_percent'];?>%
                            </div>
                        </div>
                    </div>


                    <?php foreach($v['items'] as $item_id => $item) :?>
                    <div class="col-md-6 col-xs-6" style="min-height:35px;">
                        <?php if( $item['type'] == 'BACKLOG' ):?>

                        <div class="new-item row backlog" id="<?=$item_id;?>" data-client-id="<?= $client_id;?>" data-per-int="<?= $item['percent_inc']?>" data-owed-to-date="<?=round($item['owed_to_date'])?>" data-delivered="<?=$item['delivered'];?>">
                            <div class="col-xs-1 <?=$item['class'];?>  new-item-network <?=$item_id;?>"><i class="fa fa-<?=strtolower($item['network'])?>" onclick="sego('<?= $item['network']; ?>','<?= $client_id;?>','<?=$item_id;?>')"></i></div>
                            <div class="col-xs-4 <?=$item['class'];?> new-item-action <?=$item_id;?>" ><?= $item['action']; ?></div>
                            <div class="col-xs-1 <?=$item['class'];?> new-item-button text-center notifier"><i class="fa fa-exclamation-triangle "></i></div>
                            <div class="col-xs-2 <?=$item['class'];?> new-item-button text-center new-remove <?=$item_id;?>"><i class="fa fa-times"></i></div>
                            <div class="col-xs-2 new-item-counter" data-item-count="<?= $item_id ?>"><?= $item['remaining']; ?></div>
                            <div class="col-xs-2 <?=$item['class'];?> new-item-button text-center new-commit <?=$item_id;?>"><i class="fa fa-check"></i></div>                        </div>

                        <?php elseif( $item['type'] == 'FIXED' ) :?>

                        <div class="new-item row fixed" id="<?=$item_id;?>" data-client-id="<?= $client_id;?>" data-per-int="<?= $item['percent_inc']?>" data-owed-to-date="<?=round($item['owed_to_date'])?>" data-delivered="<?=$item['delivered'];?>">
                            <div class="col-xs-1 <?=$item['class'];?>  new-item-network <?=$item_id;?>"><i class="fa fa-<?=strtolower($item['network'])?>" onclick="sego('<?= $item['network']; ?>','<?= $client_id;?>','<?=$item_id;?>')"></i></div>
                            <div class="col-xs-4 <?=$item['class'];?> new-item-action <?=$item_id;?>" ><?= $item['action']; ?></div>
                            <div class="col-xs-1 <?=$item['class'];?> new-item-button text-center notifier"><i class="fa fa-thumb-tack "></i></div>
                            <div class="col-xs-2 <?=$item['class'];?> new-item-button text-center new-remove <?=$item_id;?>"><i class="fa fa-times"></i></div>
                            <div class="col-xs-2 new-item-counter" data-item-count="<?= $item_id ?>"><?= $item['remaining']; ?></div>
                            <div class="col-xs-2 <?=$item['class'];?> new-item-button text-center new-commit <?=$item_id;?>"><i class="fa fa-check"></i></div>                        </div>

                        <?php elseif( $item['type'] == 'NYL' ) :?>

                        <div class="new-item row nyl" id="<?=$item_id;?>" data-client-id="<?= $client_id;?>" data-per-int="<?= $item['percent_inc']?>" data-owed-to-date="<?=round($item['owed_to_date'])?>" data-delivered="<?=$item['delivered'];?>">
                            <div class="col-xs-1 nyl  new-item-network <?=$item_id;?>"><i class="fa fa-<?=strtolower($item['network'])?>" onclick="sego('<?= $item['network']; ?>','<?= $client_id;?>','<?=$item_id;?>')"></i></div>
                            <div class="col-xs-5 nyl new-item-action <?=$item_id;?>" ><?= $item['action']; ?></div>
                            <div class="col-xs-2 nyl new-item-button text-center "><i class=" fa fa-play" style="color:rgb(27,192,255);"></i></div>
                            <div class="col-xs-2 new-item-counter" data-item-count="<?= $item_id ?>" data-start-amount="<?=$item['start_amount']?>">NYL</div>
                            <div class="col-xs-2 nyl new-item-button text-center new-nyl"><i class="fa fa-play"></i></div>
                        </div>

                        <?php else :?>

                        <div class="new-item row" id="<?=$item_id;?>" data-client-id="<?= $client_id;?>" data-per-int="<?= $item['percent_inc']?>" data-owed-to-date="<?=round($item['owed_to_date'])?>" data-delivered="<?=$item['delivered'];?>">
                            <div class="col-xs-1 <?=$item['class'];?> new-item-network <?=$item_id;?>"><i class="fa fa-<?=strtolower($item['network'])?>" onclick="sego('<?= $item['network']; ?>','<?= $client_id;?>','<?=$item_id;?>')"></i></div>
                            <div class="col-xs-5 <?=$item['class'];?> new-item-action <?=$item_id;?>" ><?= $item['action']; ?></div>
                            <div class="col-xs-2 <?=$item['class'];?> new-item-button text-center new-remove <?=$item_id;?>"><i class="fa fa-times"></i></div>
                            <div class="col-xs-2 new-item-counter" data-item-count="<?= $item_id ?>"><?= $item['remaining']; ?></div>
                            <div class="col-xs-2 <?=$item['class'];?> new-item-button text-center new-commit <?=$item_id;?>"><i class="fa fa-check"></i></div>                        </div>

                        <?php endif ;?>
                    </div>
                    <?php endforeach;?>

                    <?php endif;?>


                    <div style="clear:both;">
                        <!-- <a href="http://sego.dev/index.php/sego/facebook_set_page/<?php echo $client_id; ?>">Set Facebook page</a> -->
                        <button onclick="setFacebookPage('<?php echo $client_id; ?>')">Set Facebook page</button>

                        <div id="facebookAccounts-<?php echo $client_id; ?>"></div>
                    </div>


                </div>





                <div class="new-item-info" data-item-info="<?= $client_id;?>">
                    <div class="row" style="margin-left:0px;margin-right:0px;">
                        <div class="col-md-5 col-xs-5"></div>
                        <div class="col-md-7 col-xs-7 text-right">
                            <a href="javascript:void(0);" class="close-info"><i style="font-size:20px;" class="fa fa-times"></i></a>
                        </div>
                    </div>
                    <div id="info_<?= $client_id;?>"></div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>

</div>

<script>

    function load_chart(){

        $('#info-chart:visible').highcharts({
            chart: {
                type: 'column',
                style: {
                    fontFamily: 'futura-pt'
                }
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: cats
            },
            yAxis: {
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                    }
            },
            series: [{
                name: 'Undelivered',
                data: undel
            }, {
                name: 'Delivered',
                data: del
            }]
        });
    }
</script>

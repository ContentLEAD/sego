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
            <div class="row new-client-full-div" style="margin-left:0px;margin-right:0px;">
                <!--client block-->
                <div data-id="wqe1" class="new-client col-md-12">
                    <div class="col-md-8 col-xs-8">
                       <strong> <i class="fa fa-twitter"></i> Internal Development</strong>
                    </div>
                    <div class="col-md-4 col-xs-4 text-right" data-balance-counter="wqe">
                        4/16/16
                    </div>
                    <div class="col-md-12">
                        This is what happens when you've been in Haiti for five days. I'm growing dreads!… https://www.instagram.com/p/BCGRvVwFQaw/                    
                    </div>
                </div>
                <!--client order-->
                <div class="new-client-order" data-order="wqe1">
                    <!--OPTIONS-->
                </div>
            </div>
            <div class="row new-client-full-div" style="margin-left:0px;margin-right:0px;">
                <!--client block-->
                <div data-id="wqe" class="new-client col-md-12">
                    <div class="col-md-8 col-xs-8">
                       <strong> <i class="fa fa-twitter"></i> Internal Development</strong>
                    </div>
                    <div class="col-md-4 col-xs-4 text-right" data-balance-counter="wqe">
                        4/16/16
                    </div>
                    <div class="col-md-12">
                        Be the solution to #plastic pollution - sign our plastic pledge today http://goo.gl/forms/m7C77Anhgx … #kickplastic
                    </div>
                </div>
                <!--client order-->
                <div class="new-client-order" data-order="wqe">
                    <!--OPTIONS-->
                </div>
            </div>
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
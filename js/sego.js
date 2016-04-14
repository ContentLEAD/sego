function sego(network, client, item_id){
    //LOAD LOADER
     $('#info_'+client).html('<div class="col-md-12 text-center"><img src="/images/loader.gif"/></div>');
    //RAISE INFOMATION
    $('[data-order="'+client+'"]').slideToggle();
    $('[data-item-info="'+client+'"]').slideToggle();
    $.get('/index.php/ajax/get_action/'+network+'/'+client+'/'+item_id,function(html){
        $('#info_'+client).html(html);
    });

}

function twitter_validate(){
    $('#ta_twitter').keydown(function(){
        var content = $(this).val();
        x = 140-content.length;
        $('#counter').html(x);
        //LOOP
    });
}
function sego_post(network,client,item_id){
    //GET INFORMATION
    if(network == 'TWITTER'){
        //BUILD DATA POSTS
        var d = 'post='+$('textarea:visible').val();
        d += '&client='+client;
        d += '&item_id='+item_id;
        var sched = 0;
        if($('#datepicker').val() != ''){
            d += '&deploy='+$('#datepicker').val();
            sched = 1;
        }

        //SEND DATA
        $.post('index.php/sego/tweet/'+sched,d).done(function(m){
            var m = JSON.parse(m);
            console.log(m);
            if(m.success){
                $('[data-order="'+client+'"]').slideToggle();
                $('[data-item-info="'+client+'"]').slideToggle();
            }
        });
    } else if (network == 'FACEBOOK') {

        //BUILD DATA POSTS
        var sched = 0;
        if($('#datepicker').val() != ''){
            var deploy = $('#datepicker').val();
            var timestamp = $('#datepicker').datepicker('getDate') / 1000;
            sched = 1;
        }

        //SEND DATA
        $.ajax({
            method: 'POST',
            url: 'index.php/sego/facebook_post',
            data: {
                post: $('textarea:visible').val(),
                client: client,
                item_id: item_id,
                sched: sched,
                deploy: deploy,
                timestamp: timestamp
            }
        }).done(function(m) {
            var m = JSON.parse(m);
            console.log('finished the ajax call');
            console.log(m);
            if (m.success) {
                $('[data-order="' + client + '"]').slideToggle();
                $('[data-item-info="' + client + '"]').slideToggle();
            }
        }).fail(function() {
            console.log('ajax failed');
        });

    }

}

function updateStats(network, item_id) {
    $.ajax({
        url: "/index.php/sego/facebook_update_stats",
        method: 'POST',
        data: {
          item_id: item_id
        },
        success: function() {
            location.reload();
        }
    });
}


function setFacebookPage(sfid) {

    $.ajax({
        url: '/index.php/sego/facebook_set_page',
        method: 'POST',
        data: {
            sfid: sfid
        },
        success: function(response) {
            console.log(response);
            var elementName = '#facebookAccounts-' + sfid;
            $(elementName).html(response);
        }
    });

}


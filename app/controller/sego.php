<?php

class sego extends controller{

    protected $fb;
    protected $token;

    public function __construct() {
        parent::__construct();
        $this->fb = $this->fez->facebook->get_connection();

        $tokenRow = $this->fez->db->select('tokens')
                    ->from('token')
                    ->where('network="FACEBOOK"')
                    ->row();
        $this->token = $tokenRow['tokens'];

    }

    public function index() {
        echo '<h1>sego index</h1>';
    }


    // Sets facebook page ID for client account
    public function facebook_set_page() {
        // List all facebook pages and render the form
        $response = $this->fb->get('/me?fields=id,name,accounts', $this->token);
        $user = $response->getGraphUser();
        $accounts = $user['accounts'];
        $sfid = $_POST['sfid'];

        $this->fez->load->view('sego/facebook',array('accounts'=>$accounts, 'sfid'=>$sfid));

        // Handle callback and process form
        if ($_POST['pageID']) {
            //SET FACEBOOK RECORD
            $this->fez->mongo->set(array(
                'tokens.facebook.page_id'=>$_POST['pageID'],
                'tokens.facebook.page_name'=>$_POST['page_name']
                ))
                ->in('records')
                ->where(array('sfid'=>$_POST['sfid']))
                ->go();
        }

    }

    // Handles general facebook login
    public function facebook_loader($sfid = 0){
        //IS CALLBACK
        if(isset($_GET['code'])){
            //GET FACEBOOK CONNECTION
            $fb= $this->fez->facebook->get_connection();
            //GET HELPER
            $helper = $fb->getRedirectLoginHelper();
            //GRAB ACCESS TOKEN
            $accessToken = $helper->getAccessToken();

            $_SESSION['facebook_access_token'] = (string) $accessToken;

            $jsHelper = $fb->getJavaScriptHelper();
            $jsAccessToken = $jsHelper->getAccessToken();

            //CHECK IF EXISTS
            $exists = $this->fez->db->select('*')
                ->from('token')
                ->where('network = "FACEBOOK"')
                ->row();
            //REPLACING
            if($exists){
                //REPLACE
                $t = new token;
                $t->load($exists['id']);
                //SET NEW INFORMATION
                $t->set('tokens',(string)$accessToken);
                //SAVE
                $t->save();
            }else{
                //NEW
                $data = array(
                    'network'=>'FACEBOOK',
                    'tokens'=>(string) $accessToken,
                );
                //INSERT
                $this->fez->db->insert($data)
                    ->into('token')
                    ->go();
            }
            return;
        }
        //NOT CALLBACK

        //GET SIGNING
        $signin = $this->fez->facebook->signin();

        $this->fez->load->view('header');
        $this->fez->load->view('sego/facebook',array('accounts'=>$accounts, 'sfid'=>$sfid));
        $this->fez->load->view('footer');
    }

    // List all published facebook posts for an item.
    public function facebook_list_posts() {

        $item_id = $_GET['item_id'];
        $client = $_GET['client'];

        // Get client name
        $record = $this->fez->mongo->findOne()
                    ->in('records')
                    ->where(array('sfid'=>$client))
                    ->go();
        $client_name = $record['client_name'];

        //GET PUBLISHED POSTS
        $query = $this->fez->mongo->find(array('post_id', 'content', 'created_date', 'post_date', 'status', 'stats'))
             ->in('posts')
             ->where(array('status'=>'POSTED','item_id'=>$item_id))
             ->go();

        $this->fez->load->view('header');
        $this->fez->load->view('sego/facebook_list_posts', array('query'=>$query, 'item_id'=>$item_id, 'client_name'=>$client_name));
        $this->fez->load->view('footer');
    }

    // List all published facebook posts for an item.
    public function facebook_listen() {

        $item_id = $_GET['item_id'];
        $client = $_GET['client'];

        // Get client name
        $record = $this->fez->mongo->findOne()
                    ->in('records')
                    ->where(array('sfid'=>$client))
                    ->go();
        $client_name = $record['client_name'];

        $pageID = $this->fez->mongo->find(array('tokens'))
                ->in('records')
                ->where(array('sfid'=>$client))
                ->go();
        // Get first value of associative array
        $pageID = reset($pageID);
        $pageID = $pageID['tokens']['facebook_page_id'];

        $fbApp = $this->fb->getApp();

        $request = new Facebook\FacebookRequest(
          $fbApp,
          $this->token,
          'GET',
          '/' . $pageID . '?fields=access_token'
        );

        try {
            $response = $this->fb->getClient()->sendRequest($request);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $graphNode = $response->getGraphNode();
        $pageToken = $graphNode['access_token'];

        // Get all posts on page
        $request = new Facebook\FacebookRequest(
            $fbApp,
            $pageToken,
            'GET',
            '/' . $pageID . '/feed'
        );

        try {
            $response = $this->fb->getClient()->sendRequest($request);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $graphEdge = $response->getGraphEdge();
        $decodedBody = $response->getDecodedBody();
        $posts = $decodedBody['data'];

        $fbPosts = [];

        foreach ($posts as $post) {

            $post_id = $post['id'];

            $request = new Facebook\FacebookRequest(
                $fbApp,
                $this->token,
                'GET',
                '/' . $post_id . '?fields=comments{created_time,from,message,id,comments},message,created_time,likes,shares'
            );
            try {
                $response = $this->fb->getClient()->sendRequest($request);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            $graphNode = $response->getGraphNode();
            $decodedBody = $response->getDecodedBody();

         //   if ( array_key_exists('comments', $decodedBody) ) {
                array_push($fbPosts, $decodedBody);
        //    }


        }


        $this->fez->load->view('header');
        $this->fez->load->view('sego/facebook_listen', array( 'fbPosts' => $fbPosts, 'client_name' => $client_name ));
        $this->fez->load->view('footer');

    }


    // Update facebook post stats, e.g. likes
    public function facebook_update_stats() {

        $fbApp = $this->fb->getApp();

        $item_id = $_POST['item_id'];
        //GET PUBLISHED POSTS
        $query = $this->fez->mongo->find(array('post_id', 'content', 'created_date', 'post_date', 'status'))
             ->in('posts')
             ->where(array('status'=>'POSTED','item_id'=>$item_id))
             ->go();

        foreach($query as $k => $v) {
            $post_id = $v['post_id'];

            $request = new Facebook\FacebookRequest(
                $fbApp,
                $this->token,
                'GET',
                '/' . $post_id . '?fields=likes,shares,comments'
            );
            try {
                $response = $this->fb->getClient()->sendRequest($request);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            $graphNode = $response->getGraphNode();
            $likes = $graphNode['likes'];
            $numberLikes = count($likes);

            $comments = $graphNode['comments'];
            $commentsCount = count($comments);

           // $reactions = $graphNode['reactions'];
           // $reactionsCount = count($reactions);

            $shares = $graphNode['shares'];
            $sharesCount = $shares['count'];
            if (empty($sharesCount)) {
                $sharesCount = 0;
            }

            echo $numberLikes;
            foreach ($likes as $like) {
                echo '<pre>';
                var_dump($like);
                echo '<pre>';
            }

            $this->fez->mongo->set(array(
                'stats.likes '=> $numberLikes,
                'stats.shares' => $sharesCount,
                'stats.comments' => $commentsCount,
            //    'stats.reactions' => $reactionsCount
                ))
                ->in('posts')
                ->where(array('post_id'=>$post_id))
                ->go();

        }


    }

    /*
        FACEBOOK POST
    */
    public function facebook_post(){
        // First, get fresh page token.
        $pageID = $this->fez->mongo->find(array('tokens'))
                ->in('records')
                ->where(array('sfid'=>$_POST['client']))
                ->go();
        // Get first value of associative array
        $pageID = reset($pageID);
        $pageID = $pageID['tokens']['facebook_page_id'];

        $fbApp = $this->fb->getApp();

        $request = new Facebook\FacebookRequest(
          $fbApp,
          $this->token,
          'GET',
          '/' . $pageID . '?fields=access_token'
        );

        try {
            $response = $this->fb->getClient()->sendRequest($request);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $graphNode = $response->getGraphNode();
        $pageToken = $graphNode['access_token'];

        $timestamp = intval($_POST['timestamp']);
        $sched = intval($_POST['sched']);
        $post = $_POST['post'];

        // Check for urls in post
        $regex = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        if ( preg_match($regex, $post, $url) ) {
            $link = $url[0];
        } else {
            $link = '';
        }

        if ( $sched ) {
            $postArray = array (
                'message' => $post,
                'link' => $link,
                'published' => FALSE,
                'scheduled_publish_time' => $timestamp
            );
        } else {
            $postArray = array (
                'message' => $post,
                'link' => $link,
            );
        }

        // Now publish the post
        $request = new Facebook\FacebookRequest(
            $fbApp,
            $pageToken,
            'POST',
            '/' . $pageID . '/feed',
            $postArray
        );

        try {
            $response = $this->fb->getClient()->sendRequest($request);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        // resulting post info
        $graphObject = $response->getGraphObject();
        $postID = $graphObject->getProperty('id');

        // Log post in sego database
        if ($sched) {
            // Figure out deploy date
           // $deploy_date = strtotime($_POST['deploy']);
            $deploy_date = $_POST['deploy'];
            $deploy_date = strtotime( $_POST['deploy'] );
            // Make Post in mongodb
            $this->add_post($_POST['post'], 'FACEBOOK', $deploy_date, $_POST['client'], $_POST['item_id'], 'SCHEDULED', $postID);
            //MAKE COMMIT
            se::add_commit($_POST['item_id'],$u['id']->val,$deploy_date);
        } else {
            // Make Post in mongodb
            $this->add_post($_POST['post'], 'FACEBOOK', time(), $_POST['client'], $_POST['item_id'], 'POSTED', $postID);
            //MAKE COMMIT
            se::add_commit($_POST['item_id'],$u['id']->val);
        }


        // Return
        if ( !empty($postID) ) {
            echo '{"success":true}';
        } else {
            echo '{"success":false}';
        }

    }

    public function check_token(){

        $token = $this->fez->db->select('tokens')
                ->from('token')
                ->where('sfid="001G000001i2KpoIAE" AND network="FACEBOOK"')
                ->row();

        $token = $token['tokens'];

        $test = $this->fez->facebook->build_token($token);

        echo '<pre>';
        var_dump($test);
    }

    /*
        TWITTER
    */

    public function twitter_loader($sfid = 0){
        //IS CALLBACK
        if(!$sfid){
            $x = $this->fez->twitter->callback($_REQUEST['oauth_token'],$_REQUEST['oauth_verifier']);
            //CHECK IF ENTRY EXISTS
            $res = $this->fez->db->select('*')
                ->from('token')
                ->where('sfid="'.$_SESSION['sfid'].'" AND network="TWITTER"')
                ->row();

            if($res){
                $t = new token;
                $t->load($res['id']);
                $t->set('tokens',json_encode($x));
                //SAVE
                $t->save();
            }else{
                $data= array(
                    'sfid'   =>  $_SESSION['tw_sfid'],
                    'network'=>  'TWITTER',
                    'tokens' =>  json_encode($x),
                    'expiration'=> 0
                );

                $this->fez->db->insert($data)
                    ->into('token')
                    ->go();
            }
            return;
        }
        //IS LOADER
        //SET SFID IN SESSION TO GRAB LATER
        $_SESSION['tw_sfid'] = $sfid;
        //GET SIGNIN
        $signin = $this->fez->twitter->signin();
        //LOAD VIEW

        $this->fez->load->view('header');
        $this->fez->load->view('sego/twitter',array('signin'=>$signin));
        $this->fez->load->view('footer');
    }

    /*
        TWEET
    */


    public function tweet($sched = 0){

        if(!$sched){
            //GRAB TOKENS
            $token = $this->fez->db->select('tokens')
                    ->from('token')
                    ->where('sfid="'.$_POST['client'].'" AND network="TWITTER"')
                    ->row();
            //PARSE
            $token = json_decode($token['tokens']);
            //GET API CONNECTION
            $connection = $this->fez->twitter->get_connection($token);
            //TWEET
            $statues = $connection->post("statuses/update", ["status" => $_POST['post']]);
            //MAKE POST
            $this->add_post($_POST['post'],'TWITTER',time(),$_POST['client'],$_POST['item_id'],'SCHEDULED');
            //MAKE COMMIT
            se::add_commit($_POST['item_id'],$u['id']->val);
        }else{
            //FIGURE OUT DEPLOY DATE
            $deploy_date = strtotime( $_POST['deploy'] );
            //MAKE POST
            $this->add_post($_POST['post'],'TWITTER',$deploy_date,$_POST['client'],$_POST['item_id'],'POSTED');
            //MAKE COMMIT
            se::add_commit($_POST['item_id'],$u['id']->val,$deploy_date);
        }
        //RETURN
        if(isset($statues->created_at) || $sched){
            echo '{"success":true}';
        }else{
            echo '{"success":false}';
        }
    }

    public function twitter_followers($type,$sfid){
            //GRAB TOKENS
            $token = $this->fez->db->select('tokens')
                    ->from('token')
                    ->where('sfid="'.$sfid.'" AND network="TWITTER"')
                    ->row();
            //PARSE
            $token = json_decode($token['tokens']);
            //GET TREND
            if('trend' == strtolower($type)){
                $results = $this->fez->twitter->follower()->trend($token);
            }else if('demo' == strtolower($type)){
                $results = $this->fez->twitter->follower()->demo($token);
            }
            echo '<pre>';
            var_dump($results);
    }

    private function add_post($content,$network,$deploy,$sfid,$item_id,$status, $postID){
        //BUILD DATA
        $data = array(
            'post_id'=>$postID,
            'network'=>$network,
            'content'=>$content,
            'created_date'=>time(),
            'post_date'=>$deploy,
            'status'=>$status,
            'sfid'=>$sfid,
            'item_id'=>$item_id,
            'stats'=>array(
                'likes'=>0
            )
        );
        //SEND TO MONGO
        $this->fez->mongo
            ->insert($data)
            ->into('posts')
            ->go();
    }

}

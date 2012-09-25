<?php

	class AutorizationController  extends ApplicationController {

		public $userContent;
		public $user;


		public function __construct() {
		
		}

		function redirect() {
			/* Start session and load library. */
			//session_start();
			
			/* Build TwitterOAuth object with client credentials. */
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
			 
			/* Get temporary credentials. */
			$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

			/* Save temporary credentials to session. */
			$_SESSION['prevPage'] = $_SERVER['HTTP_REFERER'];
			$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
			$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
			 
			/* If last connection failed don't display authorization link. */
			switch ($connection->http_code) {
			  case 200:
			    /* Build authorize URL and redirect user to Twitter. */
			    $url = $connection->getAuthorizeURL($token);
			    header('Location: ' . $url); 
			    break;
			  default:
			    /* Show notification if something went wrong. */
			    echo 'Could not connect to Twitter. Refresh the page or try again later.';
			}
		}

		function callback() {
			//session_start();
			/* If the oauth_token is old redirect to the connect page. */
			if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
			  $_SESSION['oauth_status'] = 'oldtoken';
			  header('Location: ./clearsessions');
			}

			/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

			/* Request access tokens from twitter */
			$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

			/* Save the access tokens. Normally these would be saved in a database for future use. */
			$_SESSION['access_token'] = $access_token;

			/* Remove no longer needed request tokens */
			unset($_SESSION['oauth_token']);
			unset($_SESSION['oauth_token_secret']);

			/* If HTTP response is 200 continue otherwise send to connect page to retry */
			if (200 == $connection->http_code) {
			  /* The user has been verified and the access tokens can be saved for future use */
			  $_SESSION['status'] = 'verified';
			  //header('Location: ./');$_SERVER['HTTP_REFERER']
			  header('Location: '.$_SESSION['prevPage']);
			} else {
			  /* Save HTTP status for error dialog on connnect page.*/
			  header('Location: ./clearsessions');
			}
		}

		function clearsessions() {
			//session_start();
			session_destroy();
			 
			/* Redirect to page with the connect to Twitter option. */
			//header('Location: ./');
			header('Location: '.$_SESSION['prevPage']);
		}

		protected function verifyUser() {
						  /* If access tokens are not available redirect to connect page. */
			$user = null;
			  if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
			    
			    session_destroy();
			  } else {
			    /* Get user access tokens out of the session. */
			    $access_token = $_SESSION['access_token'];

			    /* Create a TwitterOauth object with consumer/user tokens. */
			    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

			    /* If method is set change API call made. Test is called by default. */
			    //$this->userContent = $connection->get('account/verify_credentials');
			    $this->setUserContent($connection->get('account/verify_credentials'));
			    //$query = mysql_query("SELECT * FROM users WHERE oauth_provider = 'twitter' AND oauth_uid = ". $user_info->id);
			    $user = User::find(array('conditions' => array('oauth_provider = ? AND oauth_uid = ?', 'twitter', $this->userContent->id)));

			    if(empty($user)) {
			      //$query = mysql_query("INSERT INTO users (oauth_provider, oauth_uid, username, oauth_token, oauth_secret) 
			      //VALUES ('twitter', {$user_info->id}, '{$user_info->screen_name}', '{$access_token['oauth_token']}', '{$access_token['oauth_token_secret']}')");
			      $user = new User();
			      $user->oauth_provider = 'twitter';
			      $user->oauth_uid      = $userContent->id;
			      $user->username       = $userContent->screen_name;
			      $user->image 			= $this->userContent->profile_image_url;
			      $user->oauth_token    = $access_token['oauth_token'];
			      $user->oauth_secret   = $access_token['oauth_token_secret'];
			      $user->save();
			    } else {
			      // $query = mysql_query("UPDATE users SET oauth_token = '{$access_token['oauth_token']}', oauth_secret = '{$access_token['oauth_token_secret']}' 
			      //WHERE oauth_provider = 'twitter' AND oauth_uid = {$user_info->id}");
			      //echo "encontrado ";
			      $user->oauth_token    = $access_token['oauth_token'];
			      $user->oauth_secret   = $access_token['oauth_token_secret'];
			      $user->image 			= $this->userContent->profile_image_url;
			      $user->save();
			    }

			    $_SESSION['id'] = $user->id;
			    $_SESSION['username'] = $user->username;
			    $_SESSION['oauth_uid'] = $user->oauth_uid;
			    $_SESSION['oauth_provider'] = $user->oauth_provider;
			    $_SESSION['oauth_token'] = $user->oauth_token;
			    $_SESSION['oauth_secret'] = $user->oauth_secret;
			  }

			    return $user;

		}

		function follow() {
			//$follows_wazpoapp = $connection->get('friendships/exists', array('user_a' => $_SESSION['username'], 'user_b' => 'wazpoapp'));
			$follows_wazpoapp = $connection->get('friendships/exists', array('user_a' => $content->screen_name, 'user_b' => 'wazpoapp'));
				if(!$follows_wazpoapp){
				echo 'You are NOT following @wazpoapp!';
				//$twitteroauth->post('friendships/create', array('screen_name' => 'faelazo'));
			}

		}

		function before_filter() {}

		function after_filter() {}

		public function setUser($user) {
			$this->user = $user;
		}

		public function getUser() {
			return $this->user;
		}

		public function setUserContent($userContent) {
			$this->userContent = $userContent;
		}

		public function getUserContent() {
			return $this->userContent;
		}
	}
?>
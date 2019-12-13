<?php
    namespace Callback;

    require_once("includes/include.config.php");

    use \Config\Config as Config;
    use \Facebook\Facebook as Facebook;

    class Callback extends Config{
        private $token;
        private $userData;

        public function __construct(string $appId, string $appSecret){

            parent::__construct($appId, $appSecret);
            
            # The state is necessary otherwise the script will throw an exception
            $_SESSION['FBRLH_state'] = $_GET['state'];

            $this->setAccessToken();
            $this->issetAcessToken();
        }

        /**
         * Set the access token
         * @throws \Facebook\Exceptions\FacebookSDKException throws exception if SDK error or Graph Error
         */
        private function setAccessToken() : void{
            try{
                $this->token = $this->helper->getAccessToken();
                $this->authClient($this->token);
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo "Graph error => ". $e->getMessage();
                exit();
            }catch(Facebook\Exceptions\FacebookSDKException $e){
                echo "Facebook SDK error => ".$e->getMessage();
                exit();
            }
        }

        /**
         * Checks if the token is set, otherwise throws 401 unauthorized header or 400 Bad Request
         */
        private function issetAcessToken() : void{
            if (!isset($this->token)){
                if ($this->helper->getError()){
                    # I skipped many headers. You can get all of them here
                    # https://developers.facebook.com/docs/php/howto/example_facebook_login/

                    header("HTTP/1.0 401 Unauthorized");
                    echo "Reason => ".$this->helper->getErrorReason();
                }else{
                    header("HTTP/1.0 400 Bad Request");
                    echo "Bad Request";
                }

                exit();
            }
        }

        /**
         * Auth client and get long lived access token
         * @param $token User token
         * @throws \Facebook\Exceptions\FacebookSDKException Facebook SDK Exception on fail to get Long live access token
         */
        private function authClient($token) : void{
            $authClient = $this->facebookObject->getOAuth2Client();

            # Token metadata
            $tokenMetaData = $authClient->debugToken($token);
            $tokenMetaData->validateAppId($this->appId);
            $tokenMetaData->validateExpiration();

            # Set long lived token
            if (!$token->isLongLived()){
                try{
                    $this->token = $authClient->getLongLivedAccessToken($token);
                }catch(Facebook\Exceptions\FacebookSDKException $e){
                    echo "Error getting long live access token " . $e->getMessage();
                    exit();
                }
            }

        }

        /**
         * Get the user data. Save it to session and to class property
         * @param array $fields: The fields to request from the user
         * @throws \Facebook\Exceptions\FacebookSDKException Facebook SDK Exception
         */
        public function getUserData(array $fields) : void{
            $formattedFields = implode(",", $fields);

            $response = $this->facebookObject->get("/me?fields=$formattedFields", $this->token);
            $this->userData = $response->getGraphNode()->asArray();

            # Set session variables
            $_SESSION['fb_access_token'] = (string) $this->token;
            $_SESSION['user_data'] = $this->userData;
        }

        /**
         * Public method to redirect the user on success login
         * @param string $file Where to redirect to if user successfully logged in
         */
        public function redirectTo(string $file) : void{
            header("Location: $file");
            exit();
        }

    }


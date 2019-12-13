<?php
    namespace Config;

    use Facebook\Facebook as Facebook;

    class Config{
        protected $appId;
        protected $appSecret;
        protected $facebookObject;
        protected $helper;

        public function __construct(string $appId, string $appSecret){

            # Start session and require autoload.php from Facebook
            session_start();
            require_once("vendors/Facebook/autoload.php");

            # Set private properties
            $this->appId = $appId;
            $this->appSecret = $appSecret;

            $this->facebookObject = new Facebook([
                'app_id' => "$this->appId",
                'app_secret' => "$this->appSecret",
                'default_graph_version' => "v3.2"
            ]);

            $this->helper = $this->facebookObject->getRedirectLoginHelper();
        }

        /**
         * The login url where the user will be redirected to
         * @param string $to Url
         * @param array $request optional permissions
         */
        public function loginUrl(string $to, array $request = array()) : void{
            $loginURL = $this->helper->getLoginUrl($to, $request);
            $this->printButton($loginURL);
        }

        /**
         * Prints the button to log in
         * @param $loginURL The login url where user will be redirected
         */
        private function printButton($loginURL) : void{
            echo "<a href=". $loginURL . "> <button> Log In with Facebook </button> </a>";
        }
    }
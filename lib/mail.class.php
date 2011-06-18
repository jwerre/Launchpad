<?php

    require_once("helper/phpmailer/class.phpmailer.php");
	require_once("helper/phpmailer/class.smtp.php");
	
	/**
	* Class for sending mail
	*/
	class Mail extends phpmailer
	{
		
        /**
         * Sends an email
         *
         * @param array - an array or nested arrays in this format : array('john smith', 'johnsmith@gmail.com');
         * @return boolean
         **/
		public static function email( $subject, $message, $to_name=NULL, $to_email=NULL, $from_name = NULL, $from_email = NULL )
		{

			if ( constant("SMTP_HOST") && constant("SMTP_USERNAME") && constant("SMTP_PASSWORD") ) {
                parent::IsSMTP();
                parent::$Host = SMTP_HOST;
				parent::$Port = SMTP_PORT;
				parent::$SMTPAuth = SMTP_AUTH;
				parent::$Username = SMTP_USERNAME;
				parent::$Password = SMTP_PASSWORD;
			}
			
            parent::$FromName = (isset($from_name)) ? $from_name : ADMIN_NAME;
            parent::$From = (isset($from_email)) ? $from_email : ADMIN_EMAIL;
            if(isset($to_email) && isset($to_name)){
                parent::AddAddress($to_email, $to_name);
            }

            parent::$Subject  = $subject;
		    parent::$Body     = $message;

			parent::Send();
		}

        /**
         * Adds multiple names and email addresses to email
         *
         * @param array - an array of nested arrays in this format : array ( array('johnsmith@gmail.com', 'John Smith'), array('billbob@yahoo.com', 'Bill Bob') );
         * @return boolean
         **/
        public function add_recipients( $addresses )
        {
            foreach ($addresses as $address) {
                $this->AddAddress($address[0], $address[1]);
            }
        }
	}
	
	
  
?>

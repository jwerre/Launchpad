<?php

    require_once("helper/phpmailer/class.phpmailer.php");
	require_once("helper/phpmailer/class.smtp.php");
	
	/**
	 * A class for sending email
	 *
	 * @author Jonah Werre <jonahwerre@gmail.com>
	 * @version 1.0
	 * @copyright Jonah Werre <jonahwerre@gmail.com>, 28 June, 2011
	 * @package phpmailer
	 **/
	class Mail extends phpmailer
	{
		
        /**
         * Sends an email
         *
         * @param string $subject - email subject;
         * @param string $message - email message;
         * @param string $from_name - from names;
         * @param string $from_email- from email;
         * @param string | array $to_name - an array or string recipeint name(s);
         * @param string | array $to_email - an array or string recipeint email address(es);
         * @return boolean
         **/
		public function email( $subject, $message, $from_name = NULL, $from_email = NULL, $to_name=NULL, $to_email=NULL  )
		{

			if ( constant("SMTP_HOST") && constant("SMTP_USERNAME") && constant("SMTP_PASSWORD") ) {
                IsSMTP();
                $this->Host = SMTP_HOST;
				$this->Port = SMTP_PORT;
				$this->SMTPAuth = SMTP_AUTH;
				$this->Username = SMTP_USERNAME;
				$this->Password = SMTP_PASSWORD;
			}
			
            $this->FromName = (isset($from_name)) ? $from_name : ADMIN_NAME;
            $this->From = (isset($from_email)) ? $from_email : ADMIN_EMAIL;
            if(isset($to_email) && isset($to_name)){
				if(is_string($to_email) && is_string($to_name)){
					$this->AddAddress($to_email, $to_name);
				}else{
					for ($i = 0; $i < count($to_email); $i++) {
						$this->AddAddress($to_email[$i], $to_name[$i]);
					}
				}
            }

            $this->Subject = $subject;
		    $this->Body = $message;
			return $this->Send();
		}
	}
	
	
  
?>

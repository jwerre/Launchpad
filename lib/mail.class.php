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
         * @param $subject string - email subject;
         * @param $message string - email message;
         * @param $from_name string - from names;
         * @param $from_email string - from email;
         * @param $to_name ( array | string ) - an array or string recipeint name(s);
         * @param $to_email ( array | string ) -an array or string recipeint email address(es);
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

<?php

class User_EMail {

    private $config;

    function __construct() {
        $this->config = new Zend_Config_Xml(PIMCORE_PLUGINS_PATH.'/User/config.xml');

        $smtpConfig = array(
            'ssl' => $this->config->email->ssl,
            'auth' => $this->config->email->auth,
            'username' => $this->config->email->username,
            'password' => $this->config->email->password,
            'port' => $this->config->emai->port
        );

        $tr = new Zend_Mail_Transport_Smtp($this->config->email->smtp, $smtpConfig);
        Zend_Mail::setDefaultTransport($tr);
    }


    /**
     * Send validation code
     * @param Object_User $user
     */
	public function sendValidation($user, $options = null) {


		// send conformation to user
		$mail = new Zend_Mail("utf-8");
		$mail->addTo($user->getEmail());
		$mail->setFrom($this->config->email->from);
		
		
		$mail->setSubject("Registracija: ".$_SERVER['HTTP_HOST']);
			
		$bodyText = '
Na spletni strani '.$_SERVER['HTTP_HOST'].' ste izpolnili obrazec za registracijo.


Za dokončanje registracije kliknite na spodnjo povezavo:
	http://'.$_SERVER['HTTP_HOST'].'/potrdi/'.$user->getValidation().'
	(če vas klik na povezavo ne preusmeri v brskalnik, skopirajte povezavo v brskalnik)


Če niste sami zahtevali naročila (nekdo drug vpisal vašo e-pošto), smatrajte to e-pošto kot brezpredmetno oz. nas obvestite o zlorabi.	
	
Zahvaljujemo se vam za registracijo.




';
		$mail->setBodyText($bodyText);
			
		try {
			$mail->send();
		} catch(Exception $e) {
			error_log("NAPAKA PRI POSILJANJU VALIDACIJE REGISTRACIJE UPORABNIKU: "+$e->getMessage());
		}
	}
	
	
	
	public function sendForgottenPassword($user) {
		$mail = new Zend_Mail("utf-8");
		$mail->addTo($user->getEmail());
		$mail->setFrom($this->config->email->from,$_SERVER['HTTP_HOST']);
		$mail->setSubject("Pozabljeno geslo : ".$_SERVER['HTTP_HOST']);
			
		$bodyText = '
Na spletni strani '.$_SERVER['HTTP_HOST'].' ste izpolnili zahtevo za vaše geslo.
Če niste sami zahtevali gesla (nekdo je vpisal vašo e-pošto), smatrajte to e-pošto kot nepredmetno oz. nas obvestite o zlorabi. 

Na naslovu: http://'.$_SERVER['HTTP_HOST'].'/restore-pass/'.$user->getRestorePassHash().' lahko obnovite vaše geslo.

Veselimo se vašega obiska. 


';
		$mail->setBodyText($bodyText);
						
		try {
			$mail->send();
		} catch(Exception $e) {
			error_log("ERROR User_Mail/sendForgottenPassword: "+$e->getMessage());
		}
	}
}
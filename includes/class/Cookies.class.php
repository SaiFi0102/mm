<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: ../../index.php');
	exit();
}

class Cookies
{
	
	public $cookie_timeout = 2592000;
	public $config = array();
	public $lifetime;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $COOKIECONF;
		$this->config['cookiepath'] = $COOKIECONF['cookiepath'];
		$this->config['cookiedomain'] = $COOKIECONF['cookiedomain'];
	}
	
	/**
	 * Sets a cookie
	 *
	 * @param string $name
	 * @param string $value
	 * @param bool $lifetime
	 * @return bool
	 */
	public function SetCookie($name, $value, $lifetime = false)
	{
		$expire = $lifetime ? time()+$this->cookie_timeout : 0;
		return setcookie($name, $value, $expire, $this->config['cookiepath'], $this->config['cookiedomain']) ? true : false;
	}
	
	/**
	 * Deletes a cookie, by setting the lifetime to -1 hour
	 *
	 * @param string $name
	 * @return bool
	 */
	public function DeleteCookie($name)
	{
		return setcookie($name, "", time()-3600, $this->config['cookiepath'], $this->config['cookiedomain']) ? true : false;
	}
}

?>
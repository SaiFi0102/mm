<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}
class Templates
{
	
	/**
	 * Replacement Tags
	 *
	 * @var array
	 */
	public $currentfile;
	public $theme;
	
	public function __construct($theme = "wow")
	{
		$this->theme = $theme;
	}
	
	/**
	 * Returns a message box with 2 seconds redirect delayer's URL
	 * 
	 * @param string $redirect_message
	 * @param string $redirect_location
	 */
	public function Redirect()
	{
		global $REDIRECT_INTERVAL, $REDIRECT_LOCATION;
		$return = $this->Output('redirect', false, false);
		$refresh = $REDIRECT_INTERVAL;
		$refresh = ($refresh/1000);
		$refresh = round($refresh, 2);
		$url = $REDIRECT_LOCATION;
		if(!headers_sent())
		{
			header('Refresh: '.$refresh.';url='.$url);
		}
		
		return $return;
	}
	
	/**
	* Returns a single template in a string from the templatecache or the file with all DynamiCore compatible tags
	*
	* @param string $templatename Name of template to be fetched
	* @param boolean $showheader Include header file or not
	* @param boolean $showfooter Include footer file or not
	* @param boolean $showheadinclude Include HTML headinclude file or not
	* @param boolean $gethtmlcomments Wrap template in HTML comments showing the template name?
	*
	* @return string
	* @example $templates->FetchTemplate($templatename, true, true, true, true, 0);
	* include(Output('news', true, true, true, true, 0));
	*/
	private function FetchTemplate($templatename)
	{
		if(empty($templatename))
		{
			exit("<table width=100% height=100%><tr><td valign=middle align=center><h1 style='color: #ababab;'>No template name is given on this page.</h1><a href='javascript:history.go(-1)'>Click here to go back.</a></td></tr></table>");
		}
		//variables
		$tpath = "templates/$this->theme/$templatename.php"; //Template file
		$error = "<table width=100% height=100%><tr><td valign=middle align=center><h1 style='color: #ababab;'>The template '$templatename' does not exist.</h1><a href='javascript:history.go(-1)'>Click here to go back.</a></td></tr></table>"; //Error Message when template file is not found
		
		if(!file_exists($tpath))
		{
			if($GLOBALS['AJAX_PAGE'] == true)
			{
				if(!file_exists("../../".$tpath))
				{
					exit($error);
				}
			}
			else
			{
				exit($error);
			}
		}
		return $tpath;
	}
	
	 /**
	 * Gives Template link
	 *
	 * @param string $templatename Name of template to be fetched
	 * @param boolean $showheader Include header file or not
	 * @param boolean $showfooter Include footer file or not
	 * @param boolean $showheadinclude Include HTML headinclude file or not
	 * 
	 * @return string
	 * @example include(Output('news', true, true, true, true, 0));
	 */
	public function Output($templatename, $showheader = true, $showfooter = true, $showheadinclude = true, $intemplate = false)
	{
		global $cms, $page_name;
		$error = "<table width=100% height=100%><tr><td valign=middle align=center><h1 style='color: #ababab;'>The theme '$this->theme' does not exist.</h1><a href='javascript:history.go(-1)'>Click here to go back.</a></td></tr></table>"; //Error Message when template file is not found
		$return = null;
		
		//Check if theme exists
		if(file_exists("templates/{$this->theme}/template.inc.php"))
		{
			if(!$GLOBALS['AJAX_PAGE'])
			{
				$return .= 'include_once("templates/'.$this->theme.'/template.inc.php");';
			}
		}
		else
		{
			exit($error);
		}
		
		//Varaibles
		$config = $cms->config;
		$access = $cms->CheckAccess();
		$dontfetch = false;
		
		if(!$access && !$intemplate)
		{
			if($templatename != 'redirect')
			{
				$problem = $cms->AccessProblem();
				if($problem == "login")
				{
					$return = '$page_name[] = array("No Access");
$page_name[] = array("Login"=>"login.php?ref=".urlencode($_SERVER[\'REQUEST_URI\']));
$REDIRECT_MESSAGE = "You have to be logged in to visit this page. You are being redirected to the login page.";
$REDIRECT_LOCATION = "login.php";
$REDIRECT_INTERVAL = 2000;
$REDIRECT_TYPE = "notice";
eval($templates->Redirect());
exit();';
					$dontfetch = true;
				}
				else
				{
					$templatename = $problem;
				}
			}
		}
		
		if(!$dontfetch)
		{
			if($showheadinclude) $headerincludes = $this->FetchTemplate("headerincludes");
			if($showheader) $header = $this->FetchTemplate("header");
			$template = $this->FetchTemplate($templatename);
			if($showfooter) $footer = $this->FetchTemplate("footer");
		}
		
		//Page Name Title Array
		if(!$intemplate)
		{
			$pagetitle = "<a href='index.php'>".$cms->config['websitename']."</a> &#187; ";
			$htmltitle = "";
			$metakeywords = $cms->config['websitename'];
			if(is_array($page_name))
			{
				foreach($page_name as $parray)
				{
					foreach($parray as $name => $url)
					{
						if(!$name)
						{
							//In case there is no url key($name) will be 0 and value($url) will be the name
							$pagetitle .= $url . " &#187; ";
							$htmltitle .= $url . " &#187; ";
							$metakeywords .= ",".$url;
						}
						else
						{
							$pagetitle .= "<a href='{$url}'>" . $name . "</a> &#187; ";
							$htmltitle .= $name . " &#187; ";
							$metakeywords .= ",".$name;
						}
					}
				}
			}
			$pagetitle = substr($pagetitle, 0, -8);
			$htmltitle .= $cms->config['websitetitle'];
			$metakeywords .= ",".$cms->config["metakeyw"];
			
			$return .= '$COPYRIGHT = $cms->config["copyright"];		
$TITLE = "'.$htmltitle.'";
$PAGETITLE = "'.$pagetitle.'";
$META_KEYWORDS = "'.$metakeywords.'";
$META_DESCRIPTION = $cms->config["metadesc"];
$META_EXTRA = $cms->config["metaextra"];';
			
			$pageendtime = microtime(true);
			$totaltime = $pageendtime - START_TIME;
			$totaltime = round($totaltime, 4);
			$return .= '$executiontime = '.$totaltime.' . " Seconds";';
		}
		
		if(!$dontfetch)
		{
			if($showheadinclude) $return .= " include('$headerincludes');";
			if($showheader) $return .= " include('$header');";
			$return .= " include('$template');";
			if($showfooter) $return .= " include('$footer');";
		}
		return $return;
	}
	
}
?>
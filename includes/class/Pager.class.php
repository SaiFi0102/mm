<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

class Pager {
        // Total pages in constructed Pager object.
        public $total_pages = 1;
        // Elements of target array per page.
        public $elem_per_page = 10;
        // Total number of target array's elements.
        public $count_elements = 0;
        // Do not use it.
        private $arr = array();
        // Current Page
        public $cp = null;
        // GET name
        public $paramName;

        // Class' constructor. Creates object.
        // Usage:
        // $pager = new Pager($target_array,$elems_per_page);
        //
        function __construct($total, $paramName = 'pageno', $per_page=10)
        {
				$this->elem_per_page = $per_page;
				$this->paramName = $paramName;
                
				if(is_array($total))
                {
                	$this->count_elements = count($total);
                }
                elseif(is_string($total))
                {
                	$this->count_elements = settype($total, "integer");
                }
                elseif(is_double($total) || is_float($total))
                {
                	$this->count_elements = floor($total);
                }
                else
                {
                	$this->count_elements = $total;
                }
				
                if (!isset($_GET[$paramName]))
                {
                	$this->cp = 1;
                }
                else
                {
                	$this->cp = $_GET[$paramName];
                }
				if($this->cp < 1)
				{
					$this->cp = 1;
				}
        }

        // Method Page. Returns target array's page object number $pageno.
        // Usage:
        // $page = $pager->page($pageno);
        //
        function page() {
                $page = new Page($this->cp);

                $from = $this->elem_per_page * ($this->cp-1)+1;
                $to = $this->elem_per_page;
                if ($to > $this->count_elements) {
                        $to = $this->count_elements;
                }

                $res = array();
                if($this->arr)
                {
                	reset($this->arr);
                }
                for ($i = $from; $i < ($to); $i++) {
                        if(isset($this->arr[$i]))$res[]=$this->arr[$i];
                }

                $page->setFrom($from);
                $page->setTo($to);
                $page->setResult($res);
                return $page;
        }

        // Prints page numbers
        // Usage:
        // $url = "$PHP_SELF?myvar=test";
        // $pager->printPageNumbers($pageno,'series','numbers',$url,'pageno',$seriesrange);
        //
        // $pageno is current page number.
        // 'all' or 'series' prints all page numbers or just $seriesrange pages backward and forward from current page.
        // 'numbers' prints '[1] [2]' type page numbers, 'from' prints '[1] [13] [25]' type 
        // page numbers, 'fromto' prints '[1-12] [13-24] [25-36]' type page numbers.
        // $url prints numbers with given URL plus 'pageno' parameter as page numbers variable.
        // If you print 'all' page numbers, $seriesrange variable doesn't mean nothing.
        // 
        // Simplest way to use:
        // $pager->printPageNumbers($pageno);
        //
        function printPageNumbers($seriesrange=3, $range='series', $type='numbers')
        {
                if ( $this->count_elements % $this->elem_per_page == 0 )
                {
                        $this->total_pages = floor($this->count_elements/$this->elem_per_page);
                }
                else {
                        $this->total_pages = floor($this->count_elements/$this->elem_per_page)+1;
                }
                $return = "";
                $range = (empty($range))?'all':$range;
                $type = (empty($types))?'numbers':$type;
                $cp = $this->cp;
				$url = preg_replace("/&$this->paramName=(.+)/i", "", $_SERVER['REQUEST_URI']);
				$url = preg_replace("/\?$this->paramName=(.+)/i", "", $url);
				preg_match("/\?(.*?)/i", $url) ? $get = "&" : $get = "?";
				$url = $url.$get.$this->paramName."=";
                switch ($range) {
                        case 'all':
                                for ($i=1; $i <= $this->total_pages; $i++) {
                                        $page = $this->page($i);
                                        switch ($type) {
                                        case 'numbers':
                                                if ($i != $cp) {
                                                        $return .= "[<a href=\"$url$i\">$i</a>] ";
                                                }
                                                else {
                                                        $return .= "[$i] ";
                                                }
                                                break;
                                        case 'from':
                                                if ($i != $cp) {
                                                        $return .= "[<a href=\"$url$i\">$page->from</a>] ";
                                                }
                                                else {
                                                        $return .= "[$page->from] ";
                                                }
                                                break;
                                        case 'fromto':
                                                if ($i != $cp) {
                                                        $return .= "[<a href=\"$url$i\">{$page->from}-{$page->to}</a>] ";
                                                }
                                                else {
                                                        $return .= "[{$page->from}-{$page->to}] ";
                                                }
                                                break;
                                        }
                                }
                                break;
                        case 'series':
                                if ($cp == $this->total_pages)
                                { 
                                        $seriesrange = $seriesrange*2;
                                }
                                else
                                {
                                	if ($cp == 1)
                                	{
										$seriesrange = $seriesrange*2;
									}
                                }
                                $from = ($cp-$seriesrange < 1) ? 1 : $cp-$seriesrange ;
                                $to = ($cp+$seriesrange > $this->total_pages) ? $this->total_pages : $cp+$seriesrange ;
                                $prev = ($cp > 1)? $cp-1 : null ;
                                $next = ($cp < $this->total_pages)? $cp+1 : null;
                                $return .= "<a href=\"{$url}1\">&lt;&lt;</a> "; //FIRST
                                if($prev)
								{
									$return .= "<a href=\"$url$prev\">&lt</a> "; //PREVIOS
								}
                                for ($i=$from; $i <= $to; $i++) {
                                        $page = $this->page($i);
                                        switch ($type) {
                                        case 'numbers':
                                                if ($i != $cp) {
                                                        $return .= "[<a href=\"$url$i\">$i</a>] ";
                                                }
                                                else {
                                                        $return .= "[$i] ";
                                                }
                                                break;
                                        case 'from':
                                                if ($i != $cp) {
                                                        $return .= "[<a href=\"$url$i\">$page->from</a>] ";
                                                }
                                                else {
                                                        $return .= "[$page->from] ";
                                                }
                                                break;
                                        case 'fromto':
                                                if ($i != $cp) {
                                                        $return .= "[<a href=\"$url$i\">$page->from-$page->to</a>] ";
                                                }
                                                else {
                                                        $return .= "[$page->from-$page->to] ";
                                                }
                                                break;
                                        }
                                }
                                if($next)
                                {
                                	$return .= "<a href=\"$url$next\">&gt;</a> ";
                                }
                                $return .= "<a href=\"$url$this->total_pages\">&gt;&gt;</a> ";
                                break;
                }
                return $return;
        }
        
		function GetMysqlLimit()
		{
			$start = $this->elem_per_page * ($this->cp - 1);
			$end = $this->elem_per_page;
			
			return "$start,$end";
		}
}

class Page {
        // Current page number
        var $pageno=1;
        // Target array's elements starts from $from.
        var $from=1;
        // Target array's elements ends to $to.
        var $to=1;
        // Result array.
        var $result = array();

        // Creates Page object.
        // Usage:
        // $page = $pager->page($pageno);
        //
        function Page($page=1) {
                $this->pageno = $page;
        }

        // Do not use it.
        function setFrom($from=1) {
                $this->from = $from;
        }

        // Do not use it.
        function setTo($to=1) {
                $this->to = $to;
        }

        // Do not use it.
        function setResult($res=array()) {
                $this->result = $res;
        }

        // returns Page's result array.
        function getResult() {
                return $this->result;
        }
}

?>

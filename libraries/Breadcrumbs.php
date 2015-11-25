<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Breadcrumbs Class
 *
 * This class manages the breadcrumb object
 *
 * @package		Breadcrumb
 * @version		1.1.3
 * @last edit	25/11/2015
 * @author 		Buti <buti@nobuti.com>
 * @edited by	Ranielly Ferreira <raniellyferreira@outlook.com>
 * @copyright 	Copyright (c) 2012-2015, Buti
 * @link		https://github.com/nobuti/codeigniter-breadcrumb
 * @link		https://github.com/raniellyferreira/Codeigniter-breadcrumbs
 */
 
 if(false)
 {
/*
| -------------------------------------------------------------------
| BREADCRUMB CONFIG
| -------------------------------------------------------------------
| This file will contain some breadcrumbs' settings.
| Defaults provided for twitter bootstrap 2.0
*/
$config['crumb']['divider'] 			= '<span class="divider">/</span>';
$config['crumb']['full_tag_open'] 	= '<ul class="breadcrumb">';
$config['crumb']['full_tag_close'] 	= '</ul>';
$config['crumb']['tag_open'] 		= '<li>';
$config['crumb']['tag_close'] 		= '</li>';
$config['crumb']['last_tag_open'] 	= '<li class="active">';
$config['crumb']['last_tag_close'] 	= '</li>';
$config['crumb']['a_model'] 			= '<a href="{href}">{page}</a>';
 }
 
 
class Breadcrumbs_model extends CI_Model {
	
	/**
	 * Breadcrumbs stack
	 *
     */
	private $breadcrumbs = array();
	private $configs = array();
	
	public $divider 			= '<span class="divider">/</span>';
	public $full_tag_open 	= '<ul class="breadcrumb">';
	public $full_tag_close 	= '</ul>';
	public $tag_open 		= '<li>';
	public $tag_close 		= '</li>';
	public $last_tag_open 	= '<li class="active">';
	public $last_tag_close 	= '</li>';
	public $a_model 			= '<a href="{href}">{page}</a>';
	 	
	 /**
	  * Constructor
	  *
	  * @access	public
	  *
	  */
	public function __construct()
	{
		parent::__construct();
		
		$this->configs = $this->config->item('crumb');
		
		// Load configs
		self::_load();
		
		log_message('debug', "Breadcrumbs Class Initialized");
	}
	
	// --------------------------------------------------------------------
	
	/**
	  * Load configs
	  *
	  * @access	private
	  *
	  */
	private function _load()
	{
		if(!empty($this->configs))
		{
			foreach($this->configs as $k => $item)
			{
				if(isset($this->{$k}))
				{
					$this->{$k} = $item;
				}
			}
		}
	}
	
	/**
	  * Load configs manualy
	  *
	  * @access	private
	  *
	  */
	public function load($array = array())
	{
		if(!empty($array))
		{
			foreach($array as $k => $item)
			{
				if(isset($this->{$k}))
				{
					$this->{$k} = $item;
				}
			}
		}
		return $this;
	}
	
	/**
	 * Append crumb to stack
	 *
	 * @access	public
	 * @param	string $page
	 * @param	string $href
	 * @return	void
	 */		
	function push($page, $href = NULL)
	{
		// no page or href provided
		if (empty($page)) return $this;
		
		if(is_scalar($page))
		{
			if(!empty($href))
			{
				// Prepend site url
				$href = site_url($href);
			
				// push breadcrumb
				$this->breadcrumbs[$href] = array('page' => $page, 'href' => $href);
			}
			else
			{
				// push breadcrumb
				$this->breadcrumbs[] = array('page' => $page, 'href' => '_nolink');
			}
		}
		else
		{
			foreach($page as $k => $v)
			{
				self::push($k,$v);
			}
		}
		
		return $this;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Prepend crumb to stack
	 *
	 * @access	public
	 * @param	string $page
	 * @param	string $href
	 * @return	void
	 */		
	function unshift($page, $href)
	{
		// no page or href provided
		if (empty($page) OR empty($href)) return $this;
		
		if(is_scalar($page))
		{
			// Prepend site url
			$href = site_url($href);
			
			// add at firts
			array_unshift($this->breadcrumbs, array('page' => $page, 'href' => $href));
		}
		else
		{
			foreach($page as $k => $v)
			{
				self::unshift($k,$v);
			}
		}
		
		return $this;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Generate breadcrumb
	 *
	 * @access	public
	 * @return	string
	 */		
	function show()
	{
		if (!empty($this->breadcrumbs))
		{
			// set output variable
			$output = $this->full_tag_open;
			$arrOutput = array();
			
			// construct output
			foreach ($this->breadcrumbs as $key => $crumb) 
			{
				$keys = array_keys($this->breadcrumbs);
				
				if(end($keys) == $key || $crumb['href'] == '_nolink')
				{
					$arrOutput[] = $this->last_tag_open . '' . $crumb['page'] . '' . $this->last_tag_close;
				}
				else
				{
					$arrOutput[] = $this->tag_open.self::html_vars($this->a_model,$crumb).$this->tag_close;
				}
			}
			
			// return output
			return $this->full_tag_open . implode($this->divider,$arrOutput) . $this->full_tag_close . PHP_EOL;
		}
		
		// no crumbs
		return NULL;
	}
	
	/**
	 * Replace html vars
	 *
	 */	
	 public function html_vars($str,$vars = array())
	 {
		 foreach($vars as $k => $v)
		 {
			 $str = str_replace('{'.$k.'}',$v,$str);
		 }
		 return $str;
	 }
	// --------------------------------------------------------------------
	
	
}
// END Breadcrumbs Class

/* End of file Breadcrumbs.php */
/* Location: ./application/libraries/Breadcrumbs.php */

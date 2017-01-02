<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Ezset\Library\Addon\AddonHelper;
use Joomla\String\StringHelper;
use Windwalker\Data\Data;
use Windwalker\Registry\Registry;
use Windwalker\System\ExtensionHelper;

/**
 * Class Ezset
 *
 * @property-read  JApplicationCms  app
 * @property-read  Registry         data
 * @property-read  Registry         params
 * @property-read  JInput           input
 *
 * @since 1.0
 */
class Ezset
{
	/**
	 * Property app.
	 *
	 * @var  JApplicationCms
	 */
	protected $app;

	/**
	 * Property input.
	 *
	 * @var  JInput
	 */
	protected $input;

	/**
	 * Property params.
	 *
	 * @var  Registry
	 */
	protected $params;

	/**
	 * Property data.
	 *
	 * @var Registry
	 */
	protected $data;

	/**
	 * Property instance.
	 *
	 * @var  Ezset
	 */
	protected static $instance;

	/**
	 * Get Easyset Instance.
	 *
	 * @param JApplicationCms $app
	 *
	 * @return Ezset
	 */
	public static function getInstance(JApplicationCms $app = null)
	{
		if (!static::$instance)
		{
			static::$instance = new static($app);
		}

		return static::$instance;
	}

	/**
	 * Ezset constructor.
	 *
	 * @param JApplicationCms $app
	 */
	protected function __construct(JApplicationCms $app = null)
	{
		$this->app   = $app ? : JFactory::getApplication();
		$this->input = $this->app->input;
		$this->data  = new Registry;

		$this->params = static::getAddonParams();
	}

	/**
	 * get
	 *
	 * @param string $name
	 * @param mixed  $default
	 *
	 * @return  mixed
	 */
	public function get($name, $default = null)
	{
		return $this->params->get($name, $default);
	}

	/**
	 * set
	 *
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return  static
	 */
	public function set($name, $value)
	{
		$this->params->set($name, $value);

		return $this;
	}

	/**
	 * triggerEvent
	 *
	 * @param string $event
	 * @param array  $args
	 *
	 * @return  array
	 */
	public function triggerEvent($event, $args = array())
	{
		return $this->app->triggerEvent($event, $args);
	}

	/**
	 * call
	 *
	 * @param   callable $callable
	 *
	 * @return  mixed|null
	 *
	 * @throws  InvalidArgumentException
	 */
	public function call($callable)
	{
		if (!is_array($callable))
		{
			$callable = explode('::', $callable);
		}

		if (count($callable) < 2)
		{
			throw new \InvalidArgumentException(implode('::', $callable) . ' is not callable.');
		}

		$class = \Windwalker\String\StringNormalise::toClassNamespace($callable[0]);

		$classname = 'MyEzset_' . trim(str_replace('\\', '_', $class), '\\');

		if (!is_callable(array($classname, $callable[1])))
		{
			$classname = 'MyEzset\\' . trim($class, '\\');
		}

		if (!is_callable(array($classname, $callable[1])))
		{
			$classname = 'Ezset\\' . trim($class, '\\');
		}

		if (!is_callable(array($classname, $callable[1])))
		{
			return null;
		}

		$args = func_get_args();

		array_shift($args);

		return call_user_func_array(array($classname, $callable[1]), $args);
	}

	/**
	 * Detect is this page are frontpage?
	 *
	 * @return  boolean Is frontpage?
	 */
	public static function isHome()
	{
		$langPath = null;
		$tag      = null;
		$lang     = \JFactory::getLanguage();

		// For multi language
		if (\JPluginHelper::isEnabled('system', 'languagefilter'))
		{
			$tag = $lang->getTag();
			$langCodes = \JLanguageHelper::getLanguages('lang_code');

			$langPath = $langCodes[$tag]->sef;
		}

		$uri  = \JUri::getInstance();
		$root = $uri::root(true);

		// Get site route
		$route = StringHelper::substr($uri->getPath(), StringHelper::strlen($root));

		// Remove index.php
		$route = str_replace('index.php', '', $route);

		// If Multiple language enabled, we check first part of URI is language code or not.
		if ($langPath)
		{
			$params = ExtensionHelper::getParams('plg_system_languagefilter');

			if ($tag == $lang->getDefault() && $params->get('remove_default_prefix', 0))
			{
				$langPath = '';
			}

			// If route equals lang path, means it is home route.
			if (trim($route, '/') == $langPath && ! $uri->getVar('option'))
			{
				return true;
			}
		}
		else
		{
			if (! trim($route, '/') && ! $uri->getVar('option'))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Has Html <head> so we can add custom tags.
	 *
	 * @return  bool
	 */
	public static function hasHtmlHeader()
	{
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();

		if ($app->isAdmin() || $doc->getType() !== 'html')
		{
			return false;
		}

		return true;
	}

	/**
	 * getFrontendPath
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public static function getFrontendPath($path = null)
	{
		$path = $path === null ? '' : '/' . ltrim($path, '/');

		return JPATH_ROOT . '/ezset' . $path;
	}

	/**
	 * getParams
	 *
	 * @return  Registry
	 */
	public static function getParams()
	{
		return Ezset::getInstance()->params;
	}

	/**
	 * getParams
	 *
	 * @return  Registry
	 */
	protected static function getAddonParams()
	{
		$addons = AddonHelper::getAddons();

		$registry = new Registry;

		foreach ($addons as $addon)
		{
			$data = $addon->getData();

			$registry->set($addon->getName(), json_decode($data['params']));
		}

		return $registry;
	}

	/**
	 * Method to set property data
	 *
	 * @param   Registry $data
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * __get
	 *
	 * @param string $name
	 *
	 * @return  mixed
	 * @throws \OutOfRangeException
	 */
	public function __get($name)
	{
		$allows = array(
			'data',
			'app',
			'params',
			'input'
		);

		if (in_array($name, $allows))
		{
			return $this->$name;
		}

		throw new \OutOfRangeException(sprintf('Property: %s not exists in %s', $name, get_called_class()));
	}
}

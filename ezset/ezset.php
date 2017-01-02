<?php
/**
 * @package        Asikart.Plugin
 * @subpackage     system.plg_ezset
 * @copyright      Copyright (C) 2014 Asikart.com, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\Data\Data;
use Windwalker\Registry\Registry;

// No direct access
defined('_JEXEC') or die;

$result = include_once __DIR__ . '/src/init.php';

if ($result):

/**
 * Ezset System Plugin
 *
 * @package        Joomla.Plugin
 * @subpackage     System.ezset
 * @since          1.0
 */
class PlgSystemEzset extends JPlugin
{
	/**
	 * Property app.
	 *
	 * @var  JApplicationCms
	 */
	protected $app;

	/**
	 * Property ezset.
	 *
	 * @var  \Ezset
	 */
	protected $ezset;

	/**
	 * Property params.
	 *
	 * @var  Registry
	 */
	public $params;

	/**
	 * Constructor
	 *
	 * @param  object  $subject The object to observe
	 * @param  array   $config  An array that holds the plugin configuration
	 */
	public function __construct($subject, $config)
	{
		$this->ezset = Ezset::getInstance();

		parent::__construct($subject, $config);

		$this->params = $this->ezset->params;

		$this->loadLanguage();

		$this->call(array('System\\Cache', 'prepareEzsetData'), $this->ezset);
	}

	// System Events
	// ======================================================================================

	/**
	 * onAfterInitialise
	 *
	 * @return  void
	 */
	public function onAfterInitialise()
	{
	}

	/**
	 * onAfterRoute
	 *
	 * @return  void
	 */
	public function onAfterRoute()
	{

	}

	/**
	 * onAfterDispatch
	 *
	 * @return  void
	 */
	public function onAfterDispatch()
	{

	}

	/**
	 * onAfterRender
	 *
	 * @return  void
	 */
	public function onAfterRender()
	{
	}

	/**
	 * onBeforeCompileHead
	 *
	 * @return  void
	 */
	public function onBeforeCompileHead()
	{
		$this->call(array('Seo\\Document', 'favicon'));

		/** @see \Ezset\Core\HtmlHeader::prepareHtmlHeader */
		$this->call('Core\HtmlHeader::prepareHtmlHeader', $this);
	}

	// Content Events
	// ======================================================================================

	/**
	 * Ezset prepare content method
	 * Method is called by the view
	 *
	 * @param   string $context The context of the content being passed to the plugin.
	 * @param   object $article The content object.  Note $article->text is also available.
	 * @param   object $params  The content params.
	 * @param   int    $page    The 'page' number.
	 *
	 * @return  void
	 */
	public function onContentPrepare($context, $article, $params, $page = 0)
	{
	}

	/**
	 * Ezset after display title method
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param   string $context The context of the content being passed to the plugin.
	 * @param   object $article The content object.  Note $article->text is also available.
	 * @param   object $params  The content params.
	 * @param   int    $page    The 'page' number.
	 *
	 * @return  string
	 */
	public function onContentAfterTitle($context, $article, $params, $page = 0)
	{
		$result = null;

		return $result;
	}

	/**
	 * Ezset before display content method
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param   string $context The context of the content being passed to the plugin.
	 * @param   object $article The content object.  Note $article->text is also available.
	 * @param   object $params  The content params.
	 * @param   int    $page    The 'page' number.
	 *
	 * @return  string
	 */
	public function onContentBeforeDisplay($context, $article, $params, $page = 0)
	{
		$result = null;

		return $result;
	}

	/**
	 * Ezset after display content method
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param   string $context The context of the content being passed to the plugin.
	 * @param   object $article The content object.  Note $article->text is also available.
	 * @param   object $params  The content params.
	 * @param   int    $page    The 'page' number.
	 *
	 * @return  string
	 */
	public function onContentAfterDisplay($context, $article, $params, $page = 0)
	{
		$result = null;

		$input = \JFactory::getApplication();

		return $result;
	}

	/**
	 * Ezset before save content method
	 * Method is called right before content is saved into the database.
	 * Article object is passed by reference, so any changes will be saved!
	 * NOTE:  Returning false will abort the save with an error.
	 *You can set the error by calling $article->setError($message)
	 *
	 * @param   string $context The context of the content being passed to the plugin.
	 * @param   object $article A JTableContent object.
	 * @param   bool   $isNew   If the content is just about to be created.
	 *
	 * @return  bool  If false, abort the save.
	 */
	public function onContentBeforeSave($context, $article, $isNew)
	{
		$result = array();

		return $this->assertAllTrue($result);
	}

	/**
	 * Ezset after save content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved
	 *
	 * @param   string  $context The context of the content being passed to the plugin.
	 * @param   object  $article A JTableContent object.
	 * @param   boolean $isNew   If the content is just about to be created.
	 *
	 * @return  boolean
	 */
	public function onContentAfterSave($context, $article, $isNew)
	{
		$result = array();

		return $this->assertAllTrue($result);
	}

	/**
	 * Ezset before delete method.
	 *
	 * @param   string $context The context for the content passed to the plugin.
	 * @param   object $data    The data relating to the content that is to be deleted.
	 *
	 * @return  boolean  False to abort.
	 */
	public function onContentBeforeDelete($context, $data)
	{
		$result = array();

		return $this->assertAllTrue($result);
	}

	/**
	 * Ezset after delete method.
	 *
	 * @param   string $context The context for the content passed to the plugin.
	 * @param   object $data    The data relating to the content that is to be deleted.
	 *
	 * @return  boolean
	 */
	public function onContentAfterDelete($context, $data)
	{
		$result = array();

		return $this->assertAllTrue($result);
	}

	/**
	 * Ezset on change state method.
	 *
	 * @param   string $context The context for the content passed to the plugin.
	 * @param   array  $pks     A list of primary key ids of the content that has changed state.
	 * @param   int    $value   The value of the state that the content has been changed to.
	 *
	 * @return  boolean
	 */
	public function onContentChangeState($context, $pks, $value)
	{
		$result = array();

		return $this->assertAllTrue($result);
	}



	// Form Events
	// ====================================================================================

	/**
	 * Pre process form hook.
	 *
	 * @param   JForm $form The form to be altered.
	 * @param   array $data The associated data for the form.
	 *
	 * @return  boolean
	 */
	public function onContentPrepareForm($form, $data)
	{
		$app    = JFactory::getApplication();
		$result = null;

		return $result;
	}

	// User Events
	// ====================================================================================

	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 * @param   array   $user    Holds the new user data.
	 * @param   boolean $isNew   True if a new user is stored.
	 * @param   boolean $success True if user was succesfully stored in the database.
	 * @param   string  $msg     Message.
	 *
	 * @return  boolean
	 */
	public function onUserBeforeSave($user, $isNew, $success, $msg = null)
	{
		$result = array();

		return $this->assertAllTrue($result);
	}

	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 * @param   array   $user    Holds the new user data.
	 * @param   boolean $isNew   True if a new user is stored.
	 * @param   boolean $success True if user was succesfully stored in the database.
	 * @param   string  $msg     Message.
	 *
	 * @return    boolean
	 */
	public function onUserAfterSave($user, $isNew, $success, $msg = null)
	{
		$result = array();

		return $this->assertAllTrue($result);
	}

	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @param   array $user    Holds the user data
	 * @param   array $options Array holding options (remember, autoregister, group)
	 *
	 * @return  boolean  True on success
	 */
	public function onUserLogin($user, $options = array())
	{
		$result = array();

		return $this->assertAllTrue($result);
	}

	/**
	 * This method should handle any logout logic and report back to the subject
	 *
	 * @param   array $user    Holds the user data.
	 * @param   array $options Array holding options (client, ...).
	 *
	 * @return  object  True on success
	 */
	public function onUserLogout($user, $options = array())
	{
		$result = array();

		return $this->assertAllTrue($result);
	}

	/**
	 * Utility method to act on a user before it has been saved.
	 *
	 * @param   array   $user    Holds the new user data.
	 * @param   boolean $isnew   True if a new user is stored.
	 * @param   boolean $success True if user was succesfully stored in the database.
	 * @param   string  $msg     Message.
	 *
	 * @return  boolean
	 */
	public function onUserBeforeDelete($user, $isnew, $success, $msg)
	{
		$result = array();

		return $this->assertAllTrue($result);
	}

	/**
	 * Remove all sessions for the user name
	 *
	 * @param   array   $user    Holds the user data
	 * @param   boolean $success True if user was succesfully stored in the database
	 * @param   string  $msg     Message
	 *
	 * @return  boolean
	 */
	public function onUserAfterDelete($user, $success, $msg)
	{
		$result = array();

		return $this->assertAllTrue($result);
	}

	/**
	 * Prepare content data.
	 *
	 * @param   string $context The context for the data
	 * @param   int    $data    The user id
	 *
	 * @return  boolean
	 */
	public function onContentPrepareData($context, $data)
	{
		$result = array();

		return $this->assertAllTrue($result);
	}

	/**
	 * resultBool
	 *
	 * @param bool[] $results
	 *
	 * @return  bool
	 */
	public function assertAllTrue(array $results = array())
	{
		if (in_array(false, $results))
		{
			return false;
		}

		return true;
	}

	/**
	 * call
	 *
	 * @param   callable  $callable
	 *
	 * @return  mixed
	 */
	public function call($callable)
	{
		return call_user_func_array(array($this->ezset, 'call'), func_get_args());
	}
}

endif;

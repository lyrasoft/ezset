<?php
/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2014 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace MyEzset\Listener\Acme;

/**
 * The AcmeListener class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class AcmeListener extends \JEvent
{
	/**
	 * After Initialise Event.
	 */
	public function onAfterInitialise()
	{

	}

	/**
	 * After Route Event.
	 */
	public function onAfterRoute()
	{

	}

	/**
	 * After Dispatch Event.
	 */
	public function onAfterDispatch()
	{

	}

	/**
	 * After Render Event.
	 */
	public function onAfterRender()
	{

	}

	// Content Events
	// ======================================================================================

	/**
	 * Example prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param   string  $context  The context of the content being passed to the plugin.
	 * @param   object  $article  The content object.  Note $article->text is also available
	 * @param   object  $params   The content params
	 * @param   int     $page     The 'page' number
	 *
	 * @return  void
	 */
	public function onContentPrepare($context, $article, $params, $page=0)
	{
	}

	/**
	 * Example after display title method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param   string  $context  The context for the content passed to the plugin.
	 * @param   object  $article  The content object.  Note $article->text is also available
	 * @param   object  $params   The content params
	 * @param   int     $page     The 'page' number
	 *
	 * @return  string
	 */
	public function onContentAfterTitle($context, $article, $params, $page=0)
	{
	}

	/**
	 * Example before display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param   string  $context  The context for the content passed to the plugin.
	 * @param   object  $article  The content object.  Note $article->text is also available
	 * @param   object  $params   The content params
	 * @param   int     $page     The 'page' number
	 *
	 * @return  string
	 */
	public function onContentBeforeDisplay($context, &$article, &$params, $page=0)
	{
	}

	/**
	 * Example after display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param   string  $context  The context for the content passed to the plugin.
	 * @param   object  $article  The content object.  Note $article->text is also available
	 * @param   object  $params   The content params
	 * @param   int     $page     The 'page' number
	 *
	 * @return  string
	 */
	public function onContentAfterDisplay($context, $article, $params, $page=0)
	{
	}

	/**
	 * Example before save content method
	 *
	 * Method is called right before content is saved into the database.
	 * Article object is passed by reference, so any changes will be saved!
	 *
	 * NOTE:  Returning false will abort the save with an error.
	 * You can set the error by calling $article->setError($message)
	 *
	 * @param   string  $context  The context of the content passed to the plugin.
	 * @param   object  $article  A JTableContent object
	 * @param   bool    $isNew    If the content is just about to be created
	 *
	 * @return  bool  If false, abort the save
	 */
	public function onContentBeforeSave($context, $article, $isNew)
	{
		return true;
	}

	/**
	 * Example after save content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved
	 *
	 * @param   string  $context  The context of the content passed to the plugin.
	 * @param   object  $article  A JTableContent object
	 * @param   bool    $isNew    If the content is just about to be created
	 *
	 * @return  void
	 */
	public function onContentAfterSave($context, $article, $isNew)
	{
	}

	/**
	 * Example before delete method.
	 *
	 * @param    string  $context  The context for the content passed to the plugin.
	 * @param    object  $data     The data relating to the content that is to be deleted.
	 *
	 * @return   boolean  If false, abort the delete.
	 */
	public function onContentBeforeDelete($context, $data)
	{
		return true;
	}

	/**
	 * Example after delete method.
	 *
	 * @param    string  $context  The context for the content passed to the plugin.
	 * @param    object  $data     The data relating to the content that is to be deleted.
	 *
	 * @return   void
	 */
	public function onContentAfterDelete($context, $data)
	{
	}

	/**
	 * Example after delete method.
	 *
	 * @param   string  $context  The context for the content passed to the plugin.
	 * @param   array   $pks      A list of primary key ids of the content that has changed state.
	 * @param   int     $value    The value of the state that the content has been changed to.
	 *
	 * @return  boolean
	 */
	public function onContentChangeState($context, $pks, $value)
	{
		return true;
	}

	// Form Events
	// ====================================================================================

	/**
	 * @param   \JForm  $form  The form to be altered.
	 * @param   array   $data  The associated data for the form.
	 *
	 * @return   boolean
	 */
	public function onContentPrepareForm($form, $data)
	{
		return true;
	}

	// User Events
	// ====================================================================================

	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 *
	 * @param   array    $user     Holds the new user data.
	 * @param   boolean  $isNew    True if a new user is stored.
	 * @param   boolean  $success  True if user was succesfully stored in the database.
	 * @param   string   $msg      Message.
	 *
	 * @return  boolean
	 */
	public function onUserBeforeSave($user, $isNew, $success, $msg = null)
	{
		return true;
	}

	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 *
	 * @param   array    $user     Holds the new user data.
	 * @param   boolean  $isNew    True if a new user is stored.
	 * @param   boolean  $success  True if user was succesfully stored in the database.
	 * @param   string   $msg      Message.
	 *
	 * @return    void
	 * @since    1.6
	 */
	public function onUserAfterSave($user, $isNew, $success, $msg = null)
	{
	}

	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @param   array  $user     Holds the user data
	 * @param   array  $options  Array holding options (remember, autoregister, group)
	 *
	 * @return  void
	 */
	public function onUserLogin($user, $options = array())
	{
	}

	/**
	 * This method should handle any logout logic and report back to the subject
	 *
	 * @param   array  $user     Holds the user data
	 * @param   array  $options  Array holding options (remember, autoregister, group)
	 *
	 * @return  void
	 */
	public function onUserLogout($user, $options = array())
	{
	}

	/**
	 * Utility method to act on a user before it has been saved.
	 *
	 *
	 * @param   array    $user     Holds the new user data.
	 * @param   boolean  $isnew    True if a new user is stored.
	 * @param   boolean  $success  True if user was succesfully stored in the database.
	 * @param   string   $msg      Message.
	 *
	 * @return  void
	 */
	public function onUserBeforeDelete($user, $isnew, $success, $msg = null)
	{
	}

	/**
	 * Remove all sessions for the user name
	 *
	 * @param   array    $user     Holds the user data
	 * @param   boolean  $success  True if user was succesfully stored in the database
	 * @param   string   $msg      Message
	 *
	 * @return  void
	 */
	public function onUserAfterDelete($user, $success, $msg)
	{
	}

	/**
	 * Event of onContentPrepareData
	 *
	 * @param   string  $context  The context for the data
	 * @param   int     $data     The data.
	 *
	 * @return  boolean
	 */
	public function onContentPrepareData($context, $data)
	{
		return true;
	}

	/**
	 * Event of onBeforeCompileHead
	 *
	 * @return  void
	 */
	public function onBeforeCompileHead()
	{
	}
}

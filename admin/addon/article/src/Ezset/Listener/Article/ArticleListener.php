<?php
/**
 * Part of 360 project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

namespace Ezset\Listener\Article;

use Ezset\Listener\AbstractEzsetListener;

/**
 * The ArticleListener class.
 *
 * @since  __DEPLOY_VERSION__
 */
class ArticleListener extends AbstractEzsetListener
{
	/**
	 * onAfterInitialise
	 *
	 * @return  void
	 */
	public function onAfterInitialise()
	{
		if ($this->params->get('article.seo.AliasTranslate', 0) && \Ezset::hasHtmlHeader())
		{
			/** @see \Ezset\Article\Translate::translateAlias */
			$this->call('Article\Translate::translateAlias', $this->ezset);
		}
	}

	/**
	 * onAfterRoute
	 *
	 * @return  void
	 */
	public function onAfterRoute()
	{
		if ($this->params->get('article.social.openGraph', 0) && \Ezset::hasHtmlHeader())
		{
			/** @see \Ezset\Article\Opengraph::disableGzip */
			$this->call('Article\OpenGraph::disableGzip');
		}

		$this->call('Article\Opengraph::prepareGlobalOpengraph');
	}

	/**
	 * onAfterDispatch
	 *
	 * @return  void
	 */
	public function onAfterDispatch()
	{
		// SEO Title
		/** @see \Ezset\Article\ContentSeo::setTitle */
		$this->call('Article\ContentSeo::setTitle');

		// OpenGraph
		if ($this->params->get('article.social.Opengraph', 0) && \Ezset::hasHtmlHeader())
		{
			$this->call('Article\Opengraph::overrideOpengraphMetadata');
		}
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
		// OpenGraph
		if ($this->params->get('article.social.Opengraph', 0) && \Ezset::hasHtmlHeader())
		{
			$this->call('Article\Opengraph::overrideOpengraphImages', $context, $article, $this);
		}

		// Auto Thumb
		if ($this->params->get('article.edit.AutoThumbnail', 0))
		{
			$this->call('Article\Thumb::autoThumb', $context, $article, $params);
		}

		// Input Code
		$this->call('Article\CodeInsert::insertContent', $article, $this);

		// Custom Code
		$this->call('Article\CodeInsert::customCode', 'insertArticleTop', true, $article);
		$this->call('Article\CodeInsert::customCode', 'insertContentTop', true, $article);

		// Get Meta
		if ($this->params->get('article.seo.SeoMeta', 0))
		{
			/** @see \Ezset\Article\ContentSeo::setMeta */
			$this->call('Article\ContentSeo::setMeta', $article, $this);
		}
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

		$result = $this->call('Article\CodeInsert::customCode', 'insertTitleBottom');

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

		// Blog View Clearly
		if ($this->params->get('article.blog.SimpleLayout', 1))
		{
			$this->call('Article\Blog::clearView', $context, $article, $params);
		}

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

		// Custom Code
		if ($input->get('view') === 'article')
		{
			$result = $this->call('Article\CodeInsert::customCode', 'insertContentBottom');
		}

		// FB Like
		if ($this->params->get('article.social.Fb_LikeButton'))
		{
			$this->call('Article\Facebook::likeButton', $context, $article);
		}

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

		if ('com_categories.category' !== $context)
		{
			if ($this->params->get('article.edit.TidyRepair', 0))
			{
				$this->call('Article\Content::tidyRepair', $article, $this);
			}
		}

		if ($this->params->get('article.edit.SaveFirstImage', 0))
		{
			$this->call('Article\Content::saveFirstImage', $context, $article);
		}

		return $this->assertAllTrue($result);
	}
}

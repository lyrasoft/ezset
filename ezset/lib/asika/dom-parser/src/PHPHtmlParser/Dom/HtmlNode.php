<?php
namespace PHPHtmlParser\Dom;

use PHPHtmlParser\Exceptions\UnknownChildTypeException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;

class HtmlNode extends AbstractNode {

	/**
	 * Remembers what the innerHtml was if it was scaned previously.
	 */
	protected $innerHtml = null;

	/**
	 * Remembers what the outerHtml was if it was scaned previously.
	 *
	 * @var string
	 */
	protected $outerHtml = null;

	/**
	 * Remembers what the text was if it was scaned previously.
	 *
	 * @var string
	 */
	protected $text = null;

	/**
	 * Remembers what the text was when we looked into all our
	 * children nodes.
	 *
	 * @var string
	 */
	protected $textWithChildren = null;

	/**
	 * Sets up the tag of this node.
	 */
	public function __construct($tag)
	{
		if ( ! $tag instanceof Tag)
		{
			$tag = new Tag($tag);
		}
		$this->tag = $tag;
		parent::__construct();
	}

	/**
	 * Gets the inner html of this node.
	 *
	 * @return string
	 * @throws UnkownChildTypeException
	 */
	public function innerHtml()
	{
		if ( ! $this->hasChildren())
		{
			// no children
			return '';
		}

		if ( ! is_null($this->innerHtml))
		{
			// we already know the result.
			return $this->innerHtml;
		}

		$child	= $this->firstChild();
		$string = '';

		// continue to loop until we are out of children
		while( ! is_null($child))
		{
			if ($child instanceof TextNode)
			{
				$string .= $child->text();
			}
			elseif ($child instanceof HtmlNode)
			{
				$string .= $child->outerHtml();
			}
			else
			{
				throw new UnknownChildTypeException('Unknown child type "'.get_class($child).'" found in node');
			}

			try
			{
				$child = $this->nextChild($child->id());
			}
			catch (ChildNotFoundException $e)
			{
				// no more children
				$child = null;
			}
		}
		
		// remember the results
		$this->innerHtml = $string;

		return $string;
	}

	/**
	 * Gets the html of this node, including it's own
	 * tag.
	 *
	 * @return string
	 */
	public function outerHtml()
	{
		// special handling for root
		if ( $this->tag->name() == 'root')
		{
			return $this->innerHtml();
		}

		if ( ! is_null($this->outerHtml))
		{
			// we already know the results.
			return $this->outerHtml;
		}

		$return = $this->tag->makeOpeningTag();
		if ($this->tag->isSelfClosing())
		{
			// ignore any children... there should not be any though
			return $return;
		}

		// get the inner html
		$return .= $this->innerHtml();

		// add closing tag
		$return .= $this->tag->makeClosingTag();

		// remember the results
		$this->outerHtml = $return;

		return $return;
	}

	/**
	 * Gets the text of this node (if there is any text). Or get all the text
	 * in this node, including children.
	 *
	 * @param bool $lookInChildren
	 * @return string
	 */
	public function text($lookInChildren = false)
	{
		if ($lookInChildren)
		{
			if ( ! is_null($this->textWithChildren))
			{
				// we already know the results.
				return $this->textWithChildren;
			}
		}
		elseif( ! is_null($this->text))
		{
			// we already know the results.
			return $this->text;
		}

		// find out if this node has any text children
		$text = '';
		foreach ($this->children as $child)
		{
			if ($child['node'] instanceof TextNode)
			{
				$text .= $child['node']->text;
			}
			elseif($lookInChildren and
			       $child['node'] instanceof HtmlNode)
			{
				$text .= $child['node']->text($lookInChildren);
			}
		}

		// remember our result
		if ($lookInChildren)
		{
			$this->textWithChildren = $text;
		}
		else
		{
			$this->text = $text;
		}

		return $text;
	}

	/**
	 * Method to set property innerHtml
	 *
	 * @param   mixed $innerHtml
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setInnerHtml($innerHtml)
	{
		$this->innerHtml = $innerHtml;

		return $this;
	}

	/**
	 * Method to set property outerHtml
	 *
	 * @param   string $outerHtml
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setOuterHtml($outerHtml)
	{
		$this->outerHtml = $outerHtml;

		return $this;
	}

	/**
	 * Method to set property textWithChildren
	 *
	 * @param   string $textWithChildren
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setTextWithChildren($textWithChildren)
	{
		$this->textWithChildren = $textWithChildren;

		return $this;
	}

	/**
	 * Call this when something in the node tree has changed. Like a child has been added
	 * or a parent has been changed.
	 */
	protected function clear()
	{
		$this->innerHtml = null;
		$this->outerHtml = null;
		$this->text		 = null;
	}
}

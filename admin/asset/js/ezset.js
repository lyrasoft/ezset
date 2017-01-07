/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    Please see LICENSE file.
 */

;(function($)
{
	window.Ezset = {

	/**
	 * Constructor
	 */
	init: function(config)
	{
		this.config = config;
	},

	/**
	 * Smooth Scroll
	 */
	smoothScroll: function()
	{
		window.addEvent('domready', function()
		{
			new Fx.SmoothScroll({duration: 300}, window);
		});
	},

	/**
	 * @link http://stackoverflow.com/a/1119324
	 */
	confirmLeave: function ()
	{
		var task = jQuery('input[name=task]'),
			allowTasks = ['article.save', 'article.apply', 'article.save2new', 'article.save2copy'];

		jQuery(window).on('beforeunload', function(e)
		{
			if (allowTasks.indexOf(task.val()) != -1)
			{
				return;
			}

			// If we haven't been passed the event get the window.event
			e = e || window.event;

			var message = Joomla.JText._('COM_EZSET_ARTICLE_EDIT_CONFIRM_LEAVE_MESSAGE');

			// For IE6-8 and Firefox prior to version 4
			if (e)
			{
				e.returnValue = message;
			}

			// For Chrome, Safari, IE8+ and Opera 12+
			return message;
		});
	},

	/**
	 * Logo link.
	 */
	logoLink: function()
	{
		var self = this;

		jQuery(document).ready(function()
		{
			var logo = jQuery('.admin-logo');

			logo.removeClass('disabled');

			logo.attr('href', self.config.base);
		});
	},

	/**
	 * Ajax button.
	 *
	 * @param {string} selector
	 * @param {string} url
	 * @param {string} token
	 * @param {string} targetSelector
	 */
	ajaxButton: function (selector, url, token, targetSelector)
	{
		var $element = $(selector);
		var $button = $element.find('.ajax-button');

		$button.on('click', function (e) {
			data = {};
			data[token] = 1;

			if (targetSelector)
			{
				var $inputs = $element.find(targetSelector);

				$inputs.each(function()
				{
					var $this = $(this);
					var name = $this.attr('id').replace('jform_', '');
					data[name] = $this.val();
				});
			}

			var $msgContainer = $element.find('.ajax-button-response');

			$msgContainer.text('');
			$button.attr('disabled', true);

			$.post(url, data).done(function(response, status, xhr)
			{
				if (response.success)
				{
					$msgContainer.text(response.message)
						.removeClass('text-error')
						.addClass('text-success');
				}
				else
				{
					$msgContainer.text(response.message || 'Unknown ajax error')
						.removeClass('text-success')
						.addClass('text-error');
				}

				$button.attr('disabled', false);
			}).fail(function(response)
			{
				$msgContainer.text(response.message || 'Unknown ajax error')
					.removeClass('text-success')
					.addClass('text-error');

				$button.attr('disabled', false);
			});
		});
	}
};
})(jQuery);

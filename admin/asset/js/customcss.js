/**
 * Part of Ezset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

;(function($)
{
	/**
	 * Ezset Custom CSS.
	 */
	window.EzsetCustomCSS = {

		init: function(token)
		{
			this.token = token;
		},

		/**
		 * Save.
		 *
		 * @param {string}      selector
		 * @param {string}      name
		 * @param {HtmlElement} button
		 * @param {Event}       event
		 */
		save: function(selector, name, button, event)
		{
			var self = this;
			var $button = $(button);
			var content = Joomla.editors.instances[name].getValue();

			var data = {
				content: content,
				client: $button.attr('data-client')
			};

			data[this.token] = 1;

			$button.attr('disabled', true);

			$.post('index.php?cmd=ajax.css.save', data)
				.done(function(response, status, xhr)
				{
					if (response.success)
					{
						alert(response.message);
					}
					else
					{
						alert(response.message);
					}

					$button.attr('disabled', false);
				}).fail(function(xhr, status, error)
				{
					alert(xhr.status + ' ' + error);

					$button.attr('disabled', false);
				});
		}
	}

})(jQuery);

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
		 * @param button
		 * @param event
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

			$.ajax({
				url: 'index.php?cmd=ajax.css.save',
				data: data,
				type: 'POST',
				dataType: 'JSON',
				success: function(data, status, xhr)
				{
					if (data.success)
					{
						Joomla.renderMessages([[data.message]]);
					}
					else
					{
						Joomla.renderErrorMessages([[data.message]])
					}
				},
				error: function(xhr, status, error)
				{
					Joomla.renderMessages([[xhr.status + ' ' + error]]);
				},
				beforeSend: function()
				{
					$button.attr('disabled', true);
				},
				complete: function()
				{
					$button.attr('disabled', false);
				}
			});
		}
	}

})(jQuery);

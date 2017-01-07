/**
 * Ezset object.
 */
var Ezset = {

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
	}
};


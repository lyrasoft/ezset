/**
 * Ezset object.
 */
var Ezset = {

	/**
	 * Constructor
	 *
	 * @param {object} opt
	 * @param {object} config
	 */
	init: function(opt, config)
	{

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
	}
}


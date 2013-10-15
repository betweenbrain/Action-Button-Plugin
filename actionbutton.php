<?php defined('_JEXEC') or die;

/**
 * File       actionbutton.php
 * Created    10/15/13 11:03 AM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

jimport('joomla.plugin.plugin');

class plgSystemActionbutton extends JPlugin {

	function plgSystemActionbutton(&$subject, $config) {
		parent::__construct($subject, $config);
		$this->app = JFactory::getApplication();
		$this->doc = JFactory::getDocument();
	}

	function onAfterRender() {

		if ($this->app->isAdmin()) {
			return TRUE;
		}

		$buffer = JResponse::getBody();

		/**
		 * Regex to  match shortcode
		 *
		 * ([a-zA-Z0-9\/\.:\?=(?:&amp;)_-]*) Capturing group to match URL protocol, domain, extension,
		 * letters, numbers and permitted characters in URL.
		 *
		 * ([\sa-zA-Z0-9]*) capturing group for button text
		 */
		$pattern = '/{Actionbutton ([a-zA-Z0-9\/\.:\?=(?:&amp;)_-]*) ([\sa-zA-Z0-9]*)}/i';

		preg_match_all($pattern, $buffer, $matches, PREG_SET_ORDER);

		if (count($matches)) {

			$css = "<style>
a.actionbutton {
	background      : url('//guggenheim.org/media/com_calendar2/images/site/actionbutton.png') no-repeat center;
	width           : 112px;
	height          : 42px;
	display         : table-cell;
	vertical-align  : middle;
	text-decoration : none;
	font-weight     : bold;
	text-transform  : uppercase;
	color           : #000;
	font-size       : 11px;
	text-align      : center;
}

a.actionbutton:hover {
	background-image : url('//guggenheim.org/media/com_calendar2/images/site/actionbutton_hover.png');
	cursor           : pointer;
}
</style>
";

			$buffer = str_replace('</head>', $css . '</head>', $buffer);

			foreach ($matches as $match) {

				$replacement = '<a class="actionbutton" href="' . $match[1] . '">' . $match[2] . '</a>';

				$buffer = str_replace($match[0], $replacement, $buffer);
			}

			JResponse::setBody($buffer);

			return TRUE;
		}
	}
}
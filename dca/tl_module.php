<?php if(!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Liquid Diamond 2011 
 * @author     Fabiano Mason <fabiano@liquiddiamond.it>, 
 * @package    Suggest 
 * @license    GNU/LGPL
 * @filesource
 */

/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['suggestreader'] = '{title_legend},name,headline,type;{config_legend},suggest_avatar;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['suggest_avatar'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_module']['suggest_avatar'],
	'exclude' => true,
	'inputType' => 'radio',
	'options' => array('avatar1', 'avatar2'),
	'eval' => array('mandatory' => false, 'submitOnChange' => false)
);

class tl_module_suggest extends Backend {
	/**
	 * Import the back end user object
	 */
	public function __construct() {
		parent::__construct();
		$this -> import('BackendUser', 'User');
	}

}
?>
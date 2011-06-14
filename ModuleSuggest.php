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
 * Class ModuleIssuu
 *
 * Back end module "issuu".
 * @copyright  Liquid Diamond 2011
 * @author     Fabiano Mason, Andrea Collet
 * @package    BackendModule
 */
class ModuleIssuu extends BackendModule {

	/**
	 * Data container
	 * @var object
	 */
	protected $objDc;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_suggest';

	/**
	 * Generate the module
	 * @return string
	 */
	public function generate() {
		$this -> objDc = func_get_arg(0);
		return parent::generate();
	}

	/**
	 * Generate module
	 */
	protected function compile() {
	}

	/**
	 * Return a new template object
	 * @param string
	 * @param object
	 * @return object
	 */
	protected function newTemplate($strTemplate, Database_Result$objModule) {

	}

}
?>
<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * Class ModuleSuggest 
 *
 * @copyright  Liquid Diamond 2011 
 * @author     Fabiano Mason <fabiano@liquiddiamond.it> 
 * @package    Suggest
 */
class ModuleSuggestReader extends Module {

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_suggestreader';
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate() {
		if (TL_MODE == 'BE') {
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### SITE HUMOR ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&table=tl_module&act=edit&id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}	

	/**
	 * Generate module
	 */
	protected function compile() {

		$objJoke = $this->Database->prepare("SELECT * FROM tl_suggest WHERE published = 1 ORDER BY RAND()")
								  ->limit(1)
								  ->execute();
		
		$objJoke->next();

		$objTemplate  = new FrontendTemplate('suggest_default');
		$objTemplate -> setData(array());
		$objTemplate -> count = 1;
		
		$objTemplate -> avatar = $this -> generateAvatar();
		$objTemplate -> comic = $this -> generateComic($objJoke);
		
		return $this -> Template -> joke = $objTemplate->parse();
		
	}
	
	protected function generateComic($objJoke) {
		$comic = $objJoke -> comic;
		$link = $objJoke -> link;
		$url = $objJoke -> url;
		
		return sprintf($comic, 
			'<a href="' . $url . '">' . $link . '</a>');
	}

	protected function generateAvatar() {
		return sprintf('<img src="system/modules/suggest/html/images/%s.jpg" alt="The joker"/>', $this -> suggest_avatar);
	}
}

?>
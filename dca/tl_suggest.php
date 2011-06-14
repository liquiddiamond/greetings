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
 * Table tl_suggest 
 */
$GLOBALS['TL_DCA']['tl_suggest'] = array (

	// Config
	'config' => array (
		'dataContainer'    => 'Table',
		'enableVersioning' => true,
		'onload_callback'  => array (
			array('tl_suggest', 'checkPermission')
		)
	),

	// List
	'list' => array (
		'sorting' => array (
			'mode'                  => 2,
			'fields'                => array('title'),
			'flag'                  => 1,
			'panelLayout'           => 'search,limit'
		),
		'label' => array (
			'fields'                => array('title','alias'),
			'format'                => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>'
		),
		'global_operations' => array (
			'all' => array (
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)			
		),
		'operations' => array (
			'edit' => array (
				'label'               => &$GLOBALS['TL_LANG']['tl_suggest']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array (
				'label'               => &$GLOBALS['TL_LANG']['tl_suggest']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array (
				'label'               => &$GLOBALS['TL_LANG']['tl_suggest']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array (
				'label'               => &$GLOBALS['TL_LANG']['tl_suggest']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_suggest', 'toggleIcon')
			),
			'show' => array (
				'label'               => &$GLOBALS['TL_LANG']['tl_suggest']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array (
		'__selector__'            => array('addBeMod', 'addFeMod', 'addLanguage'),
		'default'                 => 'title,alias;{suggest_config},comic,link,url;{expert_settings},published;'
	),

	// Subpalettes
	'subpalettes' => array (
		''                        => ''
	),
	
	// Fields
	'fields' => array (
		'title' => array (
			'label'                 => &$GLOBALS['TL_LANG']['tl_suggest']['title'],
			'exclude'               => true,
			'inputType'             => 'text',
			'eval'                  => array('mandatory' => true, 'maxlength' => 255)
		),
		'alias' => array (
			'label'                 => &$GLOBALS['TL_LANG']['tl_suggest']['alias'],
			'exclude'               => false,
			'inputType'             => 'text',
			'eval'                  => array('rgxp' => 'alnum', 'doNotCopy' => true, 'spaceToUnderscore' => true, 'maxlength' => 128),
			'save_callback' => array (
				array('tl_suggest', 'generateAlias')
			)
		),
		'comic' => array (
			'label'                 => &$GLOBALS['TL_LANG']['tl_suggest']['comic'],
			'exclude'               => true,
			'inputType'             => 'text',
			'eval'                  => array('mandatory' => true, 'maxlength' => 255)
		),
		'link' => array (
			'label'                 => &$GLOBALS['TL_LANG']['tl_suggest']['link'],
			'exclude'               => true,
			'inputType'             => 'text',
			'eval'                  => array('mandatory' => true, 'maxlength' => 255)
		),
		'url' => array (
			'label'                 => &$GLOBALS['TL_LANG']['tl_suggest']['url'],
			'exclude'               => true,
			'inputType'             => 'text',
			'eval'                  => array('mandatory' => true, 'maxlength' => 255)
		),
		'published' => array (
			'label'                 => &$GLOBALS['TL_LANG']['tl_suggest']['published'],
			'exclude'               => true,
			'inputType'             => 'checkbox',
			'eval'                  => array('doNotCopy'=>true)
		)
	)
);

class tl_suggest extends Backend {

	/**
	 * Import the back end user object
	 */
	public function __construct() {
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	/**
	 * Autogenerate an article alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc) {
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($varValue)) {
			$autoAlias = true;
			$varValue = standardize($dc->activeRecord->title);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_suggest WHERE id=? OR alias=?")
								   ->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > 1) {
			if (!$autoAlias) {
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
			}
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}

	/**
	 * Check permissions to edit table tl_suggest
	 */
	public function checkPermission() {
	}

	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes) {
		if (strlen($this->Input->get('tid'))) {
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_suggest::published', 'alexf')) {
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published']) {
			$icon = 'invisible.gif';
		}		

		$objPage = $this->Database->prepare("SELECT * FROM tl_suggest WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		if (!$this->User->isAdmin && !$this->User->isAllowed(4, $objPage->row())) {
			return $this->generateImage($icon) . ' ';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}

	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible) {
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_suggest::published', 'alexf')) {
			$this->log('Not enough permissions to publish/unpublish article ID "'.$intId.'"', 'tl_suggest toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_suggest', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_suggest']['fields']['published']['save_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_suggest']['fields']['published']['save_callback'] as $callback) {
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_suggest SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_suggest', $intId);
	}


}

?>
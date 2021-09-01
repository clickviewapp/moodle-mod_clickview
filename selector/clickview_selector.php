<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * ClickView video selector form element.
 *
 * @package     mod_clickview
 * @copyright   2021 ClickView Pty. Limited <info@clickview.com.au>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once('HTML/QuickForm/element.php');

/**
 * HTML class for a ClickView video selector tool.
 *
 * @package     mod_clickview
 * @category    form
 * @copyright   2021 ClickView Pty. Limited <info@clickview.com.au>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class MoodleQuickForm_clickview_selector extends HTML_QuickForm_element {

	private $_name;
	private $_values = array('width'=>null, 'height'=>null, 'autoplay'=>null, 'embedhtml'=>null, 'embedlink'=>null, 'thumbnailurl'=>null, 'title'=>null);

	function MoodleQuickForm_clickview_selector($elementName=null, $elementLabel=null, $attributes=null, $options=null) {
		parent::HTML_QuickForm_element($elementName, $elementLabel, $attributes);
	}

	function getFrozenHtml() {
		return '';
	}

	function setName($name) {
		$this->_name = $name;
	}

	function getName() {
		return $this->_name;
	}

	function setValue($values) {
		$values = (array)$values;
		foreach ($values as $name=>$value) {
			if (array_key_exists($name, $this->_values)) {
				$this->_values[$name] = $value;
			}
		}
	}

	function getValue() {
		return $this->_values;
	}

	function toHtml() {
        $config = get_config('local_clickview');

        $params = [
                'consumerKey' => $config->consumerkey,
                'singleSelectMode' => 'true'
        ];

        if (!empty($schoolid = $config->schoolid)) {
            $params['schoolId'] = $schoolid;
        }

        $url = new moodle_url($config->hostlocation . $config->iframeurl, $params);

        $elname = $this->getName();
		
		$str = 	'<div style="max-height: 494px; overflow: hidden;">'.
			'<div '.
			'style="position: relative;'.
			' width: 100%;'.
			' height: 0px;'.
			' padding-bottom: 61.8%;'.
			' min-width: 500px;'.
			' max-width: 800px;">'.
						'<iframe id="cv-plugin-frame" frameborder="0" src="' . $url . '"' .
						' style="position: absolute;'.
						' left: 0px;'.
						' top: 0px;'.
						' width: 100%;'.
						' height: 100%;'.
						' max-height: 494px;'.
						' min-width: 500px;'.
						' max-width: 800px;"></iframe>'.
			'</div></div>'.
			'<input name="'.$elname.'[width]"        id="cv-width" type="text" style="display: none;" />'.
			'<input name="'.$elname.'[height]"       id="cv-height" type="text" style="display: none;" />'.
			'<input name="'.$elname.'[embedhtml]"    id="cv-embedhtml" type="text" style="display: none;" />'.
			'<input name="'.$elname.'[embedlink]"    id="cv-embedlink" type="text" style="display: none;" />'.
			'<input name="'.$elname.'[thumbnailurl]" id="cv-thumbnailurl" type="text" style="display: none;" />'.
			'<input name="'.$elname.'[title]"        id="cv-title" type="text" style="display: none;" />'.
			'<input name="'.$elname.'[autoplay]"     id="cv-autoplay" type="text" style="display: none;" />'.
			'<input name="'.$elname.'[logging]"      id="cv-logging" type="text" style="display: none;" />'.
			'<input name="'.$elname.'[onlineurl]"    id="cv-logging-onlineurl" type="text" style="display: none;" />'.
			'<input name="'.$elname.'[eventname]"    id="cv-logging-eventname" type="text" style="display: none;" />';

		return $str;
	}
}

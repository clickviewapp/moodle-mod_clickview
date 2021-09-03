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

namespace mod_clickview;

use HTML_QuickForm_element;
use moodle_url;

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
class selector extends HTML_QuickForm_element {

    /**
     * @var array Default values of the video
     */
    private $_values = [
            'width' => null,
            'height' => null,
            'autoplay' => null,
            'embedhtml' => null,
            'embedlink' => null,
            'thumbnailurl' => null,
            'title' => null
    ];

    /**
     * Sets the input field name.
     *
     * @param string $name The input field name attribute
     */
    // phpcs:disable
    public function setName($name) {
    // phpcs:enable
        $this->updateAttributes(['name' => $name]);
    }

    /**
     * Returns the element name.
     *
     * @return string
     */
    // phpcs:disable
    public function getName() {
    // phpcs:enable
        return $this->getAttribute('name');
    }

    /**
     * Sets the value of the form element.
     *
     * @param mixed $value Array or comma delimited string of selected values
     */
    // phpcs:disable
    public function setValue($value) {
    // phpcs:enable
        if (is_array($value)) {
            $this->_values = array_values($value);
        } else {
            $this->_values = array($value);
        }
    }

    /**
     * Returns an array of values from the selected video.
     *
     * @return array
     */
    // phpcs:disable
    public function getValue() {
    // phpcs:enable
        return $this->_values;
    }

    /**
     * Returns the video in HTML code.
     *
     * @return string
     */
    // phpcs:disable
    public function toHtml() {
    // phpcs:enable
        $config = get_config('local_clickview');

        $params = [
                'consumerKey' => $config->consumerkey,
                'singleSelectMode' => 'true'
        ];

        if (!empty($schoolid = $config->schoolid)) {
            $params['schoolId'] = $schoolid;
        }

        $url = new moodle_url($config->hostlocation . $config->iframeurl, $params);

        return '<div style="max-height: 494px; overflow: hidden;">' .
                '<div ' .
                'style="position: relative;' .
                ' width: 100%;' .
                ' height: 0px;' .
                ' padding-bottom: 61.8%;' .
                ' min-width: 500px;' .
                ' max-width: 800px;">' .
                '<iframe id="cv-plugin-frame" frameborder="0" src="' . $url . '"' .
                ' style="position: absolute;' .
                ' left: 0px;' .
                ' top: 0px;' .
                ' width: 100%;' .
                ' height: 100%;' .
                ' max-height: 494px;' .
                ' min-width: 500px;' .
                ' max-width: 800px;"></iframe>' .
                '</div></div>';
    }
}

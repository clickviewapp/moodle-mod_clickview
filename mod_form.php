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
 * The main mod_clickview configuration form.
 *
 * @package     mod_clickview
 * @copyright   2021 ClickView Pty. Limited <info@clickview.com.au>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

	require_once ($CFG->dirroot.'/course/moodleform_mod.php');

	class mod_clickview_mod_form extends moodleform_mod {

		// Validate that a clickview video has indeed been selected.
		function validate($values) {
			return isset($values['width']) && isset($values['height']) && isset($values['embedhtml']) && isset($values['autoplay']);
		}

		function definition() {
			global $CFG, $PAGE;
		
			// Add the ClickView Selector to the registered types in the Moodle Quick Form wrapper.
			MoodleQuickForm::registerElementType('clickview_selector', $CFG->dirroot."/mod/clickview/selector/clickview_selector.php", 'MoodleQuickForm_clickview_selector');

			$mform = $this->_form;

			$mform->addElement('header', 'generalhdr', 'General');
			$mform->addElement('text', 'name', get_string('editor:title', 'clickview'), array('size' => 55));
			$mform->addRule('name', get_string('editor:required', 'clickview'), 'required');
			$mform->setType('name', PARAM_TEXT);

			$mform->addElement('clickview_selector', 'clickview', get_string('editor:selector', 'clickview'));
			$mform->addRule('clickview', get_string('editor:selectorerror', 'clickview'), 'required');
			$mform->addRule('clickview', get_string('editor:required', 'clickview'), 'callback', 'validate');

			$this->standard_coursemodule_elements();

			$this->add_action_buttons(true, false, null);

            $PAGE->requires->js(new moodle_url(get_config('local_clickview', 'eventsapi')));
            $PAGE->requires->js('/mod/clickview/selector/js/dialog.js');
		}

	}

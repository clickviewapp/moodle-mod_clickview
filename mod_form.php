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

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_clickview_mod_form extends moodleform_mod {

    /**
     * Defines forms elements.
     *
     * @throws moodle_exception
     */
    public function definition() {
        global $CFG, $PAGE;

        MoodleQuickForm::registerElementType('clickview_selector', $CFG->dirroot . '/mod/clickview/classes/selector.php',
                '\\mod_clickview\\selector');

        $mform = $this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('name'), ['size' => 64]);

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }

        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $mform->addElement('hidden', 'cv-name');
        $mform->setType('cv-name', PARAM_TEXT);
        $mform->addElement('hidden', 'cv-width');
        $mform->setType('cv-width', PARAM_INT);
        $mform->addElement('hidden', 'cv-height');
        $mform->setType('cv-height', PARAM_INT);
        $mform->addElement('hidden', 'cv-autoplay');
        $mform->setType('cv-autoplay', PARAM_INT);
        $mform->addElement('hidden', 'cv-embedhtml');
        $mform->setType('cv-embedhtml', PARAM_RAW);
        $mform->addElement('hidden', 'cv-embedlink');
        $mform->setType('cv-embedlink', PARAM_RAW);
        $mform->addElement('hidden', 'cv-thumbnailurl');
        $mform->setType('cv-thumbnailurl', PARAM_RAW);

        $mform->addElement('hidden', 'cv-logging');
        $mform->setType('cv-logging', PARAM_RAW);
        $mform->addElement('hidden', 'cv-logging-onlineurl');
        $mform->setType('cv-logging-onlineurl', PARAM_RAW);
        $mform->addElement('hidden', 'cv-logging-eventname');
        $mform->setType('cv-logging-eventname', PARAM_ALPHANUMEXT);

        $mform->addElement('clickview_selector', 'clickview', get_string('choosevideo', 'clickview'));

        $this->standard_grading_coursemodule_elements();

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();

        $PAGE->requires->js(new moodle_url(get_config('local_clickview', 'eventsapi')));
        $PAGE->requires->js_call_amd('mod_clickview/selector', 'init');
    }

    /**
     * Enforce validation rules here.
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array
     *
     * @throws coding_exception
     */
    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);

        foreach ($data as $key => $value) {
            $expkey = explode('-', $key);

            if ($expkey[0] === 'cv') {
                if (!isset($value)) {
                    $errors['clickview'] = get_string('required');
                }
            }
        }

        return $errors;
    }

    /**
     * Allows module to modify the data returned by form get_data().
     * This method is also called in the bulk activity completion form.
     *
     * Only available on moodleform_mod.
     *
     * @param stdClass $data the form data to be modified.
     */
    public function data_postprocessing($data): stdClass {
        parent::data_postprocessing($data);

        foreach ($data as $key => $value) {
            $expkey = explode('-', $key);

            if ($expkey[0] === 'cv') {
                $newkey = (string)$expkey[1];
                $data->$newkey = $value;

                unset($data->$key);
            }
        }

        unset($data->clickview);

        return $data;
    }
}

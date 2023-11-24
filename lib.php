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
 *  Library of interface functions and constants.
 *
 * @package     mod_clickview
 * @copyright   2021 ClickView Pty. Limited <info@clickview.com.au>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Saves a new instance of the mod_clickview into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param stdClass $data An object from the form.
 * @param mod_clickview_mod_form|null $mform The form.
 * @return int The id of the newly inserted record.
 * @throws dml_exception
 * @throws moodle_exception
 */
function clickview_add_instance(stdClass $data, mod_clickview_mod_form $mform = null): int {
    global $DB;

    $data->timecreated = time();
    $data->timemodified = $data->timecreated;

    return $DB->insert_record('clickview', $data);
}

/**
 * Updates an instance of the mod_clickview in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param stdClass $data An object from the form in mod_form.php.
 * @param mod_clickview_mod_form|null $mform The form.
 * @return bool True if successful, false otherwise.
 * @throws dml_exception
 */
function clickview_update_instance(stdClass $data, mod_clickview_mod_form $mform = null): bool {
    global $DB;

    // We allow to update the instance, without changing the selected video.
    if (empty($data->title)) {
        $record = $DB->get_record('clickview', ['id' => $data->instance, 'course' => $data->course], 'title');

        $data->title = $record->title;
    }

    $data->timemodified = time();
    $data->id = $data->instance;

    return $DB->update_record('clickview', $data);
}

/**
 * Removes an instance of the mod_clickview from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function clickview_delete_instance(int $id): bool {
    global $DB;

    $activity = $DB->get_record('clickview', ['id' => $id]);
    if (!$activity) {
        return false;
    }

    $DB->delete_records('clickview', ['id' => $id]);

    return true;
}

/**
 * Checks if clickview activity supports a specific feature.
 *
 * @uses FEATURE_MOD_ARCHETYPE
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_SHOW_DESCRIPTION
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_COMPLETION_HAS_RULES
 * @uses FEATURE_MODEDIT_DEFAULT_COMPLETION
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @uses FEATURE_BACKUP_MOODLE2
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function clickview_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_GROUPS:
            return true;
        case FEATURE_GROUPINGS:
            return true;
        case FEATURE_MOD_INTRO:
            return false;
        case FEATURE_SHOW_DESCRIPTION:
            return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return false;
        case FEATURE_MODEDIT_DEFAULT_COMPLETION:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

/**
 * Trigger the course_module_viewed event.
 *
 * @param stdClass $video ClickView video object
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 */
function clickview_view($video, $course, $cm, $context) {
    $params = [
            'context' => $context,
            'objectid' => $video->id,
    ];

    $event = \mod_clickview\event\course_module_viewed::create($params);
    $event->add_record_snapshot('course_modules', $cm);
    $event->add_record_snapshot('course', $course);
    $event->add_record_snapshot('clickview', $video);
    $event->trigger();
}

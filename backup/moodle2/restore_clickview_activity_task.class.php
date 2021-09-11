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
 * The task that provides a complete restore of mod_clickview is defined here.
 *
 * @package     mod_clickview
 * @copyright   2021 ClickView Pty. Limited <info@clickview.com.au>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/clickview/backup/moodle2/restore_clickview_stepslib.php');

/**
 * Restore task for mod_clickview.
 */
class restore_clickview_activity_task extends restore_activity_task {

    /**
     * Defines particular settings that this activity can have.
     */
    protected function define_my_settings() {
        return;
    }

    /**
     * Defines particular steps that this activity can have.
     *
     * @throws base_task_exception
     */
    protected function define_my_steps() {
        $this->add_step(new restore_clickview_activity_structure_step('clickview_structure', 'clickview.xml'));
    }

    /**
     * Defines the contents in the activity that must be processed by the link decoder.
     *
     * @return array.
     */
    public static function define_decode_contents(): array {
        return [];
    }

    /**
     * Defines the decoding rules for links belonging to the activity to be executed by the link decoder.
     *
     * @return restore_decode_rule[].
     */
    public static function define_decode_rules(): array {
        return [];
    }

    /**
     * Defines the restore log rules that will be applied by the
     * restore_logs_processor when restoring mod_clickview logs. It
     * must return one array of objects.
     *
     * @return restore_log_rule[].
     */
    public static function define_restore_log_rules(): array {
        $rules = [];

        $rules[] = new restore_log_rule('clickview', 'add', 'view.php?id={course_module}', '{clickview}');
        $rules[] = new restore_log_rule('clickview', 'update', 'view.php?id={course_module}', '{clickview}');
        $rules[] = new restore_log_rule('clickview', 'view', 'view.php?id={course_module}', '{clickview}');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied by the
     * restore_logs_processor when restoring course logs. It
     * must return one array of objects.
     *
     * Note this rules are applied when restoring course logs by the
     * restore final task, but are defined here at activity level. All
     * them are rules not linked to any module instance (cmid = 0).
     *
     * @return restore_log_rule[].
     */
    public static function define_restore_log_rules_for_course(): array {
        $rules = [];

        $rules[] = new restore_log_rule('clickview', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}

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
 * List of all ClickView video instances in course.
 *
 * @package     mod_clickview
 * @copyright   2021 ClickView Pty. Limited <info@clickview.com.au>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$id = required_param('id', PARAM_INT);

$course = get_course($id);

require_course_login($course, true);

$PAGE->set_pagelayout('incourse');

// Trigger instances list viewed event.
$event = \mod_clickview\event\course_module_instance_list_viewed::create(['context' => context_course::instance($course->id)]);
$event->add_record_snapshot('course', $course);
$event->trigger();

$strvideo = get_string('modulename', 'clickview');
$strvideos = get_string('modulenameplural', 'clickview');
$strname = get_string('name');
$strlastmodified = get_string('lastmodified');

$PAGE->set_url('/mod/clickview/index.php', ['id' => $course->id]);
$PAGE->set_title($course->shortname . ': ' . $strvideo);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($strvideos);

echo $OUTPUT->header();
echo $OUTPUT->heading($strvideos);

if (!$videos = get_all_instances_in_course('clickview', $course)) {
    notice(get_string('thereareno', 'moodle', $strvideos),
            new moodle_url('/course/view.php', ['id' => $course->id])
    );

    exit;
}

$usesections = course_format_uses_sections($course->format);

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($usesections) {
    $strsectionname = get_string('sectionname', 'format_' . $course->format);
    $table->head = array($strsectionname, $strname);
    $table->align = array('center', 'left');
} else {
    $table->head = array($strlastmodified, $strname);
    $table->align = array('left', 'left');
}

$currentsection = '';
$modinfo = get_fast_modinfo($course);

foreach ($videos as $video) {
    $cm = $modinfo->cms[$video->coursemodule];
    if ($usesections) {
        $printsection = '';
        if ($video->section !== $currentsection) {
            if ($video->section) {
                $printsection = get_section_name($course, $video->section);
            }
            if ($currentsection !== '') {
                $table->data[] = 'hr';
            }
            $currentsection = $video->section;
        }
    } else {
        $printsection = '<span class="smallinfo">' . userdate($video->timemodified) . '</span>';
    }

    $class = $video->visible ? '' : 'class="dimmed"';

    $table->data[] = [
            $printsection,
            html_writer::link(
                    new moodle_url('/mod/clickview/view.php', ['id' => $cm->id]),
                    format_string($video->name),
                    ['class' => $class]
            )
    ];
}

echo html_writer::table($table);

echo $OUTPUT->footer();

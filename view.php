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
 * Prints an instance of mod_clickview.
 *
 * @package     mod_clickview
 * @copyright   2021 ClickView Pty. Limited <info@clickview.com.au>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$id = required_param('id', PARAM_INT);

list ($course, $cm) = get_course_and_cm_from_cmid($id, 'clickview');

require_login($course, true, $cm);

$video = $DB->get_record('clickview', ['id' => $cm->instance]);

$PAGE->set_url('/mod/clickview/view.php', ['id' => $cm->id]);

$shortname = format_string($course->shortname);
$pagetitle = strip_tags($shortname . ': ' . format_string($video->name));
$PAGE->set_title(format_string($pagetitle));

$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($video->name));

$completiondetails = \core_completion\cm_completion_details::get_instance($cm, $USER->id);
$activitydates = \core\activity_dates::get_dates_for_module($cm, $USER->id);
echo $OUTPUT->activity_information($cm, $completiondetails, $activitydates);

$output = $video->embedhtml ?? '';

if (empty($output)) {
    $url = new moodle_url($video->embedlink);

    $iframe =
            '<iframe class="embed-responsive-item" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen src="' .
            $url . '" width="' . $video->width . '" height="' . $video->height . '" allow="autoplay"></iframe>';
    $output = html_writer::div($iframe, 'embed-responsive embed-responsive-16by9');
}

echo $OUTPUT->box($output);

echo $OUTPUT->footer();

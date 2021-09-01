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

	$id = required_param('id', PARAM_INT);    // Course Module ID

global $CFG;

	if (!$cm = get_coursemodule_from_id('clickview', $id)) {
		print_error('Course Module ID was incorrect');
	}
	if (!$course = $DB->get_record('course', array('id'=> $cm->course))) {
		print_error('Course is misconfigured');
	}
	if (!$cv_vid = $DB->get_record('clickview', array('id'=> $cm->instance))) {
		print_error('Course module is incorrect');
	}
	require_login($course, true, $cm);

	$PAGE->set_url('/mod/clickview/view.php', array('id' => $cm->id));

	$PAGE->set_title($cv_vid->name);
	$PAGE->set_heading(format_string($course->fullname));

	echo $OUTPUT->header();

	if(!(is_numeric($cv_vid->width) || is_numeric($cv_vid->height))) {
		print_error("This ClickView Resource has invalid fields in the moodle database.");
	}

	$autoplay = $cv_vid->autoplay === '1' ? 'true' : 'false';
	if(isset($cv_vid->embedhtml) && $cv_vid->embedhtml != '0'){
			$embed_box = '<div id="cv-player" data-test="true">'.$cv_vid->embedhtml.'</div>';
	} else {
        $config = get_config('local_clickview');

        $params = [
                'consumerKey' => $config->consumerkey,
                'shareCode' => $cv_vid->shortcode,
                'a' => $autoplay,
        ];

        $url = new moodle_url($config->hostlocation . $config->shareplayembedurl, $params);

        $embed_box = '<div id="cv-player" data-test="false"><iframe frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen src="'. $url . '" width="'.$cv_vid->width.'" height="'.$cv_vid->height.'" ></iframe></div>';
	}

	echo $OUTPUT->box($embed_box);

	echo $OUTPUT->footer($course);

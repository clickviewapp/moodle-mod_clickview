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
 * Backup steps for mod_clickview are defined here.
 *
 * @package     mod_clickview
 * @category    backup
 * @copyright   2021 ClickView Pty. Limited <info@clickview.com.au>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define the complete structure for backup, with file and id annotations.
 */
class backup_clickview_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the structure of the resulting xml file.
     *
     * @return backup_nested_element The structure wrapped by the common 'activity' element.
     * @throws base_element_struct_exception
     */
    protected function define_structure(): backup_nested_element {
        // Define each element separated.
        $columns = [
                'name',
                'width',
                'height',
                'autoplay',
                'embedhtml',
                'embedlink',
                'thumbnailurl',
                'title',
        ];

        $clickview = new backup_nested_element('clickview', ['id'], $columns);

        // Define sources.
        $clickview->set_source_table('clickview', ['id' => backup::VAR_ACTIVITYID]);

        // Return the root element, wrapped into standard activity structure.
        return $this->prepare_activity_structure($clickview);
    }
}

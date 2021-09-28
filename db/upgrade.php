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
 * This file keeps track of upgrades to the clickview module
 *
 * Sometimes, changes between versions involve
 * alterations to database structures and other
 * major things that may break installations.
 *
 * The upgrade function in this file will attempt
 * to perform all the necessary actions to upgrade
 * your older installation to the current version.
 *
 * If there's something it cannot do itself, it
 * will tell you what you need to do.
 *
 * The commands in here will all be database-neutral,
 * using the methods of database_manager class
 *
 * Please do not forget to use upgrade_set_timeout()
 * before any action that may take longer time to finish.
 *
 * @package     mod_clickview
 * @copyright   2021 ClickView Pty. Limited <info@clickview.com.au>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Function to upgrade mod_clickview.
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_clickview_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2016052001) {
        // Define field embedhtml to be added to clickview.
        $table = new xmldb_table('clickview');
        $field = new xmldb_field('embedhtml', XMLDB_TYPE_CHAR, '1024', null, XMLDB_NOTNULL, null, '0', 'autoplay');

        // Conditionally launch add field embedhtml.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field embedlink to be added to clickview.
        $field = new xmldb_field('embedlink', XMLDB_TYPE_CHAR, '1024', null, XMLDB_NOTNULL, null, null, 'embedhtml');

        // Conditionally launch add field embedlink.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field thumbnailurl to be added to clickview.
        $field = new xmldb_field('thumbnailurl', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'embedlink');

        // Conditionally launch add field thumbnailurl.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field title to be added to clickview.
        $field = new xmldb_field('title', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'thumbnailurl');

        // Conditionally launch add field title.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Clickview savepoint reached.
        upgrade_mod_savepoint(true, 2016052001, 'clickview');
    }

    if ($oldversion < 2021082701) {

        // Define field shortcode to be dropped from clickview.
        $table = new xmldb_table('clickview');
        $field = new xmldb_field('shortcode');

        // Conditionally launch drop field shortcode.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Clickview savepoint reached.
        upgrade_mod_savepoint(true, 2021082701, 'clickview');
    }

    return true;
}

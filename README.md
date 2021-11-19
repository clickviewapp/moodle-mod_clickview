# Moodle ClickView activity plugin #

[![Moodle Plugin CI](https://github.com/clickviewapp/moodle-mod_clickview/actions/workflows/moodle-ci.yml/badge.svg?branch=MOODLE_310_STABLE)](https://github.com/clickviewapp/moodle-mod_clickview/actions/workflows/moodle-ci.yml)

The ClickView activity plugin for Moodle enables you to quickly and safely browse through your entire ClickView library, the ClickView exchange and your own personal workspace from within Moodle.

This makes it easy to embed educational videos directly into your courses and share them with your students.

## Requirements ##

The plugin can only be installed if the plugin `local_clickview` is available.

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/mod/clickview

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## Subscription ##

This plugin is only available to organizations with an active ClickView subscription. You can request a free trial here https://www.clickvieweducation.com/free-trial/.

## License ##

2021 ClickView Pty. Limited <info@clickview.com.au>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.

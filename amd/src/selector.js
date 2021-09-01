// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/*
 * @package    mod_clickview
 * @copyright  2021 ClickView Pty. Limited <info@clickview.com.au>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([], function() {
    return {
        init: function () {
            var displayTitle = document.getElementById('id_name'),
                pluginFrame = document.getElementById('cv-plugin-frame'),
                eventsApi = new CVEventsApi(pluginFrame.contentWindow),
                userSetTitle = false;

            if (displayTitle) {
                displayTitle.onkeyup = function (e) {
                    if (e.target.value) {
                        userSetTitle = true;
                    }
                };
            }

            eventsApi.on('cv-lms-addvideo', function (event, detail) {
                document.getElementById('cv-width').value = detail.embed.width;
                document.getElementById('cv-height').value = detail.embed.height;
                document.getElementById('cv-autoplay').value = (detail.embed.autoplay ? '1' : '0');
                document.getElementById('cv-embedhtml').value = detail.embedHtml;
                document.getElementById('cv-embedlink').value = detail.embedLink;
                document.getElementById('cv-thumbnailurl').value = detail.thumbnailUrl;
                document.getElementById('cv-title').value = detail.title;

                if (displayTitle && !userSetTitle) {
                    displayTitle.value = detail.title;
                }
            }, true);

            eventsApi.on('cv-delegate-logging', function (event, data) {
                document.getElementById('cv-logging').value = data.JSON;
                document.getElementById('cv-logging-onlineurl').value = data.onlineUrl;
                document.getElementById('cv-logging-eventname').value = data.eventName;
            }, true);
        }
    };
});
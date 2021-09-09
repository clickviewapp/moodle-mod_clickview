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
        init: function() {
            var displayTitle = document.getElementById('id_name'),
                pluginFrame = document.getElementById('clickview_iframe'),
                eventsApi = new CVEventsApi(pluginFrame.contentWindow), // eslint-disable-line
                userSetTitle = false;

            if (displayTitle) {
                displayTitle.onkeyup = function(e) {
                    if (e.target.value) {
                        userSetTitle = true;
                    }
                };
            }

            eventsApi.on('cv-lms-addvideo', function(event, detail) {
                document.getElementsByName('cv-name')[0].value = detail.title;
                document.getElementsByName('cv-width')[0].value = detail.embed.width;
                document.getElementsByName('cv-height')[0].value = detail.embed.height;
                document.getElementsByName('cv-autoplay')[0].value = (detail.embed.autoplay ? '1' : '0');
                document.getElementsByName('cv-embedhtml')[0].value = detail.embedHtml;
                document.getElementsByName('cv-embedlink')[0].value = detail.embedLink;
                document.getElementsByName('cv-thumbnailurl')[0].value = detail.thumbnailUrl;

                if (displayTitle && !userSetTitle) {
                    displayTitle.value = detail.title;
                }
            }, true);
        }
    };
});
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
 * Admin settings for local_coursesettings.
 *
 * @package     local_coursesettings
 * @copyright   2026
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Add settings only if user has site config capability.
if ($hassiteconfig) {
    // Create the settings page for AI tools defaults.
    $settings = new admin_settingpage('local_coursesettings_aitools', new lang_string('pluginname', 'local_coursesettings'));

    // Add the enableaitools default setting.
    $settings->add(
        new admin_setting_configcheckbox(
            'moodlecourse/enableaitools',
            new lang_string('enableaitools', 'local_coursesettings'),
            new lang_string('enableaitools_help', 'local_coursesettings'),
            1  // Default to enabled
        )
    );

    // Add to the Default settings category.
    $ADMIN->add('coursedefaultsettings', $settings);
}


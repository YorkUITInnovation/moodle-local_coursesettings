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
 * Bulk AI tools enable/disable action handler.
 *
 * @package     local_coursesettings
 * @copyright   2026
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

// Require login and site config capability.
require_login();
require_capability('moodle/site:config', context_system::instance());

// Require session key and confirmation flag.
require_sesskey();

// Only accept POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_coursesettings_aitools']));
}

$confirmed = optional_param('confirmed', 0, PARAM_INT);
$enableaitools = required_param('enableaitools', PARAM_INT);

// Sanitise: value must be 0 or 1.
$enableaitools = ($enableaitools === 1) ? 1 : 0;

if (!$confirmed) {
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_coursesettings_aitools']));
}

// Update all courses except the site course (id = 1).
$DB->execute(
    'UPDATE {course} SET enableaitools = ? WHERE id <> 1',
    [$enableaitools]
);

// Redirect back to settings with a success notification.
$label = $enableaitools
    ? get_string('enableaitools_all_success', 'local_coursesettings')
    : get_string('disableaitools_all_success', 'local_coursesettings');

redirect(
    new moodle_url('/admin/settings.php', ['section' => 'local_coursesettings_aitools']),
    $label,
    null,
    \core\output\notification::NOTIFY_SUCCESS
);


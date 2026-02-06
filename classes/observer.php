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
 * Event observers for local_coursesettings.
 *
 * @package     local_coursesettings
 * @copyright   2026
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_coursesettings;

/**
 * Event observer for course creation.
 *
 * @package     local_coursesettings
 * @copyright   2026
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {

    /**
     * Observer for \core\event\course_created event.
     *
     * Applies the default enableaitools setting to newly created courses
     * based on the admin setting configured in course settings.
     *
     * @param \core\event\course_created $event The course created event.
     * @return void
     */
    public static function course_created(\core\event\course_created $event): void {
        global $DB;

        // Get the default enableaitools setting from config.
        $defaultenableaitools = get_config('moodlecourse', 'enableaitools');

        // Only update if a default is configured (not false/null).
        if ($defaultenableaitools !== false) {
            try {
                // Update the course with the default enableaitools value.
                $DB->set_field('course', 'enableaitools', $defaultenableaitools, [
                    'id' => $event->objectid,
                ]);
            } catch (\Exception $e) {
                // Log any errors but don't fail the course creation.
                debugging('Failed to set default enableaitools for course: ' . $e->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }
}


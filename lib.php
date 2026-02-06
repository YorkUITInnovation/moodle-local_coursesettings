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
 * Hook callbacks for local_coursesettings.
 *
 * @package     local_coursesettings
 * @copyright   2026
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_coursesettings;

/**
 * Hook callbacks for the local_coursesettings plugin.
 *
 * @package     local_coursesettings
 * @copyright   2026
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {

    /**
     * Hook callback for after_form_definition in course edit form.
     *
     * This hook is dispatched after the course edit form is defined.
     * It allows us to modify form defaults based on the admin setting.
     *
     * @param \core_course\hook\after_form_definition $hook The hook instance.
     * @return void
     */
    public static function after_form_definition(\core_course\hook\after_form_definition $hook): void {
        // Get the default enableaitools setting from config.
        $defaultenableaitools = get_config('moodlecourse', 'enableaitools');

        // Only set default if setting is configured (not false/null).
        if ($defaultenableaitools !== false) {
            // Check if this is a new course (no courseid yet).
            $course = $hook->formwrapper->get_course();

            // For new courses, set the default value.
            if (empty($course->id)) {
                $hook->mform->setDefault('enableaitools', $defaultenableaitools);
            }
        }
    }
}


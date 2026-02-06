# Course Settings Defaults Plugin

A Moodle 5.1 local plugin that allows administrators to set default course settings, specifically for the `enableaitools` feature.

## Features

- **Admin Setting**: Add a configurable default for the `enableaitools` setting in the Course Settings admin page (`/admin/settings.php?section=coursesettings`)
- **Event-Based Application**: Uses the `\core\event\course_created` event to apply the default setting to newly created courses
- **Form Integration**: Uses the `after_form_definition` hook to display the default in the course creation form
- **Non-Intrusive**: Does not modify any Moodle core code

## Installation

1. Extract the plugin to `/local/coursesettings/` in your Moodle installation
2. Navigate to **Site Administration > Notifications** to trigger the plugin installation
3. Once installed, navigate to **Site Administration > Courses > Course Settings > Course Settings Defaults**
4. Configure the default value for "Enable AI Tools by Default"

## How It Works

### Event Observer
When a course is created, the `course_created` event is triggered. This plugin listens for this event and:
1. Retrieves the admin-configured default value for `enableaitools`
2. Updates the newly created course with this value

### Form Hook
When a course creation form is displayed, the `after_form_definition` hook is called to:
1. Set the form field default to match the admin configuration
2. Only applies to new courses (not existing courses being edited)

## Configuration

The admin setting is located at:
**Site Administration > Courses > Course Settings > Course Settings Defaults**

- **Enable AI Tools by Default**: Check this box to enable AI tools for all newly created courses by default. Instructors can still disable this in individual course settings.

## Technical Details

- **Plugin Component**: `local_coursesettings`
- **Database Events**: Listens to `\core\event\course_created`
- **Hooks**: Uses `\core_course\hook\after_form_definition`
- **Config Namespace**: `moodlecourse/enableaitools` (stored under moodlecourse config)

## Compatibility

- Moodle 5.1 (tested)
- PHP 8.0+

## License

GNU GPL v3 or later


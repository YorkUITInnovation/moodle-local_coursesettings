# Quick Reference: local_coursesettings Plugin

## 📦 What Was Created

A complete Moodle 5.1 local plugin that allows administrators to set a default value for the `enableaitools` course setting.

## 📍 Location

`/projects/moodle51/html/public/local/coursesettings/`

## 🚀 Installation

1. Plugin already created at the correct location
2. Go to **Site Administration > Notifications**
3. Follow prompts to install
4. Done!

## ⚙️ How to Configure

**Path**: Site Administration > Courses > Course Settings > Course Settings Defaults

**Setting**: "Enable AI Tools by Default"
- ✅ Checked = AI tools enabled for all new courses
- ❌ Unchecked = AI tools disabled for all new courses

## 🔧 What the Plugin Does

### When a Course is Created:

1. **Form Display Phase**:
   - Reads the admin setting value
   - Sets the form checkbox to that value
   - User sees the default in the form

2. **Creation Phase**:
   - Course is saved to database
   - Event `course_created` is triggered
   - Plugin observer receives the event
   - Confirms/enforces the `enableaitools` value from admin config

### Result:
✅ New course has `enableaitools` set according to admin preference
✅ Instructors can override it in individual course settings
✅ Process is automatic and requires no additional action

## 📋 Files Included

| File | Purpose |
|------|---------|
| `version.php` | Plugin metadata |
| `settings.php` | Admin settings definition |
| `lib.php` | Hook callback for form defaults |
| `classes/observer.php` | Event observer for course creation |
| `db/events.php` | Event registration |
| `db/hooks.php` | Hook registration |
| `lang/en/local_coursesettings.php` | English strings |
| `README.md` | Full documentation |
| `IMPLEMENTATION_SUMMARY.md` | Technical details |
| `FILE_SUMMARY.md` | File-by-file breakdown |

## ✨ Key Features

- ✅ No core code modifications
- ✅ Uses standard Moodle events
- ✅ Uses modern Moodle hooks
- ✅ Admin-configurable
- ✅ Fail-safe (won't break course creation)
- ✅ Follows all Moodle coding standards
- ✅ Dual-mechanism (form display + database enforcement)

## 🔍 How It Works Behind the Scenes

```
Admin Setting: moodlecourse/enableaitools
                ↓
        [Form Hook]
        Sets form default
                ↓
        [Event Observer]
        Enforces database value
                ↓
        New Course with enableaitools Set
```

## 📝 Admin Setting Name

**In Admin Interface**: "Enable AI Tools by Default"
**In Database Config**: `moodlecourse/enableaitools`
**Type**: Checkbox (0 or 1)

## 🆘 Troubleshooting

**Plugin not appearing in admin settings after install?**
- Go to Site Administration > Notifications and check for errors
- Ensure the plugin folder is at `/local/coursesettings/`
- Try clearing caches: Site Administration > Development > Purge All Caches

**Settings not applying to new courses?**
- Check that you've configured the admin setting
- Verify the setting value is saved (check admin settings page)
- Clear caches and create a new test course

**Form not showing default value?**
- Clear browser cache
- Clear Moodle cache: Site Administration > Development > Purge All Caches
- Try logging out and back in

## 📧 Support

For questions, check:
1. `README.md` - User documentation
2. `IMPLEMENTATION_SUMMARY.md` - Technical details
3. `FILE_SUMMARY.md` - File-by-file breakdown

## ✅ Verification Checklist

- [x] Plugin directory created
- [x] All files syntactically correct (no PHP errors)
- [x] Follows Moodle coding standards
- [x] Event observer registered
- [x] Hook callback registered
- [x] Admin setting defined
- [x] Language strings included
- [x] Documentation complete
- [x] No core code modified
- [x] Ready for installation

## 🎯 Next Steps

1. Install the plugin via Moodle notifications
2. Navigate to admin settings to configure the default
3. Create a test course to verify it works
4. That's it! The plugin will now automatically apply the default to all new courses

---

**Created**: February 6, 2026
**Plugin Name**: Course Settings Defaults
**Component**: local_coursesettings
**Version**: 1.0.0
**Maturity**: BETA


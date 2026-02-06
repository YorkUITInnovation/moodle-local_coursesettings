# Architecture & Flow Diagrams

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                     MOODLE 5.1 SYSTEM                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │            ADMIN SETTINGS PAGE                           │  │
│  │ (Site Admin > Courses > Course Settings > Defaults)      │  │
│  │                                                            │  │
│  │  ┌────────────────────────────────────────────────────┐  │  │
│  │  │ [X] Enable AI Tools by Default                    │  │  │
│  │  │                                                    │  │  │
│  │  │ Value saved to: moodlecourse/enableaitools       │  │  │
│  │  │ Value in DB: 1 (enabled) or 0 (disabled)         │  │  │
│  │  └────────────────────────────────────────────────────┘  │  │
│  └──────────────────────────────────────────────────────────┘  │
│                           ↓                                      │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │         WHEN COURSE IS CREATED                           │  │
│  │                                                            │  │
│  │  Step 1: Form is loaded                                  │  │
│  │  ├─ HOOK: after_form_definition fires                   │  │
│  │  ├─ lib.php callback: local_coursesettings_...()       │  │
│  │  └─ Form field default = moodlecourse/enableaitools    │  │
│  │                                                            │  │
│  │  Step 2: Course data is submitted                        │  │
│  │  ├─ Course record is created in database                │  │
│  │  └─ Trigger: \core\event\course_created                │  │
│  │                                                            │  │
│  │  Step 3: Event is received                              │  │
│  │  ├─ db/events.php: Event is registered                 │  │
│  │  ├─ classes/observer.php: observer::course_created()  │  │
│  │  ├─ Read: moodlecourse/enableaitools config           │  │
│  │  └─ Update: course.enableaitools in database           │  │
│  │                                                            │  │
│  │  Result: Course has enableaitools set per admin default │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

## Plugin Component Interaction

```
                        local_coursesettings
                                │
            ┌───────────────────┼───────────────────┐
            │                   │                   │
        ┌──────────┐     ┌────────────┐     ┌──────────────┐
        │ settings │     │   lib.php  │     │ db/events.php│
        │  .php    │     │            │     │              │
        └──────────┘     └────────────┘     └──────────────┘
            │                   │                   │
            │                   ▼                   │
            │           [Hook Callback]            │
            │                                      │
            │                                      ▼
            │                            [Event Observer]
            │                                      │
            ▼                                      ▼
    [Admin Setting]              [Automatic Update]
  moodlecourse/               enableaitools value
  enableaitools                 on course creation
```

## Course Creation Flow

```
╔════════════════════════════════════════════════════════════════╗
║           USER CREATES A NEW COURSE                            ║
╚════════════════════════════════════════════════════════════════╝
                            ↓
╔════════════════════════════════════════════════════════════════╗
║ 1. FORM DISPLAY PHASE                                          ║
╠════════════════════════════════════════════════════════════════╣
║                                                                ║
║  Course Edit Form is loaded:                                 ║
║  • after_form_definition hook is triggered                   ║
║  • Hook calls: local_coursesettings_after_form_definition() ║
║  • Reads: get_config('moodlecourse', 'enableaitools')       ║
║  • Sets: $mform->setDefault('enableaitools', $value)        ║
║  • Form displays with default checkbox state                ║
║                                                                ║
║  User sees: [✓] Enable AI Tools (pre-selected)              ║
║                                                                ║
╚════════════════════════════════════════════════════════════════╝
                            ↓
╔════════════════════════════════════════════════════════════════╗
║ 2. COURSE CREATION PHASE                                       ║
╠════════════════════════════════════════════════════════════════╣
║                                                                ║
║  User clicks "Create Course" or "Save and Display"           ║
║  • Course data is validated                                  ║
║  • Course record is inserted into database                   ║
║  • \core\event\course_created is triggered                  ║
║                                                                ║
╚════════════════════════════════════════════════════════════════╝
                            ↓
╔════════════════════════════════════════════════════════════════╗
║ 3. EVENT OBSERVER PHASE                                        ║
╠════════════════════════════════════════════════════════════════╣
║                                                                ║
║  Event System:                                               ║
║  • db/events.php registers observer                          ║
║  • Callback: local_coursesettings\observer::course_created  ║
║                                                                ║
║  Observer Logic:                                             ║
║  1. Receives $event (contains course ID)                    ║
║  2. Reads: get_config('moodlecourse', 'enableaitools')     ║
║  3. Updates: $DB->set_field('course', 'enableaitools',    ║
║              $value, ['id' => $course->id])                ║
║  4. Error handling: Logs errors but doesn't fail           ║
║                                                                ║
╚════════════════════════════════════════════════════════════════╝
                            ↓
╔════════════════════════════════════════════════════════════════╗
║ 4. RESULT                                                      ║
╠════════════════════════════════════════════════════════════════╣
║                                                                ║
║  ✅ Course created successfully                              ║
║  ✅ enableaitools field set per admin default               ║
║  ✅ Course is now visible/usable                            ║
║  ✅ Instructors can modify setting if needed                ║
║                                                                ║
╚════════════════════════════════════════════════════════════════╝
```

## File Dependency Graph

```
version.php (metadata)
    ↑
    │
    ├─→ settings.php (admin UI)
    │       ↑
    │       ├─→ lang/en/local_coursesettings.php (strings)
    │       └─→ moodlecourse/enableaitools (config storage)
    │
    ├─→ lib.php (hook callback)
    │       ↑
    │       ├─→ db/hooks.php (hook registration)
    │       └─→ after_form_definition hook
    │
    └─→ classes/observer.php (event handler)
            ↑
            ├─→ db/events.php (event registration)
            └─→ course_created event
```

## Data Flow

```
┌────────────────────────────────────────────────────────────┐
│ Admin Configuration                                         │
│ (Site Admin Settings)                                       │
└────────────────────┬─────────────────────────────────────┘
                     │
                     ▼
        ┌────────────────────────┐
        │ moodlecourse/          │
        │ enableaitools = 1      │
        │ (stored in mdl_config) │
        └────────┬───────────────┘
                 │
         ┌───────┴───────┐
         │               │
         ▼               ▼
    [Form Hook]    [Event Observer]
    Hook reads     Observer reads
    from config    from config
         │               │
         ▼               ▼
    ┌─────────────┐  ┌─────────────┐
    │Form Field   │  │DB Update    │
    │Default: 1   │  │enableaitools│
    └─────────────┘  │value set    │
                     └─────────────┘
         │               │
         └───────┬───────┘
                 ▼
        ┌─────────────────────┐
        │ mdl_course table    │
        │ enableaitools = 1   │
        │ (new course record) │
        └─────────────────────┘
```

## Dual-Mechanism Approach

```
┌─ FORM HOOK ─────────────────────────────────┐
│                                              │
│  Provides User-Facing Default               │
│  ├─ Reads: moodlecourse/enableaitools      │
│  ├─ Sets: Form field default                │
│  └─ Result: UI shows correct default        │
│                                              │
│  When it fires: Course form is displayed    │
│  Why it's needed: UX - user sees default   │
│                                              │
└──────────────────────────────────────────────┘

┌─ EVENT OBSERVER ────────────────────────────┐
│                                              │
│  Provides Database Enforcement              │
│  ├─ Reads: moodlecourse/enableaitools      │
│  ├─ Updates: course.enableaitools          │
│  └─ Result: DB always has correct value    │
│                                              │
│  When it fires: After course is created    │
│  Why it's needed: Data integrity           │
│                                              │
└──────────────────────────────────────────────┘

Together: Reliable system with good UX
├─ If form input is somehow skipped
│  → Event ensures DB is still correct
│
└─ If form input is captured
   → Event confirms/enforces the value
```

## Moodle Hook System Integration

```
┌──────────────────────────────────────────────┐
│ MOODLE HOOK SYSTEM                           │
├──────────────────────────────────────────────┤
│                                              │
│  Hook: \core_course\hook\...                │
│  ├─ after_form_definition (where we hook)  │
│  └─ Other hooks...                          │
│                                              │
│  Dispatcher: di::get(hook\manager::class)   │
│  └─ Dispatches hooks to all listeners       │
│                                              │
│  Our Plugin:                                 │
│  ├─ db/hooks.php (registers callback)      │
│  └─ lib.php (implements callback)           │
│                                              │
│  Registration:                               │
│  [                                           │
│    'hook' => after_form_definition::class,  │
│    'callback' => 'local_coursesettings_...' │
│  ]                                           │
│                                              │
│  Execution:                                  │
│  Hook fires → Manager calls all → Ours runs │
│                                              │
└──────────────────────────────────────────────┘
```

## Moodle Event System Integration

```
┌──────────────────────────────────────────────┐
│ MOODLE EVENT SYSTEM                          │
├──────────────────────────────────────────────┤
│                                              │
│  Event: \core\event\course_created         │
│  ├─ Triggered when: Course is created      │
│  ├─ Data included: Course ID, user ID, etc │
│  └─ Observers can react to it              │
│                                              │
│  Our Plugin:                                 │
│  ├─ db/events.php (registers observer)     │
│  └─ classes/observer.php (receives event)  │
│                                              │
│  Registration:                               │
│  [                                           │
│    'eventname' => 'course_created',        │
│    'callback' => 'observer::course_created'│
│  ]                                           │
│                                              │
│  Execution:                                  │
│  Event fires → System notifies all → Ours   │
│  Observer runs → Updates database           │
│                                              │
└──────────────────────────────────────────────┘
```


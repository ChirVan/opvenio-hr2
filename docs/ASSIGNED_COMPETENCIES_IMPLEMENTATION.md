# Assigned Competencies Implementation

## Overview
This document outlines the implementation of the new `assigned_competencies` table and system to replace the old `skill_gap_assignments` approach. The new system provides a more comprehensive and structured way to assign competencies to employees.

## Database Changes

### New Table: `assigned_competencies`
**Location:** `database/migrations/2025_11_26_000000_create_assigned_competencies_table.php`

**Schema:**
```sql
- id (primary key)
- employee_id (indexed)
- employee_name
- job_title
- competency_id (foreign key to competencies table, indexed)
- framework_id (foreign key to competency_frameworks table, indexed)
- assignment_type (enum: development, gap_closure, skill_enhancement, mandatory)
- proficiency_level (enum: beginner, intermediate, advanced, expert)
- priority (enum: low, medium, high, critical)
- target_date (date)
- notes (text)
- status (enum: assigned, in_progress, completed, on_hold, cancelled)
- progress_percentage (integer, default 0)
- assigned_by (user id)
- assigned_at (timestamp)
- started_at (timestamp)
- completed_at (timestamp)
- timestamps
```

**Key Features:**
- Foreign key constraints for data integrity
- Multiple indexes for query performance
- Tracks progress percentage
- Supports various assignment types and priorities
- Links to actual competency records (not just keys)

## Model Changes

### New Model: `AssignedCompetency`
**Location:** `app/Modules/competency_management/Models/AssignedCompetency.php`

**Relationships:**
- `competency()` - belongs to Competency model
- `framework()` - belongs to CompetencyFramework model
- `assignedBy()` - belongs to User model

**Scopes:**
- `active()` - filters assigned and in_progress statuses
- `completed()` - filters completed statuses
- `byEmployee($employeeId)` - filters by employee
- `byPriority($priority)` - filters by priority

**Accessors:**
- `status_color` - returns color classes based on status
- `priority_color` - returns color classes based on priority
- `progress_status` - returns progress status based on percentage

## Controller Changes

### Updated: `CompetencyGapAnalysisController::assignSkillGap()`
**Location:** `app/Modules/competency_management/Controllers/CompetencyGapAnalysisController.php`

**Changes:**
1. **Renamed Parameters:**
   - `competency_key` → `competency_id`
   - `action_type` → `assignment_type`

2. **New Parameters:**
   - `priority` (required: low, medium, high, critical)
   - `target_date` (optional: date)
   - `proficiency_level` (optional: beginner, intermediate, advanced, expert)

3. **New Validations:**
   - Validates competency exists in database
   - Checks for duplicate active assignments
   - Uses foreign key relationships

4. **New Features:**
   - Stores actual competency and framework references
   - Tracks assignment metadata (assigned_by, assigned_at)
   - Returns comprehensive response with framework information

## View Changes

### Updated: `create.blade.php`
**Location:** `resources/views/competency_management/CompetencyCRUD/create.blade.php`

**Major UI Improvements:**

1. **Framework-Based Categorization:**
   - Groups competencies by their framework
   - Color-coded framework tabs
   - Shows framework descriptions
   - Dynamic framework detection

2. **Enhanced Competency Selection:**
   - Search functionality across name, framework, and description
   - Select All / Deselect All buttons
   - Category-specific selection
   - Real-time selected count with color coding
   - Displays competency status (active, draft, inactive, archived)

3. **New Form Fields:**
   - **Assignment Type** (replaces Action Type):
     - Development - General skill growth
     - Gap Closure - Address skill gaps
     - Skill Enhancement - Improve existing skills
     - Mandatory - Required competency
   
   - **Priority Level**:
     - Low (Blue)
     - Medium (Yellow) - Default
     - High (Orange)
     - Critical (Red)
   
   - **Target Completion Date**:
     - Optional date picker
     - Helps track timelines

4. **JavaScript Updates:**
   - Changed `competency_key` → `competency_id`
   - Changed `action_type` → `assignment_type`
   - Added `priority` and `target_date` to submission
   - Enhanced validation for new required fields

## Comparison: Old vs New System

### Old System (skill_gap_assignments)
```
- competency_key (string)
- action_type (critical, training, mentoring)
- status (pending, in_progress, completed, cancelled)
- Simple assignment tracking
```

### New System (assigned_competencies)
```
- competency_id (foreign key)
- framework_id (foreign key)
- assignment_type (development, gap_closure, skill_enhancement, mandatory)
- priority (low, medium, high, critical)
- proficiency_level (beginner, intermediate, advanced, expert)
- status (assigned, in_progress, completed, on_hold, cancelled)
- progress_percentage (0-100)
- target_date
- Comprehensive tracking with relationships
```

## Benefits of New System

1. **Data Integrity:**
   - Foreign key constraints ensure valid competencies
   - Relationship-based instead of string-based

2. **Better Tracking:**
   - Progress percentage tracking
   - Multiple status options including 'on_hold'
   - Target dates for planning
   - Proficiency level tracking

3. **Enhanced Categorization:**
   - Links to actual framework records
   - Supports multiple assignment types
   - Priority levels for better resource allocation

4. **Improved UI/UX:**
   - Framework-based filtering
   - Better visual organization
   - Real-time feedback
   - Enhanced search capabilities

5. **Scalability:**
   - Indexed for performance
   - Supports future features (progress tracking, reporting)
   - Flexible assignment types

## Migration Path

### From Old to New System:

1. **Database:**
   - New table created: `assigned_competencies`
   - Old table remains: `skill_gap_assignments` (for reference)

2. **Code:**
   - Controller updated to use new table
   - Model created with proper relationships
   - View updated with new fields and UI

3. **Data Migration (if needed):**
   ```php
   // Example migration script (create if needed)
   $oldAssignments = DB::connection('competency_management')
       ->table('skill_gap_assignments')->get();
   
   foreach ($oldAssignments as $old) {
       // Map old competency_key to new competency_id
       // Create AssignedCompetency record
   }
   ```

## API Response Format

### Success Response:
```json
{
    "success": true,
    "message": "Competency assigned successfully",
    "data": {
        "assignment_id": 1,
        "employee": "John Doe",
        "competency": "Leadership Skills",
        "framework": "Leadership Framework",
        "assignment_type": "development",
        "priority": "medium"
    }
}
```

### Error Response:
```json
{
    "success": false,
    "message": "This competency is already assigned to the employee"
}
```

## Future Enhancements

1. **Progress Tracking Dashboard:**
   - Visual progress bars
   - Timeline views
   - Completion statistics

2. **Notifications:**
   - Email reminders for target dates
   - Progress milestone notifications
   - Assignment confirmations

3. **Reporting:**
   - Assignment analytics
   - Employee competency matrices
   - Framework coverage reports

4. **Batch Operations:**
   - Bulk assignments
   - Team-level assignments
   - Template-based assignments

## Testing Checklist

- [ ] Migration runs successfully
- [ ] Can create new assignments
- [ ] Foreign key constraints work
- [ ] Duplicate detection works
- [ ] Form validation works
- [ ] Search functionality works
- [ ] Framework filtering works
- [ ] Progress tracking updates
- [ ] Status transitions work
- [ ] Activity logs created

## Files Modified/Created

### Created:
1. `database/migrations/2025_11_26_000000_create_assigned_competencies_table.php`
2. `app/Modules/competency_management/Models/AssignedCompetency.php`
3. `docs/ASSIGNED_COMPETENCIES_IMPLEMENTATION.md`

### Modified:
1. `app/Modules/competency_management/Controllers/CompetencyGapAnalysisController.php`
2. `resources/views/competency_management/CompetencyCRUD/create.blade.php`

## Notes

- The old `skill_gap_assignments` table is NOT dropped and can still be used for historical data
- All new assignments will use the `assigned_competencies` table
- The route name remains `competency.skill-gaps.assign` for backward compatibility
- Activity logs still created for audit trail

---
**Created:** November 26, 2025  
**Author:** Development Team  
**Version:** 1.0

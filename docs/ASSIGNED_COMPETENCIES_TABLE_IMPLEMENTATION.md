# Assigned Competencies Table - Implementation Guide

## Overview
This document outlines the new UI table added to the Competency Gap Analysis page that displays all employees with assigned competencies, including actions for training materials and learning assessments.

## ✅ What Has Been Implemented

### 1. **New UI Section Added**
- Location: `resources/views/competency_management/competency_gap_analysis.blade.php`
- Added below the existing Gap Analysis table
- Features:
  - Responsive table design with filters
  - Real-time search functionality
  - Status and priority filtering
  - Assignment type filtering
  - Pagination support

### 2. **Table Columns**
| Column | Description |
|--------|-------------|
| Employee | Employee name, avatar, and job title |
| Competency | Competency name and framework |
| Assignment Type | Development, Gap Closure, Enhancement, Mandatory |
| Priority | Critical, High, Medium, Low (color-coded) |
| Progress | Visual progress bar (0-100%) |
| Status | Assigned, In Progress, Completed, On Hold |
| Target Date | Deadline with overdue indicators |
| Assigned Date | When competency was assigned |
| Actions | Training Materials, Learning Assessment, More Options |

### 3. **Action Buttons**
- **Assign Training Materials** (Blue button)
  - Opens modal to select and assign training content
  - Supports courses, videos, documents
  
- **Schedule Learning Assessment** (Purple button)
  - Opens modal to schedule quiz/assessment
  - Select assessment type and due date
  - Add notes and instructions

- **More Actions** (Dropdown menu)
  - Update Progress
  - Change Status
  - View Details
  - Remove Assignment

### 4. **Modals Created**
1. **Training Materials Modal**
   - Search training materials
   - Multi-select checkboxes
   - Shows material type (Video, Document, Course)
   - Displays duration/page count

2. **Learning Assessment Modal**
   - Assessment type selector (Quiz, Practical, Interview, etc.)
   - Quiz/Assessment dropdown
   - Due date picker
   - Notes field
   - Helpful guidelines

### 5. **JavaScript Functions**
All client-side functionality implemented:
- `loadAssignedCompetencies()` - Fetch data from API
- `renderAssignedTable()` - Display table rows
- `filterAssignedTable()` - Apply filters
- `openTrainingModal()` - Open training assignment
- `openLearningAssessment()` - Schedule assessment
- `updateProgress()` - Update completion percentage
- `changeStatus()` - Change assignment status
- Badge rendering functions (status, priority, type)
- Date formatting and status indicators
- Pagination controls

## ⚠️ What Needs To Be Done

### 1. **Add Controller Method**
Add this method to `CompetencyGapAnalysisController.php` (at the end, before the closing `}`):

```php
/**
 * Get all assigned competencies
 */
public function getAssignedCompetencies(Request $request)
{
    try {
        $assignedCompetencies = \App\Modules\competency_management\Models\AssignedCompetency::with(['competency', 'framework'])
            ->orderBy('assigned_at', 'desc')
            ->get();

        $data = $assignedCompetencies->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'employee_id' => $assignment->employee_id,
                'employee_name' => $assignment->employee_name,
                'job_title' => $assignment->job_title,
                'competency_id' => $assignment->competency_id,
                'competency_name' => $assignment->competency ? $assignment->competency->competency_name : 'N/A',
                'framework_id' => $assignment->framework_id,
                'framework_name' => $assignment->framework ? $assignment->framework->framework_name : 'N/A',
                'assignment_type' => $assignment->assignment_type,
                'priority' => $assignment->priority,
                'proficiency_level' => $assignment->proficiency_level,
                'target_date' => $assignment->target_date ? $assignment->target_date->format('Y-m-d') : null,
                'notes' => $assignment->notes,
                'status' => $assignment->status,
                'progress_percentage' => $assignment->progress_percentage,
                'assigned_by' => $assignment->assigned_by,
                'assigned_at' => $assignment->assigned_at ? $assignment->assigned_at->format('Y-m-d H:i:s') : null,
                'started_at' => $assignment->started_at ? $assignment->started_at->format('Y-m-d H:i:s') : null,
                'completed_at' => $assignment->completed_at ? $assignment->completed_at->format('Y-m-d H:i:s') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $data->count()
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching assigned competencies: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch assigned competencies: ' . $e->getMessage(),
            'data' => []
        ], 500);
    }
}
```

### 2. **API Route Already Added**
✅ The API route has been added to `routes/api.php`:
```php
Route::get('/assigned-competencies', [CompetencyGapAnalysisController::class, 'getAssignedCompetencies']);
```

### 3. **Future Enhancements**

#### Training Materials Integration
Currently showing placeholder data. To integrate with actual training system:
```javascript
function loadTrainingMaterials() {
    // Replace with actual API call to training materials
    fetch(`/api/training-materials?competency_id=${currentCompetencyId}`)
        .then(response => response.json())
        .then(data => {
            // Render actual training materials
        });
}
```

#### Learning Assessment Integration
Currently showing placeholder quizzes. To integrate with actual assessment system:
```javascript
function loadAvailableQuizzes() {
    // Replace with actual API call to learning management system
    fetch(`/api/quizzes?competency_id=${currentCompetencyId}`)
        .then(response => response.json())
        .then(data => {
            // Render actual quizzes/assessments
        });
}
```

#### Update Progress Functionality
Implement actual progress update:
```php
// Add to CompetencyGapAnalysisController.php
public function updateProgress(Request $request, $id)
{
    $validated = $request->validate([
        'progress_percentage' => 'required|integer|min:0|max:100'
    ]);

    $assignment = AssignedCompetency::findOrFail($id);
    $assignment->progress_percentage = $validated['progress_percentage'];
    
    if ($validated['progress_percentage'] === 100 && $assignment->status !== 'completed') {
        $assignment->status = 'completed';
        $assignment->completed_at = now();
    } elseif ($validated['progress_percentage'] > 0 && $assignment->status === 'assigned') {
        $assignment->status = 'in_progress';
        $assignment->started_at = $assignment->started_at ?? now();
    }
    
    $assignment->save();

    return response()->json([
        'success' => true,
        'message' => 'Progress updated successfully'
    ]);
}
```

#### Change Status Functionality
```php
public function changeStatus(Request $request, $id)
{
    $validated = $request->validate([
        'status' => 'required|in:assigned,in_progress,completed,on_hold,cancelled'
    ]);

    $assignment = AssignedCompetency::findOrFail($id);
    $assignment->status = $validated['status'];
    
    if ($validated['status'] === 'completed') {
        $assignment->completed_at = now();
        $assignment->progress_percentage = 100;
    } elseif ($validated['status'] === 'in_progress' && !$assignment->started_at) {
        $assignment->started_at = now();
    }
    
    $assignment->save();

    return response()->json([
        'success' => true,
        'message' => 'Status updated successfully'
    ]);
}
```

## 🎨 UI Features

### Filters
- **Status Filter**: Filter by assignment status
- **Priority Filter**: Filter by priority level  
- **Assignment Type Filter**: Filter by type
- **Search Box**: Search employee name, competency, or job title

### Visual Indicators
- **Progress Bar**: Green gradient showing completion percentage
- **Status Badges**: Color-coded status indicators
- **Priority Badges**: With emoji indicators (🔴🟠🟡🔵)
- **Date Status**: Shows "Overdue" or "Due soon" for target dates
- **Empty State**: Shows when no data available

### Responsive Design
- Mobile-friendly table layout
- Hover effects on rows and buttons
- Smooth transitions
- Dropdown menus for more actions

## 📊 Data Flow

```
┌─────────────────────────────────────────────────────────┐
│  Competency Gap Analysis Page (competency_gap_analysis.blade.php)│
└───────────────────┬─────────────────────────────────────┘
                    │
                    ▼
        ┌───────────────────────┐
        │  JavaScript on Load   │
        │  loadAssignedCompetencies()│
        └───────────┬───────────┘
                    │
                    ▼
        ┌───────────────────────┐
        │  API Request          │
        │  GET /api/assigned-competencies│
        └───────────┬───────────┘
                    │
                    ▼
        ┌──────────────────────────────┐
        │  CompetencyGapAnalysisController│
        │  getAssignedCompetencies()   │
        └───────────┬──────────────────┘
                    │
                    ▼
        ┌───────────────────────┐
        │  AssignedCompetency   │
        │  Model with Relations │
        └───────────┬───────────┘
                    │
                    ▼
        ┌───────────────────────┐
        │  JSON Response        │
        │  Returns data array   │
        └───────────┬───────────┘
                    │
                    ▼
        ┌───────────────────────┐
        │  renderAssignedTable()│
        │  Display in UI        │
        └───────────────────────┘
```

## 🚀 Testing Checklist

- [ ] API endpoint returns data correctly
- [ ] Table displays assigned competencies
- [ ] Filters work (status, priority, type, search)
- [ ] Pagination works correctly
- [ ] Training modal opens and closes
- [ ] Assessment modal opens and closes
- [ ] Progress update function works
- [ ] Status change function works
- [ ] Date formatting correct
- [ ] Badges display proper colors
- [ ] Responsive on mobile devices
- [ ] Empty state displays when no data
- [ ] Loading state shows while fetching

## 📝 Notes

- All competencies must be assigned through the "Assign Competency to Employee" form first
- Data is stored in the `assigned_competencies` table
- Real-time filtering happens client-side for better performance
- Pagination is implemented but loads all data initially (can be optimized with server-side pagination)
- Training materials and assessment integration requires connection to respective modules

---
**Created:** November 26, 2025  
**Status:** UI Complete, Backend Partially Implemented  
**Next Steps:** Add controller method and integrate with training/assessment systems

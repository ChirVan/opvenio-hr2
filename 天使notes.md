
# 📢🔗🛠️🎚️⛔🚧🆕 WHAT'S NEW 

- 

# LAST TIME 01/03/26

- 📢 NEW PACKAGE INSTALLED: Prism 📢

- 🆕 New File: For the instruction prompts: 12/31/25-23:56-resources/prompts/recommendation_template.txt

- 🆕 New File: All database that are locally stored are now in the repo too: 12/31/25-17:52-storage/sql_backup/*.sql

- 🆕 New File: for testing the AI, Line 16 is the prompt: 12/31/25-17:52-app/Http/Controllers/PrismTestController.php

- 🆕 New File: for connecting to the AI: 12/31/25-17:52-app\Services\AIService.php

- 🚧 IN-DEV: A Controller recommendation using AI: 12/31/25-17:52-app\Services\AIService.php


~~線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線~~

# 📌⚠️ Important Information by 天使 ⚠️📌
> Always `npm run build` *before* pushing to git, there are circumstances that styles won't apply when deployed

> Ocassionally Sync database with HR4 using button on the dashboard, for database consistency 


~~線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線~~

## Bugs, Vulnerabilities and Adjustments 🐞🎯¯\_(ツ)_/¯
❌ Bug 1: This project is hopeless😭😭

❌ Bug 2: Data is globally declared, data leak is possible

✅ Bug 3: Dashboard, Potential Successors are displayed multiple times FIXED:12/17/2025-00:20-routes/web.php

❌ Bug 4: Middleware, not working, no response when applied to routes

❌ Bug 5: payslip.blade.php, dragging and dropping files into payslip does not record it

❌ Bug 6: training_management.course_requests table has the wrong email of employee, displayed in resources/views/training_management/grant.blade.php

❌ Vulnerability 1: Dashboard, Upcoming Trainings is static 

❌ Vulnerability 2: Nav-bar, Notification Bell is static

❌ Vulnerability 3: Nav-bar, Mail/Inbox is static

❌ Vulnerability 4: ESS is accessible by staffs and admins, indicating no middleware restrictions

✅ Vulnerability 5: Dashboard, API errors out when there's no internet connection

✅ Adjustment 1: Remove successors displaying email column for practicality FIXED:12/17/2025-14:34-resources/views/succession_planning/successors.blade.php

✅ Adjustment 2: Sidebar, Styling adjustments for practicality FIXED:12/17/2025-23:38-resources\views\layouts\sidebar.blade.php

✅ Adjustment 3: Nav-bar, Time removed seconds, not updating real-time anyway FIXED:12/17/2025-22:03-resources\views\layouts\navbar.blade.php



~~線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線~~

## TODO 📝

Training Management
# Grant Request
✅ [Assign Assessment Alert Message] 

Learning Management
# Assessment Result
✅ [Add Table Pagination]
> [Make Submit Score not Approve and Reject button on Single Assessment Evaluation]
> [Improve Alert Message After Submission on Single Assessment Evaluation]
> [Improve Confirmation Message on Step 2 Evaluation]
> [Improve Approve Confirmation message on Submission of Approve or Delete Button]

Competency Management
# Gap Analysis
> [Add Pagination on table]


> Find bugs and fix

✅ Sync HR4's db to our db: Make the api calling instantly create accounts based on the employee information retrieved, also update existing accounts 

✅ Add Prism

✅ Add AI


~~線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線~~

## Codes Parking Lot 🅿️🔧
Put code snippets here you don't want to lose


# How to prompt to AI, sample controller -天使
```
// app/Http/Controllers/AiRecommendationController.php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIService;

class AiRecommendationController extends Controller
{
    public function recommend(Request $request, AIService $ai)
    {
        $payload = $request->input('payload'); // accept employee payload as JSON
        $template = resource_path('prompts/recommendation_template.txt');

        try {
            $result = $ai->recommendFromPayload($payload, $template);
            return response()->json(['success' => true, 'result' => $result]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}

```
# Its Route:
```
Route::post('/ai/recommend', [\App\Http\Controllers\AiRecommendationController::class,'recommend'])->middleware('auth');
```

# description
```
<h1>Your code here</h1>
```


~~線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線~~

## </end>

天使 or てん◦し read as 'ten-shi' meaning angel :)

# 📢🔗🛠️🎚️⛔🚧🆕 NEW COMMIT 01/03/26
# 🆕 Installed Prism and Connected OpenAI -天使

- 📢 NEW PACKAGE INSTALLED: Prism 📢

- 🆕 New File: For the instruction prompts: 12/31/25-23:56-resources/prompts/recommendation_template.txt

- 🆕 New File: All database that are locally stored are now in the repo too: 12/31/25-17:52-storage/sql_backup/*.sql

- 🆕 New File: for testing the AI, Line 16 is the prompt: 12/31/25-17:52-app/Http/Controllers/PrismTestController.php

- 🆕 New File: for connecting to the AI: 12/31/25-17:52-app\Services\AIService.php

- 🚧 IN-DEV: A Controller recommendation using AI: 12/31/25-17:52-app\Services\AIService.php


# LAST COMMIT 12/29/25-23:49
# NEW: Added syncing db with hr4, success -天使

- 🆕 Added new Syncing database logic from HR4 API employee data 12/23/25-00:15-routes/api.php-line:23-118

- 🆕 Added button for the syncing in 12/23/25-23:16-resources/views/dashboard.blade.php-line:35-40

- 🆕 Added migrations file for adding employee_status column to opvenio_hr2-users for Syncing database logic 12/23/25-23:16-2025_12_27_222525_add_employment_status_to_users_table.php

- 🆕 Added 'employment_status' for writable 12/23/25-23:16-app/Models/User.php-line:34


~~線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線~~

# 📌⚠️ Important Information by 天使 ⚠️📌
> Always `npm run build` *before* pushing to git, there are circumstances that styles won't apply when deployed


~~線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線~~

## Bugs, Vulnerabilities and Adjustments 🐞🎯¯\_(ツ)_/¯
❌ Bug 1: This project is hopeless😭😭

❌ Bug 2: Data is globally declared, data leak is possible

✅ Bug 3: Dashboard, Potential Successors are displayed multiple times FIXED:12/17/2025-00:20-routes/web.php

❌ Bug 4: Middleware, not working, no response when applied to routes

❌ Vulnerability 1: Dashboard, Upcoming Trainings is static 

❌ Vulnerability 2: Nav-bar, Notification Bell is static

❌ Vulnerability 3: Nav-bar, Mail/Inbox is static

✅ Adjustment 1: Remove successors displaying email column for practicality FIXED:12/17/2025-14:34-resources/views/succession_planning/successors.blade.php

✅ Adjustment 2: Sidebar, Styling adjustments for practicality FIXED:12/17/2025-23:38-resources\views\layouts\sidebar.blade.php

✅ Adjustment 3: Nav-bar, Time removed seconds, not updating real-time anyway FIXED:12/17/2025-22:03-resources\views\layouts\navbar.blade.php



~~線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線~~

## TODO 📝
> Find bugs and fix

✅ Sync HR4's db to our db: Make the api calling instantly create accounts based on the employee information retrieved, also update existing accounts 

> Prepare for the integration of AI(OpenAI)

> Add Prism

> Add AI


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
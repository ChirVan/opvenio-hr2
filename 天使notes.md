
# 📢🔗🛠️🎚️⛔🆕 NEW COMMIT
- 🆕 Added new Syncing database logic from HR4 API employee data 12/23/25-00:15-routes/api.php-line:23-118

- 🆕 Added button for the syncing in 12/23/25-11:16-resources/views/dashboard.blade.php-line:35-40

- 🆕 Added migrations file for adding employee_status column to opvenio_hr2-users for Syncing database logic 2025_12_27_222525_add_employment_status_to_users_table.php

- 🆕 Added 'employment_status' for writable 12/23/25-11:16-app/Models/User.php-line:34


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

> Sync HR4's db to our db: Make the api calling instantly create accounts based on the employee information retrieved, also update existing accounts 

> Prepare for the integration of AI(OpenAI)

> Add Prism

> Add AI


~~線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線~~

## Codes Parking Lot 🅿️🔧
Put code snippets here you don't want to lose


# Working sync hr4 db logic(no delete) -天使
```
Route::post('/syncdb', function (Request $request) {

    $created = $updated = $skipped = $errors = 0;

    try{
        $response = Http::timeout(30)
            ->withOptions(['verify' => false])
            ->get('https://hr4.microfinancial-1.com/allemployees');

        if (! $response->successful()) {
            return redirect()->back()->with('sync_result', 'Failed to fetch employee data from HR4.');
        }

        $employees = $response->json();

        foreach ($employees as $employee) {
            try{
                // skip if no employee_id
                if (empty($employee['employee_id'])) {
                    $skipped++;
                    continue;
                }

                $employee_id = $employee['employee_id'];
                $name = $employee['full_name'] ?? ($employee['firstname'] ?? null);
                $email = $employee['email'] ?? null;
                $status = $employee['employment_status'] ?? null; // e.g. "Active" or "Terminated"

                $user = User::where('employee_id', $employee_id)->first();

                if ($user) {
                    // UPDATE safe fields and employment_status (do not overwrite password or role)
                    $dirty = false;

                    if ($name && $name !== $user->name) {
                        $user->name = $name;
                        $dirty = true;
                    }

                    if ($email && $email !== $user->email) {
                        $user->email = $email;
                        $dirty = true;
                    }

                    // update employment_status if changed
                    if (!is_null($status) && $status !== $user->employment_status) {
                        $user->employment_status = $status;
                        $dirty = true;
                    }

                    if ($dirty) {
                        $user->save();
                        $updated++;
                    }
                } else {
                    // CREATE user; set employment_status on create
                    $password = '12345678'; // test default (ok for now)
                    $user = User::create([
                        'employee_id' => $employee_id,
                        'name' => $name,
                        'email' => $email,
                        'role' => 'employee', // set only on create
                        'password' => Hash::make($password),
                        'employment_status' => $status,
                    ]);
                    $created++;
                }
            }catch(\Throwable $ex){
                $errors++;
                Log::error("HR4 sync error for employee {$employee['employee_id']}: " . $ex->getMessage(), [
                    'employee' => $employee,
                    'exception' => $ex,
                ]);
                continue;
            }
        }

        $message = "Sync finished — Created: {$created}, Updated: {$updated}, Skipped: {$skipped}, Errors: {$errors}";
        return redirect()->back()->with('sync_result', $message);
        
    } catch (\Exception $e) {
        Log::error('HR4 sync fatal error: '.$e->getMessage());
        return redirect()->back()->with('sync_result', 'Sync failed: API connection error.');
    }
    
});
```

# description
```
<h1>Your code here</h1>
```


~~線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線線~~

## </end>

天使 - tenshi, meaning angel :)
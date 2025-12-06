# 🎯 Quick Start Guide: Using Role Mapping Skill Gap Features

## Where to Find the Buttons

### 📍 Location: http://localhost:8000/competency/rolemapping

---

## 🔵 Button 1: "Address Immediately" (Assign Skill Gap)
**Where it appears:** In the "Critical Gaps" red box on the Development Recommendations section

**What it does:** Opens a modal to assign a skill gap

**Steps:**
1. Click "Address Immediately" button in the red Critical Gaps box
2. Modal opens with form:
   - Employee name (pre-filled)
   - Select competency from dropdown
   - Select gap level (Critical, Moderate, Minor)
   - Pick target completion date
   - Add notes (optional)
3. Click "Assign Gap" button
4. Success message appears
5. Data saved to database

---

## 🟢 Button 2: "Create Development Plan"
**Where it appears:** 
- Bottom right corner (main action button)
- OR click "Schedule Training" in yellow Minor Gaps box
- OR click "Start Development" in blue Recommended Next Steps box

**What it does:** Opens a modal to create a development plan

**Steps:**
1. Click any "Create Development Plan" / "Schedule Training" / "Start Development" button
2. Modal opens with form:
   - Employee name (pre-filled)
   - Plan title (e.g., "Q1 2025 Development Plan")
   - Start date (defaults to today)
   - End date (defaults to 90 days from now)
   - Goals & objectives
   - Action items
   - Success metrics
3. Click "Create Plan" button
4. Success message appears
5. Data saved to database

---

## 🟣 Button 3: "Schedule Assessment Retake"
**Where it appears:** Bottom right corner (next to Create Development Plan)

**What it does:** Opens a modal to schedule an assessment

**Steps:**
1. Click "Schedule Assessment Retake" button
2. Modal opens with form:
   - Employee name (pre-filled)
   - Select assessment type:
     * Competency Retake
     * Skill Validation
     * Comprehensive Evaluation
     * Progress Check
   - Scheduled date/time (defaults to 1 week from now)
   - Notes (optional)
3. Click "Schedule Assessment" button
4. Success message appears
5. Data saved to database

---

## 🔍 Testing Checklist

### ✅ Before Testing:
1. Make sure you're at: `http://localhost:8000/competency/rolemapping`
2. Make sure an employee is selected (you should see their name at the top)
3. Open browser DevTools (press F12)
4. Go to Console tab to see any errors

### ✅ Test Each Button:
1. **Assign Skill Gap:**
   - [ ] Click "Address Immediately"
   - [ ] Modal opens
   - [ ] Competencies load in dropdown
   - [ ] Fill form and submit
   - [ ] Success message appears

2. **Create Development Plan:**
   - [ ] Click "Create Development Plan"
   - [ ] Modal opens
   - [ ] Dates are pre-filled
   - [ ] Fill form and submit
   - [ ] Success message appears

3. **Schedule Assessment:**
   - [ ] Click "Schedule Assessment Retake"
   - [ ] Modal opens
   - [ ] Date is pre-filled (1 week ahead)
   - [ ] Fill form and submit
   - [ ] Success message appears

---

## 🐛 If Buttons Don't Work:

### Check 1: JavaScript Console
1. Press F12
2. Go to Console tab
3. Look for red error messages
4. Common errors:
   - "CSRF token mismatch" → Refresh the page
   - "fetch is not defined" → Browser issue, try Chrome
   - "Swal is not defined" → SweetAlert2 not loaded

### Check 2: Network Tab
1. Press F12
2. Go to Network tab
3. Click a button
4. Look for POST requests to:
   - `/competency/skill-gaps/assign`
   - `/competency/development-plans/create`
   - `/competency/assessments/schedule`
5. Click on the request
6. Check Response tab for error details

### Check 3: Laravel Logs
1. Open: `c:\xampp\htdocs\dashboard\opvenio-hr2\storage\logs\laravel.log`
2. Look for recent errors
3. Common issues:
   - Database connection error
   - Missing table (run migration)
   - Validation error

---

## 💾 Where Data is Saved

After successful submission, check these database tables:

1. **Skill Gap Assignments:**
   ```
   Database: competency_management
   Table: skill_gap_assignments
   ```

2. **Development Plans:**
   ```
   Database: competency_management
   Table: development_plans
   ```

3. **Assessment Schedules:**
   ```
   Database: competency_management
   Table: assessment_schedules
   ```

You can view the data using phpMyAdmin or any MySQL client.

---

## 🎨 Visual Cues

### Modals Should Look Like This:
- White background
- Rounded corners
- Centered on screen
- Gray overlay behind
- X button in top right to close
- Form fields with labels
- Buttons at bottom (Cancel + Submit)

### Success Messages (SweetAlert2):
- Green checkmark icon
- "Success!" title
- Brief confirmation message
- Blue "OK" button

### Error Messages:
- Red X icon
- "Error" title
- Error description
- Red "OK" button

---

## 📞 Quick Troubleshooting

**Problem:** Modal doesn't open
**Solution:** Check browser console (F12) for JavaScript errors

**Problem:** Form submits but no success message
**Solution:** Check Network tab for 500 error, then check Laravel logs

**Problem:** Competencies dropdown is empty
**Solution:** Visit http://localhost:8000/competency/competencies-list to check API

**Problem:** "Employee not found" error
**Solution:** Employee ID may not exist in external API

**Problem:** CSRF token mismatch
**Solution:** Refresh the page to get new token

---

## ✨ You're All Set!

The functionality is fully implemented and ready to use. Just:
1. Go to the rolemapping page
2. Select an employee
3. Click any button
4. Fill the form
5. Submit!

The data will be saved and you'll see a success message. 🎉

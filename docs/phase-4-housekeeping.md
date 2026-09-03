# Phase 4: Housekeeping Operations Module

## Overview
Phase 4 introduces a dedicated Housekeeping Operations Module to the Hotel Operations Management Platform. This builds upon the Room Domain introduced in Phase 3 by providing a structured workflow for room cleaning and inspection.

## Existing Task Audit Decision
The original IT Helpdesk included a `cleaning_tasks` table and `CleaningTask` model.
**Audit Result:** This implementation was tightly coupled to the `Equipment` model (representing IT equipment maintenance) and did not fit physical room cleaning workflows.
**Decision:** We preserved the existing `cleaning_tasks` architecture for IT and Engineering equipment maintenance. We created a completely isolated domain `housekeeping_tasks` for Hotel Housekeeping. No old operational history was destroyed.

## Database Schema & Models
### `housekeeping_tasks` Table
- **Primary Key:** `id`
- **Identifier:** `task_number` (Unique string generated sequentially per year, e.g., HK-2026-0001)
- **Relationships:**
  - `room_id` -> `rooms.id`
  - `assigned_to` -> `users.id`
  - `inspected_by` -> `users.id`
- **Enums:**
  - `App\Enums\HousekeepingTaskType`: `CHECKOUT_CLEANING`, `STAYOVER_CLEANING`, `DEEP_CLEANING`, `TURNDOWN`, `INSPECTION`
  - `App\Enums\HousekeepingTaskStatus`: `PENDING`, `ASSIGNED`, `IN_PROGRESS`, `COMPLETED`, `INSPECTED`, `CANCELLED`
- **Timestamps:** Tracks `scheduled_at`, `started_at`, `completed_at`, and `inspected_at`.

## Task Workflow & Room Synchronization
Task transitions are handled cleanly via `App\Services\HousekeepingTaskService`, enforcing state changes and syncing with the physical `RoomStatus`:

1. **PENDING/ASSIGNED → IN_PROGRESS** 
   - *Action:* Housekeeper starts the task.
   - *Result:* `started_at` is set. Room status changes to `CLEANING`.
2. **IN_PROGRESS → COMPLETED**
   - *Action:* Housekeeper completes the cleaning.
   - *Result:* `completed_at` is set. Room status changes to `INSPECTED` (meaning it requires inspection).
3. **COMPLETED → INSPECTED**
   - *Action:* Inspector or IT Admin passes the room.
   - *Result:* `inspected_at` and `inspected_by` are set. Room status changes to `AVAILABLE`.

## Authorization & Roles
A new `housekeeping` role represents Housekeeping Staff.
- **IT Admin:** Full CRUD and management access over all Housekeeping tasks.
- **Housekeeping:** Can view tasks, view the Housekeeping Dashboard, and interact with tasks assigned to them (Start, Complete).
- **Employee (Other Depts):** No access to Housekeeping.

## Views & UI
- **Dashboard (`/housekeeping/dashboard`)**: Provides high-level operational metrics, a list of the logged-in user's tasks, and highlights rooms requiring attention (Dirty/Cleaning).
- **Task List (`/housekeeping/tasks`)**: A filterable index of all housekeeping tasks.
- **Task Detail (`/housekeeping/tasks/{task}`)**: Shows a visual timeline of task progression and context-sensitive action buttons.
- **Room Detail Integration**: The `rooms.show` view now displays a "Housekeeping History" widget, cementing the Room Detail page as the central operational hub for a physical room.

## Known Limitations & Technical Debt
- Housekeeping currently lacks granular task checklists (e.g., ticking off "Make Bed", "Clean Bathroom").
- Real-time WebSockets/pusher notifications are not yet implemented. Staff must refresh to see new assignments.

## Recommended Phase 5
**Phase 5: Engineering & Preventive Maintenance**
- Audit and integrate the existing `cleaning_tasks` or `Equipment` domain into a unified Engineering Preventive Maintenance module.
- Introduce work orders for broken room assets (e.g., "AC broken in Room 503").

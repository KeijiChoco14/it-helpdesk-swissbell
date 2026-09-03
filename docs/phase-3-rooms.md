# Phase 3: Room Management & Room Domain Foundation

## Overview
Phase 3 introduces the foundational Room Management module to the Hotel Operations Platform. This phase transitions the application from a pure IT Helpdesk to a system capable of linking operational requests directly to hotel infrastructure (Rooms).

## Architectural Principle
The application uses the following domain relationship:
`RoomType <-> Room <-> ServiceRequest`

We specifically avoided introducing a `Hotel` or `Property` model in this phase, as the platform currently represents a single hotel operation (Swiss-Belinn Pekanbaru). Multi-tenancy or multi-property architectures are reserved for future phases if required.

## Database Schema
### 1. `room_types` Table
Defines the categories of rooms available in the hotel.
- `id` (PK)
- `name` (string)
- `code` (string, unique)
- `description` (text, nullable)
- `capacity` (integer, nullable)

### 2. `rooms` Table
Defines individual physical rooms.
- `id` (PK)
- `room_number` (string, unique)
- `floor` (integer)
- `room_type_id` (FK to `room_types`)
- `status` (string, mapped to `RoomStatus` Enum)
- `description` (text, nullable)

### 3. `service_requests` Table Updates
- A foreign key constraint `room_id` referencing `rooms.id` has been added. The relationship is `nullOnDelete()` to ensure that service requests remain intact even if a room is deleted.

## Models & Enums
- **`RoomType` Model**: Manages room categories.
- **`Room` Model**: Represents individual rooms.
- **`RoomStatus` Enum**: A PHP 8.1 Backed Enum (`app/Enums/RoomStatus.php`) standardizing room statuses: `AVAILABLE`, `OCCUPIED`, `DIRTY`, `CLEANING`, `INSPECTED`, `MAINTENANCE`, `OUT_OF_ORDER`. Includes helper methods for labels and colors.
- **`ServiceRequest` Model**: Added `room()` `belongsTo` relationship.

## Authorization
Role-based access follows the existing system:
- **IT Admin / Admin**: Full CRUD capabilities on Rooms and Room Types.
- **IT Support**: Can view Rooms and Room Types.
- **Employee**: Can view basic room information when assigned to a relevant Service Request.

## Integration with Service Requests
Users creating or editing a Service Request can now optionally assign the request to a specific Room via a dropdown menu. 
The Room Detail page (`/rooms/{room}`) displays all Service Requests associated with that room, providing a unified view of the room's operational history.

## Safety & Constraints
- **Unsafe Deletion Prevention**: The `RoomController` prevents the deletion of any Room that has associated Service Requests. Users are instead instructed to change the room's status to `OUT_OF_ORDER`.

## Known Technical Debt / Limitations
- Housekeeping, Engineering, and Front Office task tracking (e.g., automated status updates from DIRTY to CLEANING to AVAILABLE) are not yet implemented.
- The Room UI filter uses basic HTML form submission rather than an asynchronous JS datatable.

## Recommended Phase 4
Phase 4 should focus on **Housekeeping & Preventive Maintenance**.
- Introduce Task Checklists for Room Cleaning.
- Implement workflow automation (e.g., when a "Clean Room" task completes, the Room Status automatically changes to `AVAILABLE` or `INSPECTED`).
- Build a robust Engineering Preventive Maintenance schedule for specific rooms.

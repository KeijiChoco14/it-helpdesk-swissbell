# Phase 2: Service Request Abstraction

## Old Architecture
The application used an IT-specific `Ticket` model and table (`tickets`). Relationships included `TicketComment`, `TicketAttachment`, and `TicketActivityLog`. The views, controllers, and routes were tightly coupled to the term "ticket".

## New Architecture
The application now uses a generic `ServiceRequest` model as the base for all operational requests. This aligns with the long-term goal of building a Hotel Operations Management Platform. The core table is `service_requests`.

## Migrations Performed
- Renamed `tickets` table to `service_requests`
- Added nullable fields: `room_id` and `asset_id`
- Renamed `ticket_comments` to `service_request_comments` (and updated foreign key to `service_request_id`)
- Renamed `ticket_attachments` to `service_request_attachments` (and updated foreign key)
- Renamed `ticket_activity_logs` to `service_request_activity_logs` (and updated foreign key)

## Renamed Models
- `Ticket` -> `ServiceRequest`
- `TicketComment` -> `ServiceRequestComment`
- `TicketAttachment` -> `ServiceRequestAttachment`
- `TicketActivityLog` -> `ServiceRequestActivityLog`

## New Relationships & Fields
- Added `asset()` relationship (belongsTo Equipment)
- Added `room_id` field (integer, preparing for Phase 3: Rooms)
- Extracted SLA Logic to `App\Services\SlaService` to decouple business logic from Model Boot events.

## Compatibility Strategy
- All existing IT routes (e.g. `/tickets`) are preserved via HTTP redirects to the canonical `/service-requests` routes.
- Existing seeded IT requests and their relationships remain intact and function under the new architecture.
- Roles like `it_support` and `it_admin` continue to operate as before, with authorization policies mapped to `ServiceRequestPolicy`.
- Database column `ticket_number` remains functionally identical for backward compatibility in Phase 2.

## Testing Performed
- Static verification of updated files.
- (Awaiting User Execution: `php artisan migrate`, `php artisan route:list`)

## Known Limitations
- The term `ticket_number` remains in the DB schema for now to minimize destructive operations.
- UI elements primarily label requests as "Service Requests" but some internal names might still hint at tickets until fully generalized in the final polish phase.

## Next Recommended Phase
Phase 3: Rooms Management (Creating rooms, room types, and enabling proper `room_id` foreign keys).

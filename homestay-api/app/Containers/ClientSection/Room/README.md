# Room API Documentation

## Public Endpoints (No Authentication Required)

### List Rooms
- **GET** `/rooms`
- **Description**: Get a paginated list of available rooms with optional filtering
- **Query Parameters**:
  - `min_price` (optional): Minimum price filter
  - `max_price` (optional): Maximum price filter
  - `capacity` (optional): Minimum capacity required
  - `room_type_id` (optional): Filter by room type
  - `check_in` (optional): Check-in date (YYYY-MM-DD)
  - `check_out` (optional): Check-out date (YYYY-MM-DD)
  - `page` (optional): Page number for pagination
  - `per_page` (optional): Items per page (max 100)

### Find Room by ID
- **GET** `/rooms/{id}`
- **Description**: Get detailed information about a specific room
- **Parameters**:
  - `id`: Room ID
- **Query Parameters**:
  - `include_images` (optional, default: true): Include room images
  - `include_attributes` (optional, default: true): Include room attributes
  - `include_facilities` (optional, default: true): Include surrounding facilities
  - `include_pricing` (optional, default: true): Include pricing information

### Search Rooms
- **GET** `/rooms/search`
- **Description**: Search for rooms using text query and advanced filters
- **Query Parameters**:
  - `query` (required): Search query (minimum 2 characters)
  - `min_price` (optional): Minimum price filter
  - `max_price` (optional): Maximum price filter
  - `capacity` (optional): Minimum capacity required
  - `room_type_id` (optional): Filter by room type
  - `check_in` (optional): Check-in date
  - `check_out` (optional): Check-out date
  - `sort_by` (optional): Sort by field (relevance, price, name, capacity, created_at)
  - `sort_order` (optional): Sort order (asc, desc)

### Check Room Availability
- **GET** `/rooms/{id}/availability`
- **Description**: Check if a room is available for the specified dates
- **Parameters**:
  - `id`: Room ID
- **Query Parameters**:
  - `check_in` (required): Check-in date
  - `check_out` (required): Check-out date
  - `guests` (optional): Number of guests

### Get Room Availability Calendar
- **GET** `/rooms/{id}/availability/calendar`
- **Description**: Get room availability calendar for date picker
- **Parameters**:
  - `id`: Room ID
- **Query Parameters**:
  - `start_date` (optional): Calendar start date
  - `end_date` (optional): Calendar end date

### Get Featured Rooms
- **GET** `/rooms/featured`
- **Description**: Get featured rooms for homepage display
- **Returns**: Array of 6 featured rooms

### Get Search Suggestions
- **GET** `/rooms/search/suggestions`
- **Description**: Get room search suggestions for autocomplete
- **Query Parameters**:
  - `q` (required): Search query (minimum 2 characters)

### Get Popular Filters
- **GET** `/rooms/filters/popular`
- **Description**: Get popular search filters for UI
- **Returns**: Available price ranges, capacity options, and room types

### Get Room Types
- **GET** `/room-types`
- **Description**: Get available room types for filtering
- **Query Parameters**:
  - `include_room_count` (optional, default: false): Include count of available rooms

## Private Endpoints (Authentication Required)

### Get Personalized Rooms
- **GET** `/rooms/personalized`
- **Description**: Get personalized room recommendations based on user preferences

### Bookmark Room
- **POST** `/rooms/{id}/bookmark`
- **Description**: Add a room to user's bookmarks

### Remove Bookmark
- **DELETE** `/rooms/{id}/bookmark`
- **Description**: Remove a room from user's bookmarks

### Get Bookmarked Rooms
- **GET** `/rooms/bookmarked`
- **Description**: Get user's bookmarked rooms

### Add to View History
- **POST** `/rooms/{id}/view-history`
- **Description**: Add a room to user's view history

### Get Recently Viewed Rooms
- **GET** `/rooms/recently-viewed`
- **Description**: Get user's recently viewed rooms

## Response Format

All API responses follow this format:

```json
{
  "data": {
    // Response data here
  },
  "meta": {
    // Pagination metadata for paginated responses
  }
}
```

## Error Responses

```json
{
  "message": "Error message",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

## Room Data Structure

```json
{
  "object": "room",
  "id": "room_hash_id",
  "name": "Room Name",
  "description": "Room description",
  "area": 25.5,
  "capacity": 2,
  "bed_count": 1,
  "bathroom_count": 1,
  "floor": 2,
  "view_direction": "Sea view",
  "base_price": 500000,
  "formatted_price": "500,000 VND",
  "cleaning_fee": 50000,
  "status": "active",
  "check_in_time": "14:00:00",
  "check_out_time": "12:00:00",
  "min_stay_duration": 1,
  "max_stay_duration": 30,
  "advance_booking_days": 7,
  "instant_booking": true,
  "is_instant_bookable": true,
  "smoking_allowed": false,
  "pets_allowed": false,
  "parties_allowed": false,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z",
  "roomType": {
    // Room type data
  },
  "images": [
    // Room images array
  ],
  "attributes": [
    // Room attributes array
  ]
}
```

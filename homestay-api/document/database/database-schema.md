# Database Design Documentation

## Overview

This document describes the database schema for the Homestay Management System. The schema is designed to support flexible room management, pricing, discount policies, and special offers for homestay properties.

---

## Table List

- `room_types`
- `room_access_types`
- `attributes`
- `room_attributes`
- `surrounding_facilities`
- `room_surrounding_facilities`
- `room_images`
- `room_highlight_facilities`
- `room_highlight_facility_images`
- `price_ratios`
- `room_pricing_policies`
- `room_weekday_prices`
- `room_price_history`
- `room_discount_policies`
- `room_hourly_pricing`
- `room_combo_pricing`
- `room_special_offers`

---

## Table Descriptions

### 1. room_types
- **id**: PK, auto increment
- **code**: string, code for room type
- **name**: json, multi-language
- **description**: json, multi-language, nullable
- **timestamps**

### 2. room_access_types
- **id**: PK, auto increment
- **code**: string, code for access type
- **name**: json, multi-language
- **description**: json, multi-language, nullable
- **timestamps**

### 3. attributes
- **id**: PK, auto increment
- **code**: string, code for attribute
- **name**: json, multi-language
- **description**: json, multi-language, nullable
- **timestamps**

### 4. room_attributes
- **id**: PK, auto increment
- **room_id**: FK to rooms
- **attribute_id**: FK to attributes
- **value**: string, nullable
- **timestamps**

### 5. surrounding_facilities
- **id**: PK, auto increment
- **code**: string, code for facility
- **name**: json, multi-language
- **description**: json, multi-language, nullable
- **timestamps**

### 6. room_surrounding_facilities
- **id**: PK, auto increment
- **room_id**: FK to rooms
- **facility_id**: FK to surrounding_facilities
- **distance**: integer, nullable
- **timestamps**

### 7. room_images
- **id**: PK, auto increment
- **room_id**: FK to rooms
- **image_url**: text
- **s3_key**: text, nullable
- **is_cover**: boolean, default false
- **order_index**: integer, default 0
- **area_type**: string, nullable
- **timestamps**

### 8. room_highlight_facilities
- **id**: PK, auto increment
- **room_id**: FK to rooms
- **code**: string, code for highlight facility
- **name**: json, multi-language
- **description**: json, multi-language, nullable
- **timestamps**

### 9. room_highlight_facility_images
- **id**: PK, auto increment
- **facility_id**: FK to room_highlight_facilities
- **image_url**: text
- **s3_key**: text, nullable
- **is_cover**: boolean, default false
- **order_index**: integer, default 0
- **timestamps**

### 10. price_ratios
- **id**: PK, auto increment
- **code**: string, unique, code for price ratio
- **ratio**: decimal(4,2)
- **name**: json, multi-language
- **description**: json, multi-language, nullable
- **timestamps**

### 11. room_pricing_policies
- **id**: PK, auto increment
- **room_id**: FK to rooms
- **checkin_time**: time
- **checkout_time**: time
- **base_price**: decimal(12,2)
- **currency**: string, default 'VND'
- **max_guests**: integer
- **standard_guests**: integer
- **extra_adult_price**: decimal(12,2), nullable
- **extra_child_price**: decimal(12,2), nullable
- **extra_hour_price**: decimal(12,2), nullable
- **cleaning_fee**: decimal(12,2), nullable
- **deposit_amount**: decimal(12,2), nullable
- **cleaning_gap_hours**: integer, nullable
- **timestamps**

### 12. room_weekday_prices
- **id**: PK, auto increment
- **policy_id**: FK to room_pricing_policies
- **weekday**: tinyint (1=Mon, ..., 7=Sun)
- **price**: decimal(12,2)
- **timestamps**

### 13. room_price_history
- **id**: PK, auto increment
- **room_id**: FK to rooms
- **policy_snapshot**: json
- **changed_at**: timestamp, default current

### 14. room_discount_policies
- **id**: PK, auto increment
- **room_id**: FK to rooms
- **dayuse_checkin**: time, nullable
- **dayuse_checkout**: time, nullable
- **price_ratio_id**: FK to price_ratios, nullable
- **late_checkin**: time, nullable
- **late_checkout**: time, nullable
- **late_discount**: enum('0','10',...,'100'), default '0'
- **timestamps**

### 15. room_hourly_pricing
- **id**: PK, auto increment
- **room_id**: FK to rooms
- **min_hours**: tinyint
- **min_hours_price**: decimal(12,2)
- **next_hour_price**: decimal(12,2), nullable
- **currency**: string, default 'VND'
- **timestamps**

### 16. room_combo_pricing
- **id**: PK, auto increment
- **room_id**: FK to rooms
- **start_time**: time
- **end_time**: time
- **mon_price**: decimal(12,2), nullable
- **tue_price**: decimal(12,2), nullable
- **wed_price**: decimal(12,2), nullable
- **thu_price**: decimal(12,2), nullable
- **fri_price**: decimal(12,2), nullable
- **sat_price**: decimal(12,2), nullable
- **sun_price**: decimal(12,2), nullable
- **currency**: string, default 'VND'
- **timestamps**

### 17. room_special_offers
- **id**: PK, auto increment
- **room_id**: FK to rooms
- **last_minute_hours**: integer, nullable
- **last_minute_discount_percent**: decimal(5,2), nullable
- **monthly_price**: decimal(12,2), nullable
- **currency**: string, default 'VND'
- **timestamps**

---

## Relationships
- Most tables reference `rooms` via `room_id`.
- Many-to-many and join tables: `room_attributes`, `room_surrounding_facilities`, `room_highlight_facility_images`.
- Pricing and policy tables are linked to rooms and to each other via foreign keys.

---

## Notes
- All tables use InnoDB and support foreign key constraints.
- Multi-language fields use JSON for flexibility.
- Timestamps are included for tracking creation and updates.
- All monetary fields use decimal for accuracy.

---

## Last Updated
- 2025-07-26

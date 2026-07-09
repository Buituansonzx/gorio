# AdminSection

This section contains all administrative APIs for the HoneStay platform backend management.

## Purpose

The AdminSection provides APIs specifically designed for:
- **Admin Dashboard**: Management interface for administrators
- **System Configuration**: Platform settings and configurations  
- **Analytics & Reporting**: Business intelligence and reports
- **User Management**: Advanced user and partner management
- **Content Management**: Managing platform content and policies

## Key Differences from AppSection

| Feature | AppSection (Customer API) | AdminSection (Admin API) |
|---------|---------------------------|---------------------------|
| **Authentication** | User login with basic permissions | Admin login with elevated permissions |
| **Data Access** | Limited to user's own data | Full system data access |
| **Operations** | Read-mostly, limited writes | Full CRUD operations |
| **Validation** | User-focused validation | Administrative validation |
| **Rate Limiting** | Higher limits for normal usage | Lower limits but admin bypass |

## Modules

### 1. Authentication
- Admin login/logout
- Role-based access control
- Permission management
- Admin session management

### 2. Management
- User management (CRUD users)
- Partner/Host management
- Property approval/rejection
- Booking oversight

### 3. Analytics
- Revenue reports
- User analytics
- Property performance
- Booking statistics

### 4. Settings
- Platform configuration
- Payment gateway settings
- Email/SMS templates
- System maintenance

## API Structure

All AdminSection APIs follow the pattern:
```
/api/admin/v1/{module}/{action}
```

Examples:
- `POST /api/admin/v1/auth/login`
- `GET /api/admin/v1/users`
- `GET /api/admin/v1/analytics/revenue`
- `PUT /api/admin/v1/settings/payment`

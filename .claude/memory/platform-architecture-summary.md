---
name: Platform Architecture Summary
description: Summary of the single-site Laravel platform architecture and development patterns
type: project
---

# Platform Architecture Summary

## Platform Type
This is a **single-site Laravel platform** (NOT multi-tenant/SaaS) with admin panel and API endpoints.

## Core Architecture Components

### 1. Admin Panel (web.php)
- Content management system for administrators
- CRUD operations with DataTables integration
- Permission-based access control
- Blade template views
- ResponseService with view redirects

### 2. API Layer (api.php) 
- RESTful endpoints for Next.js/apps
- JSON responses via ResponseService
- Authentication via Sanctum/Passport
- API Resources for data transformation
- Public and protected endpoints

### 3. Unified Service Layer
- **Shared business logic** between Admin + API
- Single Service and Repository per feature
- No code duplication
- BaseService with sendResponse() method
- Interface-based dependency injection

### 4. Repository Layer
- DataListManager for list operations
- Eloquent ORM for database operations
- Filtering, searching, pagination
- Shared by Admin + API controllers

### 5. Database Schema
- **Single MySQL database** (no tenant isolation)
- Business categorization via `site_type` field
- Standard Laravel migrations
- Audit trail with soft deletes

## Business Categorization (site_type)

The `site_type` field categorizes content by business type within the single platform:

- **GENERAL = 1**: General content
- **SOLAR = 2**: Solar business content  
- **CAR = 3**: Car business content
- **DROP_SHIPPING = 4**: Dropshipping business content
- **BATTERY_WATER = 5**: Battery water business content

**IMPORTANT**: This is NOT multi-tenancy - it's business content categorization within a single platform for organizing different types of business content.

## Key Development Patterns

### ResponseService Pattern
```php
// Service Layer (always returns this format)
return $this->sendResponse(
    bool $success,
    string $message, 
    mixed $data = [],
    int $status = 200,
    string $errorMessage = ""
);

// Controller Layer (always uses ResponseService)
return ResponseService::send([
    'response' => $response,
    'data' => $data,
], view: $view, successRoute: $route);
```

### DataListManager Pattern
```php
// Repository list methods must use DataListManager
public function dataList($request): array
{
    return DataListManager::list(
        request: $request,
        query: Model::query(),
        searchable: ['table.column_name'],
        filters: ['filter_key' => ['column' => 'table.column_name']],
        select: ['table.id', 'table.column_name'],
        notIn: isset($request->notIn) ? $request->notIn : []
    );
}
```

### View Path Pattern
```php
// ALWAYS use viewss() helper - NEVER hardcode paths
view: viewss('your-feature', 'list')  // ✅ CORRECT
view: 'admin.your-feature.index'       // ❌ WRONG

// View paths must be registered in Viewed class
protected static array $views = [
    'your-feature' => [
        'list' => 'admin.your-feature.index',
        'create' => 'admin.your-feature.create', 
        'edit' => 'admin.your-feature.edit',
    ],
];
```

### Request Validation Pattern
```php
// ALL request classes MUST extend BaseFormRequest (NOT FormRequest)
use App\Http\Requests\BaseFormRequest;  // ✅ CORRECT

class YourFeatureCreateRequest extends BaseFormRequest
{
    // Validation rules here
}
```

## File Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/YourFeatureController.php    // Admin CRUD
│   │   └── Api/YourFeatureController.php       // API endpoints
│   ├── Services/
│   │   └── YourFeature/
│   │       ├── YourFeatureService.php          // SHARED by Admin + API
│   │       ├── YourFeatureServiceInterface.php
│   │       ├── YourFeatureRepository.php      // SHARED by Admin + API  
│   │       └── YourFeatureRepositoryInterface.php
│   └── Requests/
│       └── YourFeature/
│           ├── YourFeatureCreateRequest.php
│           └── YourFeatureUpdateRequest.php
├── Models/YourFeature.php
└── View/Components/YourFeatureComponent.php

resources/views/admin/your-feature/
├── index.blade.php
├── create.blade.php
└── edit.blade.php

routes/
├── web.php        // Admin routes
└── api.php        // API routes
```

## Development Workflow

### For New Features (Admin + API):
1. **Database Setup**: Create migration and model
2. **Request Validation**: Create Form Request classes
3. **Repository Layer**: Implement with DataListManager pattern
4. **Service Layer**: Implement with sendResponse pattern  
5. **Admin Controller**: Create with ResponseService pattern
6. **API Controller**: Create using existing service (no new service needed)
7. **API Resources**: Create for JSON transformation
8. **Routes**: Add to web.php (admin) and api.php (API)
9. **Testing**: Test both admin and API functionality

### For Admin-Only Features:
1. Follow steps 1-7 from above (skip API controller and routes)

### For API-Only Features:
1. Create service/repository (if doesn't exist)
2. Create API controller
3. Create API resources
4. Add API routes

## Important Constraints

### ✅ What This Platform IS:
- Single-site Laravel platform
- Admin panel for content management  
- RESTful API for Next.js/apps
- Business content categorization
- Shared service layer architecture
- Permission-based admin access
- Token-based API authentication

### ❌ What This Platform is NOT:
- **NOT multi-tenant/SaaS** - no tenant databases or isolation
- **NOT subscription-based** - no feature access control
- **NOT multi-domain** - single platform instance
- **NOT complex roles** - simple admin + API user model
- **NOT separate databases** - single MySQL database

## Security Architecture

### Admin Security:
- Authentication: admin.auth middleware
- Authorization: Permission-based access
- CSRF Protection: Token verification
- Input Validation: BaseFormRequest classes
- Audit Trail: All operations logged

### API Security:
- Authentication: Sanctum/Passport tokens
- Authorization: User verification
- Rate Limiting: Throttle middleware
- Input Validation: Shared Form Requests
- Data Protection: Resource transformation

## Performance Optimization

### Database:
- Proper indexing on searchable/filterable columns
- Eager loading for relationships
- Query optimization with DataListManager
- Database connection pooling

### Application:
- Caching strategies for expensive operations
- API Resources for efficient JSON responses
- Lazy loading for large datasets
- Queue system for heavy operations

### Frontend:
- DataTables with server-side processing
- Asset compilation and minification
- Image optimization
- Lazy loading for images

## Development Standards

### Code Quality:
- Follow SOLID principles
- Keep controllers thin (move logic to services)
- Use dependency injection
- Write self-documenting code
- Maintain consistent naming conventions

### Testing:
- Unit tests for services and repositories
- Feature tests for controllers
- API endpoint testing
- Security testing
- Performance testing

### Documentation:
- Clear code comments for complex logic
- API documentation (OpenAPI/Swagger)
- Database schema documentation
- Development guides and patterns

## Common Patterns Reference

### Standard Method Names:
- **Repository**: `dataList()`, `createData()`, `getModelByAny()`
- **Service**: `getListData()`, `storeOrUpdateData()`, `deleteData()`, `statusUpdate()`
- **Controller**: `index()`, `create()`, `store()`, `edit()`, `destroy()`, `status()`

### Response Patterns:
- **Service Success**: `$this->sendResponse(true, __('Success message'), $data)`
- **Service Error**: `$this->sendResponse(false, __('Error message'))`
- **Controller View**: `ResponseService::send(['data' => $data], view: viewss('feature', 'list'))`
- **Controller Redirect**: `ResponseService::send(['response' => $response], successRoute: 'feature.list')`

This architecture provides a solid foundation for building a scalable, maintainable single-site Laravel platform with efficient admin and API development.
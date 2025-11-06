# Unified API Response System

This guide explains how to use the unified API response system implemented in the Laravel application.

## Overview

The unified API response system provides consistent error handling and response formatting across all your API endpoints. It includes:

- **ApiResponse Trait**: Methods for consistent responses in controllers
- **ApiResponse Class**: Static methods for responses
- **Custom Exception Handler**: Automatic error formatting
- **Configuration**: Centralized API settings

## Features

### 1. Consistent Response Format

All API responses follow this structure:

```json
{
    "success": true/false,
    "message": "Response message",
    "data": {}, // Only for success responses
    "errors": {}, // Only for error responses
    "timestamp": "2024-01-01T00:00:00.000000Z"
}
```

### 2. Automatic Error Handling

The system automatically catches and formats:
- Validation errors (422)
- Authentication errors (401)
- Authorization errors (403)
- Not found errors (404)
- Method not allowed (405)
- Database errors (500)
- General server errors (500)

## Usage

### In Controllers

#### Using the Trait (Recommended)

Since all controllers extend the base `Controller` class, you automatically have access to response methods:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return $this->successResponse($users, 'Users retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve users');
        }
    }

    public function store(Request $request)
    {
        try {
            $user = User::create($request->validated());
            return $this->createdResponse($user, 'User created successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to create user');
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->notFoundResponse('User not found');
        }

        return $this->successResponse($user, 'User retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->notFoundResponse('User not found');
        }

        try {
            $user->update($request->validated());
            return $this->updatedResponse($user, 'User updated successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to update user');
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->notFoundResponse('User not found');
        }

        try {
            $user->delete();
            return $this->deletedResponse('User deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to delete user');
        }
    }
}
```

#### Using Static Methods

```php
use App\Http\Responses\ApiResponse;

// Success responses
return ApiResponse::success($data, 'Success message');
return ApiResponse::created($data, 'Created message');
return ApiResponse::updated($data, 'Updated message');
return ApiResponse::deleted('Deleted message');

// Error responses
return ApiResponse::error('Error message', 400);
return ApiResponse::notFound('Not found message');
return ApiResponse::unauthorized('Unauthorized message');
return ApiResponse::forbidden('Forbidden message');
return ApiResponse::serverError('Server error message');
return ApiResponse::validationError($errors, 'Validation failed');
```

### Available Response Methods

#### Success Responses
- `successResponse($data, $message, $statusCode)` - General success
- `createdResponse($data, $message)` - Resource created (201)
- `updatedResponse($data, $message)` - Resource updated (200)
- `deletedResponse($message)` - Resource deleted (200)

#### Error Responses
- `errorResponse($message, $statusCode, $errors)` - General error
- `validationErrorResponse($errors, $message)` - Validation errors (422)
- `notFoundResponse($message)` - Not found (404)
- `unauthorizedResponse($message)` - Unauthorized (401)
- `forbiddenResponse($message)` - Forbidden (403)
- `serverErrorResponse($message)` - Server error (500)

### In Form Requests

Update your form requests to use the unified error response:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Responses\ApiResponse;

class StoreUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::validationError($validator->errors())
        );
    }
}
```

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# API Response Configuration
API_DEBUG=false
API_LOG_ERRORS=true
API_LOG_LEVEL=error

# Rate Limiting
API_RATE_LIMITING=true
API_RATE_LIMIT_MAX_ATTEMPTS=60
API_RATE_LIMIT_DECAY_MINUTES=1
```

### Configuration File

The system uses `config/api.php` for centralized configuration. You can modify response formats, messages, and status codes there.

## Error Handling

### Automatic Exception Handling

The custom exception handler automatically formats all exceptions for API requests:

- **ValidationException**: Returns 422 with validation errors
- **AuthenticationException**: Returns 401
- **AuthorizationException**: Returns 403
- **ModelNotFoundException**: Returns 404
- **QueryException**: Returns 500 with database error message
- **HttpException**: Returns appropriate HTTP status code

### Debug Information

When `APP_DEBUG=true` and `API_DEBUG=true`, error responses include debug information:

```json
{
    "success": false,
    "message": "Internal server error",
    "timestamp": "2024-01-01T00:00:00.000000Z",
    "debug": {
        "file": "/path/to/file.php",
        "line": 123,
        "trace": [...]
    }
}
```

## Best Practices

### 1. Always Use Try-Catch

```php
public function store(Request $request)
{
    try {
        $user = User::create($request->validated());
        return $this->createdResponse($user, 'User created successfully');
    } catch (\Exception $e) {
        \Log::error('User creation failed: ' . $e->getMessage());
        return $this->serverErrorResponse('Failed to create user');
    }
}
```

### 2. Check for Resources Before Operations

```php
public function show($id)
{
    $user = User::find($id);
    
    if (!$user) {
        return $this->notFoundResponse('User not found');
    }

    return $this->successResponse($user, 'User retrieved successfully');
}
```

### 3. Use Appropriate Status Codes

- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error
- 500: Server Error

### 4. Consistent Error Messages

Use the predefined messages from the configuration or create consistent custom messages:

```php
return $this->notFoundResponse('User with ID ' . $id . ' not found');
return $this->validationErrorResponse($errors, 'Please check your input data');
```

## Testing

### Example Test

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class UserApiTest extends TestCase
{
    public function test_user_index_returns_success_response()
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Users retrieved successfully'
                ])
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data',
                    'timestamp'
                ]);
    }

    public function test_user_show_returns_not_found_for_invalid_id()
    {
        $response = $this->getJson('/api/users/999');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'User not found'
                ]);
    }
}
```

## Migration from Existing Code

### Before (Inconsistent Responses)

```php
// Different response formats across controllers
return response()->json(['data' => $users]);
return response()->json(['success' => true, 'users' => $users]);
return response()->json(['error' => 'Not found'], 404);
```

### After (Unified Responses)

```php
// Consistent response format
return $this->successResponse($users, 'Users retrieved successfully');
return $this->notFoundResponse('User not found');
return $this->errorResponse('Something went wrong', 400);
```

## Troubleshooting

### Common Issues

1. **Validation errors not showing**: Make sure your form request extends `FormRequest` and overrides `failedValidation`

2. **Exceptions not formatted**: Ensure your exception handler is properly registered in `bootstrap/app.php`

3. **Response format inconsistent**: Check that all controllers extend the base `Controller` class

### Debug Mode

Enable debug mode to see detailed error information:

```env
APP_DEBUG=true
API_DEBUG=true
```

## Support

For issues or questions about the unified API response system, check:

1. Laravel logs in `storage/logs/laravel.log`
2. API configuration in `config/api.php`
3. Exception handler in `app/Exceptions/Handler.php`
4. Base controller in `app/Http/Controllers/Controller.php`

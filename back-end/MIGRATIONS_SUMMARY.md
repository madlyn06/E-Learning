# New Database Migrations Created

This document lists all the new database migrations created to support the enhanced eLearning system APIs.

## Migration Files Created

### 1. Tags System
- **`2025_08_21_000000_create_elearning__tags_table.php`**
  - Stores tags for categorizing discussions and other content
  - Includes name, slug, description, color, and usage tracking

### 2. Quiz System
- **`2025_08_21_000001_create_elearning__quizzes_table.php`**
  - Stores quiz information (title, description, time limit, passing score)
  - Links to lessons and instructors
  
- **`2025_08_21_000002_create_elearning__quiz_questions_table.php`**
  - Stores quiz questions with different types (multiple choice, single choice, true/false, essay)
  - Includes options, correct answers, and point values
  
- **`2025_08_21_000003_create_elearning__quiz_attempts_table.php`**
  - Tracks user quiz attempts with scores and completion status
  - Prevents multiple attempts per user per quiz
  
- **`2025_08_21_000004_create_elearning__quiz_answers_table.php`**
  - Stores individual user answers to quiz questions
  - Tracks correctness and points earned

### 3. Discussion Forum System
- **`2025_08_21_000005_create_elearning__discussions_table.php`**
  - Stores forum discussions with types (question, discussion, announcement)
  - Includes view counts, reply counts, and like counts
  
- **`2025_08_21_000006_create_elearning__discussion_replies_table.php`**
  - Stores replies to discussions with support for nested replies
  - Tracks best answer status and like counts
  
- **`2025_08_21_000007_create_elearning__discussion_likes_table.php`**
  - Polymorphic table for liking discussions and replies
  - Prevents duplicate likes from same user
  
- **`2025_08_21_000008_create_elearning__discussion_tags_table.php`**
  - Links discussions to tags for categorization
  - Many-to-many relationship

### 4. Live Session System
- **`2025_08_21_000009_create_elearning__live_sessions_table.php`**
  - Stores live session information (title, description, scheduling)
  - Includes meeting details and recording settings
  
- **`2025_08_21_000010_create_elearning__live_session_participants_table.php`**
  - Tracks participants in live sessions with roles
  - Includes audio/video settings and screen sharing status
  
- **`2025_08_21_000011_create_elearning__live_session_chat_table.php`**
  - Stores chat messages during live sessions
  - Supports different message types and private messages
  
- **`2025_08_21_000012_create_elearning__live_session_recordings_table.php`**
  - Tracks recorded live sessions with file information
  - Includes processing status and metadata

### 5. Mobile System
- **`2025_08_21_000013_create_elearning__mobile_downloads_table.php`**
  - Tracks course downloads for offline use
  - Includes progress tracking and error handling
  
- **`2025_08_21_000014_create_elearning__mobile_app_settings_table.php`**
  - Stores user preferences for mobile app
  - Includes notification and sync settings
  
- **`2025_08_21_000015_create_elearning__mobile_learning_sessions_table.php`**
  - Tracks mobile learning sessions
  - Includes device information and session duration
  
- **`2025_08_21_000016_create_elearning__mobile_crash_reports_table.php`**
  - Stores crash reports from mobile app
  - Includes device information and crash logs

### 6. Notification System
- **`2025_08_21_000017_create_elearning__notification_settings_table.php`**
  - Global notification settings per user
  - Controls email, push, SMS, and in-app notifications
  
- **`2025_08_21_000018_create_elearning__notification_preferences_table.php`**
  - Granular notification preferences by type
  - Includes frequency and timing settings

### 7. Assignment System
- **`2025_08_21_000018_create_elearning__assignments_table.php`**
  - Stores assignment definitions and requirements
  - Includes types, scoring, due dates, and grading criteria
  
- **`2025_08_21_000019_create_elearning__assignment_submissions_table.php`**
  - Stores student submissions for assignments
  - Includes file uploads, grading, feedback, and multiple attempts

### 8. Lessons Table Updates
- **`2025_08_21_000020_add_missing_fields_to_elearning__lessons_table.php`**
  - Removes deprecated fields (is_selling, is_published, is_completed, end_of_free)
  - Adds missing fields referenced in models and services
  - Expands lesson types to support document and mixed types
  - Adds performance indexes and metadata fields

## Database Schema Features

### Key Design Principles
- **Foreign Key Constraints**: Proper referential integrity with cascade deletes where appropriate
- **Indexing**: Strategic indexes for common query patterns
- **Unique Constraints**: Prevents duplicate data where business logic requires it
- **Soft Deletes**: Some tables use status fields instead of hard deletes
- **Polymorphic Relationships**: Flexible like system for different content types

### Performance Considerations
- **Composite Indexes**: For queries that filter on multiple columns
- **Timestamp Indexes**: For time-based queries and sorting
- **Status Indexes**: For filtering by common status values
- **Short Constraint Names**: Avoids MySQL identifier length limitations

## Running the Migrations

To run these migrations, use the following command:

```bash
php artisan migrate --path=modules/elearning/database/migrations
```

To rollback specific migrations:

```bash
php artisan migrate:rollback --path=modules/elearning/database/migrations --step=1
```

## Dependencies

These migrations depend on the following existing tables:
- `elearning__users` - User accounts
- `elearning__courses` - Course information
- `elearning__lessons` - Lesson content

## Notes

- All tables use the `elearning__` prefix for consistency
- Timestamps are included for audit trails
- JSON fields are used for flexible configuration data
- Enum fields provide data validation at the database level
- Foreign key constraints ensure data integrity
- Unique constraint names are kept short to avoid MySQL identifier length issues
- Assignment system supports multiple attempts and flexible grading
- Lessons table now supports additional content types and metadata

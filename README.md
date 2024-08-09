# **Sea Study API**

## **HOW TO USE**

-   **Ensure Required Software:** Make sure you have XAMPP, Laragon, or another PHP server installed.
-   **Clone the Repository:** Clone this repository to your local machine.
-   **Install Dependencies:** In the terminal, navigate to the project directory and run:

```bash
composer install
```

-   **Run the Server:** Start the Laravel development server by executing:

```bash
php artisan serve
```

## **GET** - Popular Courses (Limit=10)

```
localhost:8000/api/courses/popular
```

### Status : 200

### Content type : application/json

### Response Sample

```json
{
    "message": "string",
    "courses": [
        {
            "id": 0,
            "name": "string",
            "slug": "string",
            "instructor_id": 0,
            "instructor_name": "string",
            "category_id": 0,
            "category_name": "string",
            "description": "string",
            "syllabus": "string",
            "image": "string",
            "price": 0,
            "level": "string",
            "average_rating": "string",
            "created_at": "string", // "2024-08-07T09:49:26.000000Z"
            "updated_at": "string" // "2024-08-07T09:49:26.000000Z"
        }
    ]
}
```

## **GET** - Courses By Category Id

```
localhost:8000/api/courses/category/{category_id}
```

### Status : 200

### Content type : application/json

```json
{
    "message": "string",
    "courses": [
        {
            "id": 0,
            "name": "string",
            "slug": "string",
            "instructor_id": 0,
            "instructor_name": "string",
            "category_id": 0,
            "category_name": "string",
            "description": "string",
            "syllabus": "string",
            "image": "string",
            "price": 0,
            "level": "string",
            "average_rating": "string",
            "created_at": "string", // "2024-08-07T09:49:26.000000Z"
            "updated_at": "string" // "2024-08-07T09:49:26.000000Z"
        }
    ]
}
```

## **GET** - Courses By Difficulty Level

### Level dapat berupa : ['beginner', 'intermediate', 'professional']

```
localhost:8000/api/courses/level/{level}
```

### Status : 200

### Content type : application/json

```json
{
    "message": "string",
    "courses": [
        {
            "id": 0,
            "name": "string",
            "slug": "string",
            "instructor_id": 0,
            "instructor_name": "string",
            "category_id": 0,
            "category_name": "string",
            "description": "string",
            "syllabus": "string",
            "image": "string",
            "price": 0,
            "level": "string",
            "average_rating": "string",
            "created_at": "string", // "2024-08-07T09:49:26.000000Z"
            "updated_at": "string" // "2024-08-07T09:49:26.000000Z"
        }
    ]
}
```

## **GET** - Purchased Courses By User Id

```
localhost:8000/api/courses/enrolled/{user_id}
```

### Status : 200

### Content type : application/json

```json
{
    "message": "string",
    "courses": [
        {
            "id": 0,
            "name": "string",
            "slug": "string",
            "instructor_id": 0,
            "instructor_name": "string",
            "category_id": 0,
            "category_name": "string",
            "description": "string",
            "syllabus": "string",
            "image": "string",
            "price": 0,
            "level": "string",
            "average_rating": "string",
            "created_at": "string", // "2024-08-07T09:49:26.000000Z"
            "updated_at": "string" // "2024-08-07T09:49:26.000000Z"
        }
    ]
}
```

## **GET** - Course Detail By Id

```
localhost:8000/api/courses/{id}
```

### Status : 200

### Content type : application/json

### Response Sample

```json
{
    "message": "string",
    "courses": {
        "id": 0,
        "name": "string",
        "slug": "string",
        "instructor_id": 0,
        "instructor_name": "string",
        "category_id": 0,
        "category_name": "string",
        "description": "string",
        "syllabus": "string",
        "image": "string",
        "price": 0,
        "level": "string",
        "average_rating": "string",
        "created_at": "string", // "2024-08-07T09:49:26.000000Z"
        "updated_at": "string", // "2024-08-07T09:49:26.000000Z"
        "contents": [
            {
                "id": 0,
                "course_id": 0,
                "title": "string",
                "description": "string",
                "file": "string",
                "created_at": "string", // "2024-08-07T09:49:26.000000Z"
                "updated_at": "string" // "2024-08-07T09:49:26.000000Z"
            }
        ]
    }
}
```

## **GET** - All Course Content By Course Id

```
localhost:8000/api/courses/{course_id}/contents
```

### Status : 200

### Content type : application/json

```json
{
    "message": "string",
    "contents": [
        {
            "id": 0,
            "course_id": 0,
            "title": "string",
            "description": "string",
            "file": "string",
            "created_at": "string", // "2024-08-07T09:49:26.000000Z"
            "updated_at": "string" // "2024-08-07T09:49:26.000000Z"
        }
    ]
}
```

## **GET** - Detail Course Content By Content Id

```
localhost:8000/api/courses/{course_id}/contents/{content_id}
```

### Status : 200

### Content type : application/json

```json
{
    "message": "string",
    "content": {
        "id": 0,
        "course_id": 0,
        "title": "string",
        "description": "string",
        "file": "string",
        "created_at": "string", // "2024-08-07T09:49:26.000000Z"
        "updated_at": "string" // "2024-08-07T09:49:26.000000Z"
    }
}
```

## **POST** - Register User & Instructor

### Role dapat berupa : ['user', 'instructor', 'admin']

```
localhost:8000/api/register/{role}
```

### Status : 201

### Content type : application/json

```json
{
    "message": "string",
    "user": {
        "name": "string",
        "email": "string",
        "role": "string",
        "balance": 0
    }
}
```

## **POST** - Login User & Instructor

```
localhost:8000/api/login
```

### Status : 201

### Content type : application/json

```json
{
    "message": "string",
    "token": "string",
    "user": {
        "name": "string",
        "email": "string",
        "role": "string"
    }
}
```

## **POST** - Add New Course

```
localhost:8000/api/courses
```

```json
req.body = {
    "name": "string",
    "instructor_id": 0,
    "category_id": 0,
    "description": "string",
    "syllabus": "string",
    "image": (file: jpeg,png,jpg,gif,svg),
    "level": "string", ["beginner", "intermediate", "professional"]
    "price": 0
}
```

### Status : 201

### Content type : application/json

```json
{
    "message": "string",
    "course": {
        "id": 0,
        "name": "string",
        "slug": "string",
        "instructor_id": 0,
        "description": "string",
        "syllabus": "string",
        "image": "string",
        "price": 0,
        "created_at": "string", // "2024-08-07T09:49:26.000000Z"
        "updated_at": "string" // "2024-08-07T09:49:26.000000Z"
    }
}
```

## **POST** - Add New Content

```
localhost:8000/api/courses/{course_id}/contents
```

```json
req.body = {
    "title": "string",
    "description": "string",
    "file": (file: pdf, mp4)
}
```

### Status : 201

### Content type : application/json

```json
{
    "message": "string",
    "content": {
        "id": 0,
        "course_id": 0,
        "title": "string",
        "description": "string",
        "file": "string",
        "created_at": "string", // "2024-08-07T09:49:26.000000Z"
        "updated_at": "string" // "2024-08-07T09:49:26.000000Z"
    }
}
```

## **POST** - Add New Category

```
localhost:8000/api/category
```

```json
req.body = {
    "name": "string"
}
```

### Status : 201

### Content type : application/json

```json
{
    "message": "string",
    "category": {
        "id": 0,
        "name": "string",
        "created_at": "string", // "2024-08-07T09:49:26.000000Z"
        "updated_at": "string" // "2024-08-07T09:49:26.000000Z"
    }
}
```

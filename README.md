# Sea Study API

## <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">GET</p> <p style="display:inline;">Popular Courses</p> (Limit=10)

```
localhost:8000/api/courses/popular
```

#### Status : <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">200</p>

#### Content type : <p style="color:DodgerBlue; display:inline; font-weight:bold;">application/json</p>

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
            "description": "string",
            "syllabus": "string",
            "image": "string",
            "price": 0,
            "created_at": "string", // "2024-08-07T09:49:26.000000Z"
            "updated_at": "string", // "2024-08-07T09:49:26.000000Z"
            "instructor": {
                "id": 0,
                "name": "string",
                "email": "string",
                "token": "string", // can be null
                "role": "string",
                "balance": 0,
                "created_at": "string", // "2024-08-07T09:49:26.000000Z"
                "updated_at": "string" // "2024-08-07T09:49:26.000000Z"
            }
        }
    ]
}
```

## <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">GET</p> <p style="display:inline;">Purchased Courses By User Id</p>

```
localhost:8000/api/courses/enrolled/{user_id}
```

#### Status : <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">200</p>

#### Content type : <p style="color:DodgerBlue; display:inline; font-weight:bold;">application/json</p>

```json
{
    "message": "string",
    "courses": [
        {
            "id": 0,
            "user_id": 0,
            "course_id": 0,
            "created_at": "string", // "2024-08-07T09:49:26.000000Z"
            "updated_at": "string", // "2024-08-07T09:49:26.000000Z"
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
    ]
}
```

## <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">GET</p> <p style="display:inline;"> Course Detail By Id</p>

```
localhost:8000/api/courses/{id}
```

#### Status : <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">200</p>

#### Content type : <p style="color:DodgerBlue; display:inline; font-weight:bold;">application/json</p>

### Response Sample

```json
{
    "message": "string",
    "courses": {
        "id": 0,
        "name": "string",
        "slug": "string",
        "instructor_id": 0,
        "description": "string",
        "syllabus": "string",
        "image": "string",
        "price": 0,
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

## <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">GET</p> <p style="display:inline;">All Course Content By Course Id</p>

```
localhost:8000/api/courses/{course_id}/contents
```

#### Status : <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">200</p>

#### Content type : <p style="color:DodgerBlue; display:inline; font-weight:bold;">application/json</p>

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

## <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">GET</p> <p style="display:inline;">Detail Course Content By Content Id</p>

```
localhost:8000/api/courses/{course_id}/contents/{content_id}
```

#### Status : <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">200</p>

#### Content type : <p style="color:DodgerBlue; display:inline; font-weight:bold;">application/json</p>

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

## <p style="color:Violet; display:inline; font-weight:bold;">POST</p> <p style="display:inline;">Register User & Instructor</p>

#### Role dapat berupa : ['user', 'instructor', 'admin']

```
localhost:8000/api/register/{role}
```

#### Status : <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">201</p>

#### Content type : <p style="color:DodgerBlue; display:inline; font-weight:bold;">application/json</p>

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

## <p style="color:Violet; display:inline; font-weight:bold;">POST</p> <p style="display:inline;">Login User & Instructor</p>

```
localhost:8000/api/login
```

#### Status : <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">201</p>

#### Content type : <p style="color:DodgerBlue; display:inline; font-weight:bold;">application/json</p>

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

## <p style="color:Violet; display:inline; font-weight:bold;">POST</p> <p style="display:inline;">Add New Course</p>

```
localhost:8000/api/courses
```

```json
req.body = {
    "name": "string",
    "instructor_id": 0,
    "description": "string",
    "syllabus": "string",
    "image": (file: jpeg,png,jpg,gif,svg),
    "price": 0
}
```

#### Status : <p style="color:MediumSeaGreen; display:inline; font-weight:bold;">201</p>

#### Content type : <p style="color:DodgerBlue; display:inline; font-weight:bold;">application/json</p>

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

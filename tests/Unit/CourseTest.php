<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use App\Models\Rating;
use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase
{
    // use RefreshDatabase;
    // use DatabaseMigrations;
    use DatabaseTransactions;
    /**
     * Test the fillable attributes of the Course model.
     *
     * @return void
     */
    public function test_fillable_attributes()
    {
        $course = new Course();

        $this->assertEquals([
            'name',
            'slug',
            'instructor_id',
            'category_id',
            'description',
            'brief',
            'image',
            'level',
            'price',
        ], $course->getFillable());
    }

    /**
     * Test the instructor relationship.
     *
     * @return void
     */

    // /**
    //  * Test the category relationship.
    //  *
    //  * @return void
    //  */
    public function test_category_relationship()
    {
        $course = Course::factory()->create();
        $category = Category::factory()->create();
        $course->category()->associate($category);
        $course->save();

        $this->assertInstanceOf(Category::class, $course->category);
        $this->assertEquals($category->id, $course->category->id);
    }

    // /**
    //  * Test the contents relationship.
    //  *
    //  * @return void
    //  */
    public function test_contents_relationship()
    {
        $course = Course::factory()->create();
        $content = Content::factory()->create(['course_id' => $course->id]);

        $this->assertTrue($course->contents->contains($content));
    }

    // /**
    //  * Test the ratings relationship.
    //  *
    //  * @return void
    //  */
    public function test_ratings_relationship()
    {
        $course = Course::factory()->create();
        $rating = Rating::factory()->create(['course_id' => $course->id]);

        $this->assertTrue($course->ratings->contains($rating));
    }

    // /**
    //  * Test the averageRating method.
    //  *
    //  * @return void
    //  */
    public function test_average_rating()
    {
        $course = Course::factory()->create();
        Rating::factory()->create(['course_id' => $course->id, 'rating' => 4]);
        Rating::factory()->create(['course_id' => $course->id, 'rating' => 5]);

        $this->assertEquals(4.5, $course->averageRating());
    }
}
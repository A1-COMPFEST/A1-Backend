<?php

namespace Tests\Unit;

use App\Models\Content;
use App\Models\Course;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentTest extends TestCase
{
    // use DatabaseTransactions;
    // use DatabaseMigrations;
    use DatabaseTransactions;
    /** @test */
    public function it_can_create_content()
    {
        $course = Course::factory()->create();

        $content = Content::factory()->create([
            'course_id' => $course->id,
            'title' => 'Sample Content',
            'description' => 'This is a sample content description.',
            'file' => 'sample.pdf'
        ]);

        $this->assertDatabaseHas('contents', [
            'course_id' => $course->id,
            'title' => 'Sample Content',
            'description' => 'This is a sample content description.',
            'file' => 'sample.pdf'
        ]);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $content = new Content();

        $this->assertEquals([
            'course_id',
            'title',
            'description',
            'file'
        ], $content->getFillable());
    }

    /** @test */
    public function it_belongs_to_a_course()
    {
        $course = Course::factory()->create();
        $content = Content::factory()->create(['course_id' => $course->id]);

        $this->assertInstanceOf(Course::class, $content->course);
        $this->assertEquals($course->id, $content->course->id);
    }
}
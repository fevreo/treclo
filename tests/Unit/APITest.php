<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Str;

class APITest extends TestCase
{
    /**
     * This unit test create a user.
     */
    public function testUserCreation()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'YOU',
            'email' => 'test1@test.com',
            'password' => '12345',
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token', 'name']
        ]);
    }

    /**
     * This unit test login in as a user.
     */
    public function testUserLogin()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => 'test1@test.com',
            'password' => '12345'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token']
        ]);
    }


    /**
     * This unit test fetch a category.
     */
    public function testCategoryFetch()
    {
        $user = \App\Models\User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/category')
            ->assertStatus(200)->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ]);
    }

    /**
     * This unit test create a category.
     */
    public function testCategoryCreation()
    {
        $this->withoutMiddleware();

        $response = $this->json('POST', '/api/category', [
            'name' => Str::random(10),
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
            'message' => 'Category Created'
        ]);
    }

    /**
     * This unit test delete a category.
     */
    public function testCategoryDeletion()
    {
        $user = \App\Models\User::find(1);

        $category = \App\Models\Category::create(['name' => 'To be deleted']);

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', "/api/category/{$category->id}")
            ->assertStatus(200)->assertJson([
                'status' => true,
                'message' => 'Category Deleted'
            ]);
    }
}

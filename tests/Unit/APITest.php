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
            'email' => 'test@test.com',
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
            'email' => 'test@test.com',
            'password' => '12345'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token']
        ]);
    }

    /**
     * This unit test delete a user.
     */
    public function testUserDeletion()
    {
        $user = \App\Models\User::where(['email' => 'test@test.com'])->first();
        $user->delete();

        $this->assertDatabaseMissing('users', [
            'email' => 'test@test.com',
        ]);
    }


    /**
     * This unit test fetch a category.
     */
    public function testCategoryFetch()
    {
        $user = \App\Models\User::find(1);

        $this->actingAs($user, 'api')
            ->json('GET', '/api/category')
            ->assertStatus(200)->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
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
            'name' => 'Test Category',
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
        $category = \App\Models\Category::where(['name' => 'Test Category'])->first();

        $this->actingAs($user, 'api')
            ->json('DELETE', "/api/category/{$category->id}")
            ->assertStatus(200)->assertJson([
                'status' => true,
                'message' => 'Category Deleted'
            ]);
    }
}

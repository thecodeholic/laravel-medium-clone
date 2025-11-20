<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses()->group('browser', 'feature');

beforeEach(function () {
    $this->seed();
});

test('user can create post through browser', function () {
    $user = User::factory()->create();
    $category = Category::first();
    
    Storage::fake('public');
    $image = UploadedFile::fake()->image('test-image.jpg', 800, 600);

    $response = $this->actingAs($user)
        ->get(route('post.create'));
    
    $response->assertOk();
    $response->assertSee('Create Post');

    $response = $this->actingAs($user)
        ->post(route('post.store'), [
            'title' => 'My Test Post Title',
            'content' => 'This is the content of my test post created through browser testing.',
            'category_id' => $category->id,
            'image' => $image,
        ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseHas('posts', [
        'title' => 'My Test Post Title',
        'user_id' => $user->id,
    ]);
});


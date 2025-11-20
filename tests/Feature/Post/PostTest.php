<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob;

uses()->group('post', 'feature');

beforeEach(function () {
    $this->seed();
    $this->category = Category::first();
    $this->user = User::factory()->create();
    Storage::fake('public');
});

test('user can create post', function () {
    Queue::fake();
    $image = UploadedFile::fake()->image('post.jpg', 800, 600);

    $response = $this->actingAs($this->user)
        ->post(route('post.store'), [
            'title' => 'Test Post Title',
            'content' => 'This is the content of the test post.',
            'category_id' => $this->category->id,
            'image' => $image,
            'published_at' => now(),
        ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseHas('posts', [
        'title' => 'Test Post Title',
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    Queue::assertPushed(PerformConversionsJob::class);
});

test('user can view published post', function () {
    $post = Post::factory()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'published_at' => now()->subDay(),
    ]);

    $response = $this->get(route('post.show', ['username' => $this->user->username, 'post' => $post]));

    $response->assertOk();
    $response->assertSee($post->title);
    $response->assertSee($post->content);
});

test('user cannot edit other users post', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::factory()->create([
        'user_id' => $owner->id,
        'category_id' => $this->category->id,
    ]);

    $response = $this->actingAs($otherUser)
        ->get(route('post.edit', ['post' => $post]));

    $response->assertForbidden();
});

test('user can update their post', function () {
    Queue::fake();
    $post = Post::factory()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    $newImage = UploadedFile::fake()->image('new-post.jpg', 800, 600);

    $response = $this->actingAs($this->user)
        ->put(route('post.update', ['post' => $post]), [
            'title' => 'Updated Post Title',
            'content' => 'Updated content here.',
            'category_id' => $this->category->id,
            'image' => $newImage,
        ]);

    $response->assertRedirect(route('myPosts'));
    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Post Title',
    ]);

    Queue::assertPushed(PerformConversionsJob::class);
});

test('user can delete their post', function () {
    $post = Post::factory()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    $response = $this->actingAs($this->user)
        ->delete(route('post.destroy', ['post' => $post]));

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

test('dashboard shows posts from followed users', function () {
    $followedUser = User::factory()->create();
    $unfollowedUser = User::factory()->create();

    $this->user->following()->attach($followedUser->id);

    $followedPost = Post::factory()->create([
        'user_id' => $followedUser->id,
        'category_id' => $this->category->id,
        'published_at' => now()->subHour(),
    ]);

    $unfollowedPost = Post::factory()->create([
        'user_id' => $unfollowedUser->id,
        'category_id' => $this->category->id,
        'published_at' => now()->subHour(),
    ]);

    visit(route('login'))
        ->fill('email', $this->user->email)
        ->fill('password', 'password')
        ->press('Log in')
        ->assertPathIs('/')
        ->assertSee($followedPost->title)
        ->assertDontSee($unfollowedPost->title);
})->group('browser');


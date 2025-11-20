<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

uses()->group('security', 'feature');

beforeEach(function () {
    $this->seed();
});

test('unauthorized access returns 403', function () {
    $owner = User::factory()->create();
    $unauthorizedUser = User::factory()->create();
    $category = Category::first();
    $post = Post::factory()->create([
        'user_id' => $owner->id,
        'category_id' => $category->id,
    ]);

    $response = $this->actingAs($unauthorizedUser)
        ->put(route('post.update', ['post' => $post]), [
            'title' => 'Hacked Title',
            'content' => 'Hacked content',
            'category_id' => $category->id,
        ]);

    $response->assertForbidden();

    $response = $this->actingAs($unauthorizedUser)
        ->delete(route('post.destroy', ['post' => $post]));

    $response->assertForbidden();
});

test('cascade deletes work correctly', function () {
    $user = User::factory()->create();
    $category = Category::first();
    $post = Post::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $clap1 = $post->claps()->create(['user_id' => User::factory()->create()->id]);
    $clap2 = $post->claps()->create(['user_id' => User::factory()->create()->id]);

    $post->delete();

    $this->assertDatabaseMissing('claps', ['id' => $clap1->id]);
    $this->assertDatabaseMissing('claps', ['id' => $clap2->id]);
});


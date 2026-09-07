<?php

namespace Tests\Feature;

use App\Models\Photo;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_public_pages_can_be_opened(): void
    {
        $this->get(route('photos.index'))->assertOk();
        $this->get(route('videos.index'))->assertOk();
        $this->get(route('members.index'))->assertOk();
        $this->get(route('timeline.index'))->assertOk();
        $this->get(route('login'))->assertOk();
    }

    public function test_member_pages_require_authentication(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('photos.create'))->assertRedirect(route('login'));
        $this->get(route('videos.create'))->assertRedirect(route('login'));
        $this->get(route('password.change'))->assertRedirect(route('login'));
    }

    public function test_member_can_login_and_logout(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->post(route('login.process'), [
            'username' => $user->username,
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_member_can_change_password(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'password123',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertRedirect(route('dashboard'));

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_member_can_create_update_and_delete_own_photo(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
            true,
        );

        $this->actingAs($user)->post(route('photos.store'), [
            'title' => 'Kenangan bersama',
            'description' => 'Deskripsi foto',
            'photo' => UploadedFile::fake()->createWithContent('memory.png', $png),
        ])->assertRedirect(route('photos.index'));

        $photo = Photo::firstOrFail();
        Storage::disk('public')->assertExists($photo->path);

        $oldPath = $photo->path;
        $this->actingAs($user)->put(route('photos.update', $photo), [
            'title' => 'Kenangan diperbarui',
            'description' => 'Deskripsi baru',
            'photo' => UploadedFile::fake()->createWithContent('replacement.png', $png),
        ])->assertRedirect(route('photos.index'));

        $photo->refresh();
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($photo->path);

        $path = $photo->path;
        $this->actingAs($user)
            ->delete(route('photos.destroy', $photo))
            ->assertRedirect(route('photos.index'));

        $this->assertDatabaseMissing('photos', ['id' => $photo->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_member_cannot_edit_another_members_media(): void
    {
        $owner = User::factory()->create();
        $otherMember = User::factory()->create();
        $photo = Photo::create([
            'user_id' => $owner->id,
            'title' => 'Foto pemilik',
            'path' => 'photos/example.jpg',
        ]);
        $video = Video::create([
            'user_id' => $owner->id,
            'title' => 'Video pemilik',
            'path' => 'videos/example.mp4',
        ]);

        $this->actingAs($otherMember)
            ->get(route('photos.edit', $photo))
            ->assertForbidden();

        $this->actingAs($otherMember)
            ->delete(route('videos.destroy', $video))
            ->assertForbidden();
    }

    public function test_member_can_upload_and_delete_video(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('videos.store'), [
            'title' => 'Video kebersamaan',
            'description' => 'Deskripsi video',
            'video' => UploadedFile::fake()->create('memory.mp4', 1024, 'video/mp4'),
        ])->assertRedirect(route('videos.index'));

        $video = Video::firstOrFail();
        Storage::disk('public')->assertExists($video->path);

        $oldPath = $video->path;
        $this->actingAs($user)->put(route('videos.update', $video), [
            'title' => 'Video kebersamaan diperbarui',
            'description' => 'Deskripsi video baru',
            'video' => UploadedFile::fake()->create('replacement.webm', 1024, 'video/webm'),
        ])->assertRedirect(route('videos.index'));

        $video->refresh();
        $this->assertSame('Video kebersamaan diperbarui', $video->title);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($video->path);

        $path = $video->path;
        $this->actingAs($user)
            ->delete(route('videos.destroy', $video))
            ->assertRedirect(route('videos.index'));

        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
        Storage::disk('public')->assertMissing($path);
    }
}

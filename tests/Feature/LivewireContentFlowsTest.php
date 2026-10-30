<?php

use App\Livewire\Announcements\AnnouncementForm;
use App\Livewire\Dashboard\NotificationBell;
use App\Livewire\Dashboard\RecentActivities;
use App\Livewire\Gallery\GalleryUpload;
use App\Livewire\Members\KaderisasiTracker;
use App\Livewire\News\NewsForm;
use App\Models\Activity;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\KaderisasiRecord;
use App\Models\Member;
use App\Models\News;
use App\Models\Rayon;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('super_admin');
    $this->actingAs($this->user);
    $this->rayon = Rayon::firstOrFail();
});

test('dashboard activity and notification components render repository data', function () {
    $activity = Activity::create([
        'organizer_id' => $this->user->id,
        'rayon_id' => $this->rayon->id,
        'title' => 'Kegiatan Terbaru',
        'location' => 'Aula',
        'start_date' => now()->addDay(),
        'end_date' => now()->addDays(2),
        'type' => 'kajian',
    ]);
    Announcement::create([
        'author_id' => $this->user->id,
        'title' => 'Pengumuman aktif',
        'content' => 'Informasi untuk anggota.',
        'target_scope' => 'all',
    ]);

    Livewire::test(RecentActivities::class)
        ->assertOk()
        ->assertSee($activity->title);

    Livewire::test(NotificationBell::class)
        ->assertOk()
        ->assertSee('1')
        ->call('toggle')
        ->assertSee('Pengumuman aktif');
});

test('announcement form creates and safely replaces an attachment', function () {
    Storage::fake('public');

    Livewire::test(AnnouncementForm::class)
        ->set('title', 'Pengumuman baru')
        ->set('content', 'Isi pengumuman')
        ->set('priority', 'high')
        ->set('attachment', UploadedFile::fake()->create('awal.pdf', 20, 'application/pdf'))
        ->call('save')
        ->assertHasNoErrors();

    $announcement = Announcement::where('title', 'Pengumuman baru')->firstOrFail();
    $previousPath = $announcement->attachment;
    Storage::disk('public')->assertExists($previousPath);

    Livewire::test(AnnouncementForm::class, ['announcement' => $announcement])
        ->set('title', 'Pengumuman diperbarui')
        ->set('attachment', UploadedFile::fake()->create('baru.pdf', 20, 'application/pdf'))
        ->call('save')
        ->assertHasNoErrors();

    $announcement->refresh();
    expect($announcement->title)->toBe('Pengumuman diperbarui');
    Storage::disk('public')->assertMissing($previousPath);
    Storage::disk('public')->assertExists($announcement->attachment);
});

test('gallery uploads images and authorizes toggle and delete actions', function () {
    Storage::fake('public');

    Livewire::test(GalleryUpload::class)
        ->set('photos', [UploadedFile::fake()->image('foto.jpg')])
        ->set('title', 'Dokumentasi')
        ->set('is_featured', true)
        ->call('upload')
        ->assertHasNoErrors();

    $photo = Gallery::firstOrFail();
    expect($photo->uploader_id)->toBe($this->user->id)
        ->and($photo->title)->toBe('Dokumentasi')
        ->and($photo->is_featured)->toBeTrue();
    Storage::disk('public')->assertExists($photo->file_path);

    Livewire::test(GalleryUpload::class)
        ->call('togglePublic', $photo->id)
        ->assertHasNoErrors();
    expect($photo->fresh()->is_public)->toBeFalse();

    Livewire::test(GalleryUpload::class)
        ->call('deletePhoto', $photo->id)
        ->assertHasNoErrors();
    expect(Gallery::find($photo->id))->toBeNull();
    Storage::disk('public')->assertMissing($photo->file_path);
});

test('kaderisasi tracker saves and deletes a member training record', function () {
    $memberUser = User::factory()->create();
    $member = Member::create([
        'user_id' => $memberUser->id,
        'rayon_id' => $this->rayon->id,
        'level' => 'kader',
    ]);

    Livewire::test(KaderisasiTracker::class, ['member' => $member])
        ->set('type', 'PKD')
        ->set('status', 'lulus')
        ->set('event_name', 'Pelatihan Kader Dasar')
        ->set('event_date', now()->toDateString())
        ->call('saveRecord')
        ->assertHasNoErrors()
        ->assertSee('PKD');

    $record = KaderisasiRecord::firstOrFail();
    expect($record->member_id)->toBe($member->id)
        ->and($member->fresh()->hasCompletedKaderisasi('PKD'))->toBeTrue();

    Livewire::test(KaderisasiTracker::class, ['member' => $member])
        ->call('deleteRecord', $record->id)
        ->assertHasNoErrors();
    expect(KaderisasiRecord::find($record->id))->toBeNull();
});

test('news form creates and edits a policy-authorized article', function () {
    Storage::fake('public');

    Livewire::test(NewsForm::class)
        ->set('title', 'Berita Kaderisasi')
        ->set('excerpt', 'Ringkasan singkat')
        ->set('content', 'Isi berita lengkap')
        ->set('category', 'artikel')
        ->set('status', 'published')
        ->set('tags', 'kaderisasi, kegiatan')
        ->set('thumbnail', UploadedFile::fake()->image('thumbnail.jpg'))
        ->call('save')
        ->assertHasNoErrors();

    $news = News::where('title', 'Berita Kaderisasi')->firstOrFail();
    $previousThumbnail = $news->thumbnail;
    expect($news->slug)->toBe('berita-kaderisasi')
        ->and($news->status)->toBe('published')
        ->and($news->tags)->toBe(['kaderisasi', 'kegiatan']);
    Storage::disk('public')->assertExists($previousThumbnail);

    Livewire::test(NewsForm::class, ['news' => $news])
        ->set('title', 'Berita Kaderisasi Diperbarui')
        ->set('thumbnail', UploadedFile::fake()->image('thumbnail-baru.jpg'))
        ->call('save')
        ->assertHasNoErrors();

    $news->refresh();
    expect($news->title)->toBe('Berita Kaderisasi Diperbarui')
        ->and($news->slug)->toBe('berita-kaderisasi');
    Storage::disk('public')->assertMissing($previousThumbnail);
    Storage::disk('public')->assertExists($news->thumbnail);
});

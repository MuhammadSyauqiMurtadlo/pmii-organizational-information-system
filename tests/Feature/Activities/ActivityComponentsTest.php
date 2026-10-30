<?php

use App\Livewire\Activities\ActivityForm;
use App\Livewire\Activities\ActivityIndex;
use App\Livewire\Activities\ActivityParticipants;
use App\Models\Activity;
use App\Models\Komisariat;
use App\Models\Member;
use App\Models\Rayon;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->owner = User::factory()->create();
    $this->owner->assignRole('super_admin');
    $komisariat = Komisariat::create(['name' => 'Komisariat Uji']);
    $this->rayon = Rayon::create([
        'komisariat_id' => $komisariat->id,
        'name' => 'Rayon Uji',
        'faculty' => 'Fakultas Uji',
    ]);
});

test('users without activity permissions cannot access management routes', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('dashboard.activities.index'))->assertForbidden();
    $this->get(route('dashboard.activities.create'))->assertForbidden();
});

function makeActivityForTest(User $owner, ?int $rayonId = null, array $attributes = []): Activity
{
    return Activity::create(array_merge([
        'organizer_id' => $owner->id,
        'rayon_id' => $rayonId,
        'title' => 'Kegiatan Uji',
        'slug' => 'kegiatan-uji',
        'location' => 'Aula',
        'start_date' => now()->addDays(2),
        'end_date' => now()->addDays(2)->addHours(2),
        'type' => 'kajian',
        'status' => 'upcoming',
    ], $attributes));
}

function makeMemberForTest(User $user, Rayon $rayon): Member
{
    return Member::create([
        'user_id' => $user->id,
        'rayon_id' => $rayon->id,
        'level' => 'kader',
    ]);
}

test('activity form creates a validated activity and stores its poster', function () {
    Storage::fake('public');
    $this->actingAs($this->owner);

    Livewire::test(ActivityForm::class)
        ->set('title', 'Pelatihan Kepemimpinan')
        ->set('start_date', now()->addDays(3)->format('Y-m-d\TH:i'))
        ->set('end_date', now()->addDays(3)->addHours(2)->format('Y-m-d\TH:i'))
        ->set('location', 'Aula Rayon')
        ->set('type', 'pelatihan')
        ->set('rayon_id', $this->rayon->id)
        ->set('max_participants', 25)
        ->set('poster', UploadedFile::fake()->image('poster.jpg'))
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard.activities.index'));

    $activity = Activity::where('title', 'Pelatihan Kepemimpinan')->firstOrFail();
    expect($activity->organizer_id)->toBe($this->owner->id)
        ->and($activity->rayon_id)->toBe($this->rayon->id)
        ->and($activity->max_participants)->toBe(25);
    Storage::disk('public')->assertExists($activity->poster);
});

test('activity form validates date ordering and loads edit data', function () {
    $this->actingAs($this->owner);
    $activity = makeActivityForTest($this->owner);

    Livewire::test(ActivityForm::class, ['activity' => $activity])
        ->assertSet('isEdit', true)
        ->assertSet('title', $activity->title)
        ->set('title', 'Kegiatan Diperbarui')
        ->set('start_date', now()->addDays(5)->format('Y-m-d\TH:i'))
        ->set('end_date', now()->addDays(4)->format('Y-m-d\TH:i'))
        ->call('save')
        ->assertHasErrors(['end_date']);

    expect($activity->fresh()->title)->toBe('Kegiatan Uji');
});

test('activity index filters and deletes only activities allowed by policy', function () {
    $this->actingAs($this->owner);
    $activity = makeActivityForTest($this->owner);
    makeActivityForTest($this->owner, null, [
        'title' => 'Aksi Sosial',
        'slug' => 'aksi-sosial',
        'type' => 'sosial',
    ]);

    Livewire::test(ActivityIndex::class)
        ->assertSee('Kegiatan Uji')
        ->assertSee('Aksi Sosial')
        ->set('search', 'Aksi')
        ->assertSee('Aksi Sosial')
        ->assertDontSee('Kegiatan Uji');

    $admin = User::where('email', 'superadmin@pmii.ac.id')->firstOrFail();
    $this->actingAs($admin);
    Livewire::test(ActivityIndex::class)
        ->call('deleteActivity', $activity->id)
        ->assertHasNoErrors();

    expect(Activity::find($activity->id))->toBeNull();
});

test('participants can be added, attendance updated, and removed by an activity editor', function () {
    $this->actingAs($this->owner);
    $activity = makeActivityForTest($this->owner, $this->rayon->id, ['max_participants' => 1]);
    $member = makeMemberForTest(User::factory()->create(), $this->rayon);

    Livewire::test(ActivityParticipants::class, ['activity' => $activity])
        ->assertSee('Peserta Kegiatan')
        ->set('showAddForm', true)
        ->set('selectedMember', $member->id)
        ->call('addParticipant')
        ->assertHasNoErrors()
        ->assertDontSee('Belum ada peserta');

    $participant = $activity->participants()->firstOrFail();
    expect($participant->attendance)->toBe('registered');

    Livewire::test(ActivityParticipants::class, ['activity' => $activity])
        ->call('updateAttendance', $participant->id, 'attended')
        ->assertHasNoErrors();

    expect($participant->fresh()->attendance)->toBe('attended');

    Livewire::test(ActivityParticipants::class, ['activity' => $activity])
        ->call('removeParticipant', $participant->id)
        ->assertHasNoErrors();

    expect($activity->participants()->exists())->toBeFalse();
});

test('participants cannot exceed activity capacity or change attendance outside valid values', function () {
    $this->actingAs($this->owner);
    $activity = makeActivityForTest($this->owner, $this->rayon->id, ['max_participants' => 1]);
    $member = makeMemberForTest(User::factory()->create(), $this->rayon);
    $activity->participants()->create(['member_id' => $member->id]);
    $anotherMember = makeMemberForTest(User::factory()->create(), $this->rayon);

    Livewire::test(ActivityParticipants::class, ['activity' => $activity])
        ->set('selectedMember', $anotherMember->id)
        ->call('addParticipant')
        ->assertHasErrors('selectedMember');

    $participant = $activity->participants()->firstOrFail();
    Livewire::test(ActivityParticipants::class, ['activity' => $activity])
        ->call('updateAttendance', $participant->id, 'invalid')
        ->assertHasErrors('attendance');

    expect($activity->participants()->count())->toBe(1);
});

<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\Rayon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class MemberForm extends Component
{
    use WithFileUploads;

    public ?Member $member = null;

    public bool $isEdit = false;

    // User fields
    public string $name = '';

    public string $email = '';

    public string $nim = '';

    public string $phone = '';

    public string $password = '';

    public string $status = 'active';

    public string $student_faculty = '';

    public string $student_major = '';

    public ?int $entry_year = null;

    public $avatar = null;

    // Member fields
    public ?int $rayon_id = null;

    public string $member_number = '';

    public string $joined_date = '';

    public string $generation = '';

    public string $level = 'kader';

    public string $position = '';

    public string $bio = '';

    public string $gender = '';

    public string $birth_date = '';

    public string $role = 'anggota';

    protected function rules(): array
    {
        $userId = $this->member?->user_id ?? 'NULL';
        $emailRule = $this->isEdit ? "required|email|unique:users,email,{$userId}" : 'required|email|unique:users,email';

        return [
            'name' => 'required|string|min:3|max:255',
            'email' => $emailRule,
            'nim' => "nullable|string|max:20|unique:users,nim,{$userId}",
            'phone' => 'nullable|string|max:20',
            'password' => $this->isEdit ? 'nullable|min:8' : 'required|min:8',
            'rayon_id' => 'required|exists:rayons,id',
            'level' => 'required|in:kader,anggota_muda,anggota,anggota_senior',
            'joined_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'avatar' => 'nullable|image|max:2048',
            'role' => 'required|in:anggota,admin_rayon',
        ];
    }

    public function mount(?Member $member = null): void
    {
        $user = auth()->user();

        if ($user->isAdminRayon()) {
            $this->rayon_id = $user->rayon_id;
            $this->role = 'anggota';
        }

        if ($member && $member->exists) {
            $this->isEdit = true;
            $this->member = $member;
            $this->fill([
                'name' => $member->user->name,
                'email' => $member->user->email,
                'nim' => $member->user->nim ?? '',
                'phone' => $member->user->phone ?? '',
                'status' => $member->user->status,
                'student_faculty' => $member->user->student_faculty ?? '',
                'student_major' => $member->user->student_major ?? '',
                'entry_year' => $member->user->entry_year,
                'rayon_id' => $member->rayon_id,
                'member_number' => $member->member_number ?? '',
                'level' => $member->level,
                'position' => $member->position ?? '',
                'bio' => $member->bio ?? '',
                'gender' => $member->gender ?? '',
                'birth_date' => $member->birth_date?->format('Y-m-d') ?? '',
                'joined_date' => $member->joined_date?->format('Y-m-d') ?? '',
                'generation' => $member->generation ?? '',
                'role' => $member->user->roles->first()?->name ?? 'anggota',
            ]);
        }
    }

    public function save(): void
    {
        $this->validate();

        // Guard: admin rayon tidak boleh bikin role selain anggota
        if (auth()->user()->isAdminRayon() && $this->role !== 'anggota') {
            $this->addError('role', 'Admin rayon hanya dapat membuat akun dengan role Anggota.');

            return;
        }

        DB::transaction(function () {
            $avatarPath = null;

            if ($this->avatar) {
                // Fix: hapus avatar lama sebelum upload baru (hindari storage leak)
                if ($this->isEdit && $this->member->user->avatar) {
                    Storage::disk('public')->delete($this->member->user->avatar);
                }
                $avatarPath = $this->avatar->store('avatars', 'public');
            }

            $userData = array_filter(
                [
                    'rayon_id' => $this->rayon_id,
                    'name' => $this->name,
                    'email' => $this->email,
                    'nim' => $this->nim ?: null,
                    'phone' => $this->phone ?: null,
                    'status' => $this->status,
                    'student_faculty' => $this->student_faculty ?: null,
                    'student_major' => $this->student_major ?: null,
                    'entry_year' => $this->entry_year,
                    'avatar' => $avatarPath, // null kalau tidak upload baru
                ],
                fn ($v) => $v !== null,
            );

            // Kalau edit tapi tidak upload avatar baru, jangan overwrite
            if ($this->isEdit && ! $this->avatar) {
                unset($userData['avatar']);
            }

            if (! $this->isEdit || $this->password) {
                $userData['password'] = Hash::make($this->password);
            }

            $user = $this->isEdit ? tap($this->member->user)->update($userData) : User::create($userData);

            $user->syncRoles([$this->role]);

            $memberData = [
                'user_id' => $user->id,
                'rayon_id' => $this->rayon_id,
                'member_number' => $this->member_number ?: null,
                'level' => $this->level,
                'position' => $this->position ?: null,
                'bio' => $this->bio ?: null,
                'gender' => $this->gender ?: null,
                'birth_date' => $this->birth_date ?: null,
                'joined_date' => $this->joined_date ?: null,
                'generation' => $this->generation ?: null,
            ];

            $this->isEdit ? $this->member->update($memberData) : Member::create($memberData);
        });

        session()->flash('success', $this->isEdit ? 'Data anggota berhasil diperbarui.' : 'Anggota baru berhasil ditambahkan.');

        $this->redirect(route('dashboard.members.index'), navigate: true);
    }

    public function render()
    {
        $user = auth()->user();

        $rayons = match (true) {
            $user->isSuperAdmin() || $user->isAdminKomisariat() => Rayon::active()->get(),
            $user->isAdminRayon() => Rayon::where('id', $user->rayon_id)->get(),
            default => collect(),
        };

        return view('livewire.members.member-form', compact('rayons'));
    }
}
?>

<div>
    {{-- Nothing in life is to be feared, it is only to be understood. Now is the time to understand more, so that we may fear less. - Maria Skłodowska-Curie --}}
</div>

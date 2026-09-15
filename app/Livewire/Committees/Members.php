<?php

namespace App\Livewire\Committees;

use App\Models\Committee;
use App\Models\CurrentMember;
use App\Models\Department;
use App\Models\Group;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.start')]
class Members extends Component
{
    #[Locked]
    public string $token = "";

    #[Locked]
    public ?int $committeeID;

    public ?int $memberID;
    public string $name = "";
    public string $email = "";
    public string $flag = "elected";
    public ?int $group = null;
    public array $department = [];

    private function getDepartmentNames($members)
    {
        // Handle grouped data (array of arrays)
        if (is_array($members) && !$members instanceof \Illuminate\Support\Collection) {
            foreach ($members as &$group) {
                if ($group instanceof \Illuminate\Support\Collection) {
                    foreach ($group as $m) {
                        $departmentIDs = json_decode($m->job, true) ?? [];
                        $departments = [];
                        foreach ($departmentIDs as $d) {
                            $department = Department::where('id', $d)->first();
                            if ($department) {
                                $departments[] = $department->name;
                            }
                        }
                        $m->job = $departments;
                    }
                }
            }
            return $members;
        }

        // Handle flat collection
        foreach ($members as $m) {
            $departmentIDs = json_decode($m->job, true) ?? [];
            $departments = [];
            foreach ($departmentIDs as $d) {
                $department = Department::where('id', $d)->first();
                if ($department) {
                    $departments[] = $department->name;
                }
            }
            $m->job = $departments;
        }
        return $members;
    }

    public function mount($committee)
    {
        $this->token = $committee;
        $committeeData = Committee::where('token', $committee)->first();
        $this->committeeID = $committeeData->id;
    }

    public function render()
    {
        $committee = Committee::where('token', $this->token)->first();
        $groups = Group::where('committee', $this->committeeID)->orderBy('name')->get();
        $departments = Department::where('committee', $committee->id)->orderBy('name')->get();

        $membersElected = [];
        if ($groups->count() > 0) {
            $allElected = CurrentMember::where('committee', $this->committeeID)
                ->where('flag_elected', true)
                ->whereIn('group', $groups->pluck('id'))
                ->get();

            foreach ($groups as $index => $group) {
                $membersElected[$index] = $allElected->where('group', $group->id)->values();
            }
        } else {
            $membersElected = CurrentMember::where('committee', $this->committeeID)
                ->where('flag_elected', true)
                ->get();
        }

        $membersElected = $this->getDepartmentNames($membersElected);
        $membersActive = CurrentMember::where('committee', $this->committeeID)->where('flag_active', true)->where('flag_elected', false)->get();
        $membersActive = $this->getDepartmentNames($membersActive);
        $membersStaff = CurrentMember::where('committee', $this->committeeID)->where('flag_staff', true)->get();
        $membersStaff = $this->getDepartmentNames($membersStaff);

        return view('livewire.committees.members', [
            'committee' => $committee,
            'groups' => $groups,
            'departments' => $departments,
            'membersElected' => $membersElected,
            'membersActive' => $membersActive,
            'membersStaff' => $membersStaff,
        ]);
    }

    public function toggleSuspended($id)
    {
        $member = CurrentMember::find($id);
        $member->update(['flag_suspended' => !$member->flag_suspended]);
    }

    public function clearModalFields()
    {
        $this->name = '';
        $this->email = '';
        $this->department = [];
        $this->flag = 'elected';
        $this->group = null;
    }

    public function openAddModal()
    {
        $this->clearModalFields();
        Flux::modal('new')->show();
    }

    public function addMember()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $flagElected = false;
        $flagActive = false;
        $flagStaff = false;

        if ($this->flag === 'elected') {
            $flagElected = true;
        } elseif ($this->flag === 'active') {
            $flagActive = true;
        } elseif ($this->flag === 'staff') {
            $flagStaff = true;
        }

        if ($this->flag !== 'elected') {
            $this->group = null;
        }

        CurrentMember::create([
            'committee' => $this->committeeID,
            'name' => $this->name,
            'email' => $this->email,
            'job' => json_encode($this->department),
            'flag_elected' => $flagElected,
            'flag_active' => $flagActive,
            'flag_staff' => $flagStaff,
            'flag_suspended' => false,
            'group' => $this->group,
        ]);

        Flux::modal('new')->close();
        return redirect()->back();
    }

    public function openEditModal($memberID)
    {
        $member = CurrentMember::where('id', $memberID)->first();
        $this->memberID = $member->id;

        if ($this->flag !== 'elected') {
            $this->group = null;
        }

        if ($member) {
            $this->memberID = $member->id;
            $this->name = $member->name;
            $this->email = $member->email;
            $this->department = json_decode($member->job);
            if ($member->flag_elected) {
                $this->flag = 'elected';
            } elseif ($member->flag_active) {
                $this->flag = 'active';
            } elseif ($member->flag_staff) {
                $this->flag = 'staff';
            }
            $this->group = $member->group;
        } else {
            $this->clearModalFields();
        }

        Flux::modal('edit')->show();
    }

    public function updateGroup()
    {
        $committee = Committee::select('id')->where('token', $this->token)->first();

        $this->validate([
            'groupName' => 'required',
        ]);

        Group::where('id', $this->memberID)->update([
            'name' => $this->name,
            'email' => $this->email,
            'job' => json_encode($this->department),
            'flag_elected' => $flagElected,
            'flag_active' => $flagActive,
            'flag_staff' => $flagStaff,
            'flag_suspended' => false,
            'group' => $this->group,
        ]);

        Flux::modal('edit')->close();
        return redirect()->back();
    }
}

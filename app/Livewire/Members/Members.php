<?php

namespace App\Livewire\Members;

use App\Models\Committee;
use App\Models\CurrentMember;
use App\Models\Department;
use App\Models\Group;
use Livewire\Component;

class Members extends Component
{
    public $committee;
    public $committeeID;

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
        $this->committee = $committee;
        $committeeData = Committee::where('token', $committee)->first();
        $this->committeeID = $committeeData->id;
    }

    public function render()
    {
        $groups = Group::where('committee', $this->committeeID)->orderBy('name')->get();

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
        $membersRef = CurrentMember::where('committee', $this->committeeID)->where('flag_ref', true)->where('flag_elected', false)->get();
        $membersRef = $this->getDepartmentNames($membersRef);
        $membersStaff = CurrentMember::where('committee', $this->committeeID)->where('flag_staff', true)->get();
        $membersStaff = $this->getDepartmentNames($membersStaff);

        return view('livewire.members.members', [
            'groups' => $groups,
            'membersElected' => $membersElected,
            'membersRef' => $membersRef,
            'membersStaff' => $membersStaff,
        ]);
    }

    public function toggleSuspended($id)
    {
        $member = CurrentMember::find($id);
        $member->update(['flag_suspended' => !$member->flag_suspended]);
    }
}

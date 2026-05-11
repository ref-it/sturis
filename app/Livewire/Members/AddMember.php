<?php

namespace App\Livewire\Members;

use App\Models\Committee;
use App\Models\CurrentMember;
use App\Models\Department;
use App\Models\Group;
use Livewire\Component;

class AddMember extends Component
{
    public $committee;
    public $committeeID;

    public string $name = "";
    public string $flag = "elected";
    public ?int $group = null;
    public array $department = [];
    public bool $suspended = false;

    public function mount($committee)
    {
        $this->committee = $committee;
        $committeeData = Committee::where('token', $committee)->first();
        $this->committeeID = $committeeData->id;
    }

    public function render()
    {
        $groups = Group::where('committee', $this->committeeID)->orderBy('name')->get();
        $departments = Department::where('committee', $this->committeeID)->orderBy('name')->get();

        return view('livewire.members.add-member', [
            'groups' => $groups,
            'departments' => $departments,
        ]);
    }

    public function save()
    {
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

        CurrentMember::create([
            'committee' => $this->committeeID,
            'name' => $this->name,
            'job' => json_encode($this->department),
            'flag_elected' => $flagElected,
            'flag_ref' => $flagActive,
            'flag_staff' => $flagStaff,
            'flag_suspended' => $this->suspended,
            'group' => $this->group,
        ]);

        $this->redirect('/' . $this->committee . '/members', navigate: true);
    }
}

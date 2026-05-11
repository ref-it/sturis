<?php

namespace App\Livewire;

use App\Models\Committee;
use Illuminate\Http\Request;
use Livewire\Component;

class Sidebar extends Component
{
    public string $committee = "";

    public function render(Request $request)
    {
        $committees = Committee::orderBy('name')->get();

        if ($request->route()->parameter('committee')) {
            $this->committee = $request->route()->parameter('committee');
        } else {
            $this->committee = $committees[0]->token;
        }

        return view('livewire.sidebar', [
            'committees' => $committees,
        ]);
    }

    public function switchCommittee()
    {
        return redirect()->route('meetings', ['committee' => $this->committee]);
    }
}

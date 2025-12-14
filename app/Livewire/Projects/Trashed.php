<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class Trashed extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function restore($id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->restore();
        
        session()->flash('success', 'Project restored successfully.');
    }

    public function forceDelete($id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->forceDelete();
        
        session()->flash('success', 'Project permanently deleted.');
    }

    public function render()
    {
        $projects = Project::onlyTrashed()->with(['client', 'currency'])->orderBy('deleted_at', 'desc')->paginate(15);
        
        return view('livewire.projects.trashed', [
            'projects' => $projects,
        ]);
    }
}

<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('delete', $project);
        $project->delete();
        
        session()->flash('success', 'Project deleted successfully.');
    }

    public function render()
    {
        $projects = Project::with(['client', 'currency'])->orderBy('name')->paginate(15);
        
        return view('livewire.projects.index', [
            'projects' => $projects,
        ]);
    }
}

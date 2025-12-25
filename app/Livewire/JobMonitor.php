<?php

namespace App\Livewire;

use App\Models\JobRun;
use Livewire\Component;
use Livewire\WithPagination;

class JobMonitor extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, success, failed, running
    public $autoRefresh = true;

    protected $queryString = ['filter'];

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function toggleAutoRefresh()
    {
        $this->autoRefresh = !$this->autoRefresh;
    }

    public function render()
    {
        $query = JobRun::query()->orderBy('created_at', 'desc');

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        return view('livewire.job-monitor', [
            'jobRuns' => $query->paginate(20),
            'stats' => [
                'total' => JobRun::count(),
                'success' => JobRun::where('status', 'success')->count(),
                'failed' => JobRun::where('status', 'failed')->count(),
                'running' => JobRun::where('status', 'running')->count(),
            ],
        ])->layout('layouts.app')->title('Job Monitor');
    }
}

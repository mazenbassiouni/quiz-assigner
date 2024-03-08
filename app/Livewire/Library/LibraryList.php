<?php

namespace App\Livewire\Library;

use Livewire\Component;
use App\Models\Department;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class LibraryList extends Component
{
    public bool $first_filter = false;

    public ?string $type = null;

    public ?int $department_id = null;

    public ?string $branch = null;

    public function mount(){
        if(!auth()->user()->hasRole('Admin')){
            $this->department_id = auth()->user()->department_id;
        }
    }

    public function setType(?string $type = null): void{
        if($this->first_filter && $type != 'specialized'){
            $this->department_id = null;
        }
        if($type != 'specialized'){
            $this->first_filter = false;
            $this->department_id = null;
            $this->branch = null;
        }
        $this->type = $type;
    }

    public function setBranch(?string $branch = null): void{
        $this->first_filter = false;
        $this->branch = $branch;
    }

    public function backFirst(): void{
        $this->first_filter = true;
        $this->type = null;
        $this->department_id = null;
        $this->branch = null;
    }

    public function resetAll(): void{
        $this->type = null;
        $this->department_id = null;
        $this->branch = null;
    }

    public function download(string $path, string $name){
        sleep(10);
        // return Storage::download('public/'.$path, $name);
    }

    public function render()
    {
        $documentsBuilder = Document::query();

        if($this->type){
            $documentsBuilder->where('type', $this->type);
        }

        if($this->department_id && $this->type == 'specialized'){
            $documentsBuilder->where('department_id', $this->department_id);
        }

        if($this->branch && $this->type == 'specialized'){
            $documentsBuilder->where('branch', $this->branch);
        }

        $documents = $documentsBuilder->get();

        return view('livewire.pages.library.library-list')->with([
            'departments' => Department::All(),
            'branches' => [
                'General' => 'General',
                'Tech Docs' => 'Tech Docs',
                'Maintenance' => 'Maintenance',
                'OEM Courses' => 'OEM Courses'
            ],
            'documents' => $documents,
        ]);
    }
}

<?php

namespace App\View\Components;
use App\Models\Document;

use Illuminate\View\Component;

class FolderStructure extends Component
{
    /**
     * The folder.
     *
     * @var string
     */
    public $folders;

    /**
     * The documents.
     *
     * @var string
     */
    public $documents;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($folders, $documents)
    {
        $this->folders = $folders;
        $this->documents = $documents;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.folder-structure', compact(['folders' => $this->folders, 'documents'=> $this->documents]));
    }
}

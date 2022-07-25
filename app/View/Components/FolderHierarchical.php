<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FolderHierarchical extends Component
{
    public $folders;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($folders)
    {
        $this->folders = $folders;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $rows = $this->buildTree($this->folders);
        $tree = '<tbody>';
        foreach ($rows as $row) {
            $tree .= $this->setTableRow($row);
            if (isset($row['children']) && count($row['children'])) {
                $tree .= $this->childView($row);
            }
        }
        $tree .= '</tbody>';
        return view('components.folder-hierarchical', compact('tree'));
    }
    
    /**
     * build tree array
     */
    public function buildTree(array $elements, $parentId = 0)
    {
        $branch = [];
        foreach ($elements as $key => $element) {
            // print_r($element);
            if ($element['parent_folder_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                array_push($branch, $element);
            }
        }
        return $branch;
    }

    public function setTableRow($row)
    {
        $id = $row['id'];
        $name = $row['name'];
        $parent_folder_id = isset($row['parent_folder_id']) && !empty($row['parent_folder_id']) ? $row['parent_folder_id'] : null;
        $onclick = "onclick='rename_folder($id, \"" . $name . "\")'";
        $tree = '';
        $tree .= "<tr class='treegrid-" . $row['id'] . " treegrid-parent-" . $row['parent_folder_id'] . "' data-id='" . $row['id'] . "'> \n";
        $tree .= "<td><input type='checkbox' class='open-popup-sub-main' data-id='" . $row['id'] . "' data-parent='$parent_folder_id'></td> \n";
        $tree .= "<td> $name </td> \n";
        $tree .= "<td>" . $row['documents_count'] . "</td> \n";
        $tree .= "<td>
                    <div class='dropdown more-btn'>
                        <button class='btn dropdown-toggle' type='button' id='dropdownMenu2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            <span>...</span>
                        </button>
                        <div class='dropdown-menu' aria-labelledby='dropdownMenu2'>
                            <a class='dropdown-item' $onclick>
                                <i class='fa fa-pencil'></i> " . trans('label.rename_folder') . "
                            </a>
                            <a class='dropdown-item' data-id='' onclick='delete_folder(" . $id . ")'>
                                <i class='fa fa-archive'></i> " . trans('label.delete_folder') . "
                            </a>
                        </div>
                    </div>
                </td> \n";
        return $tree;
    }

    public function childView($rows)
    {
        $tree = '';
        foreach ($rows['children'] as $row) {
            if (isset($row['children']) && count($row['children'])) {
                $tree .= $this->setTableRow($row);
                $tree .= $this->childView($row);
            } else {
                $tree .= $this->setTableRow($row);
            }
        }
        return $tree;
    }
}
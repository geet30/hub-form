<div id="new_category_modal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal_close">&times;</span>
            <div class="category_title">Create New Category / listing</div>
        </div>
        <div class="modal-body">
        <button type="button" class="btn btn-success float-right hideshow_create_category" style=" margin: 5px;" >Create New Category </button>
          
            <div class="p-3 d-none new_cat_form" style="
                padding: 10px;
                border: 1px solid  #e1e1e1; 
                ">
                {{ Form::open(array('action' => 'DocumentsController@create_category', 'method' => 'post', 'id' => 'new_cat_form', 'enctype' => 'multipart/form-data'))}}
                <div class="new_cat_div">
                    <input type="hidden" name="action_id" id="action_id" value="">
                    <label class="category_label"> Category </label>
                    <input type="text" name="name" id="name" class="form-control category" placeholder="Category Name" maxlength="50">
                    <!-- <br> -->
                    <div class="div" style="
    padding-top: 4px;
">
                        <input type="button" name="close" value="Done" class="btn btn-success category-btn new-category-btn" style="
    margin-left: 10px;
">
                        <input type="button" class="btn btn-success cancel_modal" value="Cancel">
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <hr>

            <table id="category_table" class="table-responsive">
                <thead>
                    <tr class="top-heading">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($categories as $category) {
                    ?>

                        <tr>
                            <td>C-00{{ $category->id ?? ''}}</td>
                            <td><?= $category['name'] ?></td>
                            <td>

                                <div class="dropdown more-btn">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span>...</span>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">

                                        <a href="" class="dropdown-item" onclick="edit_category('{{$category->id}}','{{$category->name}}')"><i class="fa fa-pencil"></i> {{trans('label.edit')}} </a>
                                        <a class="dropdown-item delete_category" data-id="" onclick="delete_category({{$category->id}})"><i class="fa fa-archive"></i> {{trans('label.delete')}} </a>

                                    </div>
                                </div>

                            </td>
                        </tr>


                    <?php

                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<div id="edit_category_modal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal_close">&times;</span>
            <div class="category_title">Edit Category</div>
        </div>
        <div class="modal-body">
          
                {{ Form::open(array('action' => 'DocumentsController@edit_category', 'method' => 'post', 'id' => 'edit_cat_form', 'enctype' => 'multipart/form-data'))}}
                <div class="new_cat_div">
                    <input type="hidden" name="category_id" id="category_id" value="">
                    <label class="category_label"> Category </label>
                    <input type="text" name="name" id="category_name" class="category form-control" placeholder="Category Name" maxlength="50"><br>
                    <div class="button_div">
                        <input type="submit" name="close" value="Done" class="btn btn-success ">
                        <input type="button" class="btn btn-success cancel_modal" value="Cancel">
                    </div>
                </div>
                {{ Form::close() }}

        </div>
    </div>
</div>
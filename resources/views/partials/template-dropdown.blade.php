<div class="drop_opt" id="drop_opt_{{
    isset($key)? $key+1 : 
    (isset($section_no) && !empty($section_no) ?
     $section_no : 1)
    }}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" >
    <div class="col-md-12">
        <label class="type_order" >Type Order</label>
        <div class="row">
            <div class="col-md-3">
            
            <select  name="type_order[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}]" class="type-order  type-order-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" onchange="change_type({{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}, {{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}, this)"> 
                <option value="">--Select--</option>
                @if(isset($question->dropdown_type) && !empty($question->dropdown_type))
                    @foreach ($question->dropdown_type as $type_keys => $dropdown_types)
                        <option value="{{$dropdown_types->type_name ?? ''}}" data-name = "{{$dropdown_types->type_name}}" data-id="{{$dropdown_types->id}}" @if($dropdown_types->selected_type == 1) selected @endif>{{ ucfirst($dropdown_types->type_name)}}</option>
                        @if($dropdown_types->type_name != 'audit' && count($question->dropdown_type) < 2)
                            <option value="audit" data-name = "" data-id="">Audit</option>    
                        @endif
                    @endforeach
                @else
                    <option value="audit" data-name = "" data-id="" selected>Audit</option>
                @endif
            </select>
            </div>
            <div class="col-md-3 more_type  more_type_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" style="display: none">
                <input type="text" placeholder="Write type" class="dropdown_type" name="dropdown_type[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}]">
            </div>
            @if(!isset($question->dropdown_type) || empty($question->dropdown_type) || count($question->dropdown_type) < 2 && array_key_exists(0, $question->dropdown_type) && $question->dropdown_type[0]->type_name == 'audit')
                <div class="col-md-3 add_type add_type_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}">
                    <a class="add_type_button" onclick="add_type({{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}, {{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}})">+ Add More Type</a>
                </div>
            @endif
        </div>
    </div> 
    <div class="col-md-12">
        <div class="row">
            
            @if(isset($question['dropdown_type']) && !empty($question['dropdown_type']))
                @foreach ($question['dropdown_type'] as $type_detail)
                    @if(isset($type_detail['selected_type']) && $type_detail['selected_type'] == 1 && $type_detail['type_name'] != 'audit')
                        <div class="{{$type_detail['type_name']}}-{{$type_detail['id']}}">         
                            <div class="col-md-3 dropdown-options-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}} dropdown-options" style="display: 'block'}}"> 
                                <a class="add_option_button" onclick="add_option('{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}', '{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}');">+ Add More Option</a>
                            </div>
                            <div class="audit-option-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}  audit-option" style="display:none">
                                @php $i = 0; @endphp
                                @foreach (App\Models\Template::getdropDownArray() as $drop_key => $drop_value)
                                    <div class="col-md-3">
                                        <div class="option-box">
                                        <input type="text"  placeholder="Write your options" name="option[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][{{$i}}]" class="options_ques ques_option_first {{$drop_key}}" value="{{$drop_value}}" readonly>
                                        </div>
                                    </div>
                                @php $i++; @endphp
                                @endforeach
                            </div>
                            @foreach ($type_detail['options'] as $drop_key => $drop_value)
                                <div class="drop_option drop_option_old_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}} col-md-12" id="drop_option_old_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_{{isset($drop_key) && !empty($drop_key) ? $drop_key+1 : 1}}" >        
                                    <div class="row">
                                        <div class="col-md-3"> 
                                        <input type="hidden" name="option_id[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey : (isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][{{isset($drop_key) && !empty($drop_key) ? $drop_key : 0}}]" value="{{$drop_value['id'] ?? ''}}">
                                            <input type="text"  placeholder="Write your options" value="{{$drop_value['option_name'] ?? ''}}" name="old_options[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey : (isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][{{isset($drop_key) && !empty($drop_key) ? $drop_key : 0}}]"  id="options_ques_{{isset($key)? $key+1 :(isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_{{isset($drop_key) && !empty($drop_key) ? $drop_key+1 : 1}}" class="options_ques ques_option_first">
                                        </div>
                                        <div class="col-md-2 failed-item failed-item-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" > 
                                            @if($drop_value['failed_item'] == 1)
                                                <span class="checked">
                                            @endif
                                                <input type="radio" name="old_failed_item[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}]" class="failed_item  failed_item_input failed_item_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_{{isset($drop_key) && !empty($drop_key) ? $drop_key+1 : 1}}"  value="{{isset($drop_key) && !empty($drop_key) ? $drop_key : 0}}" {{$drop_value['failed_item'] == 1 ? 'checked' : ''}}>Failed Item
                                            @if($drop_value['failed_item'] == 1)
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-2 color-code color-code-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" id="color-code-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" >
                                            <select name="old_color_code[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][{{isset($drop_key) && !empty($drop_key) ? $drop_key : 0}}]" class="color_code_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_{{isset($drop_key) && !empty($drop_key) ? $drop_key+1 : 1}} color-options">
                                                <option value="">--Select--</option>
                                                @foreach (App\Models\Template::getpinColorArray() as $pin_key => $pin_value)
                                                    <option value="{{ $pin_value }}" class="{{$pin_key}}-pin" {{isset($drop_value['color_code']) && $drop_value['color_code'] ==  $pin_value ? 'selected' : '' }}>{{ $pin_key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif(isset($type_detail['selected_type']) && $type_detail['selected_type'] == 1 && $type_detail['type_name'] == 'audit')
                        <div class="{{$type_detail['type_name']}}-{{$type_detail['id']}}">
                            {{-- <div class="col-md-3 dropdown-options-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}} dropdown-options" style="display: none"> 
                                <a onclick="add_option('{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}', '{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}');">+ Add More Option</a>
                            </div> --}}
                            <div class="audit-option-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}  audit-option" style="display:block">
                                @php $i = 0; @endphp
                                @foreach (App\Models\Template::getdropDownArray() as $drop_key => $drop_value)
                                    <div class="col-md-3">
                                        <div class="option-box">
                                        <input type="text"  placeholder="Write your options" name="option[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][{{$i}}]" class="options_ques ques_option_first {{$drop_key}}" value="{{$drop_value}}" readonly>
                                        </div>
                                    </div>
                                @php $i++; @endphp
                                @endforeach
                            </div>
                            <div class="drop_opt_sec_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}    drop_opt_sec option_section" id="drop_opt_sec_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_1" >
                                <div class="col-md-3 dropdown-options-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}} dropdown-options" style="display: none"> 
                                    <a class="add_option_button" onclick="add_option('{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}', '{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}');">+ Add More Option</a>
                                </div>
                                <div class="drop_option drop_option_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}} col-md-12" id="drop_option_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_1" >        
                                    <div class="row">
                                        <div class="col-md-3"> 
                                                <input type="text"  placeholder="Write your options" name="new_options[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey : (isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][0]"  id="options_ques_{{isset($key)? $key+1 :(isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_1" class="options_ques_input options_ques ques_option_first">
                                        </div>
                                        <div class="col-md-2 failed-item failed-item-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" > 
                                            <input type="radio" name="failed_item[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}]" class="failed_item_input failed_item failed_item_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_1 "  value="0">Failed Item
                                        </div>
                                        <div class="col-md-2 color-code color-code-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" id="color-code-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" >
                                            <select name="color_code[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][0]" class="color_code_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_1 color-options">
                                                <option value="">--Select--</option>
                                                @foreach (App\Models\Template::getpinColorArray() as $pin_key => $pin_value)
                                                    <option value="{{ $pin_value }}" class="{{$pin_key}}-pin">{{ $pin_key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if( $type_detail['selected_type'] == 0 && $type_detail['type_name'] != 'audit')
                        <div class="{{$type_detail['type_name']}}-{{$type_detail['id']}}" style="display: none">         
                            <div class="col-md-3 dropdown-options-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}} dropdown-options" style="display: 'block'}}"> 
                                <a class="add_option_button" onclick="add_option('{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}', '{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}');">+ Add More Option</a>
                            </div>
                            @foreach ($type_detail['options'] as $drop_key => $drop_value)
                                <div class="drop_option drop_option_old_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}} col-md-12" id="drop_option_old_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_{{isset($drop_key) && !empty($drop_key) ? $drop_key+1 : 1}}" >        
                                    <div class="row">
                                        <div class="col-md-3"> 
                                            <input type="hidden" name="option_id[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey : (isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][{{isset($drop_key) && !empty($drop_key) ? $drop_key : 0}}]" value="{{$drop_value['id'] ?? ''}}">
                                            <input type="text"  placeholder="Write your options" value="{{$drop_value['option_name'] ?? ''}}" name="old_options[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey : (isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][{{isset($drop_key) && !empty($drop_key) ? $drop_key : 0}}]"  id="options_ques_{{isset($key)? $key+1 :(isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_{{isset($drop_key) && !empty($drop_key) ? $drop_key+1 : 1}}" class="options_ques ques_option_first">
                                        </div>
                                        <div class="col-md-2 failed-item failed-item-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" > 
                                            @if($drop_value['failed_item'] == 1)
                                                <span class="checked">
                                            @endif
                                                <input type="radio" name="old_failed_item[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}]" class="failed_item failed_item_input failed-item_input failed_item_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_{{isset($drop_key) && !empty($drop_key) ? $drop_key+1 : 1}}"  value="{{isset($drop_key) && !empty($drop_key) ? $drop_key : 0}}" {{$drop_value['failed_item'] == 1 ? 'checked' : ''}}>Failed Item
                                            @if($drop_value['failed_item'] == 1)
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-2 color-code color-code-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" id="color-code-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" >
                                            <select name="old_color_code[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][{{isset($drop_key) && !empty($drop_key) ? $drop_key : 0}}]" class="color_code_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_{{isset($drop_key) && !empty($drop_key) ? $drop_key+1 : 1}} color-options">
                                                <option value="">--Select--</option>
                                                @foreach (App\Models\Template::getpinColorArray() as $pin_key => $pin_value)
                                                    <option value="{{ $pin_value }}" class="{{$pin_key}}-pin" {{isset($drop_value['color_code']) && $drop_value['color_code'] ==  $pin_value ? 'selected' : '' }}>{{ $pin_key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-md-3 dropdown-options-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}} dropdown-options" style="display: none"> 
                    <a class="add_option_button"  onclick="add_option('{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}', '{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}');">+ Add More Option</a>
                </div>
                <div class="audit-option-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}  audit-option">
                    @php $i = 0; @endphp
                    @foreach (App\Models\Template::getdropDownArray() as $drop_key => $drop_value)
                        <div class="col-md-3">
                            <div class="option-box">
                            <input type="text"  placeholder="Write your options" name="option[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][{{$i}}]" class="options_ques ques_option_first {{$drop_key}}" value="{{$drop_value}}" readonly>
                            </div>
                        </div>
                    @php $i++; @endphp
                    @endforeach
                </div>

                <div class="drop_opt_sec drop_opt_sec_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}} option_section  " id="drop_opt_sec_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_1">
                    <div class="drop_option drop_option_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}} col-md-12" id="drop_option_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_1" >        
                        <div class="row">
                            <div class="col-md-3"> 
                                    <input type="text"  placeholder="Write your options" name="new_options[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey : (isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][0]"  id="options_ques_{{isset($key)? $key+1 :(isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_1" class="options_ques_input options_ques ques_option_first">
                            </div>
                            <div class="col-md-2 failed-item failed-item-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" > 
                                <input type="radio" name="failed_item[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}]" class="failed_item  failed_item_input failed-item_input  failed_item_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_1 "  value="0">Failed Item
                            </div>
                            <div class="col-md-2 color-code color-code-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" id="color-code-{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}-{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}" >
                                <select name="color_code[{{isset($key)? $key : (isset($section_no) && !empty($section_no) ? $section_no-1 : 0)}}][{{isset($queskey)? $queskey :(isset($question_no) && !empty($question_no) ? $question_no-1 : 0)}}][0]" class="color_code_{{isset($key)? $key+1 : (isset($section_no) && !empty($section_no) ? $section_no : 1)}}_{{isset($queskey)? $queskey+1 :(isset($question_no) && !empty($question_no) ? $question_no : 1)}}_1 color-options">
                                    <option value="">--Select--</option>
                                    @foreach (App\Models\Template::getpinColorArray() as $pin_key => $pin_value)
                                        <option value="{{ $pin_value }}" class="{{$pin_key}}-pin">{{ $pin_key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
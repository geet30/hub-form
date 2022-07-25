<div class="answer row">
    @if(!empty($question->answers))
    <!-- for check text -->
    @if(in_array($question->question_type, array(config('constants.question_type.text'),config('constants.question_type.date'),config('constants.question_type.number'),config('constants.question_type.location'))))
    <div class="col-md-10">
        <input type="text" class="answer @if($question->question_type == config('constants.question_type.date')) date_picker @endif" value="{{$question->answers->answer}}" @if (!$checkPer) disabled="disabled" readonly="readonly" @endif>
    </div>
    @if ($checkPer)
    <button class="update_answer btn btn-info" data-id="{{$question->answers->id}}" data-type="1">Update</button>
    @endif
    <!-- for check option -->
    @elseif($question->question_type == config('constants.question_type.two_option'))
    <div class="col-md-10">
        <input type="radio" class="answer"
            name="answer" value="1"
            @if($question->answers->answer==1)
        checked @endif @if (!$checkPer) disabled="disabled" readonly="readonly" @endif>
        <label for="male">Yes</label><br>
        <input type="radio" class="answer"
            name="answer" value="0"
            @if($question->answers->answer==0)
        checked @endif @if (!$checkPer) disabled="disabled" readonly="readonly" @endif>
        <label for="female">No</label><br>
    </div>
    @if ($checkPer)
    <button class="update_answer btn btn-info" data-id="{{$question->answers->id}}"
    data-type="1">Update</button>
    @endif
    <!-- config('constants.question_type.dropdown'),config('constants.question_type.date'),config('constants.question_type.number'),config('constants.question_type.multi_choice'),config('constants.question_type.multi_select')) -->
    <!-- for check option -->
    @elseif($question->question_type == config('constants.question_type.dropdown'))
    <div class="col-md-10">
    <select class="answer dropdown_ans" @if (!$checkPer) disabled="disabled" readonly="readonly" @endif style="background-color: #{{$question->answers->dropdown_color}}; color:black">
            <option>Select</option>
            @if(!empty($question->dropdown_type) && count($question->dropdown_type) > 0)
            @foreach ($question->dropdown_type as $drop_type)
                @if(!empty($drop_type->options) && count($drop_type->options) > 0)
                    @foreach ($drop_type->options as $option)
                        <option value="{{$option->id}}"
                            @if($question->answers->answer==$option->option_name)
                        selected @endif style="background-color: #{{$option->color_code}}">{{$option->option_name}}
                        </option>
                    @endforeach
                @endif
            @endforeach
            @endif
        </select>
    </div>
    @if ($checkPer)
    <button
    class="update_answer btn btn-info"
    data-id="{{$question->answers->id}}"
    data-type="1">Update</button>
    @endif
    <!-- for multiple checkbox option -->
    @elseif($question->question_type == config('constants.question_type.multi_choice'))
    <div class="col-md-10">
        @if(!empty($question->type_option) && count($question->type_option))
        @foreach ($question->type_option as $option)
        <input type="radio" class="answer checkbox-answer"
            value="{{$option}}" name="answer"
            @if($question->answers->answer == $option) checked @endif @if (!$checkPer) disabled="disabled" readonly="readonly" @endif>
        <label for="{{$option}}">
            {{$option}}</label><br>
        @endforeach
        @endif
    </div>
    @if ($checkPer)
    <button class="update_answer btn btn-info" data-id="{{$question->answers->id}}" data-type="1">Update</button>
    @endif
    <!-- for check signature -->
    @elseif(in_array($question->question_type, array(config('constants.question_type.signature'))))
    <div class="col-md-10">
        @if(!empty($question->answers->answer))
        <img src="{{url('/uploads/'.$question->answers->answer.'')}}"
            class="img-responsive sign-image"
            alt="{{$question->answers->answer}}" @if (!$checkPer) disabled="disabled" readonly="readonly" @endif/>
        <input type="hidden" class="answer" value="{{$question->answers->answer}}" @if (!$checkPer) disabled="disabled" readonly="readonly" @endif>
        @endif
    </div>
    @if ($checkPer)
    {{-- <button class="update_answer btn btn-info" data-id="{{$question->answers->id}}" data-type="1">Update</button> --}}
    @endif
    <!-- for check multiple select -->
    <!-- for check multiple select -->
    @elseif($question->question_type == config('constants.question_type.multi_select'))
    @if($question->answers->type_option)
    <div class="col-md-10">
        @if(!empty($question->type_option) && count($question->type_option))
        @foreach ($question->type_option as
        $option)
        <input type="checkbox" class="answer mcq-answer" value="{{$option}}" 
            @if(in_array($option,$question->answers->type_option))
        checked @endif @if (!$checkPer) disabled="disabled" readonly="readonly" @endif>
        <label for="{{$option}}">
            {{$option}}</label><br>
        @endforeach
        @endif
    </div>
    @if ($checkPer)
    <button class="update_answer btn btn-info" data-id="{{$question->answers->id}}" data-type="7">Update</button>
    @endif
    @endif
    @endif
    @endif
</div>
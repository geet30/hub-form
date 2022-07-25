<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<style>
    h2 {
        text-align: center;
    }
    
    .panel-default>.panel-heading {
        color: #333;
        background-color: #f7faff;
        padding: 15px;
    }
    
    h4.panel-title a {
        width: 100%;
        display: inline-block;
        text-decoration: none;
    }
    
    .panel-group .panel {
        border-radius: 4px;
        margin-top: 15px;
        margin-bottom: 15px;
    }
</style>

<body>

    <div class="container">
        <h2>FAQ</h2>
        <div class="panel-group" id="accordion">
        @if(!empty($faqs))
            @foreach($faqs as $key=>$faq)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{($key+1)}}">{{$faq->faqs}}</a>
                        </h4>
                    </div>
                    <div id="collapse{{($key+1)}}" class="panel-collapse collapse @if($key==0) in @endif">
                        <div class="panel-body">{{$faq->answer}}</div>
                    </div>
                </div>
            @endforeach
            @else
             No Content Found!
        @endif
        </div>
    </div>

</body>

</html>
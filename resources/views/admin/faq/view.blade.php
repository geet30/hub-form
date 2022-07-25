<?php //pr($faqs);die; ?>
<html>
<title> FAQ </title>
<style>
    body {
        position: fixed;
        top: 0;
        left: 0;
        border: 10px solid #000;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    .faq{
        margin: auto;
        width: 60%;
        padding: 10px;
        border: 10px solid #000;
        height: 80%;
        margin-top:30px;
    }
    h2 {
        text-align:center;
        margin-top : 20px;
        margin-bottom:30px;
    }

    .ques_ans{
        margin-top:20px;
        font-size:20px;
    }

    .ques, .ans {
        margin:20px;
    }

</style>
<body>
    <div class="faq">
        <h2> FAQ </h2>
        <div class="ques_ans">
            @foreach($faqs as $key=>$faq)
                <div class="ques">Q{{($key+1)}}. {{$faq->faqs}}</div>
                <div class="ans">A. {{$faq->answer}}</div>
            @endforeach
        </div>
    </div>
</body>
</html>
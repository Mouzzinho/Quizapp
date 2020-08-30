<?php
    require_once('function.php');
    session_start();

    if(isSet($_GET['op']))
        $op = $_GET['op'];
    else  {
        $op = ' ';
    }



    switch($op)	{
        case 'get_question':
            $conn = conn();

            $question = $conn -> query("SELECT pytania.id, pytania.text FROM `pytania` INNER JOIN zestawy ON pytania.id=zestawy.question_id WHERE zestawy.id = '".$_GET['question']."'")->fetch_assoc();

            $answer_a = $conn -> query("SELECT odpowiedzi.id, odpowiedzi.text FROM `odpowiedzi` INNER JOIN zestawy ON odpowiedzi.id=zestawy.answer_1_id WHERE zestawy.id = '".$_GET['question']."'")->fetch_assoc();
            $answer_b = $conn -> query("SELECT odpowiedzi.id, odpowiedzi.text FROM `odpowiedzi` INNER JOIN zestawy ON odpowiedzi.id=zestawy.answer_2_id WHERE zestawy.id = '".$_GET['question']."'")->fetch_assoc();
            $answer_c = $conn -> query("SELECT odpowiedzi.id, odpowiedzi.text FROM `odpowiedzi` INNER JOIN zestawy ON odpowiedzi.id=zestawy.answer_3_id WHERE zestawy.id = '".$_GET['question']."'")->fetch_assoc();
            $answer_d = $conn -> query("SELECT odpowiedzi.id, odpowiedzi.text FROM `odpowiedzi` INNER JOIN zestawy ON odpowiedzi.id=zestawy.answer_4_id WHERE zestawy.id = '".$_GET['question']."'")->fetch_assoc();
            
            echo json_encode(array('question' => $question, 'answer_a' => $answer_a, 'answer_b' => $answer_b, 'answer_c' => $answer_c, 'answer_d' => $answer_d, 'question_id' => $_GET['question']));
        break;

        case 'check_answer':
            $conn = conn();

            $correct_answer = $conn -> query("SELECT odpowiedzi.id FROM `odpowiedzi` INNER JOIN zestawy ON odpowiedzi.id=zestawy.answer_c_id WHERE zestawy.id = '".$_GET['question']."'")->fetch_assoc();

            if($correct_answer['id'] == $_GET['answer']) {
                echo json_encode(array('check' => "true", 'correct' => $correct_answer, 'answer' => $_GET['answer']));
            } else {
                echo json_encode(array('check' => "false", 'correct' => $correct_answer, 'answer' => $_GET['answer']));
            }
        break;

        default:
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="page start-page">
        <div class="start-wrapper">
            <div class="start-wrapper__title">Quiz APP</div>
            <div class="start-wrapper__playbutton">Graj</div>
        </div>
    </div>
    <div class="page asnwer-page" style="display: none;">
        <div class="start-wrapper-second">
            <div class="question-wrapper">
                <div class="question-header">
                    <p class="question-number"></p>
                    <p class="question-correct"></p>
                </div>
                <div class="question-main"></div>
                <div class="question-footer"></div>
            </div>
            <div class="answer-wrapper">
                <div class="answer first" data-id=""></div>
                <div class="answer second" data-id=""></div>
                <div class="answer third" data-id=""></div>
                <div class="answer fourth" data-id=""></div>
            </div>
        </div>
    </div>
    <div class="page result-page" style="display: none;">
        <div class="start-wrapper">
            <div class="result-wrapper">
                <p class="correct-answers">Poprawnych odpowiedzi:</p>
                <p class="correct-number"></p>
                <p class="score"></p>
            </div>
            <div class="play-again">
                <p class="play-again__text">Zagraj Ponownie</p>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="script.js"></script>
</body>

</html>

<?php
    }
?>
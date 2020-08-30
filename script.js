$(document).ready(function () {

    let questionNumber;
    let correctAnswer;

    $('.start-wrapper__playbutton').on('click', function () {
        $('.start-page').css('display', 'none');
        $('.asnwer-page').css('display', 'block');
        initGame();
    })

    $('.answer').on('click', function () {
        if ($(this).hasClass('blocked')) {
            return false;
        } else $('.answer').addClass('blocked');

        question_id = questionNumber;
        let answer_id = $(this).attr('data-id');
        checkAnswer(answer_id, question_id);

    })
    $('.play-again').on('click', function () {

    })

    function initGame() {
        questionNumber = 1;
        correctAnswer = 0;
        questionDownload(questionNumber);
    }

    function questionDownload(question_id) {
        $.ajax({
            url: '?op=get_question' + '&question=' + question_id,
            dataType: "json",
            success: function (response) {

                let tablica = [
                    [response.answer_a.text, response.answer_a.id],
                    [response.answer_b.text, response.answer_b.id],
                    [response.answer_c.text, response.answer_c.id],
                    [response.answer_d.text, response.answer_d.id]
                ]
                shuffle(tablica);
                $('.answer.first').text(tablica[0][0]).attr('data-id', tablica[0][1]).removeClass('green red')
                $('.answer.second').text(tablica[1][0]).attr('data-id', tablica[1][1]).removeClass('green red');
                $('.answer.third').text(tablica[2][0]).attr('data-id', tablica[2][1]).removeClass('green red');
                $('.answer.fourth').text(tablica[3][0]).attr('data-id', tablica[3][1]).removeClass('green red');
                $('.question-main').text(response.question.text);
                $('.question-number').text('Pytanie ' + questionNumber + '/10');
                $('.question-correct').text('Poprawnie ' + correctAnswer);
                $('.answer').removeClass('blocked');
            },
            error: function (error) {
                console.log(error);
            }
        })
    }

    function checkAnswer(answer_id, question_id) {
        $.ajax({
            url: '?op=check_answer' + '&question=' + question_id + '&answer=' + answer_id,
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response.check == 'true') {
                    $('.answer[data-id=' + answer_id + ']').addClass('green');
                    correctAnswer++;
                    $('.question-correct').text('Poprawnie ' + correctAnswer);

                } else $('.answer[data-id=' + answer_id + ']').addClass('red');
                question_id++;
                setTimeout(() => {
                    if (questionNumber >= 10) {
                        endGame();
                    } else {
                        questionDownload(question_id);
                        questionNumber++;
                    }

                }, 3000);
            },
            error: function (error) {
                console.log(error);
            }
        })
    }

    function endGame() {

        $('.asnwer-page').css('display', 'none');
        $('.result-page').css('display', 'block');
        $('.play-again').on('click', function () {
            initGame();
            $('.result-page').css('display', 'none');
            $('.start-page').css('display', 'block');
        })

        if (correctAnswer <= 3) {
            $('.correct-number').text(correctAnswer);
            $('.score').text('Słabiutko, Spróbuj ponownie.');

        } else if (correctAnswer > 3 && correctAnswer <= 6) {
            $('.correct-number').text(correctAnswer);
            $('.score').text('Całkiem, całkiem');
        } else {
            $('.correct-number').text(correctAnswer);
            $('.score').text('Gratulacje, wspaniały wynik!');
        }

    }

    function shuffle(array) {
        var currentIndex = array.length,
            temporaryValue, randomIndex;
        while (0 !== currentIndex) {
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex -= 1;
            temporaryValue = array[currentIndex];
            array[currentIndex] = array[randomIndex];
            array[randomIndex] = temporaryValue;
        }
        return array;
    }
});
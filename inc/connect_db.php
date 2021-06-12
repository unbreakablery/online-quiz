<?php
	$env = "dev";

	if ($env == "dev") {
		// $connect = mysqli_connect("localhost","dbo864933279","A!!7BAdvdBZx6n_") or die("Database connection failed.");
		// mysqli_select_db($connect, "db864933279");
		// $connect = mysqli_connect("localhost","jasondashboard","n93uxa*{t+9-") or die("Database connection failed.");
		$connect = mysqli_connect("localhost","root","") or die("Database connection failed.");
		mysqli_select_db($connect, "emedica-quiz");
	} else if ($env == "staging") {
		$connect = mysqli_connect("localhost","","") or die("Database connection failed.");
		mysqli_select_db($connect, "");
	} else {
		$connect = mysqli_connect("localhost","","") or die("Database connection failed.");
		mysqli_select_db($connect, "");
	}

	function getYourTime($seconds) {
        $mins = floor($seconds / 60);
        $secs = $seconds % 60;
        return ($mins < 10 ? "0" . $mins : $mins) . ":" . ($secs < 10 ? "0" . $secs : $secs);
	}
	function getScore($que_type, $cor_ans, $answers, $points = 4) {
		$total_score = strlen($cor_ans) * $points;

		$score = 0;
		if ($que_type == "SEQ") {
			for ($i = 0; $i < strlen($cor_ans); $i++) {
                for ($j = 0; $j < strlen($answers); $j++) {
                    if ((ord($cor_ans[$i]) - 65 + 1) == $answers[$j]) {
                        $score += $points - abs($i - $j);
                    }
                }	
            }
		} else if ($que_type == "MR") {
			for ($i = 0; $i < strlen($cor_ans); $i++) {
				for ($j = 0; $j < strlen($answers); $j++) {
					if ((ord($cor_ans[$i]) - 65 + 1) == $answers[$j]) {
						$score += $points;
					}
				}	
			}
		} else if ($que_type == "MC") {
			$score = ($answers == 0) ? 0 : ($points - abs(ord($cor_ans) - 65 + 1 - $answers));
		}

		return array(
						'total' 	=> $total_score,
						'checked' 	=> $score
					);
	}
	function getTargetScore($quiz_id) {
		global $connect;
		$query = "
					SELECT
						SUM(LENGTH(`cor_ans`) * 4) AS t_score
					FROM
						`questions`
					WHERE
						`quiz_id` = $quiz_id
				";
		$result = mysqli_query($connect, $query);
		$row = mysqli_fetch_array($result);
		return $row['t_score'];
	}
	function getExam($exam_id) {
		global $connect;
		$query = "
					SELECT
						*
					FROM
						`exams`
					WHERE
						`id` = $exam_id
				";
		$result = mysqli_query($connect, $query);
		return mysqli_fetch_array($result);
	}
	function getExam2($user_id, $quiz_id) {
		global $connect;
		$query = "
					SELECT
						*
					FROM
						`exams`
					WHERE
						`user_id` = '$user_id' AND
						`quiz_id` = $quiz_id
				";
		$result = mysqli_query($connect, $query);
		return mysqli_fetch_array($result);
	}
	function getQuiz($quiz_id) {
		global $connect;
		$query = "
					SELECT
						q1.*,
						COUNT(q2.id) AS cnt_que
					FROM `quizzes` AS q1
					LEFT JOIN `questions` AS q2 ON q2.quiz_id = q1.id
					WHERE
						q1.id = $quiz_id
					GROUP BY q1.id
				";
		$result = mysqli_query($connect, $query);
		return mysqli_fetch_array($result);
	}
	function existQuiz($quiz_id, $quiz_code, $quiz_type) {
		global $connect;
		$query = "
					SELECT
						*
					FROM `quizzes` AS qq
					WHERE
						quiz_code = '$quiz_code' AND 
						quiz_type = '$quiz_type' AND 
						id != $quiz_id
				";
		$result = mysqli_query($connect, $query);
		if (mysqli_num_rows($result) == 1) {
			return true;
		} else {
			return false;
		}
	}
	function getQuestion($que_id) {
		global $connect;
		$query = "
					SELECT
						*
					FROM
						`questions`
					WHERE
						`id` = $que_id
				";
		$result = mysqli_query($connect, $query);
		return mysqli_fetch_array($result);
	}
	function removeExam($user_id, $quiz_id) {
		global $connect;
		$query = "
					DELETE FROM `exams`
					WHERE
						`user_id` = '$user_id' AND
						`quiz_id` = $quiz_id
				";
		$result = mysqli_query($connect, $query);
		return $result;
	}
	function getQueIDs($quiz_id) {
		global $connect;
		$query = "
					SELECT 
						temp.id
					FROM 
						(SELECT
							*
						FROM questions AS q
						WHERE
							q.quiz_id = $quiz_id
						ORDER BY RAND()) AS temp
					ORDER BY temp.que_type DESC
                ";
        $result = mysqli_query($connect, $query);
        $que_ids = array();
        while($row = mysqli_fetch_array($result)) {
            $que_ids[] = $row['id'];
        }
		return $que_ids;
	}
	function getExamDetail($exam_id, $que_id) {
		global $connect;
		$query = "
                    SELECT
                        *
                    FROM
                        `exam_detail`
                    WHERE
						`exam_id` = $exam_id AND
						`que_id` = $que_id
                ";
        $result = mysqli_query($connect, $query);
        return mysqli_fetch_array($result);
	}
	function updateExamDetail($data) {
		global $connect;
		$exam_id = $data['exam_id'];
		$que_id = $data['que_id'];
		$answers = $data['answers'];

		$query = "
					UPDATE `exam_detail`
					SET
						`answers` = '$answers'
					WHERE
						`exam_id` = $exam_id AND
						`que_id` = $que_id
				";
		$result = mysqli_query($connect, $query);
		return $result;
	}
	function saveExamDetail($data) {
		global $connect;
		$exam_id = $data['exam_id'];
		$que_id = $data['que_id'];
		$answers = $data['answers'];

		$query = "
					INSERT INTO `exam_detail` 
						(`exam_id`, `que_id`, `answers`) VALUES (
				";
		$query .= $exam_id . ", ";
		$query .= $que_id . ", ";
		$query .= "'" . $answers . "')";
		mysqli_query($connect, $query);
        return mysqli_insert_id($connect);
	}
	function updateExamMock($data) {
		global $connect;
		$exam_id = $data['exam_id'];
		$cur_que_idx = $data['cur_que_idx'];
		$spent_time = $data['spent_time'];

		$query = "
					UPDATE `exams`
					SET
						`cur_que_idx` = $cur_que_idx,
						`spent_time` = $spent_time
					WHERE
						`id` = $exam_id
				";
		$result = mysqli_query($connect, $query);
		return $result;
	}
	function updateExamMock1($data) {
		global $connect;
		$exam_id = $data['exam_id'];
		$cur_que_idx = $data['cur_que_idx'];
		$spent_time = $data['spent_time'];
		$score = $data['score'];
		$total_score = $data['total_score'];

		$query = "
					UPDATE `exams`
					SET
						`cur_que_idx` = $cur_que_idx,
						`spent_time` = $spent_time,
						`score` = $score,
						`total_score` = $total_score,
						`state` = 1,
						`end_date` = NOW()
					WHERE
						`id` = $exam_id
				";
		$result = mysqli_query($connect, $query);
		return $result;
	}
	function existExamDetail($exam_id, $que_id) {
		global $connect;
		$query = "
                    SELECT
                        *
                    FROM
                        `exam_detail`
                    WHERE
						`exam_id` = $exam_id AND
						`que_id` = $que_id
                ";
		$result = mysqli_query($connect, $query);
		if (mysqli_num_rows($result) == 1) {
			return true;
		} else {
			return false;
		}
	}
	function removeExamDetail($exam_id) {
		global $connect;
		$query = "
					DELETE FROM `exam_detail`
					WHERE
						`exam_id` = $exam_id
				";
		$result = mysqli_query($connect, $query);
		return $result;
	}
	function getQuestions($quiz_id) {
		global $connect;

		$query = "
					SELECT
						`id`,
						`que_type`,
						UPPER(`cor_ans`) AS `cor_ans`,
						`cor_fb`
					FROM
						`questions`
					WHERE
						`quiz_id` = $quiz_id
				";
		$result = mysqli_query($connect, $query);
		$questions = array();
		while ($row = mysqli_fetch_array($result)) {
			$questions[] = $row;
		}
		return $questions;
	}
	function getAllQuestions() {
		global $connect;

		$query = "
					SELECT
						q1.id,
						q2.quiz_code,
						q1.que_type,
						q1.que_text,
						UPPER(cor_ans) AS cor_ans,
						q1.points
					FROM
						questions AS q1
					LEFT JOIN quizzes AS q2 ON q1.quiz_id = q2.id
					ORDER BY q1.quiz_id
				";
		$result = mysqli_query($connect, $query);
		$questions = array();
		while ($row = mysqli_fetch_array($result)) {
			$questions[] = $row;
		}
		return $questions;
	}
	function getExamQuestions($exam_id) {
		global $connect;
		$exam = getExam($exam_id);
		$que_ids = str_replace("|", ",", $exam['que_ids']);

		$query = "
					SELECT
						*
					FROM
						questions AS q
					WHERE
						q.id IN ($que_ids)
					ORDER BY FIELD(q.id, $que_ids);
				";
		$result = mysqli_query($connect, $query);
		$questions = array();
		while ($row = mysqli_fetch_array($result)) {
			$questions[] = $row;
		}
		return $questions;
	}
	function getExamQueFlagged($exam_id) {
		global $connect;
		$exam = getExam($exam_id);
		$questions = explode("|", $exam['f_que_ids']);
		return $questions;
	}
	function saveExamQueFlagged($exam_id, $f_que_ids) {
		global $connect;
		$query = "
					UPDATE `exams`
					SET
						`f_que_ids` = '$f_que_ids'
					WHERE
						`id` = $exam_id
			";
		$result = mysqli_query($connect, $query);
		return $result;
	}
	function getExamDetailQuestions($exam_id) {
		global $connect;

		$query = "
					SELECT
						*
					FROM
						`exam_detail`
					WHERE
						`exam_id` = $exam_id
				";
		$result = mysqli_query($connect, $query);
		$questions = array();
		while ($row = mysqli_fetch_array($result)) {
			$questions[] = $row;
		}
		return $questions;
	}
	function getQuizzes() {
		global $connect;
		$query = "
					SELECT
						`q1`.`id`,
						`q1`.`quiz_code`,
						COUNT(`q2`.`quiz_id`) AS `cnt_que`,
						`q1`.`quiz_type`,
						`q1`.`limit_time`
					FROM 
						`quizzes` AS `q1`
					LEFT JOIN `questions` AS `q2` ON `q1`.`id` = `q2`.`quiz_id`
					GROUP BY `q1`.`id`
					ORDER BY `q1`.`id`
				";
		$result = mysqli_query($connect, $query);

		$quizzes = array();
		while($row = mysqli_fetch_array($result)) {
			$quizzes[] = $row;
		}
		return $quizzes;
	}
	function saveQuiz($data) {
		global $connect;
		$quiz_code 		= $data['quiz_code'];
		$quiz_type 		= $data['quiz_type'];
		$limit_time 	= $data['limit_time'];

		$query = "
					INSERT INTO `quizzes` 
						(`quiz_code`, `quiz_type`, `limit_time`) 
					VALUES 
						('$quiz_code', '$quiz_type', $limit_time)

				";
		mysqli_query($connect, $query);
        return mysqli_insert_id($connect);
	}
	function updateQuiz($data) {
		global $connect;
		$quiz_id 		= $data['quiz_id'];
		$quiz_code 		= $data['quiz_code'];
		$quiz_type 		= $data['quiz_type'];
		$limit_time 	= $data['limit_time'];
		$query = "
					UPDATE `quizzes`
					SET
						`quiz_code` = '$quiz_code',
						`quiz_type` = '$quiz_type',
						`limit_time` = $limit_time
					WHERE
						`id` = $quiz_id
				";
		$result = mysqli_query($connect, $query);
		return $result;
	}
	function removeQuiz($quiz_id) {
		global $connect;
		$query = "
					DELETE FROM `quizzes`
					WHERE
						`id` = $quiz_id
				";
		$result1 = mysqli_query($connect, $query);

		$query = "
					DELETE FROM `questions`
					WHERE
						`quiz_id` = $quiz_id
				";
		$result2 = mysqli_query($connect, $query);
		return $result1 && $result2;
	}
	function removeQuestion($id) {
		global $connect;
		$query = "
					DELETE FROM `questions`
					WHERE
						`id` = $id
				";
		$result = mysqli_query($connect, $query);
		return $result;
	}
	function getMaxQueID($quiz_id) {
		global $connect;
		$query = "
					SELECT
						MAX(que_id) AS max_que_id
					FROM
						questions
					WHERE
						quiz_id = $quiz_id
				";
		$result = mysqli_query($connect, $query);
		if ($result) {
			$result = mysqli_fetch_array($result);
			return $result['max_que_id'];
		} else {
			return null;
		}
	}
	function getEvaluations() {
		global $connect;

		$query = "
					SELECT
						*
					FROM
						eval_setting
					ORDER BY from_value
				";
		$result = mysqli_query($connect, $query);
		$evaluations = array();
		while ($row = mysqli_fetch_array($result)) {
			$evaluations[] = $row;
		}
		return $evaluations;
	}
	function getEvalFromValue($value) {
		global $connect;

		$query = "
					SELECT
						*
					FROM eval_setting
					WHERE 
						$value BETWEEN from_value AND to_value

				";
		$result = mysqli_query($connect, $query);

		return mysqli_fetch_array($result);
	}
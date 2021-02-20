/*
SQLyog Community v13.1.6 (64 bit)
MySQL - 10.4.13-MariaDB : Database - emedica-quiz
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`emedica-quiz` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `emedica-quiz`;

/*Table structure for table `eval_setting` */

DROP TABLE IF EXISTS `eval_setting`;

CREATE TABLE `eval_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_value` int(11) NOT NULL,
  `to_value` int(11) NOT NULL,
  `feedback_text` text NOT NULL,
  `feedback_class` varchar(255) NOT NULL,
  `chart_color` varchar(255) NOT NULL,
  `chart_class` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `eval_setting` */

/*Table structure for table `exam_detail` */

DROP TABLE IF EXISTS `exam_detail`;

CREATE TABLE `exam_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL COMMENT 'id of exam table',
  `que_id` int(11) NOT NULL COMMENT 'id of question table',
  `answers` varchar(255) NOT NULL COMMENT 'user entered and exploded by | answer list(E|B|A|D|C)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `exam_detail` */

/*Table structure for table `exams` */

DROP TABLE IF EXISTS `exams`;

CREATE TABLE `exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL COMMENT 'name of exam taker',
  `quiz_id` int(11) NOT NULL COMMENT 'id of quizzes table',
  `cnt_que` int(11) NOT NULL COMMENT 'count of questions per quiz',
  `que_ids` varchar(255) NOT NULL COMMENT 'radomized and exploded by | question ids',
  `cur_que_idx` int(11) NOT NULL COMMENT 'index of current question',
  `score` int(11) NOT NULL COMMENT 'current score of exam taker',
  `total_score` int(11) NOT NULL COMMENT 'possible total score',
  `state` tinyint(1) NOT NULL COMMENT 'exam state(0: pending, 1: end)',
  `spent_time` int(11) DEFAULT NULL COMMENT 'spent time in exam',
  `start_date` datetime NOT NULL COMMENT 'start datetime for exam',
  `end_date` datetime DEFAULT NULL COMMENT 'end datetime for exam',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `exams` */

/*Table structure for table `questions` */

DROP TABLE IF EXISTS `questions`;

CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL COMMENT 'id of quizzes table',
  `que_id` int(11) DEFAULT NULL,
  `que_type` varbinary(50) DEFAULT NULL COMMENT 'question type(SEQ/MR, SEQ: ranked questions, MR: multiple choice)',
  `que_text` text DEFAULT NULL,
  `ans_1` text DEFAULT NULL,
  `ans_2` text DEFAULT NULL,
  `ans_3` text DEFAULT NULL,
  `ans_4` text DEFAULT NULL,
  `ans_5` text DEFAULT NULL,
  `ans_6` text DEFAULT NULL,
  `ans_7` text DEFAULT NULL,
  `ans_8` text DEFAULT NULL,
  `cor_ans` varchar(10) DEFAULT NULL COMMENT 'correct answers(DBACE)',
  `cor_fb` text DEFAULT NULL,
  `inc_fb` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `questions` */

/*Table structure for table `quizzes` */

DROP TABLE IF EXISTS `quizzes`;

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_code` varchar(255) NOT NULL COMMENT 'quiz name/code',
  `quiz_type` varchar(255) DEFAULT NULL COMMENT 'quiz type(untimed/timed)',
  `limit_time` int(11) DEFAULT NULL COMMENT 'exam time limit(secs)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `quizzes` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

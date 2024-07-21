/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.25-MariaDB : Database - znc-register
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`znc-register` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `znc-register`;

/*Table structure for table `attendance` */

DROP TABLE IF EXISTS `attendance`;

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_attendance` (`student_id`,`date`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;

/*Data for the table `attendance` */

insert  into `attendance`(`id`,`student_id`,`date`,`status`) values 
(1,1,'2024-07-01','present'),
(2,2,'2024-07-01','present'),
(3,1,'2024-07-02','present'),
(4,2,'2024-07-02','present'),
(5,1,'2024-07-03','present'),
(6,2,'2024-07-03','absent'),
(7,1,'2024-07-04','absent'),
(8,2,'2024-07-04','present'),
(9,1,'2024-07-05','present'),
(10,2,'2024-07-05','present'),
(11,1,'2024-06-03','present'),
(12,2,'2024-06-03','present'),
(13,1,'2024-06-04','present'),
(14,2,'2024-06-04','present'),
(15,1,'2024-06-05','present'),
(16,2,'2024-06-05','present'),
(17,1,'2024-07-11','absent'),
(18,2,'2024-07-11','present'),
(19,1,'2024-05-06','present'),
(20,2,'2024-05-06','present'),
(21,1,'2024-05-07','present'),
(22,2,'2024-05-07','absent'),
(23,1,'2024-05-08','present'),
(24,2,'2024-05-08','present');

/*Table structure for table `students` */

DROP TABLE IF EXISTS `students`;

CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `index_no` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `students` */

insert  into `students`(`id`,`name`,`email`,`phone`,`address`,`index_no`) values 
(1,'adhly','adhlyazhar00@gmail.com','0751443323','52/2 canal,road, puttalam','18506'),
(2,'adhla','adhla00@gmail.com','0751443323','52/2 canal,road, puttalam','18508'),
(7,'mazin','adhlyazhar00@gmail.com','0751443323','52/2 canal,road, puttalam','18507'),
(8,'heela','adhlyazhar00@gmail.com','0751443323','52/2 canal,road, puttalam','18509');

/*Table structure for table `user_student_assignments` */

DROP TABLE IF EXISTS `user_student_assignments`;

CREATE TABLE `user_student_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `user_student_assignments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `user_student_assignments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user_student_assignments` */

insert  into `user_student_assignments`(`id`,`user_id`,`student_id`) values 
(1,1,1),
(3,1,2),
(4,3,7),
(5,3,8);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`password`,`role`) values 
(1,'grade-01','123@','teacher'),
(2,'admin','2005@18','admin'),
(3,'grade-02','123@','teacher'),
(4,'grade-03','123@','teacher'),
(5,'grade-04','123@','teacher'),
(6,'superadmin','2005@18','admin'),
(7,'class-1','123@','teacher');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

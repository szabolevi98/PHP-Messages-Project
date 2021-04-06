/*
Navicat MySQL Data Transfer

Source Server         : xampp
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : db_example

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-04-06 19:28:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `messages`
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_hungarian_ci NOT NULL,
  `message` varchar(10000) COLLATE utf8_hungarian_ci NOT NULL,
  `message_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `answer` varchar(10000) COLLATE utf8_hungarian_ci NOT NULL,
  `answer_date` timestamp NOT NULL DEFAULT '2000-01-01 01:01:01' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_hungarian_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `register_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

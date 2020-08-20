/*
 Navicat Premium Data Transfer

 Source Server         : Local
 Source Server Type    : MySQL
 Source Server Version : 80019
 Source Host           : localhost:3306
 Source Schema         : biopulse

 Target Server Type    : MySQL
 Target Server Version : 80019
 File Encoding         : 65001

 Date: 20/08/2020 08:38:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for accounts
-- ----------------------------
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `robot_key` varchar(255) NOT NULL,
  `user_key` varchar(50) DEFAULT NULL,
  `user_pass` varchar(50) DEFAULT NULL,
  `video_channel` varchar(255) DEFAULT NULL,
  `instruction_channel` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of accounts
-- ----------------------------
BEGIN;
INSERT INTO `accounts` VALUES (1, 'jmugonix-one', '1595-248', 'yKGiwL', 'cvid-chan-fqRDTzRLdWKB', 'cinst-chan-5HKPKU5N6P8T', '2020-08-20 06:00:33');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

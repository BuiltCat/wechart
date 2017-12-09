/*
Navicat MySQL Data Transfer

Source Server         : aa
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : wechart_upload

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2017-12-09 11:33:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for file
-- ----------------------------
DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
  `fileid` varchar(14) NOT NULL,
  `userid` varchar(9) NOT NULL,
  `filepath` varchar(255) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`fileid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of file
-- ----------------------------
INSERT INTO `file` VALUES ('20171209014955', '155042242', 'public\\uploads//155042242', 'logo.png', '2017-12-09 01:49:55');
INSERT INTO `file` VALUES ('20171209015556', '155042242', 'public\\uploads//155042242', '123.png', '2017-12-09 01:55:56');
INSERT INTO `file` VALUES ('20171209015958', '155042242', 'public\\/155042242', '456.png', '2017-12-09 01:59:58');
INSERT INTO `file` VALUES ('20171209021754', '155042242', 'public\\/155042242', 'logo1.png', '2017-12-09 02:17:54');
INSERT INTO `file` VALUES ('20171209021840', '155042242', 'public\\/155042242', 'logo2.png', '2017-12-09 02:18:40');
INSERT INTO `file` VALUES ('20171209022038', '155042242', 'public\\/155042242', 'logo3.png', '2017-12-09 02:20:38');
INSERT INTO `file` VALUES ('20171209022513', '155042242', 'public\\uploads//155042242', 'logo4.png', '2017-12-09 02:25:13');
INSERT INTO `file` VALUES ('20171209022527', '155042242', 'public\\uploads//155042242', 'logo5.png', '2017-12-09 02:25:27');
INSERT INTO `file` VALUES ('20171209022649', '155042242', 'public\\uploads//155042242', 'logo6.png', '2017-12-09 02:26:49');
INSERT INTO `file` VALUES ('20171209022817', '155042242', 'public\\uploads//155042242', 'logo7.png', '2017-12-09 02:28:17');
INSERT INTO `file` VALUES ('20171209022939', '155042242', 'public\\uploads//155042242', 'logo8.png', '2017-12-09 02:29:39');
INSERT INTO `file` VALUES ('20171209023215', '155042242', 'public\\uploads//155042242', 'logo9.png', '2017-12-09 02:32:15');

-- ----------------------------
-- Table structure for stuclass
-- ----------------------------
DROP TABLE IF EXISTS `stuclass`;
CREATE TABLE `stuclass` (
  `userid` varchar(9) DEFAULT NULL,
  `userclass` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of stuclass
-- ----------------------------
INSERT INTO `stuclass` VALUES ('155042242', '155042242计算机科学与技术');
INSERT INTO `stuclass` VALUES ('155042242', '1550422计算机科学与技术');
INSERT INTO `stuclass` VALUES ('155042242', '155042243计算机科学与技术');

-- ----------------------------
-- Table structure for teacher
-- ----------------------------
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `userid` varchar(20) NOT NULL,
  `userpw` varchar(255) DEFAULT NULL,
  `usernm` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of teacher
-- ----------------------------
INSERT INTO `teacher` VALUES ('wangli', '123456', '王莉');

-- ----------------------------
-- Table structure for teaclass
-- ----------------------------
DROP TABLE IF EXISTS `teaclass`;
CREATE TABLE `teaclass` (
  `userid` varchar(20) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of teaclass
-- ----------------------------
INSERT INTO `teaclass` VALUES ('wangli', '1550422计算机科学与技术');
INSERT INTO `teaclass` VALUES ('wangli', '155042242计算机科学与技术');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userid` varchar(9) NOT NULL,
  `userpw` varchar(20) DEFAULT NULL,
  `usernm` varchar(10) DEFAULT NULL,
  `sex` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('155042242', '123456', '王钟浩', '男');

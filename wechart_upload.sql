/*
Navicat MySQL Data Transfer

Source Server         : aa
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : wechart_upload

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2017-11-16 17:02:18
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
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userid` varchar(9) NOT NULL,
  `userpw` varchar(20) DEFAULT NULL,
  `usernm` varchar(10) DEFAULT NULL,
  `userclass` varchar(7) DEFAULT NULL,
  `userphone` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

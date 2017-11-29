/*
 Navicat Premium Data Transfer

 Source Server         : mycompter
 Source Server Type    : MySQL
 Source Server Version : 50635
 Source Host           : localhost
 Source Database       : wechart_upload

 Target Server Type    : MySQL
 Target Server Version : 50635
 File Encoding         : utf-8

 Date: 11/29/2017 11:01:58 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `file`
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
--  Table structure for `teacher`
-- ----------------------------
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `userid` varchar(9) NOT NULL,
  `userpw` varchar(255) DEFAULT NULL,
  `usernm` varchar(10) DEFAULT NULL,
  `userphone` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `teaclass`
-- ----------------------------
DROP TABLE IF EXISTS `teaclass`;
CREATE TABLE `teaclass` (
  `userid` varchar(9) DEFAULT NULL,
  `class` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userid` varchar(9) NOT NULL,
  `userpw` varchar(20) DEFAULT NULL,
  `usernm` varchar(10) DEFAULT NULL,
  `userclass` varchar(7) DEFAULT NULL,
  `userphone` varchar(11) DEFAULT NULL,
  `sex` varchar(2) DEFAULT NULL,
  `need` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;

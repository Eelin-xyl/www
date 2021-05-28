/*
Navicat MySQL Data Transfer

Source Server         : root
Source Server Version : 50731
Source Host           : localhost:3306
Source Database       : can402-cw2

Target Server Type    : MYSQL
Target Server Version : 50731
File Encoding         : 65001

Date: 2021-05-28 12:07:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `comment`
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `cit` int(8) NOT NULL AUTO_INCREMENT,
  `pid` int(8) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `uid` varchar(32) NOT NULL,
  `time` varchar(32) NOT NULL,
  PRIMARY KEY (`cit`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comment
-- ----------------------------
INSERT INTO `comment` VALUES ('1', '1', '123', '1001', '1622001851');
INSERT INTO `comment` VALUES ('2', '1', 'hhhhhh', '1001', '1622002362');
INSERT INTO `comment` VALUES ('3', '2', 'test', '1003', '1622003444');
INSERT INTO `comment` VALUES ('4', '2', 'good picture', '1005', '1622023193');
INSERT INTO `comment` VALUES ('5', '5', 'good piture', '1002', '1622171748');
INSERT INTO `comment` VALUES ('6', '6', 'hahahhah', '1002', '1622171758');
INSERT INTO `comment` VALUES ('7', '8', 'nice beautiful photo', '1002', '1622171781');
INSERT INTO `comment` VALUES ('8', '10', 'peace', '1002', '1622171792');

-- ----------------------------
-- Table structure for `likes`
-- ----------------------------
DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `pid` int(8) NOT NULL,
  `uid` varchar(32) NOT NULL,
  `ilike` varchar(32) NOT NULL,
  PRIMARY KEY (`pid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of likes
-- ----------------------------
INSERT INTO `likes` VALUES ('5', '1001', 'dislike');
INSERT INTO `likes` VALUES ('10', '1001', 'like');
INSERT INTO `likes` VALUES ('6', '1002', 'dislike');
INSERT INTO `likes` VALUES ('8', '1002', 'like');
INSERT INTO `likes` VALUES ('9', '1004', 'like');
INSERT INTO `likes` VALUES ('7', '1004', 'dislike');
INSERT INTO `likes` VALUES ('5', '1004', 'like');
INSERT INTO `likes` VALUES ('11', '1004', 'like');
INSERT INTO `likes` VALUES ('1', '1004', 'like');
INSERT INTO `likes` VALUES ('1', '1005', 'like');

-- ----------------------------
-- Table structure for `picture`
-- ----------------------------
DROP TABLE IF EXISTS `picture`;
CREATE TABLE `picture` (
  `pid` int(8) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) NOT NULL,
  `description` varchar(256) NOT NULL,
  `tag` varchar(64) NOT NULL,
  `src` varchar(256) NOT NULL,
  `ilike` int(8) NOT NULL,
  `idislike` int(8) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of picture
-- ----------------------------
INSERT INTO `picture` VALUES ('1', '1001', 'basketball court', 'wallpaper', 'Image/Picture/202105251215351 (42).jpg', '2', '0');
INSERT INTO `picture` VALUES ('2', '1001', 'this is a tower', 'wallpaper', 'Image/Picture/202105251232034fba63058d63aa97dddacd2d42e42520.jpg', '0', '0');
INSERT INTO `picture` VALUES ('3', '1005', 'this is cartoon', 'cartoon', 'Image/Picture/2021052615324459df06d7c561f.jpg', '0', '0');
INSERT INTO `picture` VALUES ('5', '1005', 'test picture', 'computer', 'Image/Picture/2021052615341059f6b761ba343.jpg', '1', '1');
INSERT INTO `picture` VALUES ('6', '1002', 'this is a lake', 'lake', 'Image/Picture/2021052615424303879_forlornkeel_2560x1440.jpg', '0', '1');
INSERT INTO `picture` VALUES ('7', '1002', 'this is a lake', 'lake', 'Image/Picture/2021052715352703484_moonlitnight_2560x1440.jpg', '0', '1');
INSERT INTO `picture` VALUES ('8', '1004', 'this is a city', 'city', 'Image/Picture/2021052715381703397_rockefellercallsitaday_2560x1440.jpg', '1', '0');
INSERT INTO `picture` VALUES ('9', '1004', 'this is a city', 'city', 'Image/Picture/20210527153840138-140HQ14003.jpg', '1', '0');
INSERT INTO `picture` VALUES ('10', '1004', 'this is a lake', 'lake', 'Image/Picture/2021052715391203338_emptiness_2560x1440.jpg', '1', '0');
INSERT INTO `picture` VALUES ('11', '1003', 'this is a city', 'city', 'Image/Picture/2021052715402403403_stellarnightovermanhattan_2560x1440.jpg', '1', '0');

-- ----------------------------
-- Table structure for `userlist`
-- ----------------------------
DROP TABLE IF EXISTS `userlist`;
CREATE TABLE `userlist` (
  `uid` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `photo` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userlist
-- ----------------------------
INSERT INTO `userlist` VALUES ('1001', '123', 'Alpha', 'My name is Alpha2', 'Image/Photo/1621950717timg.jpg');
INSERT INTO `userlist` VALUES ('1002', '123', 'Broval', 'My name is Broval', null);
INSERT INTO `userlist` VALUES ('1003', '123', 'Charlie', 'My name is Charlie', 'Image/Photo/1622010409noface.png');
INSERT INTO `userlist` VALUES ('1004', '123', 'Delta', 'My name is Delta', null);
INSERT INTO `userlist` VALUES ('1005', '123', 'Echo', 'My name is Echo', 'Image/Photo/1622016591bot.png');

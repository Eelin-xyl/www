/*
Navicat MySQL Data Transfer

Source Server         : root
Source Server Version : 50731
Source Host           : localhost:3306
Source Database       : web

Target Server Type    : MYSQL
Target Server Version : 50731
File Encoding         : 65001

Date: 2021-05-28 12:08:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `goods`
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
  `gid` int(30) NOT NULL AUTO_INCREMENT,
  `category` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `price` double(30,0) NOT NULL,
  `sales` int(8) NOT NULL,
  `amount` int(8) NOT NULL,
  `origin` varchar(30) NOT NULL,
  `uid` int(30) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sname` varchar(30) NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=129 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of goods
-- ----------------------------
INSERT INTO `goods` VALUES ('1', '图书音像', '教材书', '30', '3', '47', '四川眉山', '1001', '/shop/20180621\\79bc2a1ce2b2cd1eaac42c5487b8994e.jpg', '朱老师的店铺');
INSERT INTO `goods` VALUES ('2', '箱包户外', '李宁书包', '60', '2', '80', '四川眉山', '1001', '/shop/20200301\\dc7770ed62fc60333bbf87f50cc7706d.jpg', '朱老师的店铺');
INSERT INTO `goods` VALUES ('3', '男装女装', '阿迪达斯短袖衫', '60', '2', '66', '四川眉山', '1001', '/shop/20180621\\414d288d9736bee2fe634d21b31e3129.jpg', '朱老师的店铺');
INSERT INTO `goods` VALUES ('4', '手机电脑', '战神笔记本', '3500', '3', '27', '四川眉山', '1001', '/shop/20180621\\bb2b0aac29dc746764358b8dea23de0c.jpg', '朱老师的店铺');
INSERT INTO `goods` VALUES ('5', '男装女装', 'pengjunjie', '100', '6', '99', '四川成都', '1234', '/shop/20190617\\a4bd9485a7be4e278322ae8e903514cf.png', '我是1234的店铺');
INSERT INTO `goods` VALUES ('125', '图书音像', 'owl', '20', '0', '50', '四川成都', '1234', '/shop/20200303\\d4eaca32264aa9b5c1a0f15027831322.jpg', '我是1234的店铺');
INSERT INTO `goods` VALUES ('127', '箱包户外', '测试', '20', '0', '20', '四川眉山', '1001', '/shop/20200515\\4bb46711667d813694c3ca73756a222c.png', '朱老师的店铺');
INSERT INTO `goods` VALUES ('128', '男装女装', '111', '10', '0', '10', '四川成都', '1234', '/shop/20200515\\0716707a2c666e443f4f30318526a0f9.png', '我是1234的店铺');

-- ----------------------------
-- Table structure for `order`
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `oid` int(30) NOT NULL AUTO_INCREMENT,
  `uid` int(30) NOT NULL,
  `gid` int(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `number` int(30) NOT NULL,
  `total` double(30,0) NOT NULL,
  `image` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `status` varchar(30) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES ('1', '1001', '2', '李宁书包', '1', '80', '/shop/20180621\\42015974613726ea19c972405a6e0aa0.jpg', '1530526100', '已付款', '', '0');
INSERT INTO `order` VALUES ('2', '1234', '4', '战神笔记本', '3', '3500', '/shop/20180621\\bb2b0aac29dc746764358b8dea23de0c.jpg', '1560778097', '已付款', '', '0');
INSERT INTO `order` VALUES ('3', '1234', '2', '李宁书包', '1', '80', '/shop/20180621\\42015974613726ea19c972405a6e0aa0.jpg', '1583045043', '已付款', '非常好！非常棒！', '1583120426');
INSERT INTO `order` VALUES ('4', '1234', '1', '教材书', '1', '30', '/shop/20180621\\79bc2a1ce2b2cd1eaac42c5487b8994e.jpg', '1583140796', '已付款', '33333', '1583214388');
INSERT INTO `order` VALUES ('5', '1234', '5', 'pengjunjie', '1', '100', '/shop/20190617\\a4bd9485a7be4e278322ae8e903514cf.png', '1583212156', '待付款', '', '0');
INSERT INTO `order` VALUES ('8', '1234', '1', '教材书', '2', '30', '/shop/20180621\\79bc2a1ce2b2cd1eaac42c5487b8994e.jpg', '1589545496', '已付款', '1111', '1589545525');

-- ----------------------------
-- Table structure for `shopcart`
-- ----------------------------
DROP TABLE IF EXISTS `shopcart`;
CREATE TABLE `shopcart` (
  `uid` int(30) NOT NULL,
  `gid` int(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `price` double(30,0) NOT NULL,
  `number` int(30) NOT NULL,
  `total` double(30,0) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`,`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shopcart
-- ----------------------------
INSERT INTO `shopcart` VALUES ('1001', '1004', '战神笔记本', '3500', '1', '3500', '/shop/20180621\\bb2b0aac29dc746764358b8dea23de0c.jpg');
INSERT INTO `shopcart` VALUES ('1001', '1002', '教材书', '30', '2', '30', '/shop/20180621\\79bc2a1ce2b2cd1eaac42c5487b8994e.jpg');
INSERT INTO `shopcart` VALUES ('1001', '1003', '阿迪达斯短袖衫', '60', '1', '60', '/shop/20180621\\414d288d9736bee2fe634d21b31e3129.jpg');
INSERT INTO `shopcart` VALUES ('1234', '1001', '李宁书包', '60', '2', '60', '/shop/20200301\\dc7770ed62fc60333bbf87f50cc7706d.jpg');

-- ----------------------------
-- Table structure for `userlist`
-- ----------------------------
DROP TABLE IF EXISTS `userlist`;
CREATE TABLE `userlist` (
  `uid` int(30) NOT NULL,
  `password` varchar(30) CHARACTER SET latin1 NOT NULL,
  `nickname` varchar(30) NOT NULL,
  `origin` varchar(30) NOT NULL,
  `money` double(30,0) NOT NULL,
  `regtime` int(11) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userlist
-- ----------------------------
INSERT INTO `userlist` VALUES ('1234', '1234', '我是1234', '四川成都', '111177', '1583120426');
INSERT INTO `userlist` VALUES ('12345', '12345', '我是12345', '四川成都', '500', '1523712515');
INSERT INTO `userlist` VALUES ('123456', '123456', '我是123456', '四川成都', '500', '1523712568');
INSERT INTO `userlist` VALUES ('123', '123', '我是123', '四川成都', '80', '1528963166');
INSERT INTO `userlist` VALUES ('1001', '1001', '朱老师', '四川眉山', '260', '1529460643');

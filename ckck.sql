/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : ck

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2018-01-30 16:35:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for hb_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `hb_admin_log`;
CREATE TABLE `hb_admin_log` (
`log_id`  int(10) NOT NULL AUTO_INCREMENT COMMENT '后台操作日志记录id' ,
`admin_name`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '管理员名称' ,
`admin_id`  int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员id' ,
`operate_target`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '操作模块' ,
`operate_ip`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '操作ip' ,
`operate_content`  longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '日志记录内容（不能记录sql）' ,
`operate_time`  datetime NULL DEFAULT NULL COMMENT '操作时间' ,
`operate_status`  tinyint(1) NOT NULL COMMENT '操作状态：1成功，2失败' ,
`remark`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注' ,
PRIMARY KEY (`log_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='后台操作日志表'
AUTO_INCREMENT=19775

;

-- ----------------------------
-- Records of hb_admin_log
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for hb_admins
-- ----------------------------
DROP TABLE IF EXISTS `hb_admins`;
CREATE TABLE `hb_admins` (
`admin_id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`admin_name`  varchar(18) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '账号' ,
`admin_nick`  varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '昵称' ,
`admin_password`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码' ,
`remember_token`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`is_super`  tinyint(1) NULL DEFAULT 0 COMMENT '1 教师  2学生  0超级管理员' ,
`remark`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注' ,
`created_at`  timestamp NULL DEFAULT NULL ,
`updated_at`  timestamp NULL DEFAULT NULL ,
`deleted_at`  timestamp NULL DEFAULT NULL ,
PRIMARY KEY (`admin_id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='管理员表'
AUTO_INCREMENT=45

;

-- ----------------------------
-- Records of hb_admins
-- ----------------------------
BEGIN;
INSERT INTO `hb_admins` VALUES ('1', 'admin', '我是管理员', '$2y$10$V0hNhpBGLJwb61FT.px.RujHomJpJhBvHg9vlTgha0pP8agVdPiI6', null, '3', '', '2017-07-25 17:11:30', '2017-12-25 11:02:07', null);
COMMIT;

-- ----------------------------
-- Table structure for hb_encrypt_token
-- ----------------------------
DROP TABLE IF EXISTS `hb_encrypt_token`;
CREATE TABLE `hb_encrypt_token` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`name`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '项目名' ,
`token`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'token值' ,
`publickey_path`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公钥路径' ,
`is_used`  tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否适用' ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='RSA加密、解密秘钥'
AUTO_INCREMENT=3

;

-- ----------------------------
-- Records of hb_encrypt_token
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for hb_migrations
-- ----------------------------
DROP TABLE IF EXISTS `hb_migrations`;
CREATE TABLE `hb_migrations` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`migration`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`batch`  int(11) NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=2

;

-- ----------------------------
-- Records of hb_migrations
-- ----------------------------
BEGIN;
INSERT INTO `hb_migrations` VALUES ('1', '2018_01_20_214434_create_sessions_table', '1');
COMMIT;

-- ----------------------------
-- Table structure for hb_permission_role
-- ----------------------------
DROP TABLE IF EXISTS `hb_permission_role`;
CREATE TABLE `hb_permission_role` (
`permission_id`  int(10) UNSIGNED NOT NULL ,
`role_id`  int(10) UNSIGNED NOT NULL ,
PRIMARY KEY (`permission_id`, `role_id`),
FOREIGN KEY (`permission_id`) REFERENCES `hb_permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`role_id`) REFERENCES `hb_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
INDEX `permission_role_role_id_foreign` (`role_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Records of hb_permission_role
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for hb_permissions
-- ----------------------------
DROP TABLE IF EXISTS `hb_permissions`;
CREATE TABLE `hb_permissions` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`display_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`description`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`pid`  int(10) NULL DEFAULT 0 COMMENT '父级ID' ,
`level`  tinyint(1) NULL DEFAULT 1 COMMENT '栏目所属层级' ,
`path`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '页面url' ,
`show`  tinyint(1) NULL DEFAULT 0 COMMENT '是否显示 0 不显示 1显示' ,
`created_at`  timestamp NULL DEFAULT NULL ,
`updated_at`  timestamp NULL DEFAULT NULL ,
PRIMARY KEY (`id`),
UNIQUE INDEX `permissions_name_unique` (`name`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of hb_permissions
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for hb_role_admin
-- ----------------------------
DROP TABLE IF EXISTS `hb_role_admin`;
CREATE TABLE `hb_role_admin` (
`admin_id`  int(10) UNSIGNED NOT NULL ,
`role_id`  int(10) UNSIGNED NOT NULL ,
PRIMARY KEY (`admin_id`, `role_id`),
FOREIGN KEY (`admin_id`) REFERENCES `hb_admins` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`role_id`) REFERENCES `hb_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
INDEX `role_user_role_id_foreign` (`role_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Records of hb_role_admin
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for hb_roles
-- ----------------------------
DROP TABLE IF EXISTS `hb_roles`;
CREATE TABLE `hb_roles` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`display_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' ,
`description`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' ,
`r_level`  int(11) NULL DEFAULT 2 COMMENT 'role等级 1 2 ' ,
`created_at`  timestamp NULL DEFAULT NULL ,
`updated_at`  timestamp NULL DEFAULT NULL ,
`deleted_at`  timestamp NULL DEFAULT NULL ,
PRIMARY KEY (`id`),
UNIQUE INDEX `roles_name_unique` (`name`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of hb_roles
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for hb_user
-- ----------------------------
DROP TABLE IF EXISTS `hb_user`;
CREATE TABLE `hb_user` (
`user_id`  int(11) NOT NULL AUTO_INCREMENT ,
`openid`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`nick_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '昵称' ,
`sex`  tinyint(1) NULL DEFAULT 2 COMMENT '性别  1 男 0 女 2 未知' ,
`mobile`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号' ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '市' ,
`province`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '省' ,
`avatarUrl`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '头像' ,
`created_at`  timestamp NULL DEFAULT NULL ,
`updated_at`  timestamp NULL DEFAULT NULL ,
`user_account`  decimal(20,2) NULL DEFAULT 0.00 COMMENT '账户余额' ,
`ip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`token`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`user_id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=27

;

-- ----------------------------
-- Records of hb_user
-- ----------------------------
BEGIN;
INSERT INTO `hb_user` VALUES ('21', '123123', 'ck', '2', null, '', '', '', '2018-01-20 21:27:30', '2018-01-22 22:35:16', '1618.64', '127.0.0.1', 'DVjcX1yC');
COMMIT;

-- ----------------------------
-- Auto increment value for hb_admin_log
-- ----------------------------
ALTER TABLE `hb_admin_log` AUTO_INCREMENT=19775;

-- ----------------------------
-- Auto increment value for hb_admins
-- ----------------------------
ALTER TABLE `hb_admins` AUTO_INCREMENT=45;

-- ----------------------------
-- Auto increment value for hb_encrypt_token
-- ----------------------------
ALTER TABLE `hb_encrypt_token` AUTO_INCREMENT=3;

-- ----------------------------
-- Auto increment value for hb_migrations
-- ----------------------------
ALTER TABLE `hb_migrations` AUTO_INCREMENT=2;

-- ----------------------------
-- Auto increment value for hb_permissions
-- ----------------------------
ALTER TABLE `hb_permissions` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for hb_roles
-- ----------------------------
ALTER TABLE `hb_roles` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for hb_user
-- ----------------------------
ALTER TABLE `hb_user` AUTO_INCREMENT=27;

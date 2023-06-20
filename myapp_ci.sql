/*
 Navicat Premium Data Transfer

 Source Server         : Lokal
 Source Server Type    : MySQL
 Source Server Version : 50539
 Source Host           : localhost:3306
 Source Schema         : myapp_ci

 Target Server Type    : MySQL
 Target Server Version : 50539
 File Encoding         : 65001

 Date: 18/06/2023 19:04:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `role_id` int(11) NULL DEFAULT NULL,
  `is_active` int(1) NULL DEFAULT NULL,
  `date_created` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'Admin', 'admin@administrator.com', 'default.jpg', '$2y$10$lGRh7Mpgn8DFCXCV99CZUu8lesBAu1/EJJ41mqJR/8Hyymgg4hzIW', 1, 1, '2023-06-10');
INSERT INTO `user` VALUES (2, 'Hasan Ali', 'hasanali@gmail.com', 'default.jpg', '$2y$10$GhFTXHoHoLUhnpA1byAsxePwNBBx3WzcKHEjEg7U5ejwalHL26O72', 2, 1, '2023-06-10');

-- ----------------------------
-- Table structure for user_access_menu
-- ----------------------------
DROP TABLE IF EXISTS `user_access_menu`;
CREATE TABLE `user_access_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NULL DEFAULT NULL,
  `menu_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user_access_menu
-- ----------------------------
INSERT INTO `user_access_menu` VALUES (1, 1, 1);
INSERT INTO `user_access_menu` VALUES (2, 1, 7);
INSERT INTO `user_access_menu` VALUES (3, 2, 7);
INSERT INTO `user_access_menu` VALUES (4, 1, 3);

-- ----------------------------
-- Table structure for user_menu
-- ----------------------------
DROP TABLE IF EXISTS `user_menu`;
CREATE TABLE `user_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user_menu
-- ----------------------------
INSERT INTO `user_menu` VALUES (1, 'Admin');
INSERT INTO `user_menu` VALUES (3, 'Menu');
INSERT INTO `user_menu` VALUES (4, 'Master Akademik');
INSERT INTO `user_menu` VALUES (7, 'User');

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role`  (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user_role
-- ----------------------------
INSERT INTO `user_role` VALUES (1, 'Administrator');
INSERT INTO `user_role` VALUES (2, 'Guru');
INSERT INTO `user_role` VALUES (3, 'Keuangan');

-- ----------------------------
-- Table structure for user_sub_menu
-- ----------------------------
DROP TABLE IF EXISTS `user_sub_menu`;
CREATE TABLE `user_sub_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `is_active` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user_sub_menu
-- ----------------------------
INSERT INTO `user_sub_menu` VALUES (1, 1, 'Dashboard', 'admin', 'bi bi-grid', 1);
INSERT INTO `user_sub_menu` VALUES (2, 7, 'My Profile', 'user', 'bi bi-person', 1);
INSERT INTO `user_sub_menu` VALUES (3, 3, 'Menu Management', 'menu', 'bi bi-menu-app', 1);
INSERT INTO `user_sub_menu` VALUES (4, 3, 'Sub Menu Management', 'menu/subMenu', 'bi bi-menu-button-fill', 1);
INSERT INTO `user_sub_menu` VALUES (5, 1, 'Role Access Management', 'admin/roleAccess', 'bi bi-key', 1);

-- ----------------------------
-- View structure for view_kelas
-- ----------------------------
DROP VIEW IF EXISTS `view_kelas`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_kelas` AS select `tk`.`kd_kelas` AS `kd_kelas`,`tk`.`nama_kelas` AS `nama_kelas`,`tk`.`kd_tingkatan` AS `kd_tingkatan`,`tk`.`kd_jurusan` AS `kd_jurusan`,`ttk`.`nama_tingkatan` AS `nama_tingkatan`,`tj`.`nama_jurusan` AS `nama_jurusan` from ((`tbl_kelas` `tk` join `tbl_tingkatan_kelas` `ttk`) join `tbl_jurusan` `tj`) where ((`tk`.`kd_tingkatan` = `ttk`.`kd_tingkatan`) and (`tk`.`kd_jurusan` = `tj`.`kd_jurusan`)) ;

-- ----------------------------
-- View structure for view_user
-- ----------------------------
DROP VIEW IF EXISTS `view_user`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_user` AS select `tu`.`id_user` AS `id_user`,`tu`.`nama_lengkap` AS `nama_lengkap`,`tu`.`username` AS `username`,`tu`.`password` AS `password`,`tu`.`id_level_user` AS `id_level_user`,`tu`.`foto` AS `foto`,`tlu`.`nama_level` AS `nama_level` from (`tbl_user` `tu` join `tbl_level_user` `tlu`) where (`tu`.`id_level_user` = `tlu`.`id_level_user`) ;

-- ----------------------------
-- View structure for view_walikelas
-- ----------------------------
DROP VIEW IF EXISTS `view_walikelas`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_walikelas` AS select `tg`.`nama_guru` AS `nama_guru`,`tk`.`nama_kelas` AS `nama_kelas`,`tw`.`id_walikelas` AS `id_walikelas`,`tw`.`id_tahun_akademik` AS `id_tahun_akademik`,`tj`.`nama_jurusan` AS `nama_jurusan`,`ttk`.`nama_tingkatan` AS `nama_tingkatan`,`tta`.`tahun_akademik` AS `tahun_akademik` from (((((`tbl_walikelas` `tw` join `tbl_kelas` `tk`) join `tbl_guru` `tg`) join `tbl_jurusan` `tj`) join `tbl_tingkatan_kelas` `ttk`) join `tbl_tahun_akademik` `tta`) where ((`tw`.`kd_kelas` = `tk`.`kd_kelas`) and (`tw`.`id_guru` = `tg`.`id_guru`) and (`tk`.`kd_jurusan` = `tj`.`kd_jurusan`) and (`tk`.`kd_tingkatan` = `ttk`.`kd_tingkatan`) and (`tw`.`id_tahun_akademik` = `tta`.`id_tahun_akademik`)) ;

SET FOREIGN_KEY_CHECKS = 1;

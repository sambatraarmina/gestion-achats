/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50736
 Source Host           : localhost:3306
 Source Schema         : gestion_achats_db

 Target Server Type    : MySQL
 Target Server Version : 50736
 File Encoding         : 65001

 Date: 24/10/2022 11:48:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_panier
-- ----------------------------
DROP TABLE IF EXISTS `t_panier`;
CREATE TABLE `t_panier`  (
  `panier_id` int(11) NOT NULL AUTO_INCREMENT,
  `panier_user` int(11) NULL DEFAULT NULL,
  `panier_montant` float NULL DEFAULT NULL,
  `panier_nbarticle` int(11) NULL DEFAULT NULL,
  `panier_montantmensuel` float NULL DEFAULT NULL,
  `panier_status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `panier_datecrea` datetime NULL DEFAULT NULL,
  `panier_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`panier_id`) USING BTREE,
  INDEX `FK_panier_user`(`panier_user`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of t_panier
-- ----------------------------
INSERT INTO `t_panier` VALUES (6, 46, 224000, 4, 74666.7, 'ENC', '2022-10-24 00:41:53', '2022-10-24 00:41:53');
INSERT INTO `t_panier` VALUES (7, 73, 14000, 2, 4666.67, 'VALID', '2022-10-24 03:56:30', '2022-10-24 03:56:30');

-- ----------------------------
-- Table structure for t_panierdetails
-- ----------------------------
DROP TABLE IF EXISTS `t_panierdetails`;
CREATE TABLE `t_panierdetails`  (
  `panierdetails_id` int(11) NOT NULL AUTO_INCREMENT,
  `panierdetails_panier` int(11) NULL DEFAULT NULL,
  `panierdetails_article` int(11) NULL DEFAULT NULL,
  `panierdetails_prix` float NULL DEFAULT NULL,
  `panierdetails_qte` int(11) NULL DEFAULT NULL,
  `panierdetails_total` float NULL DEFAULT NULL,
  `panierdetails_datecrea` datetime NULL DEFAULT NULL,
  `panierdetails_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`panierdetails_id`) USING BTREE,
  INDEX `FK_panierdetails_panier`(`panierdetails_panier`) USING BTREE,
  INDEX `FK_panierdetails_article`(`panierdetails_article`) USING BTREE,
  CONSTRAINT `FK_panierdetails_article` FOREIGN KEY (`panierdetails_article`) REFERENCES `tr_article` (`article_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FK_panierdetails_panier` FOREIGN KEY (`panierdetails_panier`) REFERENCES `t_panier` (`panier_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of t_panierdetails
-- ----------------------------
INSERT INTO `t_panierdetails` VALUES (1, 6, 4, 7000, 2, 14000, '2022-10-24 00:41:53', '2022-10-24 00:41:53');
INSERT INTO `t_panierdetails` VALUES (3, 6, 1, 100000, 1, 100000, '2022-10-24 03:44:59', '2022-10-24 03:44:59');
INSERT INTO `t_panierdetails` VALUES (4, 7, 4, 7000, 2, 14000, '2022-10-24 03:56:30', '2022-10-24 03:56:30');
INSERT INTO `t_panierdetails` VALUES (5, 6, 2, 110000, 1, 110000, '2022-10-24 10:27:06', '2022-10-24 10:27:06');

-- ----------------------------
-- Table structure for tr_article
-- ----------------------------
DROP TABLE IF EXISTS `tr_article`;
CREATE TABLE `tr_article`  (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `article_categorie` int(11) NOT NULL,
  `article_nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `article_prix` float NULL DEFAULT NULL,
  `article_unite` int(11) NULL DEFAULT NULL,
  `article_descrpition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `article_fournisseur` int(11) NULL DEFAULT NULL,
  `article_datecrea` datetime NULL DEFAULT NULL,
  `article_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`article_id`) USING BTREE,
  INDEX `FK_article_categorie`(`article_categorie`) USING BTREE,
  INDEX `FK_article_fournisseur`(`article_fournisseur`) USING BTREE,
  INDEX `FK_article_unitereference`(`article_unite`) USING BTREE,
  CONSTRAINT `FK_article_categorie` FOREIGN KEY (`article_categorie`) REFERENCES `tr_categorie` (`categorie_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FK_article_fournisseur` FOREIGN KEY (`article_fournisseur`) REFERENCES `tr_fournisseur` (`fournisseur_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FK_article_unitereference` FOREIGN KEY (`article_unite`) REFERENCES `tr_unitereference` (`unitereference_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tr_article
-- ----------------------------
INSERT INTO `tr_article` VALUES (1, 1, 'Riz Tsipala', 100000, 1, NULL, 1, '2022-10-21 09:51:16', '2022-10-21 09:51:20');
INSERT INTO `tr_article` VALUES (2, 1, 'Riz Gasy\r\n', 110000, 1, NULL, 1, '2022-10-21 09:52:02', '2022-10-21 09:52:05');
INSERT INTO `tr_article` VALUES (3, 1, 'Riz de lux\r\n', 110000, 1, NULL, 1, '2022-10-21 09:52:36', '2022-10-21 09:52:39');
INSERT INTO `tr_article` VALUES (4, 2, 'Huile Soja Hina 1L\r\n', 7000, 2, NULL, 1, '2022-10-21 09:53:29', '2022-10-21 09:53:31');

-- ----------------------------
-- Table structure for tr_categorie
-- ----------------------------
DROP TABLE IF EXISTS `tr_categorie`;
CREATE TABLE `tr_categorie`  (
  `categorie_id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie_nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `categorie_datecrea` datetime NULL DEFAULT NULL,
  `categorie_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`categorie_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tr_categorie
-- ----------------------------
INSERT INTO `tr_categorie` VALUES (1, 'Riz\r\n', '2022-10-20 11:52:31', '2022-10-20 11:52:35');
INSERT INTO `tr_categorie` VALUES (2, 'Huile', '2022-10-20 11:52:47', '2022-10-20 11:52:50');
INSERT INTO `tr_categorie` VALUES (3, 'Conserve', '2022-10-20 11:53:03', '2022-10-20 11:53:06');
INSERT INTO `tr_categorie` VALUES (4, 'Pates ', '2022-10-20 11:53:26', '2022-10-20 11:53:29');

-- ----------------------------
-- Table structure for tr_fournisseur
-- ----------------------------
DROP TABLE IF EXISTS `tr_fournisseur`;
CREATE TABLE `tr_fournisseur`  (
  `fournisseur_id` int(11) NOT NULL AUTO_INCREMENT,
  `fournisseur_nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `fournisseur_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `fournisseur_adresse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `fournisseur_datecrea` datetime NULL DEFAULT NULL,
  `fournisseur_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`fournisseur_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tr_fournisseur
-- ----------------------------
INSERT INTO `tr_fournisseur` VALUES (1, 'Mme Lalatiana', '034 09 863 91\r\n', 'Ampandrana\r\n', '2022-10-20 11:41:20', '2022-10-20 11:41:23');

-- ----------------------------
-- Table structure for tr_role
-- ----------------------------
DROP TABLE IF EXISTS `tr_role`;
CREATE TABLE `tr_role`  (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `role_datecrea` datetime NULL DEFAULT NULL,
  `role_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of tr_role
-- ----------------------------
INSERT INTO `tr_role` VALUES (1, 'Admin', '2022-02-01 19:16:13', '2022-02-01 19:16:15');
INSERT INTO `tr_role` VALUES (2, 'Agent', '2022-02-01 19:16:27', '2022-02-01 19:16:29');

-- ----------------------------
-- Table structure for tr_site
-- ----------------------------
DROP TABLE IF EXISTS `tr_site`;
CREATE TABLE `tr_site`  (
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `site_datecrea` datetime NULL DEFAULT NULL,
  `site_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`site_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of tr_site
-- ----------------------------
INSERT INTO `tr_site` VALUES (1, 'SETEX', '2022-03-01 10:17:13', '2022-03-01 10:17:16');
INSERT INTO `tr_site` VALUES (2, 'MONTECRISTO', '2022-03-01 10:17:34', '2022-03-01 10:17:38');
INSERT INTO `tr_site` VALUES (3, 'LA TONELLE', '2022-04-01 08:04:57', '2022-04-01 08:04:57');
INSERT INTO `tr_site` VALUES (4, 'MAETRA', '2022-04-01 08:07:50', '2022-04-01 08:07:50');

-- ----------------------------
-- Table structure for tr_statuscommande
-- ----------------------------
DROP TABLE IF EXISTS `tr_statuscommande`;
CREATE TABLE `tr_statuscommande`  (
  `statuscommande_id` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `statuscommande_libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `statuscommande_datecrea` datetime NULL DEFAULT NULL,
  `statuscommande_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`statuscommande_id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tr_statuscommande
-- ----------------------------
INSERT INTO `tr_statuscommande` VALUES ('ENC', 'En cours', '2022-10-24 07:16:09', '2022-10-24 07:16:12');
INSERT INTO `tr_statuscommande` VALUES ('ANNUL', 'Annulée', '2022-10-24 07:17:56', '2022-10-24 07:17:59');
INSERT INTO `tr_statuscommande` VALUES ('VALID', 'Validée', '2022-10-24 07:18:07', '2022-10-24 07:18:09');
INSERT INTO `tr_statuscommande` VALUES ('TERM', 'Terminée', '2022-10-24 07:18:18', '2022-10-24 07:18:20');

-- ----------------------------
-- Table structure for tr_unitereference
-- ----------------------------
DROP TABLE IF EXISTS `tr_unitereference`;
CREATE TABLE `tr_unitereference`  (
  `unitereference_id` int(11) NOT NULL AUTO_INCREMENT,
  `unitereference_nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `unitereference_datecrea` datetime NULL DEFAULT NULL,
  `unitereference_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`unitereference_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tr_unitereference
-- ----------------------------
INSERT INTO `tr_unitereference` VALUES (1, 'sac', '2022-10-21 09:50:49', '2022-10-21 09:50:52');
INSERT INTO `tr_unitereference` VALUES (2, 'pièce', '2022-10-21 09:50:56', '2022-10-21 09:50:59');

-- ----------------------------
-- Table structure for tr_user
-- ----------------------------
DROP TABLE IF EXISTS `tr_user`;
CREATE TABLE `tr_user`  (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `usr_nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `usr_prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `usr_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `usr_matricule` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `usr_initiale` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `usr_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `usr_dateembauche` datetime NULL DEFAULT NULL,
  `usr_role` int(11) NULL DEFAULT NULL,
  `usr_actif` bit(1) NULL DEFAULT b'1',
  `usr_site` int(11) NULL DEFAULT NULL,
  `usr_ingress` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `usr_datecrea` datetime NULL DEFAULT NULL,
  `usr_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`usr_id`) USING BTREE,
  INDEX `tr_user_ibfk_1`(`usr_role`) USING BTREE,
  INDEX `tr_user_ibfk_2`(`usr_site`) USING BTREE,
  CONSTRAINT `tr_user_ibfk_1` FOREIGN KEY (`usr_role`) REFERENCES `tr_role` (`role_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tr_user_ibfk_2` FOREIGN KEY (`usr_site`) REFERENCES `tr_site` (`site_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of tr_user
-- ----------------------------
INSERT INTO `tr_user` VALUES (1, 'admin', 'admin', 'admin', 'ec193d0ab40f1268b1d53a057ca5a270', '1', 'ADM', 'admin@setex.mg', '2022-06-10 10:19:17', 1, b'1', 1, NULL, '2022-06-10 10:19:36', '2022-06-10 10:19:39');

SET FOREIGN_KEY_CHECKS = 1;

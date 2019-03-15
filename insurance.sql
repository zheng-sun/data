-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        5.7.14 - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 导出 insurance 的数据库结构
CREATE DATABASE IF NOT EXISTS `insurance` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `insurance`;

-- 导出  表 insurance.project 结构
CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project` varchar(500) NOT NULL COMMENT '项目名称',
  `big_classification` varchar(500) NOT NULL COMMENT '大分类',
  `middle_classification` varchar(500) NOT NULL COMMENT '中分类',
  `small_classification` varchar(500) NOT NULL COMMENT '小分类',
  `code` varchar(500) NOT NULL COMMENT '编码',
  `name` varchar(500) NOT NULL COMMENT '职业名称',
  `type` varchar(50) NOT NULL COMMENT '职业分类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=183 DEFAULT CHARSET=utf8 COMMENT='保险项目职业分类表';

-- 数据导出被取消选择。
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2022-05-26 07:39:28
-- 服务器版本： 5.7.37-log
-- PHP 版本： 8.0.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `yiitest`
--

-- --------------------------------------------------------

--
-- 表的结构 `supplier`
--

CREATE TABLE `supplier` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET ascii NOT NULL,
  `code` char(3) DEFAULT NULL,
  `t_status` enum('ok','hold') NOT NULL DEFAULT 'ok'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `code`, `t_status`) VALUES
(1, 'supplier1', 'su1', 'ok'),
(2, 'supplier2', 'su2', 'ok'),
(3, 'supplier3', 'su3', 'hold'),
(4, 'supplier4', 'su4', 'ok'),
(5, 'supplier5', 'su5', 'hold'),
(6, 'supplier6', 'su6', 'ok'),
(7, 'supplier7', 'su7', 'hold'),
(8, 'supplier8', 'su8', 'ok'),
(9, 'supplier9', 'su9', 'hold'),
(10, 'supplier10', 's10', 'hold'),
(11, 'supplier11', 's11', 'ok'),
(12, 'supplier12', 's12', 'hold');

--
-- 转储表的索引
--

--
-- 表的索引 `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_code` (`code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

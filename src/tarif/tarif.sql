-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- Хост: localhost
-- Время создания: Июн 17 2013 г., 02:04
-- Версия сервера: 5.0.51
-- Версия PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- База данных: `ucheb`
-- 

-- --------------------------------------------------------

-- 
-- Структура таблицы `tarif`
-- 

CREATE TABLE `tarif` (
  `fio` varchar(255) NOT NULL,
  `ekz_budg` int(10) unsigned default '0' COMMENT 'Экзамен бюджет',
  `ekz_nbudg` int(10) unsigned default '0' COMMENT 'Экзамен внебюджет',
  `tarif_budg` int(10) unsigned default '0' COMMENT 'Тарификация бюджет',
  `tarif_nbudg` int(10) unsigned default '0' COMMENT 'Тарификация внебюджет',
  `pred_budg` int(10) unsigned default '0' COMMENT 'Изменение преднагрузки бюджет',
  `pred_nbudg` int(10) unsigned default '0' COMMENT 'Изменение преднагрузки внебюджет',
  `perc_budg` int(10) unsigned default '0' COMMENT '5% праздничных бюджет',
  `perc_nbudg` int(10) unsigned default '0' COMMENT '5%праздничных внебюджет',
  `not_budg` int(10) unsigned default '0' COMMENT 'Не выполнено бюджет',
  `not_nbudg` int(10) unsigned default '0' COMMENT 'Не выполнено внебюджет'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Дамп данных таблицы `tarif`
-- 


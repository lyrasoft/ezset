
CREATE TABLE IF NOT EXISTS `#__ezset_addons` (
  `id` int(11) NOT NULL,
  `name` char(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `manifest` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `params` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `protect` tinyint(1) NOT NULL,
  `path` varchar(255) NOT NULL,
  `client` char(20) NOT NULL,
  `access` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

ALTER TABLE `#__ezset_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state` (`state`),
  ADD KEY `element` (`name`),
  ADD KEY `access` (`access`);

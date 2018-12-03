# bbs-practice

サンプル
https://vast-dawn-19775.herokuapp.com/
--
-- テーブルの構造 `post`
--

CREATE TABLE `post` (
  `id` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `name` varchar(10) NOT NULL,
  `text` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  `color` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `fname` varchar(1000) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `thread`
--

CREATE TABLE `thread` (
  `id` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `number` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `text` text NOT NULL,
  `color` text NOT NULL,
  `time` date NOT NULL,
  `fname` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `id` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fname` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `vic`
--

-- --------------------------------------------------------

--
-- Структура таблицы `test_table1`
--

CREATE TABLE IF NOT EXISTS `test_table1` (
`id` int(11) NOT NULL,
  `test_text` text NOT NULL,
  `test_num` int(11) NOT NULL,
  `tinyint` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `test_table2`
--

CREATE TABLE IF NOT EXISTS `test_table2` (
  `id` int(11) NOT NULL,
  `test1` text NOT NULL,
  `test2` text NOT NULL,
  `test3` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `test_table1`
--
ALTER TABLE `test_table1`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `test_table1`
--
ALTER TABLE `test_table1`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
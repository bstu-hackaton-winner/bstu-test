-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 31 2023 г., 09:48
-- Версия сервера: 8.0.19
-- Версия PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `bstu`
--

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `value` text,
  `date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Site logs';

-- --------------------------------------------------------

--
-- Структура таблицы `media`
--

CREATE TABLE `media` (
  `id` int NOT NULL,
  `name` text,
  `file` text,
  `type` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `media`
--

INSERT INTO `media` (`id`, `name`, `file`, `type`) VALUES
(1, 'Default subscription', 'default.png', 'png'),
(5, '123', '8mVcimC8TMw.jpg', 'jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `offline_answers`
--

CREATE TABLE `offline_answers` (
  `id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `answers` json NOT NULL,
  `questions` json NOT NULL,
  `user_agent` text,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `quiz_sign` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `offline_answers`
--

INSERT INTO `offline_answers` (`id`, `quiz_id`, `answers`, `questions`, `user_agent`, `date`, `quiz_sign`) VALUES
(1, 9, '[\"123\", 1, 0]', '[{\"time\": 22, \"answers\": [], \"is_free\": true, \"question\": \"Вопрос номер 1&quot;&quot;&quot;\"}, {\"time\": 30, \"answers\": [\"Ответ 1&quot;&quot;\", \"Ответ 2\", \"Ответ 3\"], \"is_free\": false, \"question\": \"Вопрос номер 2\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 2\"], \"is_free\": false, \"question\": \"Вопрос номер 3\"}]', 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.1.2 Mobile/15E148 Safari/604.1', '2021-07-20 14:59:24', '6fd8c6bc93f6dba92884c2b62ce234f0'),
(2, 9, '[\"3123123\", 0, 1]', '[{\"time\": 22, \"answers\": [], \"is_free\": true, \"question\": \"Вопрос номер 1&quot;&quot;&quot;\"}, {\"time\": 30, \"answers\": [\"Ответ 1&quot;&quot;\", \"Ответ 2\", \"Ответ 3\"], \"is_free\": false, \"question\": \"Вопрос номер 2\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 2\"], \"is_free\": false, \"question\": \"Вопрос номер 3\"}]', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_1 rv:2.0) Gecko/20130428 Firefox/36.0', '2021-07-20 15:00:22', '6fd8c6bc93f6dba92884c2b62ce234f0'),
(3, 9, '[\"Привет\", 1, 0]', '[{\"time\": 22, \"answers\": [], \"is_free\": true, \"question\": \"Вопрос номер 1&quot;&quot;&quot;\"}, {\"time\": 30, \"answers\": [\"Ответ 1&quot;&quot;\", \"Ответ 2\", \"Ответ 3\"], \"is_free\": false, \"question\": \"Вопрос номер 2\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 2\"], \"is_free\": false, \"question\": \"Вопрос номер 3\"}]', 'Mozilla/5.0 (Windows; U; Windows NT 6.2) AppleWebKit/532.37.3 (KHTML, like Gecko) Version/4.0.3 Safari/532.37.3', '2021-07-20 16:07:49', '6fd8c6bc93f6dba92884c2b62ce234f0'),
(4, 9, '[\"123\", 1, 0]', '[{\"time\": 22, \"answers\": [], \"is_free\": true, \"question\": \"Вопрос номер 1&quot;&quot;&quot;\"}, {\"time\": 30, \"answers\": [\"Ответ 1&quot;&quot;\", \"Ответ 2\", \"Ответ 3\"], \"is_free\": false, \"question\": \"Вопрос номер 2\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 2\"], \"is_free\": false, \"question\": \"Вопрос номер 3\"}]', 'Mozilla/5.0 (Linux; U; Android 10; ru-ru; Redmi 9 Build/QP1A.190711.020) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/71.0.3578.141 Mobile Safari/537.36 XiaoMi/MiuiBrowser/12.4.1-g', '2021-07-20 16:21:46', '6fd8c6bc93f6dba92884c2b62ce234f0'),
(5, 9, '[\"Все понравилось\", 4, 1]', '[{\"time\": 22, \"answers\": [], \"is_free\": true, \"question\": \"Что вам понравилось в нашем заведении\"}, {\"time\": 30, \"answers\": [\"1 (Ужастно)\", \"2 (Плохо)\", \"3 (Удовлетворимо)\", \"4 (Хорошо)\", \"5 (Отлично)\"], \"is_free\": false, \"question\": \"Оцените работу персонала по 5 балльной шкале\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 2\"], \"is_free\": false, \"question\": \"Вопрос номер 3\"}]', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:90.0) Gecko/20100101 Firefox/90.0', '2021-07-30 00:00:17', 'bf5cd1a8a483a978539a3bd16c45b58c'),
(6, 9, '[\"Привет мир\", 3, 0]', '[{\"time\": 22, \"answers\": [], \"is_free\": true, \"question\": \"Что вам понравилось в нашем заведении\"}, {\"time\": 30, \"answers\": [\"1 (Ужастно)\", \"2 (Плохо)\", \"3 (Удовлетворимо)\", \"4 (Хорошо)\", \"5 (Отлично)\"], \"is_free\": false, \"question\": \"Оцените работу персонала по 5 балльной шкале\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 2\"], \"is_free\": false, \"question\": \"Вопрос номер 3\"}]', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:90.0) Gecko/20100101 Firefox/90.0', '2021-07-30 00:04:40', 'bf5cd1a8a483a978539a3bd16c45b58c'),
(7, 9, '[\"123\", 0, 0]', '[{\"time\": 22, \"answers\": [], \"is_free\": true, \"question\": \"Что вам понравилось в нашем заведении\"}, {\"time\": 30, \"answers\": [\"1 (Ужасно)\", \"2 (Плохо)\", \"3 (Удовлетворимо)\", \"4 (Хорошо) 5\"], \"is_free\": false, \"question\": \"Оцените работу персонала по 5 балльной шкале\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 2\"], \"is_free\": false, \"question\": \"Вопрос номер 3\"}]', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', '2023-07-30 14:53:02', '40d091c47b1c253f56d05f5ed0679bad'),
(8, 9, '[\"123\", 2, 0]', '[{\"time\": 22, \"answers\": [], \"is_free\": true, \"question\": \"Что вам понравилось в нашем заведении\"}, {\"time\": 30, \"answers\": [\"1 (Ужасно)\", \"2 (Плохо)\", \"3 (Удовлетворимо)\", \"4 (Хорошо) 5\"], \"is_free\": false, \"question\": \"Оцените работу персонала по 5 балльной шкале\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 2\"], \"is_free\": false, \"question\": \"Вопрос номер 3\"}]', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', '2023-07-30 16:08:57', '40d091c47b1c253f56d05f5ed0679bad'),
(9, 9, '[\"гшнгш\", 1, 0]', '[{\"time\": 22, \"answers\": [], \"is_free\": true, \"question\": \"Что вам понравилось в нашем заведении\"}, {\"time\": 30, \"answers\": [\"1 (Ужасно)\", \"2 (Плохо)\", \"3 (Удовлетворимо)\", \"4 (Хорошо) 5\"], \"is_free\": false, \"question\": \"Оцените работу персонала по 5 балльной шкале\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 2\"], \"is_free\": false, \"question\": \"Вопрос номер 3\"}]', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', '2023-07-30 16:33:19', '40d091c47b1c253f56d05f5ed0679bad'),
(10, 14, '[1]', '[{\"time\": 30, \"answers\": [\"T[0] = 1\", \"T = (1) + T[1:]\", \"T = (1,) + T[1:]\", \"T.startswith(1)\"], \"is_free\": false, \"question\": \"Имеется кортеж вида T = (4, 2, 3). Какая из операций приведёт к тому, что имя T будет ссылаться на кортеж (1, 2, 3)?\"}]', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', '2023-07-31 09:18:15', '3928e2d649c3fc8abf1a7cd5f4bbad84');

-- --------------------------------------------------------

--
-- Структура таблицы `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int NOT NULL,
  `owner_id` int NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `lite_mode` tinyint(1) DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `questions` json NOT NULL COMMENT 'Always must to contain:\n-> string question\n-> array answers\n-> int time in seconds for question\n\nExample: [{"question": "Test", "answers": ["Yes", "No"], "time": "30"}]',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 - отключен, 1 - работает',
  `type` int NOT NULL DEFAULT '0' COMMENT '0 - онлайн, 1 - оффлайн',
  `seo_link` varchar(15) DEFAULT NULL,
  `terminal_link` varchar(5) DEFAULT NULL,
  `deactivation_date` int DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `quizzes`
--

INSERT INTO `quizzes` (`id`, `owner_id`, `name`, `description`, `lite_mode`, `created`, `questions`, `active`, `type`, `seo_link`, `terminal_link`, `deactivation_date`) VALUES
(4, 1, 'Тестовый опрос', 'Опрос для тестового подключения', 0, '2021-06-18 11:47:43', '[{\"time\": 30, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\"], \"question\": \"Тестовый вопрос\"}, {\"time\": 100, \"answers\": [\"Да\", \"Нет\", \"Ответ 3\", \"Ответ 4\", \"Ответ 5\", \"Ответ 6\"], \"question\": \"Тестовый вопрос 2\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 440\"], \"question\": \"Вопрос номер 3\"}]', 1, 0, NULL, 'ffeer', -1),
(9, 1, 'Офлайн опрос', 'Тестовый офлайн опрос', 0, '2021-07-18 17:29:31', '[{\"time\": 22, \"answers\": [], \"is_free\": true, \"question\": \"Что вам понравилось в нашем заведении\"}, {\"time\": 30, \"answers\": [\"1 (Ужасно)\", \"2 (Плохо)\", \"3 (Удовлетворимо)\", \"4 (Хорошо) 5\"], \"is_free\": false, \"question\": \"Оцените работу персонала по 5 балльной шкале\"}, {\"time\": 30, \"answers\": [\"Ответ 1\", \"Ответ 2\"], \"is_free\": false, \"question\": \"Вопрос номер 3\"}]', 1, 1, 'quiztest', 'dsfer', 1753308960),
(12, 2, 'Новый опрос', '', 0, '2021-07-30 03:13:53', '[]', 1, 1, 'KdLzE', 'NnLXm', -1),
(13, 1, 'Новый опрос', '', 0, '2023-07-30 15:53:43', '[]', 1, 0, 'Rivkf', 'REcYv', -1),
(14, 1, 'Тест на знание Python', 'Пришло время узнать, как хорошо вы разбираетесь в Python. Проверьте свои знания в нашем тесте из 15 вопросов по различным аспектам языка. Сможете правильно ответить на все вопросы?\r\n', 0, '2023-07-31 09:12:56', '[{\"time\": 30, \"answers\": [\"T[0] = 1\", \"T = (1) + T[1:]\", \"T = (1,) + T[1:]\", \"T.startswith(1)\"], \"is_free\": false, \"question\": \"Имеется кортеж вида T = (4, 2, 3). Какая из операций приведёт к тому, что имя T будет ссылаться на кортеж (1, 2, 3)?\"}]', 1, 1, 'uTOEF', 'wXonq', -1);

-- --------------------------------------------------------

--
-- Структура таблицы `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int NOT NULL,
  `name` text NOT NULL,
  `display_name` text NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `site_settings`
--

INSERT INTO `site_settings` (`id`, `name`, `display_name`, `value`) VALUES
(1, 'site_name_option', 'Название сайта', 'BSTU-Test'),
(2, 'captcha_public_option', 'Публичный ключ ReCaptcha', '6LeqU3gbAAAAAF2otl0rTgut2cA4RIRdUjYvYgYL'),
(3, 'captcha_private_option', 'Секретный ключ ReCaptcha', '6LeqU3gbAAAAAHUmpScey2KOenEGorYXMXT5k432');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(32) NOT NULL,
  `passwd` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(64) NOT NULL,
  `phone` text,
  `activation` varchar(43) DEFAULT NULL,
  `recovery_token` varchar(32) DEFAULT NULL,
  `subscription_type` int NOT NULL DEFAULT '0',
  `subscription_end` int NOT NULL DEFAULT '0',
  `account_type` int NOT NULL DEFAULT '0',
  `admin` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0' COMMENT '0 - Активен\n1 - Заблокирован',
  `auth_token` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `passwd`, `name`, `phone`, `activation`, `recovery_token`, `subscription_type`, `subscription_end`, `account_type`, `admin`, `status`, `auth_token`) VALUES
(1, 'test@mail.ru', '202cb962ac59075b964b07152d234b70', 'Павел Павловв', '88005553536', '', '', 3, 1658970060, 0, 1, 0, 'b28c3aaa6f5829d95afc0a584d379d8f'),
(2, 'te1st@mail.ru', '9758153d75c6e57a87d3d96ea4eae1ac', 'Some Body', '', '', NULL, 2, 1658846220, 0, 0, 0, '4feb2be9d72796423df8bef5daabb50f'),
(3, 'te231st@mail.ru', 'e10adc3949ba59abbe56e057f20f883e', 'Иван Иванов', '', '5c3f7e68e4c7facef39bb0af2048a35f_RJIGVKTJRJ', NULL, 0, 0, 1, 0, 0, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `offline_answers`
--
ALTER TABLE `offline_answers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quizzes_terminal_link_uindex` (`terminal_link`);

--
-- Индексы таблицы `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auth_token` (`auth_token`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `media`
--
ALTER TABLE `media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `offline_answers`
--
ALTER TABLE `offline_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

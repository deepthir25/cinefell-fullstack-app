-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2025 at 06:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



--
-- Database: `cinefeel`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--


INSERT INTO `favorites` (`favorite_id`, `user_id`, `movie_id`, `added_at`) VALUES
(18, 1, 7, '2025-08-10 03:46:18'),
(19, 1, 11, '2025-08-10 03:56:01');

-- --------------------------------------------------------

--
-- Table structure for table `moods`
--

CREATE TABLE `moods` (
  `mood_id` int(11) NOT NULL,
  `mood_name` varchar(50) NOT NULL,
  `mapped_genre` varchar(50) NOT NULL,
  `emoji` varchar(10) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moods`
--

INSERT INTO `moods` (`mood_id`, `mood_name`, `mapped_genre`, `emoji`, `description`) VALUES
(1, 'Happy', 'Comedy', '😊', 'Light-hearted and fun movies'),
(2, 'Sad', 'Drama', '😢', 'Emotional and touching stories'),
(3, 'Excited', 'Action', '🤩', 'High-energy thrillers'),
(4, 'Calm', 'Documentary', '😌', 'Peaceful and informative films'),
(5, 'Romantic', 'Romance', '🥰', 'Love stories and romantic comedies'),
(6, 'Scared', 'Horror', '😱', 'Spooky and thrilling movies');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `genre` varchar(50) NOT NULL,
  `mood_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  `trailer_link` varchar(255) DEFAULT NULL,
  `release_year` int(11) DEFAULT NULL,
  `duration` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `genre`, `mood_id`, `description`, `poster_url`, `trailer_link`, `release_year`, `duration`) VALUES
(6, 'Pather Panchali', 'Drama', 4, 'A poetic debut by Satyajit Ray, capturing rural Bengali life with lyrical realism.', 'https://upload.wikimedia.org/wikipedia/en/7/77/Pather_panchali_poster_in_color_1.jpg', '', 1955, '125min'),
(7, 'The Lunchbox', 'Calm-Romantic Drama', 4, 'An Indian film where a mistaken delivery in Mumbai&#039;s famous lunchbox system leads to an unusual friendship between a lonely housewife and an older man on the verge of retirement. It&#039;s a beautiful exploration of connection and longing.', 'https://th.bing.com/th/id/OIP.7WxVVRvm0MHjxZ4R8YXE3wHaLH?w=122&amp;h=183&amp;c=7&amp;r=0&amp;o=7&amp;dpr=1.3&amp;pid=1.7&amp;rm=3', 'https://www.youtube.com/watch?v=sK3R0rvnlPs', 2013, '1hr 73min'),
(8, 'Amélie', 'Quirky Romance', 5, 'rench director Jean-Pierre Jeunet is known for his unique visual style, and 2001&#039;s Amelie is a marvel of sight and sound, in addition to being a wonderfully whimsical romance film. Audrey Tautou stars in the title role, a waitress in Paris whose sheltered upbringing had starved her of human connection for much of her early years.\r\n\r\nShe devotes her time to discreetly helping those around her while waiting for her own dreams of romance to come true. Tautou is fantastic in the film, which was a massive global hit, grossing over $170 million worldwide.', 'https://static1.srcdn.com/wordpress/wp-content/uploads/2021/01/Amelie.jpg?q=50&amp;fit=crop&amp;w=800&amp;dpr=1.5', '', 2001, '2hr 33min'),
(9, 'In the Mood for Love', 'Calm‑Romantic', 5, 'Set in 1960s Hong Kong, this film explores the lives of two neighbors who bond over the infidelity of their respective spouses. Their growing affection for each other is portrayed with exquisite restraint and visual poetry.', 'https://tse3.mm.bing.net/th/id/OIP._xlnShg9nlGEt65AxJbIcgHaJ2?rs=1&amp;pid=ImgDetMain&amp;o=7&amp;rm=3', 'https://www.youtube.com/watch?v=m8GuedsQnWQ', 2000, '1hr 63min'),
(10, 'Call Me by Your Name', 'Calm-Romantic Drama', 4, 'It’s the summer of 1983 in the north of Italy, and Elio Perlman (Timothée Chalamet), a precocious 17- year-old American-Italian boy, spends his days in his family’s 17th century villa transcribing and playing classical music, reading, and flirting with his friend Marzia (Esther Garrel).\r\nElio enjoys a close relationship with his father (Michael Stuhlbarg), an eminent professor specializing in Greco-Roman culture, and his mother Annella (Amira Casar), a translator, who favor him with the fruits of high culture in a setting that overflows with natural delights. While Elio’s sophistication and intellectual gifts suggest he is already a fully-fledged adult, there is much that yet remains innocent and unformed about him, particularly about matters of the heart.\r\nOne day, Oliver (Armie Hammer), a charming American scholar working on his doctorate, arrives as the annual summer intern tasked with helping Elio’s father. Amid the sun-drenched\r\nsplendor of the setting, Elio and Oliver discover the heady beauty of awakening desire over the course of a summer that will alter their lives forever', 'https://image.tmdb.org/t/p/original/tcNniniS4rfqrLH0oORikJfnIwY.jpg', 'https://www.youtube.com/watch?v=Z9AYPxH5NTM', 2017, '2hr 2min'),
(11, '3 Idiots', 'Comedy -Drama', 1, '3 Idiots Is An Award Winning Bollywood Comedy Movie, Directed By Rajkumar Hirani, Starring Aamir Khan And Kareena Kapoor In The Lead Roles. The Film Won 35 Awards. In College, Farhan And Raju Form A Great Bond With Rancho Due To His Positive And Refreshing Outlook To Life. Years Later, A Bet Gives Them A Chance To Look For Their Long-lost Friend Whose Existence Seems Rather Elusive.', 'https://tse1.mm.bing.net/th/id/OIP.Xnku5BaoIvmWY-EEYlKdiwAAAA?rs=1&amp;pid=ImgDetMain&amp;o=7&amp;rm=3', 'https://www.youtube.com/watch?v=K0eDlFX9GMc', 2009, '2hr 83min'),
(12, 'Queen', 'Romantic -Comedy', 1, 'What happens when a simple, conservative Delhi girl gets dumped the day before her wedding? In the case of Rani, she uses the opportunity to go on her honeymoon anyway, setting off on a hilarious rollercoaster adventure through Paris and Amsterdam!', 'https://images.justwatch.com/poster/139213530/s718', 'https://www.youtube.com/watch?v=M_HP8xgXhBU', 2013, '2hr 43mn'),
(13, 'Barfi!', 'Romantic Comedy-Drama', 1, 'A sweet, inclusive tale of love and innocence.', 'https://mir-s3-cdn-cf.behance.net/project_modules/1400/4638eb98043521.5ed3715a7f9be.jpg', 'https://www.youtube.com/watch?v=3YUZ4dDCLXw', 2012, '150 min'),
(14, 'Zindagi Na Milegi Dobara', 'Coming-of-age Comedy-Drama', 1, 'A heartfelt road-trip of self-discovery and friendship.', 'https://m.media-amazon.com/images/M/MV5BMzQzMTA4ODY4OF5BMl5BanBnXkFtZTcwNjgyMDQxNw@@._V1_.jpg', 'https://www.youtube.com/watch?v=FJrpcDgC3zU', 2011, '155 min'),
(15, 'La La Land', 'Musical Romance', 2, 'Dreamers chasing love and art in modern-day LA.', 'https://upload.wikimedia.org/wikipedia/en/a/ab/La_La_Land_%28film%29.png', 'https://www.youtube.com/watch?v=0pdqf4P9MB8', 2016, '128 min'),
(16, 'Dilwale Dulhania Le Jayenge', 'Romantic Comedy-Drama', 2, 'Bollywood’s most enduring love story across generations.', 'https://upload.wikimedia.org/wikipedia/en/8/80/Dilwale_Dulhania_Le_Jayenge_poster.jpg', 'https://www.youtube.com/watch?v=c25GKl5VNeY', 1995, '189 min'),
(17, 'Jab We Met', 'Romantic Comedy', 2, 'Love and laughter converge on a journey of two lively strangers.', 'https://socialpakora.com/wp-content/uploads/2022/01/maxresdefault.jpg', 'https://www.youtube.com/watch?v=y3BHvxXGBO4', 2007, '138 min'),
(18, 'Hasee Toh Phasee', 'Romantic Drama', 2, 'Quirky chemistry and emotional arc between two unconventional lovers.', 'https://image.tmdb.org/t/p/original/vDvYBzir4QazHAjxDGxFkjdfgxo.jpg', 'https://www.youtube.com/watch?v=4gYjK5ySgGk', 2014, '137 min'),
(19, 'Alaipayuthey', 'Romantic Drama', 3, 'Tamil love, passion, and marital challenges with memorable visuals and music.', 'https://tse3.mm.bing.net/th/id/OIP.KXcoWqS0zaW9mo2wJSbrowHaLH?rs=1&amp;pid=ImgDetMain&amp;o=7&amp;rm=3', 'https://www.youtube.com/watch?v=_sG4UYReorw', 2000, '151 min'),
(20, 'Vinnaithaandi Varuvaayaa', 'Romantic Drama', 3, 'A cross-cultural Indian romance marked by longing and melody.', 'https://images.justwatch.com/poster/250689377/s718/vinnaithaandi-varuvaayaa.jpg', 'https://www.youtube.com/watch?v=5nGRqHeaEtA', 2010, '153 min'),
(21, 'The Notebook', 'Romantic Drama', 4, 'Love tested across decades—timeless tears guaranteed.', 'https://upload.wikimedia.org/wikipedia/en/8/86/Posternotebook.jpg', 'https://www.youtube.com/watch?v=FC6biTjEyZw', 2004, '123 min'),
(22, 'La Vie d’Adèle (Blue Is the Warmest Color)', 'Romantic Drama', 4, 'Intense, raw exploration of a passionate and transformative relationship.', 'https://tse4.mm.bing.net/th/id/OIP.PtFfgHlQYG9OapmKlcpc2wHaJ4?rs=1&amp;pid=ImgDetMain&amp;o=7&amp;rm=3', 'https://www.youtube.com/watch?v=Y2OLRrocn3s', 2013, '180 min'),
(23, 'Life Is Beautiful (La Vita è Bella)', 'Romantic Tragicomedy', 4, 'A father shields his family with humor amid wartime tragedy.', 'https://upload.wikimedia.org/wikipedia/en/7/7c/Vitaebella.jpg', 'https://www.youtube.com/watch?v=pAYEQP8gx3w', 1997, '116 min'),
(24, 'Cinema Paradiso', 'Romantic Drama', 5, 'A boy’s lifelong love for cinema intertwines with his first love.', 'https://m.media-amazon.com/images/M/MV5BM2FhYjEyYmYtMDI1Yy00YTdlLWI2NWQtYmEzNzAxOGY1NjY2XkEyXkFqcGdeQXVyNTA3NTIyNDg@._V1_.jpg', 'https://www.youtube.com/watch?v=C2-GX0Tltgw', 1988, '155 min'),
(25, 'Ek Main Aur Ekk Tu', 'Romantic Comedy', 2, 'A spontaneous Vegas marriage leads to an unconventional, witty romance between two mismatched souls.', 'https://im.indiatimes.in/video/2012/Jan/261088_239172546119646_1355967151_n_cr_1326786012.jpg', 'https://www.youtube.com/watch?v=wI2hljDJmOQ', 2012, '111 min'),
(27, 'Jaane Tu Ya Jaane Na', 'Romantic Comedy', 1, 'Two college friends realize they are in love after a series of misunderstandings.', 'https://tse2.mm.bing.net/th/id/OIP.NgbNNEC3qhTSAxjNSObk1QHaDt?rs=1&amp;pid=ImgDetMain&amp;o=7&amp;rm=3', 'https://www.youtube.com/watch?v=8t2xjXoI7a8', 2008, '150 min'),
(28, 'Dil Dhadakne Do', 'Comedy-Drama', 1, 'A dysfunctional family&#039;s cruise trip brings out hidden secrets and bonds.', 'https://tse3.mm.bing.net/th/id/OIP.8EoHVZ4g8zNFO0Lv0pHHugAAAA?rs=1&amp;pid=ImgDetMain&amp;o=7&amp;rm=3', 'https://www.youtube.com/watch?v=G6s6L4tG2t8', 2015, '170 min'),
(30, 'Piku', 'Comedy-Drama', 1, 'A quirky father-daughter relationship takes a road trip to Kolkata, filled with humor and emotions.', 'https://d229kpbsb5jevy.cloudfront.net/sony4/landscape_thumb/Piku_31march_1920x1080.jpg', 'https://www.youtube.com/watch?v=6k8vUqFj4w8', 2015, '123 min'),
(31, 'Kabhi Alvida Naa Kehna', 'Romantic Drama', 3, 'Two extramarital affairs lead to complex emotional entanglements.', 'https://i.ytimg.com/vi/h9fIHRGZKM0/maxresdefault.jpg', 'https://www.youtube.com/watch?v=H3yJ7cY9V8Q', 2006, '193 min'),
(32, 'Taare Zameen Par', 'Drama', 3, 'A dyslexic child struggles with school life until a teacher discovers his hidden talent.', 'https://www.robinage.com/wp-content/uploads/2023/03/tare-zameen-pr.jpg', 'https://www.youtube.com/watch?v=4p1q4v7Y5aI', 2007, '165 min'),
(33, 'My Name Is Khan', 'Drama', 3, 'An autistic man embarks on a journey to meet the President of the United States.', 'https://tse3.mm.bing.net/th/id/OIP.21nzXzq-BMWqybq9nN1lFAHaFj?rs=1&amp;pid=ImgDetMain&amp;o=7&amp;rm=3', 'https://www.youtube.com/watch?v=8r1yGzJ5w9Y', 2010, '165 min'),
(34, 'Aashiqui 2', 'Romantic Musical', 3, 'A talented singer&#039;s career declines due to alcoholism, while his protege rises to fame.', 'https://m.media-amazon.com/images/M/MV5BMjEzNzczNTg2M15BMl5BanBnXkFtZTcwMjUxNjk0OQ@@._V1_FMjpg_UX1000_.jpg', 'https://www.youtube.com/watch?v=8t2xjXoI7a8', 2013, '135 min'),
(35, 'Rockstar', 'Musical Romance', 3, 'A young man&#039;s journey to stardom is marred by heartbreak and personal loss.', 'https://akamaividz2.zee5.com/image/upload/w_1170,h_658,c_scale,f_auto,q_auto/resources/0-0-105985/list/rockstarhi1170x658.jpg', 'https://www.youtube.com/watch?v=G6s6L4tG2t8', 2011, '155 min'),
(36, 'Raaz', 'Horror', 5, 'A couple&#039;s life is turned upside down by supernatural occurrences.', 'https://i.ytimg.com/vi/HJHBeRvyNT8/maxresdefault.jpg', 'https://www.youtube.com/watch?v=Jp_sP9U6Q6g', 2002, '148 min'),
(37, 'Talaash', 'Thriller', 5, 'A police officer investigates a mysterious car accident that leads to supernatural revelations.', 'https://i.ytimg.com/vi/V5cA6jBU07I/maxresdefault.jpg', 'https://www.youtube.com/watch?v=lFzVzqk-0Hc', 2012, '139 min'),
(38, 'Stree', 'Horror Comedy', 5, 'A small town is haunted by a female spirit who abducts men during festivals.', 'https://static.abplive.com/wp-content/uploads/2018/12/06193752/5c092d3822656stree.jpg?impolicy=abp_cdn&amp;imwidth=1200&amp;imheight=628', 'https://www.youtube.com/watch?v=LH6dr6j0EqA', 2018, '129 min'),
(39, 'Pari', 'Horror', 5, 'A dark tale of supernatural events surrounding a mysterious woman.', 'https://tse3.mm.bing.net/th/id/OIP.Ua-FStc1SFMLRs7DVHDI_QHaKs?rs=1&amp;pid=ImgDetMain&amp;o=7&amp;rm=3', 'https://www.youtube.com/watch?v=9CXGuqIffMo', 2018, '124 min'),
(40, '13B: Fear Has a New Address', 'Horror Thriller', 5, 'A family encounters supernatural events linked to their television.', 'https://tse2.mm.bing.net/th/id/OIP.L8DqDGOFaqtrNv5hfTPFjwHaEN?rs=1&amp;pid=ImgDetMain&amp;o=7&amp;rm=3', 'https://www.youtube.com/watch?v=0n2kyg49nDE', 2009, '120 min');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@cinefeel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-07-15 10:06:22'),
(2, 'cinefeelss', 'cinefeel@gmail.com', '$2y$10$awehxFiPnIsVW8Tgl3tpT.cRdB58C0U1Dx4iFzBfaaDf8GPMtaami', 'user', '2025-07-15 10:09:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`movie_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `moods`
--
ALTER TABLE `moods`
  ADD PRIMARY KEY (`mood_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`),
  ADD KEY `mood_id` (`mood_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `moods`
--
ALTER TABLE `moods`
  MODIFY `mood_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`mood_id`) REFERENCES `moods` (`mood_id`) ON DELETE SET NULL;
COMMIT;


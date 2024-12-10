-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 04:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `group6_movies_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `directors`
--

CREATE TABLE `directors` (
                             `id` int(11) NOT NULL,
                             `name` varchar(255) NOT NULL,
                             `bio` varchar(255) NOT NULL,
                             `birthDate` date NOT NULL,
                             `deathDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `directors`
--

INSERT INTO `directors` (`id`, `name`, `bio`, `birthDate`, `deathDate`) VALUES
                                                                            (1, 'Quentin Tarantino', 'Quentin Tarantino is an American filmmaker known for his unconventional storytelling, stylized violence, and sharp dialogue. His films include \"Pulp Fiction\" (1994), \"Kill Bill\" (2003), and \"Inglourious Basterds\" (2009).', '1963-03-27', NULL),
                                                                            (2, 'Steven Spielberg', 'Steven Spielberg is an American filmmaker and one of the most influential directors in cinematic history. Known for his work on \"Jaws\" (1975), \"E.T.\" (1982), and \"Schindler\'s List\" (1993), he revolutionized blockbuster filmmaking.', '1946-12-18', NULL),
                                                                            (3, 'Sofia Coppola', 'Sofia Coppola is an American director and screenwriter known for her introspective and atmospheric films. Her works include \"Lost in Translation\" (2003), \"The Virgin Suicides\" (1999), and \"The Beguiled\" (2017).', '1971-05-14', NULL),
                                                                            (4, 'Akira Kurosawa\r\n', 'Akira Kurosawa was a Japanese filmmaker widely regarded as one of the most influential directors in film history. His masterpieces include \"Seven Samurai\" (1954), \"Rashomon\" (1950), and \"Ikiru\" (1952), blending Japanese tradition with Western cinema.', '1910-03-23', '1998-09-06'),
                                                                            (5, 'Greta Gerwig', 'Greta Gerwig is an American director, actress, and screenwriter known for her work in coming-of-age films. She is celebrated for \"Lady Bird\" (2017) and \"Little Women\" (2019), which explore themes of family, identity, and womanhood.', '1983-08-04', NULL),
                                                                            (6, 'Ridley Scott', 'Ridley Scott is a British filmmaker known for his work on films such as \"Alien\" (1979) and \"Gladiator\" (2000). He is a pioneer in the science fiction and historical epic genres.', '1937-11-30', NULL),
                                                                            (7, 'James Cameron', 'James Cameron is a Canadian filmmaker recognized for his work on blockbuster films like \"Titanic\" (1997) and \"Avatar\" (2009). He is known for his innovative use of technology in filmmaking.', '1954-08-16', NULL),
                                                                            (8, 'David Fincher', 'David Fincher is an American director, producer, and writer known for his work on dark, psychological thrillers such as \"Seven\" (1995) and \"Fight Club\" (1999).', '1962-08-28', NULL),
                                                                            (9, 'Guillermo del Toro', 'Guillermo del Toro is a Mexican filmmaker known for his mastery in the fantasy genre. He is famous for films like \"Pan\'s Labyrinth\" (2006) and \"The Shape of Water\" (2017), blending horror, fantasy, and fairy tales.', '1964-10-09', NULL),
                                                                            (10, 'Wes Anderson', 'Wes Anderson is an American director, screenwriter, and producer known for his distinctive visual and narrative style. His works include films like \"The Grand Budapest Hotel\" (2014) and \"Moonrise Kingdom\" (2012).', '1969-05-01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
                          `id` int(11) NOT NULL,
                          `genreName` varchar(255) NOT NULL,
                          `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `genreName`, `description`) VALUES
                                                            (1, 'Drama', 'Character-driven stories that emphasize emotional and relational development. This film delves into deep conversations and personal struggles.\r\n'),
                                                            (2, 'Adventure', 'Focuses on action-packed sequences, often involving exploration, danger, and discovery. '),
                                                            (3, 'Action', 'Highlights physical conflicts, including battles, fights, and other dynamic sequences. Seven Samurai has intense sword fighting and strategic warfare.'),
                                                            (4, 'Coming-of-Age', 'Focuses on the transition from youth to adulthood, exploring themes of identity, growth, and self-discovery.'),
                                                            (5, 'Comedy', 'Comedy is a genre of film, television, literature, and performance that aims to provoke laughter and entertain through humor. It often explores the absurdities of human behavior, societal norms, and everyday life, using various techniques such as exaggera');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
                          `id` int(11) NOT NULL,
                          `movieName` varchar(255) NOT NULL,
                          `releaseDate` date NOT NULL,
                          `studioId` int(11) NOT NULL,
                          `directorId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `movieName`, `releaseDate`, `studioId`, `directorId`) VALUES
                                                                                      (1, 'Pulp Fiction', '1994-10-14', 1, 1),
                                                                                      (2, 'Jurassic Park\r\n', '1993-06-11', 2, 2),
                                                                                      (3, 'Lost in Translation', '2003-09-12', 3, 3),
                                                                                      (4, 'Seven Samurai\r\n', '1954-04-26', 4, 4),
                                                                                      (5, 'Lady Bird', '2017-11-03', 5, 5),
                                                                                      (6, 'Gladiator', '2000-05-05', 2, 6),
                                                                                      (7, 'Avatar', '2009-12-18', 2, 7),
                                                                                      (8, 'Fight Club', '1999-10-15', 1, 8),
                                                                                      (9, 'Pan\'s Labyrinth', '2006-10-11', 4, 9),
                                                                                      (10, 'The Grand Budapest Hotel', '2014-03-28', 5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `movies_genres`
--

CREATE TABLE `movies_genres` (
                                 `movieId` int(11) NOT NULL,
                                 `genreId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies_genres`
--

INSERT INTO `movies_genres` (`movieId`, `genreId`) VALUES
                                                       (2, 3),
                                                       (2, 2),
                                                       (5, 4),
                                                       (5, 5),
                                                       (4, 3),
                                                       (4, 2),
                                                       (3, 5),
                                                       (3, 1),
                                                       (1, 3),
                                                       (1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviewer`
--

CREATE TABLE `reviewer` (
                            `id` int(11) NOT NULL,
                            `name` varchar(255) NOT NULL,
                            `password` varchar(100) NOT NULL,
                            `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                            `username` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviewer`
--

INSERT INTO `reviewer` (`id`, `name`, `password`, `created_at`, `username`) VALUES
                                                                                (1, 'Emily R.', '$2y$10$iBjvDVeqSj8Xi5QVILfUaugi9pWxNTkGkAwPP/Qi5QJwR9.ploAHO', '2024-11-16 23:11:17', 'root'),
                                                                                (2, 'Mark T.\r\n', '', '2024-10-03 02:39:13', ''),
                                                                                (3, 'Sarah L.\r\n', '', '2024-10-03 02:39:27', ''),
                                                                                (4, 'John M.\r\n', '', '2024-10-03 02:39:27', ''),
                                                                                (5, 'Alex K.\r\n', '', '2024-10-03 02:39:33', ''),
                                                                                (7, 'User', '', '2024-11-18 04:44:11', ''),
                                                                                (8, 'test', '$2y$10$DRb5VABD1.6clQPM1K/YX.ZUvhCqlNrXq1AcerzWnXdHup3RIJODy', '2024-12-10 09:30:38', 'test'),
                                                                                (9, 'test', '$2y$10$LWUooW0X2iseat7CiieIKuoEYoVlw1MBmNIx9vTFFS6sNQNe6j7Wy', '2024-12-10 10:39:32', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
                           `id` int(11) NOT NULL,
                           `reviewerId` int(11) NOT NULL,
                           `review` varchar(500) NOT NULL,
                           `movieId` int(11) NOT NULL,
                           `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                           `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `reviewerId`, `review`, `movieId`, `created_at`, `rating`) VALUES
                                                                                            (1, 5, 'Pulp Fiction is a masterpiece! The non-linear storytelling and unforgettable dialogue kept me on the edge of my seat. Tarantino’s ability to blend humor with violence is nothing short of genius. Each character is so distinct and memorable, especially Samuel L. Jackson as Jules. It’s a film that demands multiple viewings to catch all the nuances.', 1, '2024-10-05 13:36:21', 5),
                                                                                            (2, 3, 'Jurassic Park was a childhood favorite of mine, and watching it again as an adult reminded me of its groundbreaking effects. The tension was palpable, especially during the T-Rex scenes. Spielberg created a sense of wonder and terror that’s hard to match. However, I felt some parts of the story could have been stronger, but overall, it’s a thrilling ride!', 2, '2024-10-05 13:36:21', 4),
                                                                                            (3, 3, 'Lost in Translation is beautifully understated. The chemistry between Bill Murray and Scarlett Johansson is captivating, and I love how it captures the feeling of isolation in a foreign country. The cinematography is stunning, and the soundtrack is perfect. It’s a film that resonates with anyone who’s ever felt out of place. A true gem!', 3, '2024-10-05 13:36:21', 5),
                                                                                            (4, 4, 'Seven Samurai is an epic that redefined cinema. Kurosawa\'s direction is masterful, and the film\'s themes of honor and sacrifice resonate deeply. The character development is phenomenal; you really feel for each samurai and the villagers they protect. The battles are incredibly choreographed, and despite its length, it never feels dull. An absolute classic!', 4, '2024-10-05 13:36:21', 3),
                                                                                            (5, 5, 'Lady Bird captures the complexities of mother-daughter relationships beautifully. Greta Gerwig’s direction feels authentic, and the performances, especially by Saoirse Ronan, are fantastic. The humor is sharp, and I loved the coming-of-age aspects. It made me reflect on my own teenage years. I did wish for a bit more depth in some characters, but overall, it’s a delightful film!', 5, '2024-10-05 13:36:21', 4);

-- --------------------------------------------------------

--
-- Table structure for table `studios`
--

CREATE TABLE `studios` (
                           `id` int(11) NOT NULL,
                           `name` varchar(255) NOT NULL,
                           `description` varchar(255) NOT NULL,
                           `foundingDate` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studios`
--

INSERT INTO `studios` (`id`, `name`, `description`, `foundingDate`) VALUES
                                                                        (1, 'Miramax Films\r\n', 'Miramax Films was founded by brothers Bob and Harvey Weinstein, initially focused on distributing independent films. The studio gained prominence in the 1990s by producing award-winning films like Pulp Fiction (1994), The English Patient (1996), and Shake', '1979'),
                                                                        (2, 'Universal Pictures\r\n', 'Universal Pictures is one of the oldest and most successful film studios in the world, established by Carl Laemmle. It played a key role in shaping Hollywood during its formative years, and is known for producing a wide variety of iconic films. From the U', '1912'),
                                                                        (3, 'Focus Features', 'Focus Features is an American film production and distribution company, created as a merger of USA Films, Universal Focus, and Good Machine. Focus specializes in art-house films and independent cinema with broad appeal, producing critically acclaimed and ', '2002'),
                                                                        (4, 'Toho Co., Ltd.', 'Toho Co., Ltd. is a Japanese film production and distribution company best known for creating the legendary Godzilla franchise. Toho is also recognized for producing and distributing some of Japan’s most critically acclaimed films, including the works of ', '1932'),
                                                                        (5, 'A24', 'A24 is an independent entertainment company that has quickly risen to prominence for its focus on original, boundary-pushing films. Since its founding, A24 has produced and distributed numerous critically acclaimed films, including Lady Bird (2017), Moonl', '2012');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
                          `id` int(11) NOT NULL,
                          `user` int(11) NOT NULL,
                          `value` varchar(255) NOT NULL,
                          `created_at` datetime NOT NULL,
                          `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `user`, `value`, `created_at`, `updated_at`) VALUES
                                                                             (1, 1, 'a5032607bd50e310bb59c5ed50ed05ad33a3fe04345c21f44de7ca442d005b2dd811d6522818e7faedd014c3c6f50be6ef535d0e83712a2ca6db1a8f38eef29f', '2024-11-18 00:07:14', '2024-12-10 05:38:32'),
                                                                             (2, 8, '69e5d0ab19383a01fe5013f7df4a23ebec7ccf89aec85dfde980b30ebe7f7d3f8b83224fddf131805309901d1762905a757ac3c62bd61475de6e46d34f753201', '2024-12-10 05:39:37', '2024-12-10 16:49:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `directors`
--
ALTER TABLE `directors`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_movies_studios` (`studioId`),
    ADD KEY `fk_movies_directors` (`directorId`);

--
-- Indexes for table `movies_genres`
--
ALTER TABLE `movies_genres`
    ADD KEY `fk_genre_intersection` (`genreId`),
    ADD KEY `fk_movie_intersection` (`movieId`);

--
-- Indexes for table `reviewer`
--
ALTER TABLE `reviewer`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_reviews_movies` (`reviewerId`),
    ADD KEY `fk_movies_reviews` (`movieId`);

--
-- Indexes for table `studios`
--
ALTER TABLE `studios`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
    ADD PRIMARY KEY (`id`),
    ADD KEY `user` (`user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `directors`
--
ALTER TABLE `directors`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviewer`
--
ALTER TABLE `reviewer`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `studios`
--
ALTER TABLE `studios`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
    ADD CONSTRAINT `fk_movies_directors` FOREIGN KEY (`directorId`) REFERENCES `directors` (`id`),
    ADD CONSTRAINT `fk_movies_studios` FOREIGN KEY (`studioId`) REFERENCES `studios` (`id`);

--
-- Constraints for table `movies_genres`
--
ALTER TABLE `movies_genres`
    ADD CONSTRAINT `fk_genre_intersection` FOREIGN KEY (`genreId`) REFERENCES `genres` (`id`),
    ADD CONSTRAINT `fk_movie_intersection` FOREIGN KEY (`movieId`) REFERENCES `movies` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
    ADD CONSTRAINT `fk_movies_reviews` FOREIGN KEY (`movieId`) REFERENCES `movies` (`id`),
    ADD CONSTRAINT `fk_reviews_reviewer` FOREIGN KEY (`reviewerId`) REFERENCES `reviewer` (`id`);

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
    ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user`) REFERENCES `reviewer` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 12:18 AM
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
(1, 'Quentin Tarantino', 'Quentin Tarantino is a renowned American director and screenwriter known for his highly stylized films, often featuring non-linear narratives, sharp dialogue, and graphic violence. His works include Pulp Fiction (1994), Reservoir Dogs (1992), Kill Bill (2', '1963-03-27', NULL),
(2, 'Steven Spielberg', 'One of the most influential directors in cinema history, Steven Spielberg is an American filmmaker best known for creating some of the highest-grossing and most beloved films of all time. His works include Jaws (1975), E.T. (1982), Jurassic Park (1993), S', '1946-12-18', NULL),
(3, 'Sofia Coppola', 'Sofia Coppola is an American director, screenwriter, and actress, known for her introspective and visually striking films that explore themes of loneliness, youth, and identity. She gained critical acclaim with The Virgin Suicides (1999) and Lost in Trans', '1971-05-14', NULL),
(4, 'Akira Kurosawa\r\n', 'Akira Kurosawa was a legendary Japanese filmmaker, widely regarded as one of the most important and influential directors in cinema history. He directed films such as Rashomon (1950), Seven Samurai (1954), and Yojimbo (1961), which have left a lasting imp', '1910-03-23', '1998-09-06'),
(5, 'Greta Gerwig', 'Greta Gerwig is an American actress, writer, and director, celebrated for her work in independent and mainstream cinema. She transitioned from acting to directing, making her solo directorial debut with Lady Bird (2017), which was nominated for five Acade', '1983-08-04', NULL);

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
(5, 'Lady Bird', '2017-11-03', 5, 5);

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
(5, 'Alex K.\r\n', '', '2024-10-03 02:39:33', '');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `directors`
--
ALTER TABLE `directors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviewer`
--
ALTER TABLE `reviewer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

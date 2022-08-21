--
-- Base de données : `cinema`
--

DROP SCHEMA IF EXISTS `cinema`;
CREATE SCHEMA `cinema` DEFAULT CHARACTER SET utf8 ;
USE `cinema` ;

-- --------------------------------------------------------

--
-- Structure de la table `acteur`
--

CREATE TABLE `acteur` (
  `acteur_id` int(10) UNSIGNED NOT NULL,
  `acteur_nom` varchar(255) NOT NULL,
  `acteur_prenom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `acteur`
--

INSERT INTO `acteur` (`acteur_id`, `acteur_nom`, `acteur_prenom`) VALUES
(1, 'Montand', 'Yves'),
(2, 'Depardieu', 'Gérard'),
(3, 'Auteuil', 'Daniel'),
(4, 'Depardieu', 'Élisabeth'),
(5, 'Béart', 'Emmanuelle'),
(6, 'Girardot', 'Hippolyte'),
(7, 'De Tonquedec', 'Guillaume'),
(8, 'Carré', 'Isabelle'),
(9, 'Bouillette', 'Christian'),
(10, 'Lavernhe', 'Benjamin'),
(11, 'Gadebois', 'Grégory'),
(12, 'Lefèbvre', 'Lorenzo'),
(13, 'Montpetit', 'Sara'),
(14, 'Ricard', 'Sébastien'),
(15, 'Florent', 'Hélène'),
(16, 'Schneider', 'Émile'),
(17, 'Pilon', 'Antoine Olivier'),
(18, 'Naylor', 'Robert'),
(19, 'Dubreuil', 'Martin'),
(20, 'Gilmore', 'Danny'),
(21, 'Arcand', 'Gabriel'),
(22, 'Sicotte', 'Gilbert'),
(23, 'Bibeau', 'Émilie'),
(24, 'Cloutier', 'Fabien'),
(25, 'Papineau', 'François'),
(26, 'Girard', 'Rémy'),
(27, 'Vachon', 'Arnaud'),
(28, 'Huard', 'Xavier'),
(29, 'Dujardin', 'Jean'),
(30, 'Lindinger', 'Natacha'),
(31, 'N\'Diaye', 'Fatou'),
(32, 'Yordanoff', 'Wladimir '),
(33, 'Casta', 'Melodie'),
(34, 'Niney', 'Pierre'),
(35, 'Merad', 'Kad'),
(36, 'Ayala', 'David'),
(37, 'Cissokho', 'Lamine'),
(38, 'Khammes', 'Sofian'),
(39, 'Lottin', 'Pierre'),
(40, 'Nabié', 'Wabinlé'),
(41, 'Medvedev', 'Alexandre'),
(42, 'Benchnafa', 'Saïd'),
(43, 'Hands', 'Marina'),
(44, 'Stocker', 'Laurent'),
(45, 'Seydoux', 'Léa'),
(46, 'Köhler', 'Juliane'),
(47, 'Biolay', 'Benjamin'),
(48, 'Gardin', 'Blanche'),
(49, 'Arioli', 'Emanuele'),
(50, 'Zemmar', 'Jawad');

-- --------------------------------------------------------

--
-- Structure de la table `film`
--

CREATE TABLE `film` (
  `film_id` int(10) UNSIGNED NOT NULL,
  `film_titre` varchar(255) NOT NULL,
  `film_duree` smallint(5) UNSIGNED NOT NULL,
  `film_annee_sortie` year(4) NOT NULL,
  `film_resume` text NOT NULL,
  `film_affiche` varchar(255) NOT NULL,
  `film_bande_annonce` varchar(255) NOT NULL DEFAULT '',
  `film_statut` tinyint(4) NOT NULL DEFAULT 0,
  `film_genre_id` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `film`
--

INSERT INTO `film` (`film_id`, `film_titre`, `film_duree`, `film_annee_sortie`, `film_resume`, `film_affiche`, `film_bande_annonce`, `film_statut`, `film_genre_id`) VALUES
(1, 'MARIA CHAPDELAINE', 158, 2021, 'En 1910, Maria Chapdelaine, une jeune fille de dix-sept ans, vit avec sa famille aux abords de la rivière Péribonka au nord du lac Saint-Jean. Les Chapdelaine travaillent sans relâche pour repousser toujours plus loin les limites de la forêt. Là où les difficultés du quotidien s’arriment avec la délicatesse d’une vie familiale chaleureuse, Maria, forte et pleine d’espoir, se retrouve devant des dilemmes de taille. François Paradis, un ancien voisin de la famille qu’elle aime depuis son enfance, devenu coureur des bois et guide pour les étrangers, lui fait la promesse de revenir au printemps pour l’épouser. Mais le printemps se fait attendre, et deux prétendants vont alors se manifester. Lorenzo Surprenant, un jeune exilé qui travaille dans les «factries» du Massachusetts, offre à Maria de le suivre aux États-Unis, et Eutrope Gagnon, leur vaillant voisin, lui propose de défricher les lots de terre qu’il a pris tout près de ceux des Chapdelaine. Maria, poussée dans le monde adulte, devra soudainement choisir son avenir de femme.', 'medias/affiches/a-1.jpg', 'medias/bandes-annonces/ba-1.mp4', 1, 4),
(2, 'DÉLICIEUX', 112, 2021, 'XVIIIe siècle. Un cuisinier est limogé par son maître. Il trouve le courage au contact d’une jeune femme étonnante de se libérer de sa condition de domestique et de proposer son savoir-faire directement au public en créant le premier restaurant.', 'medias/affiches/a-2.jpg', 'medias/bandes-annonces/ba-2.mp4', 1, 3),
(3, 'OSS 117 - BONS BAISERS D\'AFRIQUE', 116, 2021, '1981.\r\nHubert Bonisseur de la Bath, alias OSS 117, est de retour. Pour cette nouvelle mission, plus délicate, plus périlleuse et plus torride que jamais, il est contraint de faire équipe avec un jeune collègue, le prometteur OSS 1001.', 'medias/affiches/a-3.jpg', '', 1, 2),
(4, 'LE CLUB VINLAND', 125, 2020, 'LE CLUB VINLAND, c’est l’histoire d’un éducateur exceptionnel dans un collège de garçons de l’est du Québec de la fin des années 1940. Adulé de ses élèves, mais perçu comme trop dérangeant par les supérieurs de sa congrégation, le charismatique Frère Jean est un progressiste annonciateur des changements à venir dans le Québec des années 1950 et 1960. Voulant à la fois résoudre une énigme historique, motiver ses élèves et empêcher le décrochage d’Émile, un étudiant en difficultés, Frère Jean entreprend de conduire des fouilles archéologiques visant à prouver l’établissement d’une colonie viking (le Vinland) sur la côte du Saint-Laurent. L’entreprise bouleversera la vie du collège et laissera sa marque sur les destins du jeune Émile et de Frère Jean lui-même.', 'medias/affiches/a-4.jpg', '', 1, 4),
(5, 'VIVRE EN GRAND', 96, 2020, 'Depuis 6 ans, Melati, 18 ans combat la pollution plastique qui ravage son pays l’Indonésie. Comme elle, une génération se lève pour réparer le monde. Partout, adolescents et jeunes adultes luttent pour les droits humains, le climat, la liberté d’expression, la justice sociale, l’accès à l’éducation ou l’alimentation. La dignité. Seuls contre tous, parfois au péril de leur vie et sécurité, ils protègent, dénoncent, soignent les autres. La Terre. Et ils changent tout. Melati part à leur rencontre à travers le globe. Elle veut comprendre comment tenir et poursuivre son action. Des favelas de Rio aux villages reculés du Malawi, des embarcations de fortune au large de l’île de Lesbos aux cérémonies amérindiennes dans les montagnes du Colorado, Rene, Mary, Xiu, Memory, Mohamad et Winnie nous révèlent un monde magnifique, celui du courage et de la joie, de l’engagement pour plus grand que soi. Alors que tout semble ou s’effondré, cette jeunesse nous montre comment vivre. Et ce qu’être au monde, aujourd’hui, signifie.', 'medias/affiches/a-5.jpg', '', 1, 5),
(6, 'UN TRIOMPHE', 106, 2019, 'Un acteur en galère accepte pour boucler ses fins de mois d’animer un atelier théâtre en prison. Surpris par les talents de comédien des détenus, il se met en tête de monter avec eux une pièce sur la scène d’un vrai théâtre. Commence alors une formidable aventure humaine. Inspiré d’une histoire vraie.', 'medias/affiches/a-6.jpg', '', 1, 3),
(7, 'FRANCE', 133, 2021, '« France » est à la fois le portrait d\'une femme, journaliste à la télévision, d\'un pays, et d\'un système, celui des médias.', 'medias/affiches/a-7.jpg', '', 1, 3),
(8, 'JOSEP', 71, 2020, 'Josep est un film d\'animation français, belge et espagnol réalisé par Aurel sorti en 2020. Ce film relate la vie du dessinateur et homme politique Josep Bartolí au temps de la guerre civile espagnole, puis de la seconde guerre mondiale et sa relation avec la peintre mexicaine Frida Kahlo.', 'medias/affiches/a-8.jpg', '', 1, 1),
(9, 'JEAN DE FLORETTE', 120, 1986, 'Ugolin, jeune paysan de retour du service militaire, rêve de faire fortune dans la culture des œillets. Son oncle, César Soubeyran, dit « le Papet », est prêt à tout pour que son neveu réussisse. Mais pour cela, les deux hommes doivent faire échouer les projets d\'un citadin, Jean de Florette, venu s\'installer sur la propriété qu\'ils convoitent. Jean de Florette vient d\'emménager avec sa femme Aimée et sa petite fille Manon dans cette maison de famille. Motivé, souriant, le jeune homme ignore les projets machiavéliques de ses voisins. Peu apprécié par les villageois du fait de son arrivée toute récente, il finira par laisser la vie dans ses travaux.', 'medias/affiches/a-9.jpg', '', 1, 4),
(10, 'MANON DES SOURCES', 120, 1986, 'Les années ont passé depuis la mort de Jean de Florette. Ugolin Soubeyran est désormais propriétaire des Romarins et prospère grâce à la culture des œillets. Son oncle César souhaite maintenant qu\'il se marie pour perpétuer le nom de la famille. Ugolin tombe sous le charme de Manon, la fille du bossu, devenue une belle jeune femme. Mais la vérité sur les circonstances qui ont conduit son père à la mort menace d\'éclater à tout moment. Ugolin, malgré les avertissements de son oncle, essaye désespérément de séduire la jeune femme. Cette dernière, par souci de vengeance, va boucher la source. Un jeune instituteur arrive au village, et tombe sous le charme de la belle sauvageonne, qui va tomber à son tour amoureuse de lui.', 'medias/affiches/a-10.jpg', '', 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `film_acteur`
--

CREATE TABLE `film_acteur` (
  `f_a_film_id` int(10) UNSIGNED NOT NULL,
  `f_a_acteur_id` int(10) UNSIGNED NOT NULL,
  `f_a_priorite` tinyint(3) UNSIGNED NOT NULL DEFAULT 99
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `film_acteur`
--

INSERT INTO `film_acteur` (`f_a_film_id`, `f_a_acteur_id`, `f_a_priorite`) VALUES
(1, 13, 1),
(1, 14, 2),
(1, 15, 3),
(1, 16, 4),
(1, 17, 5),
(1, 18, 6),
(1, 19, 7),
(1, 20, 8),
(1, 21, 9),
(1, 22, 10),
(2, 7, 1),
(2, 8, 2),
(2, 9, 3),
(2, 10, 4),
(2, 11, 5),
(2, 12, 6),
(3, 29, 1),
(3, 30, 2),
(3, 31, 3),
(3, 32, 4),
(3, 33, 5),
(3, 34, 6),
(4, 14, 6),
(4, 23, 1),
(4, 24, 2),
(4, 25, 3),
(4, 26, 4),
(4, 27, 5),
(4, 28, 7),
(6, 35, 1),
(6, 36, 2),
(6, 37, 3),
(6, 38, 4),
(6, 39, 5),
(6, 40, 6),
(6, 41, 7),
(6, 42, 8),
(6, 43, 9),
(6, 44, 10),
(7, 45, 1),
(7, 46, 2),
(7, 47, 3),
(7, 48, 4),
(7, 49, 5),
(7, 50, 6),
(9, 1, 1),
(9, 2, 2),
(9, 3, 3),
(9, 4, 4),
(10, 1, 1),
(10, 3, 3),
(10, 4, 5),
(10, 5, 2),
(10, 6, 4);

-- --------------------------------------------------------

--
-- Structure de la table `film_pays`
--

CREATE TABLE `film_pays` (
  `f_p_film_id` int(10) UNSIGNED NOT NULL,
  `f_p_pays_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `film_pays`
--

INSERT INTO `film_pays` (`f_p_film_id`, `f_p_pays_id`) VALUES
(1, 1),
(2, 4),
(3, 4),
(4, 1),
(5, 4),
(6, 4),
(7, 4),
(7, 6),
(7, 9),
(7, 10),
(8, 3),
(8, 4),
(8, 10),
(9, 4),
(9, 6),
(9, 7),
(9, 8),
(10, 4),
(10, 6),
(10, 7);

-- --------------------------------------------------------

--
-- Structure de la table `film_realisateur`
--

CREATE TABLE `film_realisateur` (
  `f_r_film_id` int(10) UNSIGNED NOT NULL,
  `f_r_realisateur_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `film_realisateur`
--

INSERT INTO `film_realisateur` (`f_r_film_id`, `f_r_realisateur_id`) VALUES
(1, 2),
(2, 4),
(3, 5),
(4, 3),
(5, 9),
(6, 6),
(7, 8),
(8, 7),
(9, 1),
(10, 1);

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE `genre` (
  `genre_id` tinyint(3) UNSIGNED NOT NULL,
  `genre_nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `genre`
--

INSERT INTO `genre` (`genre_id`, `genre_nom`) VALUES
(1, 'Animation'),
(2, 'Comédie'),
(3, 'Comédie dramatique'),
(4, 'Drame'),
(5, 'Documentaire');

-- --------------------------------------------------------

--
-- Structure de la table `pays`
--

CREATE TABLE `pays` (
  `pays_id` int(10) UNSIGNED NOT NULL,
  `pays_nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pays`
--

INSERT INTO `pays` (`pays_id`, `pays_nom`) VALUES
(1, 'Canada'),
(2, 'États-Unis'),
(3, 'Espagne'),
(4, 'France'),
(5, 'Grande-Bretagne'),
(6, 'Italie'),
(7, 'Suisse'),
(8, 'Autriche'),
(9, 'Allemagne'),
(10, 'Belgique');

-- --------------------------------------------------------

--
-- Structure de la table `realisateur`
--

CREATE TABLE `realisateur` (
  `realisateur_id` int(10) UNSIGNED NOT NULL,
  `realisateur_nom` varchar(255) NOT NULL,
  `realisateur_prenom` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `realisateur`
--

INSERT INTO `realisateur` (`realisateur_id`, `realisateur_nom`, `realisateur_prenom`) VALUES
(1, 'Berri', 'Claude'),
(2, 'Pilote', 'Sébastien'),
(3, 'Pilon', 'Benoît'),
(4, ' Besnard', 'Éric'),
(5, 'Bedos', 'Nicolas'),
(6, 'Courcol', 'Emmanuel'),
(7, 'Aurel', ''),
(8, 'Dumont', 'Bruno'),
(9, 'Vasseur', 'Flore');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `salle_numero` tinyint(3) UNSIGNED NOT NULL,
  `salle_nb_places` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`salle_numero`, `salle_nb_places`) VALUES
(1, 200),
(2, 120),
(3, 50);

-- --------------------------------------------------------

--
-- Structure de la table `seance`
--

CREATE TABLE `seance` (
  `seance_film_id` int(10) UNSIGNED NOT NULL,
  `seance_salle_numero` tinyint(3) UNSIGNED NOT NULL,
  `seance_date` date NOT NULL,
  `seance_heure` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `seance`
--

INSERT INTO `seance` (`seance_film_id`, `seance_salle_numero`, `seance_date`, `seance_heure`) VALUES
(2, 1, '2021-10-25', '14:00:00'),
(2, 1, '2021-10-25', '17:00:00'),
(2, 1, '2021-10-26', '07:00:00'),
(2, 1, '2021-10-26', '10:00:00'),
(2, 1, '2021-10-26', '14:00:00'),
(2, 1, '2021-10-26', '17:00:00'),
(2, 1, '2021-10-26', '20:00:00'),
(2, 1, '2021-10-26', '23:00:00'),
(2, 1, '2021-10-31', '15:00:00'),
(5, 2, '2021-10-25', '14:00:00'),
(5, 2, '2021-10-26', '14:00:00');

-- --------------------------------------------------------

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `acteur`
--
ALTER TABLE `acteur`
  ADD PRIMARY KEY (`acteur_id`);

--
-- Index pour la table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`film_id`),
  ADD KEY `fk_film_genre_idx` (`film_genre_id`);

--
-- Index pour la table `film_acteur`
--
ALTER TABLE `film_acteur`
  ADD PRIMARY KEY (`f_a_film_id`,`f_a_acteur_id`),
  ADD KEY `fk_f_a_acteur_idx` (`f_a_acteur_id`),
  ADD KEY `fk_f_a_film_idx` (`f_a_film_id`);

--
-- Index pour la table `film_pays`
--
ALTER TABLE `film_pays`
  ADD PRIMARY KEY (`f_p_film_id`,`f_p_pays_id`),
  ADD KEY `fk_f_p_pays_idx` (`f_p_pays_id`),
  ADD KEY `fk_f_p_film_idx` (`f_p_film_id`);

--
-- Index pour la table `film_realisateur`
--
ALTER TABLE `film_realisateur`
  ADD PRIMARY KEY (`f_r_film_id`,`f_r_realisateur_id`),
  ADD KEY `fk_f_r_realisateur_idx` (`f_r_realisateur_id`),
  ADD KEY `fk_f_r_film_idx` (`f_r_film_id`);

--
-- Index pour la table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`genre_id`);

--
-- Index pour la table `pays`
--
ALTER TABLE `pays`
  ADD PRIMARY KEY (`pays_id`);

--
-- Index pour la table `realisateur`
--
ALTER TABLE `realisateur`
  ADD PRIMARY KEY (`realisateur_id`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`salle_numero`);

--
-- Index pour la table `seance`
--
ALTER TABLE `seance`
  ADD PRIMARY KEY (`seance_film_id`,`seance_salle_numero`,`seance_date`,`seance_heure`),
  ADD KEY `fk_seance_salle_idx` (`seance_salle_numero`),
  ADD KEY `fk_seance_film_idx` (`seance_film_id`);

-- --------------------------------------------------------

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `acteur`
--
ALTER TABLE `acteur`
  MODIFY `acteur_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `film`
--
ALTER TABLE `film`
  MODIFY `film_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `pays`
--
ALTER TABLE `pays`
  MODIFY `pays_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `realisateur`
--
ALTER TABLE `realisateur`
  MODIFY `realisateur_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

-- --------------------------------------------------------

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `film`
--
ALTER TABLE `film`
  ADD CONSTRAINT `fk_film_genre` FOREIGN KEY (`film_genre_id`) REFERENCES `genre` (`genre_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `film_acteur`
--
ALTER TABLE `film_acteur`
  ADD CONSTRAINT `fk_f_a_acteur` FOREIGN KEY (`f_a_acteur_id`) REFERENCES `acteur` (`acteur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_f_a_film` FOREIGN KEY (`f_a_film_id`) REFERENCES `film` (`film_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `film_pays`
--
ALTER TABLE `film_pays`
  ADD CONSTRAINT `fk_f_p_film` FOREIGN KEY (`f_p_film_id`) REFERENCES `film` (`film_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_f_p_pays` FOREIGN KEY (`f_p_pays_id`) REFERENCES `pays` (`pays_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `film_realisateur`
--
ALTER TABLE `film_realisateur`
  ADD CONSTRAINT `fk_f_r_film` FOREIGN KEY (`f_r_film_id`) REFERENCES `film` (`film_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_f_r_realisateur` FOREIGN KEY (`f_r_realisateur_id`) REFERENCES `realisateur` (`realisateur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `seance`
--
ALTER TABLE `seance`
  ADD CONSTRAINT `fk_seance_film` FOREIGN KEY (`seance_film_id`) REFERENCES `film` (`film_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_seance_salle` FOREIGN KEY (`seance_salle_numero`) REFERENCES `salle` (`salle_numero`) ON DELETE NO ACTION ON UPDATE NO ACTION;
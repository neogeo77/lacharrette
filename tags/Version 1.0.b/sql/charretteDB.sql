-- phpMyAdmin SQL Dump
-- version 2.9.1.1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Mercredi 22 Juillet 2009 à 19:31
-- Version du serveur: 5.0.27
-- Version de PHP: 5.2.0
-- 
-- Base de données: `charretteDB`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `appartenance`
-- 

CREATE TABLE `appartenance` (
  `trajet` char(3) NOT NULL,
  `trigramme` char(3) NOT NULL,
  PRIMARY KEY  (`trajet`,`trigramme`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `appartenance`
-- 

INSERT INTO `appartenance` (`trajet`, `trigramme`) VALUES 
('ORL', 'DFO'),
('ORL', 'MBE'),
('ORL', 'PPI'),
('ORL', 'RCO'),
('ORL', 'YDE');

-- --------------------------------------------------------

-- 
-- Structure de la table `calendrier`
-- 

CREATE TABLE `calendrier` (
  `trajet` char(3) NOT NULL,
  `jour` date NOT NULL,
  `trigramme` char(3) NOT NULL default '',
  `code` char(1) NOT NULL,
  PRIMARY KEY  (`trajet`,`jour`,`trigramme`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `calendrier`
-- 

INSERT INTO `calendrier` (`trajet`, `jour`, `trigramme`, `code`) VALUES 
('ORL', '2009-07-31', 'DFO', 'C'),
('ORL', '2009-07-27', 'MBE', 'C'),
('ORL', '2009-07-30', 'PPI', 'C'),
('ORL', '2009-07-29', 'RCO', 'C'),
('ORL', '2009-07-28', 'YDE', 'C');

-- --------------------------------------------------------

-- 
-- Structure de la table `calendrierhistorique`
-- 

CREATE TABLE `calendrierhistorique` (
  `trajet` char(3) NOT NULL,
  `jour` date NOT NULL,
  `trigramme` char(3) NOT NULL default '',
  `code` char(1) NOT NULL,
  PRIMARY KEY  (`trajet`,`jour`,`trigramme`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `calendrierhistorique`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `compteur`
-- 

CREATE TABLE `compteur` (
  `trajet` char(3) NOT NULL,
  `trigramme` char(3) NOT NULL,
  `nbreC` int(11) NOT NULL,
  `nbreP` int(11) NOT NULL,
  `reportNbreC` int(11) NOT NULL default '0',
  `reportNbreP` int(11) NOT NULL default '0',
  `nbreCDepart` int(11) default NULL,
  `nbrePDepart` int(11) default NULL,
  PRIMARY KEY  (`trajet`,`trigramme`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `compteur`
-- 

INSERT INTO `compteur` (`trajet`, `trigramme`, `nbreC`, `nbreP`, `reportNbreC`, `reportNbreP`, `nbreCDepart`, `nbrePDepart`) VALUES 
('ORL', 'MBE', 22, 70, 215, 556, NULL, NULL),
('ORL', 'DFO', 30, 88, 237, 617, NULL, NULL),
('ORL', 'RCO', 26, 81, 40, 103, NULL, NULL),
('ORL', 'PPI', 30, 91, 40, 103, NULL, NULL),
('ORL', 'YDE', 31, 96, 12, 33, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Structure de la table `datecompteurs`
-- 

CREATE TABLE `datecompteurs` (
  `trajet` char(3) NOT NULL,
  `jour` date NOT NULL,
  PRIMARY KEY  (`trajet`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Ne contient qu''un seul enreg. : la date des compteurs';

-- 
-- Contenu de la table `datecompteurs`
-- 

INSERT INTO `datecompteurs` (`trajet`, `jour`) VALUES 
('ORL', '2009-07-13');

-- --------------------------------------------------------

-- 
-- Structure de la table `joursferies`
-- 

CREATE TABLE `joursferies` (
  `trajet` char(3) NOT NULL,
  `jour` date NOT NULL,
  PRIMARY KEY  (`trajet`,`jour`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `joursferies`
-- 

INSERT INTO `joursferies` (`trajet`, `jour`) VALUES 
('ORL', '2009-01-01'),
('ORL', '2009-01-02'),
('ORL', '2009-04-13'),
('ORL', '2009-05-01'),
('ORL', '2009-05-08'),
('ORL', '2009-05-21'),
('ORL', '2009-05-22'),
('ORL', '2009-06-01'),
('ORL', '2009-07-14'),
('ORL', '2009-11-11'),
('ORL', '2009-12-25');

-- --------------------------------------------------------

-- 
-- Structure de la table `messagecm`
-- 

CREATE TABLE `messagecm` (
  `trajet` char(3) NOT NULL,
  `texte` text,
  `date` date NOT NULL default '0000-00-00',
  `boolDefaut` char(1) NOT NULL default 'O',
  PRIMARY KEY  (`trajet`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `messagecm`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `messagesforum`
-- 

CREATE TABLE `messagesforum` (
  `id` int(11) NOT NULL auto_increment,
  `trigramme` char(3) collate latin1_general_ci NOT NULL,
  `trajet` char(3) collate latin1_general_ci NOT NULL,
  `texte` text collate latin1_general_ci,
  `dateMsg` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `supprime` char(1) collate latin1_general_ci NOT NULL default 'N',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=13 ;

-- 
-- Contenu de la table `messagesforum`
-- 

INSERT INTO `messagesforum` (`id`, `trigramme`, `trajet`, `texte`, `dateMsg`, `supprime`) VALUES 
(5, 'YDE', 'ORL', 'Bonjour les charretteux,\r\n\r\nmaintenant, comme vous le voyez, il y a un... forum !\r\nC''est Brahim qui m''a demandé d''ajouter ça, il a intérêt à laisser un message.\r\nVous pouvez utiliser des émoticones :), comme les ados :p\r\n\r\nJ''ai fait quelques autres modifs aussi, suite à des demandes : pour accéder à la galerie photo, il faut maintenant être authentifié (autrement dit, un internaute quelconque ne pourra plus voir nos photos)\r\n\r\nLes "anciens" de la charrette pourront continuer à se connecter (pour accéder à la galerie et au forum), ce qui n''était pas le cas avant.\r\nEt ils sont maintenant listés dans un nouveau tableau de la page "Autres infos".', '2009-06-28 18:24:16', 'N');

-- --------------------------------------------------------

-- 
-- Structure de la table `personne`
-- 

CREATE TABLE `personne` (
  `trigramme` char(3) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `voiture` varchar(50) NOT NULL,
  `nbrePlaces` int(11) NOT NULL,
  `tel_travail` varchar(50) default NULL,
  `tel_personnel` varchar(50) default NULL,
  `date_arrivee` date default NULL,
  `date_depart` date default NULL,
  `email1` varchar(50) NOT NULL,
  `email2` varchar(50) default NULL,
  `ultramaster` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`trigramme`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `personne`
-- 

INSERT INTO `personne` (`trigramme`, `nom`, `prenom`, `password`, `voiture`, `nbrePlaces`, `tel_travail`, `tel_personnel`, `date_arrivee`, `date_depart`, `email1`, `email2`, `ultramaster`) VALUES 
('MBE', 'BEGGARD', 'Mohammed', 'password', 'Picasso', 5, '9999', '06 xx xx xx xx', '2002-11-20', NULL, 'test@test.com', '', 'N'),
('DFO', 'FONTAINE', 'Delphine', 'password', 'C3 full options', 4, '9999', '06 xx xx xx xx', '2004-09-30', NULL, 'test@test.com', '', 'N'),
('RCO', 'CONCHON', 'Régis', 'password', 'Léone', 4, '9999', '06 xx xx xx xx', '2008-03-17', NULL, 'test@test.com', '', 'N'),
('PPI', 'PIECOURT', 'Pascal', 'password', 'BMW 3 Compact', 4, '9999', '06 xx xx xx xx', '2008-04-01', NULL, 'test@test.com', '', 'N'),
('YDE', 'DEYDIER', 'Yann', 'password', 'Clio bleu vert nuit 1.5dci 80ch, clim, abs', 4, '9999', '06 xx xx xx xx', '2008-10-01', NULL, 'test@test.com', '', 'O');

-- --------------------------------------------------------

-- 
-- Structure de la table `trajet`
-- 

CREATE TABLE `trajet` (
  `trajet` char(3) NOT NULL,
  `titre` varchar(60) NOT NULL default '""',
  `texteMsgDefaut` text NOT NULL,
  `lienGoogleMap` varchar(255) NOT NULL,
  `lienAncienneCharrette` varchar(255) NOT NULL,
  `trajetLie` char(3) default NULL,
  `couleur` char(7) default '#000000',
  `distance` int(11) default NULL,
  `ordre` int(11) NOT NULL default '0',
  PRIMARY KEY  (`trajet`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `trajet`
-- 

INSERT INTO `trajet` (`trajet`, `titre`, `texteMsgDefaut`, `lienGoogleMap`, `lienAncienneCharrette`, `trajetLie`, `couleur`, `distance`, `ordre`) VALUES 
('ORL', 'La Charrette Orléans-Esvres', '7h00 -> <b></b>, \r\n7h30 -> <b></b>, \r\n\r\n#COND\r\n\r\n#PASS', 'http://maps.google.fr/maps?f=q&source=s_q&hl=fr&geocode=&q=&sll=47.15984,2.988281&sspn=23.591599,56.953125&ie=UTF8&ll=47.895478,1.854458&spn=0.005676,0.013905&z=17', 'http://spreadsheets.google.com/pub?key=pHS_o2VoWa1RDJ6wqiPyDAg&output=html', NULL, '#AA0000', 125, 1);

-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 18. November 2011 um 12:01
-- Server Version: 5.1.44
-- PHP-Version: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `myzeitung`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cake_sessions`
--

CREATE TABLE `cake_sessions` (
  `id` varchar(255) NOT NULL,
  `data` text,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `expires` (`expires`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `cake_sessions`
--

INSERT INTO `cake_sessions` VALUES('eb9179cf59c42a814efa872ddf9d1140', 'Config|a:4:{s:9:"userAgent";s:32:"fe59fc018fdcbaf75252b6e6da23082a";s:4:"time";i:1321625271;s:7:"timeout";i:10;s:8:"language";s:3:"deu";}Auth|a:2:{s:4:"User";a:22:{s:2:"id";s:2:"18";s:8:"group_id";s:1:"3";s:4:"name";s:0:"";s:11:"description";s:0:"";s:3:"url";s:0:"";s:5:"email";s:21:"tim.wiegard@gmail.com";s:8:"username";s:3:"tim";s:5:"image";N;s:7:"created";s:19:"2011-11-10 11:14:54";s:8:"modified";s:19:"2011-11-16 19:06:28";s:9:"lastlogin";N;s:7:"enabled";s:1:"1";s:12:"visible_home";s:1:"0";s:14:"allow_messages";s:1:"1";s:14:"allow_comments";s:1:"1";s:18:"subscription_count";s:1:"0";s:16:"subscriber_count";s:1:"0";s:10:"post_count";s:2:"14";s:12:"repost_count";s:1:"0";s:13:"comment_count";s:1:"0";s:11:"paper_count";s:1:"0";s:11:"topic_count";s:1:"0";}s:7:"Setting";a:0:{}}_Token|s:232:"a:5:{s:3:"key";s:40:"03b199342b43685896fad2a4643c6cd87e1a0078";s:7:"expires";i:1321619266;s:18:"allowedControllers";a:0:{}s:14:"allowedActions";a:0:{}s:14:"disabledFields";a:3:{i:0;s:5:"image";i:1;s:10:"User.image";i:2;s:4:"data";}}";Message|a:0:{}', 1321625272);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `author_count` int(11) NOT NULL,
  `post_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=97 ;

--
-- Daten für Tabelle `categories`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category_paper_posts`
--

CREATE TABLE `category_paper_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `post_user_id` int(11) NOT NULL,
  `content_paper_id` int(11) NOT NULL,
  `reposter_id` int(11) DEFAULT NULL,
  `reposter_username` varchar(64) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

--
-- Daten für Tabelle `category_paper_posts`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `text` text NOT NULL,
  `created` datetime NOT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `comments`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paper_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reason_id` int(3) NOT NULL,
  `comments` text NOT NULL,
  `reporter_id` int(11) DEFAULT NULL,
  `reporter_email` varchar(255) NOT NULL,
  `reporter_name` varchar(100) NOT NULL,
  `complaintstatus_id` int(3) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `complaints`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `complaintstatus`
--

CREATE TABLE `complaintstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `complaintstatus`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `complaintstatuses`
--

CREATE TABLE `complaintstatuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `complaintstatuses`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_papers`
--

CREATE TABLE `content_papers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  `paper_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Daten für Tabelle `content_papers`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `last_message_id` int(11) unsigned NOT NULL,
  `conversation_message_count` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `conversations`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `conversation_messages`
--

CREATE TABLE `conversation_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text CHARACTER SET latin1 NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `conversation_messages`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `conversation_users`
--

CREATE TABLE `conversation_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `last_viewed_message` int(11) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `conversation_id` (`conversation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `conversation_users`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `groups`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `helpelements`
--

CREATE TABLE `helpelements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT NULL,
  `page_id` int(11) NOT NULL,
  `accessor` varchar(100) NOT NULL,
  `deu` text,
  `eng` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `order` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Daten für Tabelle `helpelements`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `helppages`
--

CREATE TABLE `helppages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deu` text,
  `eng` text,
  `description` varchar(100) DEFAULT NULL,
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Daten für Tabelle `helppages`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `i18n`
--

CREATE TABLE `i18n` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `locale` varchar(6) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(10) NOT NULL,
  `field` varchar(255) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `locale` (`locale`),
  KEY `model` (`model`),
  KEY `row_id` (`foreign_key`),
  KEY `field` (`field`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `i18n`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `installs`
--

CREATE TABLE `installs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `namespace` varchar(100) NOT NULL,
  `version` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Daten für Tabelle `installs`
--

INSERT INTO `installs` VALUES(1, 'category_paper_posts', 'category_paper_posts_0.1.4.php');
INSERT INTO `installs` VALUES(2, 'comments', 'comments_0.1.0.php');
INSERT INTO `installs` VALUES(10, 'posts', 'posts_0.2.0.php');
INSERT INTO `installs` VALUES(4, 'users', 'users_0.2.8.php');
INSERT INTO `installs` VALUES(5, 'categories', 'categories_0.1.3.php');
INSERT INTO `installs` VALUES(6, 'papers', 'papers_0.2.0.php');
INSERT INTO `installs` VALUES(7, 'topics', 'topics_0.1.3.php');
INSERT INTO `installs` VALUES(8, 'content_paper', 'content_paper_0.1.2.php');
INSERT INTO `installs` VALUES(9, 'conversations', 'conversations_0.1.4.php');
INSERT INTO `installs` VALUES(16, 'complaints', 'complaints_0.1.4.php');
INSERT INTO `installs` VALUES(19, 'routes', 'routes_0.1.4.php');
INSERT INTO `installs` VALUES(18, 'settings', 'settings_0.2.0.php');
INSERT INTO `installs` VALUES(20, 'invitations', 'invitations_0.1.1.php');
INSERT INTO `installs` VALUES(21, 'helpcenter', 'helpcenter_0.1.1.php');
INSERT INTO `installs` VALUES(22, 'general', 'general_0.1.0.php');
INSERT INTO `installs` VALUES(23, 'robot', 'robot_0.1.0.php');
INSERT INTO `installs` VALUES(27, 'rss', 'rss_0.1.2.php');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `invitations`
--

CREATE TABLE `invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `text` varchar(1000) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Daten für Tabelle `invitations`
--

INSERT INTO `invitations` VALUES(10, 18, 'hansi', '2011-11-10 18:22:35');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `invitee`
--

CREATE TABLE `invitee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invitation_id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `reminder_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Daten für Tabelle `invitee`
--

INSERT INTO `invitee` VALUES(10, 10, 'tim.wiegard@googlemail.com', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `papers`
--

CREATE TABLE `papers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `premium_route` varchar(100) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `url` varchar(100) NOT NULL,
  `image` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `visible_home` tinyint(1) NOT NULL DEFAULT '0',
  `visible_index` tinyint(1) NOT NULL DEFAULT '1',
  `subscription_count` int(11) NOT NULL,
  `author_count` int(11) NOT NULL,
  `post_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `visible` (`visible_home`),
  KEY `visible_index` (`visible_index`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Daten für Tabelle `papers`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `content_preview` varchar(250) NOT NULL,
  `image` text,
  `links` text NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  `enabled` int(3) NOT NULL DEFAULT '1',
  `reposters` text,
  `allow_comments` varchar(10) DEFAULT NULL,
  `comment_count` int(11) NOT NULL,
  `repost_count` int(11) NOT NULL,
  `view_count` int(11) NOT NULL,
  `rss_item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=391 ;

--
-- Daten für Tabelle `posts`
--

INSERT INTO `posts` VALUES(61, 18, NULL, 'ich hab nen langen titel ohne punkte', '<p>content auch ohne punkte</p>', '', NULL, '', '2011-11-15 14:30:29', '2011-11-15 14:30:28', 1, NULL, 'default', 0, 0, 0, 0);
INSERT INTO `posts` VALUES(62, 18, NULL, 'hnier aber mit punkten weil der viel zu lang ist lang ist lang ist lang ist lang ist', '<p>hnier aber mit punkten weil der viel zu lang ist lang ist lang ist lang ist lang isthnier aber mit punkten weil der viel zu lang ist lang ist lang ist lang ist lang isthnier aber mit punkten weil der viel zu lang ist lang ist lang ist lang ist lang isthnier aber mit punkten weil der viel zu lang ist lang ist lang ist lang ist lang isthnier aber mit punkten weil der viel zu lang ist lang ist lang ist lang ist lang isthnier aber mit punkten weil der viel zu lang ist lang ist lang ist lang ist lang isthnier aber mit punkten weil der viel zu lang ist lang ist lang ist lang ist lang isthnier aber mit punkten weil der viel zu lang ist lang ist lang ist lang ist lang isthnier aber mit punkten weil der viel zu lang ist lang ist lang ist lang ist lang isthnier aber mit punkten weil der viel zu lang ist lang ist lang ist lang ist lang ist</p>', '', NULL, '', '2011-11-15 14:30:57', '2011-11-15 14:30:57', 1, NULL, 'default', 0, 0, 0, 0);
INSERT INTO `posts` VALUES(198, 18, NULL, 'GerÃ¤umteÂ Occupy-Aktivisten: Trotz ohne Kopf', '<p>bla</p>', '', NULL, '', '2011-11-16 16:24:53', '2011-11-16 16:24:53', 1, NULL, 'default', 0, 0, 0, 0);
INSERT INTO `posts` VALUES(389, 18, NULL, 'Ex-MillionÃ¤r Rabeder: &quot;FÃ¼r viele war ich das Geld-Schwein&quot;', 'Karl Rabeder war reich. Dann stieg er aus: Er versteigerteÂ seineÂ Villa, verkaufteÂ seine Segelflieger, Luxuskarossen, Firma undÂ gab den ErlÃ¶sÂ fÃ¼r einen guten Zweck. Im Interview erzÃ¤hlt er, wieÂ es sichÂ jetztÂ mit nur 1000 EuroÂ lebt - und erklÃ¤rt, was Geld und GlÃ¼ck miteinander zu tun haben.', '', NULL, 'a:2:{i:0;s:60:"http://www.spiegel.de/panorama/0,1518,794458,00.html#ref=rss";i:1;s:52:"http://www.spiegel.de/panorama/0,1518,794458,00.html";}', '2011-11-16 19:06:27', '2011-11-16 06:31:00', 1, NULL, NULL, 0, 0, 0, 324);
INSERT INTO `posts` VALUES(390, 18, NULL, 'ÃœbergroÃŸe Autos: Viel zu breit', '<img src="http://www.spiegel.de/images/image-279076-thumbsmall-knnq.jpg" hspace="5" align="left" />WarumÂ gehen AutosÂ immer mehrÂ in die Breite? Die Ã¼bergroÃŸen Karosserien verstopfenÂ die StÃ¤dte,Â sie verursachen Chaos in WohnstraÃŸenÂ und sorgen auf AutobahnbaustellenÂ fÃ¼r Lebensgefahr.Â DabeiÂ ist das Breitenwachstum technischÂ Ã¼berflÃ¼ssig - undÂ pumptÂ meist nur das Ego der Besitzer auf.', '', NULL, 'a:2:{i:0;s:64:"http://www.spiegel.de/auto/aktuell/0,1518,795662,00.html#ref=rss";i:1;s:56:"http://www.spiegel.de/auto/aktuell/0,1518,795662,00.html";}', '2011-11-16 19:06:28', '2011-11-16 06:24:00', 1, NULL, NULL, 0, 0, 0, 325);
INSERT INTO `posts` VALUES(381, 18, NULL, 'Rekord-Surfer McNamara: &quot;Sie war hÃ¶her als alles, was ich je gesehen hatte&quot;', 'Garrett McNamara hat vermutlichÂ einenÂ Weltrekord aufgestellt: Der Profi-Surfer bezwang einen 27,4 Meter hohen Brecher. Im Interview spricht der 44-JÃ¤hrige Ã¼ber seinen Ritt auf der Monsterwelle - und den Moment, in dem gigantische Wassermassen Ã¼ber ihm zusammenbrachen.', '', NULL, 'a:2:{i:0;s:63:"http://www.spiegel.de/sport/sonst/0,1518,798125,00.html#ref=rss";i:1;s:55:"http://www.spiegel.de/sport/sonst/0,1518,798125,00.html";}', '2011-11-16 19:06:25', '2011-11-16 15:07:00', 1, NULL, NULL, 0, 0, 0, 316);
INSERT INTO `posts` VALUES(382, 18, NULL, 'BÃ¼tikofer twittert: &quot;Vergesst diesen Joschka Fischer!&quot;', '<img src="http://www.spiegel.de/images/image-283498-thumbsmall-qwox.jpg" hspace="5" align="left" />Ihre AnimositÃ¤ten sind legendÃ¤r:Â Die GrÃ¼nen Joschka Fischer und Reinhard BÃ¼tikofer haben sich schon zu ihrer aktiven Zeit in der Bundespolitik heftig beharkt - und sie tun es noch immer. Ein Interview des Ex-AuÃŸenministers zur Euro-Krise kommentierte BÃ¼tikofer per Twitter,Â kurz und gemein.Â ', '', NULL, 'a:2:{i:0;s:71:"http://www.spiegel.de/politik/deutschland/0,1518,798171,00.html#ref=rss";i:1;s:63:"http://www.spiegel.de/politik/deutschland/0,1518,798171,00.html";}', '2011-11-16 19:06:25', '2011-11-16 14:22:00', 1, NULL, NULL, 0, 0, 0, 317);
INSERT INTO `posts` VALUES(384, 18, NULL, 'Schuldenkrise: Fataler Euro-Dominoeffekt', 'Das Schuldendesaster in Europa greift auf immer mehr LÃ¤nder Ã¼ber. Und die Politik reagiert hilfloser denn je.Â Das Hauptproblem der KrisenbekÃ¤mpfer: Sie lernen nichtÂ aus den Fehlern anderer LÃ¤nder - und noch nicht einmal aus ihren eigenen.Â ', '', NULL, 'a:2:{i:0;s:62:"http://www.spiegel.de/wirtschaft/0,1518,798118,00.html#ref=rss";i:1;s:54:"http://www.spiegel.de/wirtschaft/0,1518,798118,00.html";}', '2011-11-16 19:06:26', '2011-11-16 14:00:00', 1, NULL, NULL, 0, 0, 0, 319);
INSERT INTO `posts` VALUES(385, 18, NULL, 'Zwickauer Terrorzelle: Neonazis hatten auch Politiker im Visier', 'Die Zwickauer Terrorzelle plante mÃ¶glicherweise auch AnschlÃ¤ge auf Politiker. Auf einer Datei der VerdÃ¤chtigen fanden Ermittler die Namen des GrÃ¼nen-Bundestagsabgeordneten Montag und des CSU-Parlamentariers Uhl. Beide zeigen sich bestÃ¼rzt.', '', NULL, 'a:2:{i:0;s:71:"http://www.spiegel.de/politik/deutschland/0,1518,798121,00.html#ref=rss";i:1;s:63:"http://www.spiegel.de/politik/deutschland/0,1518,798121,00.html";}', '2011-11-16 19:06:26', '2011-11-16 12:44:00', 1, NULL, NULL, 0, 0, 0, 320);
INSERT INTO `posts` VALUES(386, 18, NULL, 'Dubioser Waffenhandel: Deutsche KnarrenÂ fÃ¼r denÂ ZigarrenkÃ¶nig', '<img src="http://www.spiegel.de/images/image-283537-thumbsmall-uzzy.jpg" hspace="5" align="left" />Dickes GeschÃ¤ft fÃ¼r Heckler & Koch: Ein indischer ZigarrenhÃ¤ndler bestellte bei dem RÃ¼stungskonzern 17.000 Maschinenpistolen - doch der Deal kÃ¶nnteÂ gegen deutsche Ausfuhrbestimmungen verstoÃŸen. AuffÃ¤llig ist, dass StaatsanwÃ¤lte bereits in einem Ã¤hnlichen Fall gegen das schwÃ¤bische Unternehmen ermitteln.', '', NULL, 'a:2:{i:0;s:74:"http://www.spiegel.de/wirtschaft/unternehmen/0,1518,797640,00.html#ref=rss";i:1;s:66:"http://www.spiegel.de/wirtschaft/unternehmen/0,1518,797640,00.html";}', '2011-11-16 19:06:26', '2011-11-16 11:44:00', 1, NULL, NULL, 0, 0, 0, 321);
INSERT INTO `posts` VALUES(387, 18, NULL, 'Jobmarkt fÃ¼r Absolventen: &quot;Wir wollen Sie!&quot;', 'Die Jugend kommt gewaltig: AngehendeÂ Absolventen haben derzeit so gute Jobchancen wie nie zuvor, das gilt selbst fÃ¼r Geisteswissenschaftler. Dennoch fÃ¼hlen sichÂ nicht alle umworben. FÃ¼nfÂ Bewerber berichten von ihren Erfahrungen - und davon, was schieflaufen kann.', '', NULL, 'a:2:{i:0;s:72:"http://www.spiegel.de/karriere/berufsstart/0,1518,796964,00.html#ref=rss";i:1;s:64:"http://www.spiegel.de/karriere/berufsstart/0,1518,796964,00.html";}', '2011-11-16 19:06:27', '2011-11-16 11:16:00', 1, NULL, NULL, 0, 0, 0, 322);
INSERT INTO `posts` VALUES(388, 18, NULL, 'Vortrag bei Sicherheitstagung: Guttenberg kehrt auf die internationale BÃ¼hne zurÃ¼ck', '<img src="http://www.spiegel.de/images/image-183009-thumbsmall-ondv.jpg" hspace="5" align="left" />Erstmals seit seinem RÃ¼cktritt zeigt sich Karl-Theodor zu Guttenberg der Ã–ffentlichkeit. Bei einem Vortrag in Kanada will der gestrauchelte Politiker nach SPIEGEL-ONLINE-Informationen Ã¼ber die Wirtschaftskrise sprechen - angekÃ¼ndigt ist er als "angesehener Staatsmann".', '', NULL, 'a:2:{i:0;s:67:"http://www.spiegel.de/politik/ausland/0,1518,798027,00.html#ref=rss";i:1;s:59:"http://www.spiegel.de/politik/ausland/0,1518,798027,00.html";}', '2011-11-16 19:06:27', '2011-11-16 10:16:00', 1, NULL, NULL, 0, 0, 0, 323);
INSERT INTO `posts` VALUES(383, 18, NULL, 'DFB-Kader: Der groÃŸe EM-Check', '<img src="http://www.spiegel.de/images/image-283060-thumbsmall-ygnu.jpg" hspace="5" align="left" />Wer darf mit zur Europameisterschaft, wer muss zu Hause bleiben? 23 PlÃ¤tze hat Bundestrainer Joachim LÃ¶w fÃ¼r den EM-Kader im kommenden Jahr zu vergeben, 18 Spieler haben ihre Nominierung schon so gut wie sicher. Einige mÃ¼ssen noch zittern, andere sind fast chancenlos.', '', NULL, 'a:2:{i:0;s:66:"http://www.spiegel.de/sport/fussball/0,1518,792962,00.html#ref=rss";i:1;s:58:"http://www.spiegel.de/sport/fussball/0,1518,792962,00.html";}', '2011-11-16 19:06:26', '2011-11-16 14:03:00', 1, NULL, NULL, 0, 0, 0, 318);
INSERT INTO `posts` VALUES(380, 18, NULL, 'Finanzkrise in Fernost: In den FÃ¤ngen der Schattenbanker', '<img src="http://www.spiegel.de/images/image-170761-thumbsmall-wrqh.jpg" hspace="5" align="left" />Jahrelang hat China seine Wirtschaft mit billigem Geld gepÃ¤ppelt - und dabei eine gewaltige Kreditblase geschaffen. Nun wollen die roten Machthaber dieÂ Gefahr entschÃ¤rfen. Ein riskantes ManÃ¶ver, im schlimmsten Fall droht dem Finanzsystem der Kollaps.', '', NULL, 'a:2:{i:0;s:74:"http://www.spiegel.de/wirtschaft/unternehmen/0,1518,798185,00.html#ref=rss";i:1;s:66:"http://www.spiegel.de/wirtschaft/unternehmen/0,1518,798185,00.html";}', '2011-11-16 19:06:25', '2011-11-16 15:55:00', 1, NULL, NULL, 0, 0, 0, 315);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `posts_users`
--

CREATE TABLE `posts_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `repost` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `post_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=391 ;

--
-- Daten für Tabelle `posts_users`
--

INSERT INTO `posts_users` VALUES(388, 0, 18, NULL, 388, '2011-11-16 10:16:00', 1);
INSERT INTO `posts_users` VALUES(381, 0, 18, NULL, 381, '2011-11-16 15:07:00', 1);
INSERT INTO `posts_users` VALUES(386, 0, 18, NULL, 386, '2011-11-16 11:44:00', 1);
INSERT INTO `posts_users` VALUES(387, 0, 18, NULL, 387, '2011-11-16 11:16:00', 1);
INSERT INTO `posts_users` VALUES(384, 0, 18, NULL, 384, '2011-11-16 14:00:00', 1);
INSERT INTO `posts_users` VALUES(198, 0, 18, NULL, 198, '2011-11-16 16:24:53', 1);
INSERT INTO `posts_users` VALUES(383, 0, 18, NULL, 383, '2011-11-16 14:03:00', 1);
INSERT INTO `posts_users` VALUES(62, 0, 18, NULL, 62, '2011-11-15 14:30:57', 1);
INSERT INTO `posts_users` VALUES(390, 0, 18, NULL, 390, '2011-11-16 06:24:00', 1);
INSERT INTO `posts_users` VALUES(61, 0, 18, NULL, 61, '2011-11-15 14:30:28', 1);
INSERT INTO `posts_users` VALUES(382, 0, 18, NULL, 382, '2011-11-16 14:22:00', 1);
INSERT INTO `posts_users` VALUES(389, 0, 18, NULL, 389, '2011-11-16 06:31:00', 1);
INSERT INTO `posts_users` VALUES(385, 0, 18, NULL, 385, '2011-11-16 12:44:00', 1);
INSERT INTO `posts_users` VALUES(380, 0, 18, NULL, 380, '2011-11-16 15:55:00', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `reasons`
--

CREATE TABLE `reasons` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `value` varchar(2255) NOT NULL,
  `type` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Daten für Tabelle `reasons`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `robot_tasks`
--

CREATE TABLE `robot_tasks` (
  `id` char(36) NOT NULL,
  `robot_task_action_id` char(36) NOT NULL,
  `action` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `parameters` blob,
  `scheduled` datetime NOT NULL,
  `started` datetime DEFAULT NULL,
  `finished` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `robot_task_action_id` (`robot_task_action_id`),
  KEY `status__weight__scheduled` (`status`,`weight`,`scheduled`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `robot_tasks`
--

INSERT INTO `robot_tasks` VALUES('4ec38b81-a874-47af-b2ba-6f7b407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2231223b7d, '2011-11-16 11:08:01', '2011-11-16 11:08:02', NULL, '2011-11-16 11:08:01', '2011-11-16 11:08:02');
INSERT INTO `robot_tasks` VALUES('4ec38b81-bcfc-4b0e-9fbe-6f7b407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2232223b7d, '2011-11-16 11:08:01', '2011-11-16 11:08:32', NULL, '2011-11-16 11:08:01', '2011-11-16 11:08:32');
INSERT INTO `robot_tasks` VALUES('4ec38b81-3b20-4cd8-bb69-6f7b407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2233223b7d, '2011-11-16 11:08:01', '2011-11-16 11:08:34', NULL, '2011-11-16 11:08:01', '2011-11-16 11:08:34');
INSERT INTO `robot_tasks` VALUES('4ec38d76-00d4-40c1-a690-a35c407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2231223b7d, '2011-11-16 11:16:22', '2011-11-16 11:16:22', NULL, '2011-11-16 11:16:22', '2011-11-16 11:16:22');
INSERT INTO `robot_tasks` VALUES('4ec38f79-8ad4-41f0-a4f2-a4eb407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2231223b7d, '2011-11-16 11:24:57', '2011-11-16 11:24:57', NULL, '2011-11-16 11:24:57', '2011-11-16 11:24:57');
INSERT INTO `robot_tasks` VALUES('4ec38f79-c0d0-44b3-8f36-a4eb407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2232223b7d, '2011-11-16 11:24:57', '2011-11-16 11:29:57', NULL, '2011-11-16 11:24:57', '2011-11-16 11:29:57');
INSERT INTO `robot_tasks` VALUES('4ec38ce4-7494-4f83-bcb4-a2f3407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2231223b7d, '2011-11-16 11:13:56', '2011-11-16 11:13:57', NULL, '2011-11-16 11:13:56', '2011-11-16 11:13:57');
INSERT INTO `robot_tasks` VALUES('4ec38c1f-9eb4-43d9-86c9-a265407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2231223b7d, '2011-11-16 11:10:39', '2011-11-16 11:10:40', NULL, '2011-11-16 11:10:39', '2011-11-16 11:10:40');
INSERT INTO `robot_tasks` VALUES('4ec38f11-61a0-449b-9a9c-a49e407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2231223b7d, '2011-11-16 11:23:13', '2011-11-16 11:23:13', NULL, '2011-11-16 11:23:13', '2011-11-16 11:23:13');
INSERT INTO `robot_tasks` VALUES('4ec38ec1-1154-4a23-866a-a466407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2234223b7d, '2011-11-16 11:21:53', '2011-11-16 11:21:53', NULL, '2011-11-16 11:21:53', '2011-11-16 11:21:53');
INSERT INTO `robot_tasks` VALUES('4ec38d76-302c-4dd2-8cad-a35c407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2232223b7d, '2011-11-16 11:16:22', '2011-11-16 11:20:32', NULL, '2011-11-16 11:16:22', '2011-11-16 11:20:32');
INSERT INTO `robot_tasks` VALUES('4ec38f79-f3d0-45b9-bf5d-a4eb407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2234223b7d, '2011-11-16 11:24:57', '2011-11-16 11:30:00', NULL, '2011-11-16 11:24:57', '2011-11-16 11:30:00');
INSERT INTO `robot_tasks` VALUES('4ec38d76-263c-4132-a20f-a35c407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2233223b7d, '2011-11-16 11:16:22', '2011-11-16 11:20:36', NULL, '2011-11-16 11:16:22', '2011-11-16 11:20:36');
INSERT INTO `robot_tasks` VALUES('4ec38f11-d6dc-4677-99dd-a49e407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2234223b7d, '2011-11-16 11:23:13', '2011-11-16 11:23:31', NULL, '2011-11-16 11:23:13', '2011-11-16 11:23:31');
INSERT INTO `robot_tasks` VALUES('4ec38f11-e2c0-4c78-b1bb-a49e407a3330', '4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, 'running', 0x613a313a7b733a373a22666565645f6964223b733a313a2232223b7d, '2011-11-16 11:23:13', '2011-11-16 11:23:36', NULL, '2011-11-16 11:23:13', '2011-11-16 11:23:36');
INSERT INTO `robot_tasks` VALUES('4ec3fba4-9164-4870-be21-f38a407a3330', '4ec38b81-1c7c-4b2e-b85c-6f7b407a3330', '/rss/scheduleAllFeedsForCrawling', 0, 'pending', NULL, '2011-11-16 19:06:33', NULL, NULL, '2011-11-16 19:06:28', '2011-11-16 19:06:28');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `robot_task_actions`
--

CREATE TABLE `robot_task_actions` (
  `id` char(36) NOT NULL,
  `action` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `action` (`action`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `robot_task_actions`
--

INSERT INTO `robot_task_actions` VALUES('4ec38b81-1bdc-4489-9001-6f7b407a3330', '/rss/feedCrawl', 0, '2011-11-16 11:08:01', '2011-11-16 11:08:01');
INSERT INTO `robot_task_actions` VALUES('4ec38b81-1c7c-4b2e-b85c-6f7b407a3330', '/rss/scheduleAllFeedsForCrawling', 0, '2011-11-16 11:08:01', '2011-11-16 11:08:01');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `premiumnew` tinyint(1) DEFAULT NULL,
  `premium` tinyint(1) DEFAULT NULL,
  `ref_type` varchar(10) NOT NULL,
  `ref_id` int(11) NOT NULL,
  `source` varchar(255) NOT NULL,
  `target_controller` varchar(100) NOT NULL,
  `target_action` varchar(100) NOT NULL,
  `target_param` varchar(20) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=625 ;

--
-- Daten für Tabelle `routes`
--

INSERT INTO `routes` VALUES(109, NULL, NULL, 'POST', 4196, '/a/tim/nationalelf-reus-wieder-fit', 'posts', 'view', '4196', NULL);
INSERT INTO `routes` VALUES(110, NULL, NULL, 'POST', 4197, '/a/tim/montenegros-grosse-fussball-hoffnung', 'posts', 'view', '4197', NULL);
INSERT INTO `routes` VALUES(111, NULL, NULL, 'POST', 4198, '/a/tim/hiddink-zittert-um-seinen-job', 'posts', 'view', '4198', NULL);
INSERT INTO `routes` VALUES(112, NULL, NULL, 'POST', 4199, '/a/tim/gomez-vor-50-dfb-einsatz-kapitaensfrage-weiter-offen', 'posts', 'view', '4199', NULL);
INSERT INTO `routes` VALUES(113, NULL, NULL, 'POST', 4200, '/a/tim/oral-neuer-trainer-beim-fc-ingolstadt', 'posts', 'view', '4200', NULL);
INSERT INTO `routes` VALUES(114, NULL, NULL, 'POST', 4201, '/a/tim/1-ffc-frankfurt-trotz-niederlage-weiter', 'posts', 'view', '4201', NULL);
INSERT INTO `routes` VALUES(115, NULL, NULL, 'POST', 4202, '/a/tim/die-stuetzen-der-ukrainischen-nationalmannschaft', 'posts', 'view', '4202', NULL);
INSERT INTO `routes` VALUES(116, NULL, NULL, 'POST', 4203, '/a/tim/robert-enke-ausnahmetorwart-und-besonderer-mensch', 'posts', 'view', '4203', NULL);
INSERT INTO `routes` VALUES(117, NULL, NULL, 'POST', 4204, '/a/tim/daum-traeumt-schon-vom-konzert-der-grossen', 'posts', 'view', '4204', NULL);
INSERT INTO `routes` VALUES(118, NULL, NULL, 'POST', 4205, '/a/tim/oliver-reck-bleibt-cheftrainer-des-msv-duisburg', 'posts', 'view', '4205', NULL);
INSERT INTO `routes` VALUES(119, NULL, NULL, 'POST', 4206, '/a/tim/podolski-befeuert-abschiedsgeruechte', 'posts', 'view', '4206', NULL);
INSERT INTO `routes` VALUES(120, NULL, NULL, 'POST', 4207, '/a/tim/aufholjagd-der-trend-am-bundesliga-wochenende', 'posts', 'view', '4207', NULL);
INSERT INTO `routes` VALUES(121, NULL, NULL, 'POST', 4208, '/a/tim/bayern-zittert-sich-zum-erfolg', 'posts', 'view', '4208', NULL);
INSERT INTO `routes` VALUES(122, NULL, NULL, 'POST', 4209, '/a/tim/pukki-rettet-schalke-einen-punkt', 'posts', 'view', '4209', NULL);
INSERT INTO `routes` VALUES(123, NULL, NULL, 'POST', 4210, '/a/tim/das-raetsel-hoffenheim', 'posts', 'view', '4210', NULL);
INSERT INTO `routes` VALUES(124, NULL, NULL, 'POST', 4211, '/a/tim/hamburg-beweist-einmal-mehr-moral', 'posts', 'view', '4211', NULL);
INSERT INTO `routes` VALUES(125, NULL, NULL, 'POST', 4212, '/a/tim/werder-fehlen-die-worte', 'posts', 'view', '4212', NULL);
INSERT INTO `routes` VALUES(126, NULL, NULL, 'POST', 4213, '/a/tim/reus-macht-sich-unersetzlich', 'posts', 'view', '4213', NULL);
INSERT INTO `routes` VALUES(127, NULL, NULL, 'POST', 4214, '/a/tim/ein-aergerlicher-tag-fuer-den-club', 'posts', 'view', '4214', NULL);
INSERT INTO `routes` VALUES(128, NULL, NULL, 'POST', 4215, '/a/tim/dortmund-zu-stark-fuer-wolfsburg', 'posts', 'view', '4215', NULL);
INSERT INTO `routes` VALUES(129, NULL, NULL, 'POST', 4216, '/a/tim/mainz-05-darf-wieder-mal-feiern', 'posts', 'view', '4216', NULL);
INSERT INTO `routes` VALUES(130, NULL, NULL, 'POST', 4217, '/a/tim/dfb-schiedsrichter-mussten-zum-rapport', 'posts', 'view', '4217', NULL);
INSERT INTO `routes` VALUES(131, NULL, NULL, 'POST', 4218, '/a/tim/ultras-im-fokus', 'posts', 'view', '4218', NULL);
INSERT INTO `routes` VALUES(132, NULL, NULL, 'POST', 4219, '/a/tim/keine-party-mehr-in-mainz', 'posts', 'view', '4219', NULL);
INSERT INTO `routes` VALUES(133, NULL, NULL, 'POST', 4220, '/a/tim/hsv-gegen-lautern-nur-moralischer-sieger', 'posts', 'view', '4220', NULL);
INSERT INTO `routes` VALUES(134, NULL, NULL, 'POST', 4221, '/a/tim/mainz-05-darf-wieder-mal-feiern-a4221', 'posts', 'view', '4221', NULL);
INSERT INTO `routes` VALUES(135, NULL, NULL, 'POST', 4222, '/a/tim/dfb-schiedsrichter-mussten-zum-rapport-a4222', 'posts', 'view', '4222', NULL);
INSERT INTO `routes` VALUES(136, NULL, NULL, 'POST', 4223, '/a/tim/ultras-im-fokus-a4223', 'posts', 'view', '4223', NULL);
INSERT INTO `routes` VALUES(137, NULL, NULL, 'POST', 4224, '/a/tim/keine-party-mehr-in-mainz-a4224', 'posts', 'view', '4224', NULL);
INSERT INTO `routes` VALUES(138, NULL, NULL, 'POST', 4225, '/a/tim/hsv-gegen-lautern-nur-moralischer-sieger-a4225', 'posts', 'view', '4225', NULL);
INSERT INTO `routes` VALUES(139, NULL, NULL, 'POST', 1, '/a/tim/dfb-elf-gegen-oranje-die-historischen-duelle', 'posts', 'view', '1', 235);
INSERT INTO `routes` VALUES(140, NULL, NULL, 'POST', 2, '/a/tim/turbine-potsdam-schockt-1-ffc-frankfurt', 'posts', 'view', '2', 236);
INSERT INTO `routes` VALUES(141, NULL, NULL, 'POST', 3, '/a/tim/overath-tritt-als-praesident-des-1-fc-koeln-zurueck', 'posts', 'view', '3', 237);
INSERT INTO `routes` VALUES(142, NULL, NULL, 'POST', 4, '/a/tim/england-schlaegt-weltmeister-spanien', 'posts', 'view', '4', 238);
INSERT INTO `routes` VALUES(143, NULL, NULL, 'POST', 5, '/a/tim/em-fuer-tuerkei-fast-schon-ausser-sicht', 'posts', 'view', '5', 239);
INSERT INTO `routes` VALUES(144, NULL, NULL, 'POST', 6, '/a/tim/mlapa-trifft-drei-mal-gegen-griechenland', 'posts', 'view', '6', 240);
INSERT INTO `routes` VALUES(145, NULL, NULL, 'POST', 7, '/a/tim/oral-neuer-trainer-beim-fc-ingolstadt-a7', 'posts', 'view', '7', 241);
INSERT INTO `routes` VALUES(146, NULL, NULL, 'POST', 8, '/a/tim/1-ffc-frankfurt-trotz-niederlage-weiter-a8', 'posts', 'view', '8', 242);
INSERT INTO `routes` VALUES(147, NULL, NULL, 'POST', 9, '/a/tim/robert-enke-ausnahmetorwart-und-besonderer-mensch-a9', 'posts', 'view', '9', 243);
INSERT INTO `routes` VALUES(148, NULL, NULL, 'POST', 10, '/a/tim/die-stuetzen-der-ukrainischen-nationalmannschaft-a10', 'posts', 'view', '10', 244);
INSERT INTO `routes` VALUES(149, NULL, NULL, 'POST', 11, '/a/tim/oliver-reck-bleibt-cheftrainer-des-msv-duisburg-a11', 'posts', 'view', '11', 245);
INSERT INTO `routes` VALUES(150, NULL, NULL, 'POST', 12, '/a/tim/podolski-befeuert-abschiedsgeruechte-a12', 'posts', 'view', '12', 246);
INSERT INTO `routes` VALUES(151, NULL, NULL, 'POST', 13, '/a/tim/aufholjagd-der-trend-am-bundesliga-wochenende-a13', 'posts', 'view', '13', 247);
INSERT INTO `routes` VALUES(152, NULL, NULL, 'POST', 14, '/a/tim/bayern-zittert-sich-zum-erfolg-a14', 'posts', 'view', '14', 248);
INSERT INTO `routes` VALUES(153, NULL, NULL, 'POST', 15, '/a/tim/pukki-rettet-schalke-einen-punkt-a15', 'posts', 'view', '15', 249);
INSERT INTO `routes` VALUES(154, NULL, NULL, 'POST', 16, '/a/tim/das-raetsel-hoffenheim-a16', 'posts', 'view', '16', 250);
INSERT INTO `routes` VALUES(155, NULL, NULL, 'POST', 17, '/a/tim/hamburg-beweist-einmal-mehr-moral-a17', 'posts', 'view', '17', 251);
INSERT INTO `routes` VALUES(156, NULL, NULL, 'POST', 18, '/a/tim/werder-fehlen-die-worte-a18', 'posts', 'view', '18', 252);
INSERT INTO `routes` VALUES(157, NULL, NULL, 'POST', 19, '/a/tim/reus-macht-sich-unersetzlich-a19', 'posts', 'view', '19', 253);
INSERT INTO `routes` VALUES(158, NULL, NULL, 'POST', 20, '/a/tim/ein-aergerlicher-tag-fuer-den-club-a20', 'posts', 'view', '20', 254);
INSERT INTO `routes` VALUES(159, NULL, NULL, 'POST', 21, '/a/tim/dortmund-zu-stark-fuer-wolfsburg-a21', 'posts', 'view', '21', 255);
INSERT INTO `routes` VALUES(160, NULL, NULL, 'POST', 22, '/a/tim/mainz-05-darf-wieder-mal-feiern-a22', 'posts', 'view', '22', 256);
INSERT INTO `routes` VALUES(161, NULL, NULL, 'POST', 23, '/a/tim/dfb-schiedsrichter-mussten-zum-rapport-a23', 'posts', 'view', '23', 257);
INSERT INTO `routes` VALUES(162, NULL, NULL, 'POST', 24, '/a/tim/ultras-im-fokus-a24', 'posts', 'view', '24', 258);
INSERT INTO `routes` VALUES(163, NULL, NULL, 'POST', 25, '/a/tim/keine-party-mehr-in-mainz-a25', 'posts', 'view', '25', 259);
INSERT INTO `routes` VALUES(164, NULL, NULL, 'POST', 26, '/a/alf/overath-tritt-als-praesident-des-1-fc-koeln-zurueck', 'posts', 'view', '26', 260);
INSERT INTO `routes` VALUES(165, NULL, NULL, 'POST', 27, '/a/alf/podolski-befeuert-abschiedsgeruechte', 'posts', 'view', '27', 261);
INSERT INTO `routes` VALUES(166, NULL, NULL, 'POST', 28, '/a/alf/aufholjagd-der-trend-am-bundesliga-wochenende', 'posts', 'view', '28', 262);
INSERT INTO `routes` VALUES(167, NULL, NULL, 'POST', 29, '/a/alf/bayern-zittert-sich-zum-erfolg', 'posts', 'view', '29', 263);
INSERT INTO `routes` VALUES(168, NULL, NULL, 'POST', 30, '/a/alf/pukki-rettet-schalke-einen-punkt', 'posts', 'view', '30', 264);
INSERT INTO `routes` VALUES(169, NULL, NULL, 'POST', 31, '/a/alf/das-raetsel-hoffenheim', 'posts', 'view', '31', 265);
INSERT INTO `routes` VALUES(170, NULL, NULL, 'POST', 32, '/a/alf/hamburg-beweist-einmal-mehr-moral', 'posts', 'view', '32', 266);
INSERT INTO `routes` VALUES(171, NULL, NULL, 'POST', 33, '/a/alf/werder-fehlen-die-worte', 'posts', 'view', '33', 267);
INSERT INTO `routes` VALUES(172, NULL, NULL, 'POST', 34, '/a/alf/reus-macht-sich-unersetzlich', 'posts', 'view', '34', 268);
INSERT INTO `routes` VALUES(173, NULL, NULL, 'POST', 35, '/a/alf/ein-aergerlicher-tag-fuer-den-club', 'posts', 'view', '35', 269);
INSERT INTO `routes` VALUES(174, NULL, NULL, 'POST', 36, '/a/alf/dortmund-zu-stark-fuer-wolfsburg', 'posts', 'view', '36', 270);
INSERT INTO `routes` VALUES(175, NULL, NULL, 'POST', 37, '/a/alf/mainz-05-darf-wieder-mal-feiern', 'posts', 'view', '37', 271);
INSERT INTO `routes` VALUES(176, NULL, NULL, 'POST', 38, '/a/alf/dfb-schiedsrichter-mussten-zum-rapport', 'posts', 'view', '38', 272);
INSERT INTO `routes` VALUES(177, NULL, NULL, 'POST', 39, '/a/alf/ultras-im-fokus', 'posts', 'view', '39', 273);
INSERT INTO `routes` VALUES(178, NULL, NULL, 'POST', 40, '/a/alf/keine-party-mehr-in-mainz', 'posts', 'view', '40', 274);
INSERT INTO `routes` VALUES(179, NULL, NULL, 'POST', 41, '/a/tim/tumulte-und-traenen-bei-overath-abschied', 'posts', 'view', '41', 275);
INSERT INTO `routes` VALUES(180, NULL, NULL, 'POST', 42, '/a/alf/tumulte-und-traenen-bei-overath-abschied', 'posts', 'view', '42', 276);
INSERT INTO `routes` VALUES(181, NULL, NULL, 'POST', 43, '/a/alf/tumulte-und-traenen-bei-overath-abschied-a43', 'posts', 'view', '43', 277);
INSERT INTO `routes` VALUES(182, NULL, NULL, 'POST', 44, '/a/alf/overath-tritt-als-praesident-des-1-fc-koeln-zurueck-a44', 'posts', 'view', '44', 278);
INSERT INTO `routes` VALUES(183, NULL, NULL, 'POST', 45, '/a/alf/podolski-befeuert-abschiedsgeruechte-a45', 'posts', 'view', '45', 279);
INSERT INTO `routes` VALUES(184, NULL, NULL, 'POST', 46, '/a/alf/aufholjagd-der-trend-am-bundesliga-wochenende-a46', 'posts', 'view', '46', 280);
INSERT INTO `routes` VALUES(185, NULL, NULL, 'POST', 47, '/a/alf/bayern-zittert-sich-zum-erfolg-a47', 'posts', 'view', '47', 281);
INSERT INTO `routes` VALUES(186, NULL, NULL, 'POST', 48, '/a/alf/pukki-rettet-schalke-einen-punkt-a48', 'posts', 'view', '48', 282);
INSERT INTO `routes` VALUES(187, NULL, NULL, 'POST', 49, '/a/alf/das-raetsel-hoffenheim-a49', 'posts', 'view', '49', 283);
INSERT INTO `routes` VALUES(188, NULL, NULL, 'POST', 50, '/a/alf/hamburg-beweist-einmal-mehr-moral-a50', 'posts', 'view', '50', 284);
INSERT INTO `routes` VALUES(189, NULL, NULL, 'POST', 51, '/a/alf/werder-fehlen-die-worte-a51', 'posts', 'view', '51', 285);
INSERT INTO `routes` VALUES(190, NULL, NULL, 'POST', 52, '/a/alf/reus-macht-sich-unersetzlich-a52', 'posts', 'view', '52', 286);
INSERT INTO `routes` VALUES(191, NULL, NULL, 'POST', 53, '/a/alf/ein-aergerlicher-tag-fuer-den-club-a53', 'posts', 'view', '53', 287);
INSERT INTO `routes` VALUES(192, NULL, NULL, 'POST', 54, '/a/alf/dortmund-zu-stark-fuer-wolfsburg-a54', 'posts', 'view', '54', 288);
INSERT INTO `routes` VALUES(193, NULL, NULL, 'POST', 55, '/a/alf/mainz-05-darf-wieder-mal-feiern-a55', 'posts', 'view', '55', 289);
INSERT INTO `routes` VALUES(194, NULL, NULL, 'POST', 56, '/a/alf/dfb-schiedsrichter-mussten-zum-rapport-a56', 'posts', 'view', '56', 290);
INSERT INTO `routes` VALUES(195, NULL, NULL, 'POST', 57, '/a/alf/ultras-im-fokus-a57', 'posts', 'view', '57', 291);
INSERT INTO `routes` VALUES(196, NULL, NULL, 'POST', 58, '/a/tim/london-2012-der-ball-laeuft-nicht-rund', 'posts', 'view', '58', 292);
INSERT INTO `routes` VALUES(197, NULL, NULL, 'POST', 59, '/a/tim/london-2012-ein-jahr-vor-der-eroeffnungsfeier', 'posts', 'view', '59', 293);
INSERT INTO `routes` VALUES(198, NULL, NULL, 'POST', 60, '/a/tim/dosb-gegen-olympia-schnellschuesse', 'posts', 'view', '60', 294);
INSERT INTO `routes` VALUES(199, NULL, NULL, 'POST', 61, '/a/tim/deutschlands-gescheiterte-olympia-bewerbungen', 'posts', 'view', '61', 295);
INSERT INTO `routes` VALUES(200, NULL, NULL, 'POST', 62, '/a/tim/diskussion-um-neue-deutsche-olympia-bewerbung', 'posts', 'view', '62', 296);
INSERT INTO `routes` VALUES(201, NULL, NULL, 'POST', 63, '/a/tim/olympia-2018-pyeongchang-erhaelt-den-zuschlag', 'posts', 'view', '63', 297);
INSERT INTO `routes` VALUES(202, NULL, NULL, 'POST', 64, '/a/tim/diese-illustre-runde-entscheidet-ueber-olympia-2018', 'posts', 'view', '64', 298);
INSERT INTO `routes` VALUES(203, NULL, NULL, 'POST', 65, '/a/tim/polit-prominenz-unterstuetzt-muenchen-in-durban', 'posts', 'view', '65', 299);
INSERT INTO `routes` VALUES(204, NULL, NULL, 'POST', 66, '/a/tim/pyeongchang-ist-bereit-fuer-olympia', 'posts', 'view', '66', 300);
INSERT INTO `routes` VALUES(205, NULL, NULL, 'POST', 67, '/a/tim/muenchen-2018-die-koepfe-hinter-der-bewerbung', 'posts', 'view', '67', 301);
INSERT INTO `routes` VALUES(206, NULL, NULL, 'POST', 68, '/a/tim/cas-kippt-die-osaka-regel', 'posts', 'view', '68', 302);
INSERT INTO `routes` VALUES(207, NULL, NULL, 'POST', 69, '/a/tim/studie-doping-mit-system-auch-im-westen', 'posts', 'view', '69', 303);
INSERT INTO `routes` VALUES(208, NULL, NULL, 'POST', 70, '/a/tim/deutsche-dopingjaeger-ruesten-auf', 'posts', 'view', '70', 304);
INSERT INTO `routes` VALUES(209, NULL, NULL, 'POST', 71, '/a/tim/longos-ehemann-und-trainer-unter-verdacht', 'posts', 'view', '71', 305);
INSERT INTO `routes` VALUES(210, NULL, NULL, 'POST', 72, '/a/tim/doping-kontrollen-im-fussball-zu-lasch', 'posts', 'view', '72', 306);
INSERT INTO `routes` VALUES(211, NULL, NULL, 'POST', 73, '/a/tim/cas-befasst-sich-mit-dem-quot-fall-ullrich-quot', 'posts', 'view', '73', 307);
INSERT INTO `routes` VALUES(212, NULL, NULL, 'POST', 74, '/a/tim/landis-attackiert-contador', 'posts', 'view', '74', 308);
INSERT INTO `routes` VALUES(213, NULL, NULL, 'POST', 75, '/a/tim/top-sprinter-positiv-getestet', 'posts', 'view', '75', 309);
INSERT INTO `routes` VALUES(214, NULL, NULL, 'POST', 76, '/a/tim/cas-kippt-die-osaka-regel-a76', 'posts', 'view', '76', 310);
INSERT INTO `routes` VALUES(215, NULL, NULL, 'POST', 77, '/a/tim/studie-doping-mit-system-auch-im-westen-a77', 'posts', 'view', '77', 311);
INSERT INTO `routes` VALUES(216, NULL, NULL, 'POST', 78, '/a/tim/deutsche-dopingjaeger-ruesten-auf-a78', 'posts', 'view', '78', 312);
INSERT INTO `routes` VALUES(217, NULL, NULL, 'POST', 79, '/a/tim/longos-ehemann-und-trainer-unter-verdacht-a79', 'posts', 'view', '79', 313);
INSERT INTO `routes` VALUES(218, NULL, NULL, 'POST', 80, '/a/tim/doping-kontrollen-im-fussball-zu-lasch-a80', 'posts', 'view', '80', 314);
INSERT INTO `routes` VALUES(219, NULL, NULL, 'POST', 81, '/a/tim/cas-befasst-sich-mit-dem-quot-fall-ullrich-quot-a81', 'posts', 'view', '81', 315);
INSERT INTO `routes` VALUES(220, NULL, NULL, 'POST', 82, '/a/tim/landis-attackiert-contador-a82', 'posts', 'view', '82', 316);
INSERT INTO `routes` VALUES(221, NULL, NULL, 'POST', 83, '/a/tim/top-sprinter-positiv-getestet-a83', 'posts', 'view', '83', 317);
INSERT INTO `routes` VALUES(222, NULL, NULL, 'POST', 84, '/a/tim/weltcup-auftakt-in-sjusjoen-gesichert', 'posts', 'view', '84', 318);
INSERT INTO `routes` VALUES(223, NULL, NULL, 'POST', 85, '/a/tim/grandiose-vonn-siegt-rebensburg-zweite', 'posts', 'view', '85', 319);
INSERT INTO `routes` VALUES(224, NULL, NULL, 'POST', 86, '/a/tim/alpin-biathlon-und-co-ein-blick-auf-den-winter', 'posts', 'view', '86', 320);
INSERT INTO `routes` VALUES(225, NULL, NULL, 'POST', 87, '/a/tim/contadors-verhandlung-verschoben', 'posts', 'view', '87', 321);
INSERT INTO `routes` VALUES(226, NULL, NULL, 'POST', 88, '/a/tim/gespraeche-zum-tourstart-in-berlin', 'posts', 'view', '88', 322);
INSERT INTO `routes` VALUES(227, NULL, NULL, 'POST', 89, '/a/tim/tour-de-france-langsamer-spannender-und-sauberer', 'posts', 'view', '89', 323);
INSERT INTO `routes` VALUES(228, NULL, NULL, 'POST', 90, '/a/tim/htc-zukunft-scheint-gesichert', 'posts', 'view', '90', 324);
INSERT INTO `routes` VALUES(229, NULL, NULL, 'POST', 91, '/a/tim/rueckblick-licht-und-schatten-bei-der-tour', 'posts', 'view', '91', 325);
INSERT INTO `routes` VALUES(230, NULL, NULL, 'POST', 92, '/a/tim/cavendish-zum-fuenften-evans-toursieger', 'posts', 'view', '92', 326);
INSERT INTO `routes` VALUES(231, NULL, NULL, 'POST', 93, '/a/tim/freude-trauer-und-enttaeuschung-die-deutsche-bilanz', 'posts', 'view', '93', 327);
INSERT INTO `routes` VALUES(232, NULL, NULL, 'POST', 94, '/a/tim/paris-ein-gefuehl-fuer-die-ewigkeit', 'posts', 'view', '94', 328);
INSERT INTO `routes` VALUES(233, NULL, NULL, 'POST', 95, '/a/tim/etappenrueckblick-auf-drei-wochen-tour', 'posts', 'view', '95', 329);
INSERT INTO `routes` VALUES(234, NULL, NULL, 'POST', 96, '/a/tim/quo-vadis-deutscher-radsport', 'posts', 'view', '96', 330);
INSERT INTO `routes` VALUES(235, NULL, NULL, 'POST', 1, '/a/tim/contadors-verhandlung-verschoben-a1', 'posts', 'view', '1', NULL);
INSERT INTO `routes` VALUES(236, NULL, NULL, 'POST', 2, '/a/alf/contadors-verhandlung-verschoben', 'posts', 'view', '2', NULL);
INSERT INTO `routes` VALUES(237, NULL, NULL, 'POST', 3, '/a/tim/gespraeche-zum-tourstart-in-berlin-a3', 'posts', 'view', '3', NULL);
INSERT INTO `routes` VALUES(238, NULL, NULL, 'POST', 4, '/a/alf/gespraeche-zum-tourstart-in-berlin', 'posts', 'view', '4', NULL);
INSERT INTO `routes` VALUES(239, NULL, NULL, 'POST', 5, '/a/tim/tour-de-france-langsamer-spannender-und-sauberer-a5', 'posts', 'view', '5', NULL);
INSERT INTO `routes` VALUES(240, NULL, NULL, 'POST', 6, '/a/alf/tour-de-france-langsamer-spannender-und-sauberer', 'posts', 'view', '6', NULL);
INSERT INTO `routes` VALUES(241, NULL, NULL, 'POST', 7, '/a/tim/htc-zukunft-scheint-gesichert-a7', 'posts', 'view', '7', NULL);
INSERT INTO `routes` VALUES(242, NULL, NULL, 'POST', 8, '/a/alf/htc-zukunft-scheint-gesichert', 'posts', 'view', '8', NULL);
INSERT INTO `routes` VALUES(243, NULL, NULL, 'POST', 9, '/a/tim/rueckblick-licht-und-schatten-bei-der-tour-a9', 'posts', 'view', '9', NULL);
INSERT INTO `routes` VALUES(244, NULL, NULL, 'POST', 10, '/a/alf/rueckblick-licht-und-schatten-bei-der-tour', 'posts', 'view', '10', NULL);
INSERT INTO `routes` VALUES(245, NULL, NULL, 'POST', 11, '/a/tim/cavendish-zum-fuenften-evans-toursieger-a11', 'posts', 'view', '11', NULL);
INSERT INTO `routes` VALUES(246, NULL, NULL, 'POST', 12, '/a/alf/cavendish-zum-fuenften-evans-toursieger', 'posts', 'view', '12', NULL);
INSERT INTO `routes` VALUES(247, NULL, NULL, 'POST', 13, '/a/tim/freude-trauer-und-enttaeuschung-die-deutsche-bilanz-a13', 'posts', 'view', '13', NULL);
INSERT INTO `routes` VALUES(248, NULL, NULL, 'POST', 14, '/a/alf/freude-trauer-und-enttaeuschung-die-deutsche-bilanz', 'posts', 'view', '14', NULL);
INSERT INTO `routes` VALUES(249, NULL, NULL, 'POST', 15, '/a/tim/paris-ein-gefuehl-fuer-die-ewigkeit-a15', 'posts', 'view', '15', NULL);
INSERT INTO `routes` VALUES(250, NULL, NULL, 'POST', 16, '/a/alf/paris-ein-gefuehl-fuer-die-ewigkeit', 'posts', 'view', '16', NULL);
INSERT INTO `routes` VALUES(251, NULL, NULL, 'POST', 17, '/a/tim/etappenrueckblick-auf-drei-wochen-tour-a17', 'posts', 'view', '17', NULL);
INSERT INTO `routes` VALUES(252, NULL, NULL, 'POST', 18, '/a/alf/etappenrueckblick-auf-drei-wochen-tour', 'posts', 'view', '18', NULL);
INSERT INTO `routes` VALUES(253, NULL, NULL, 'POST', 19, '/a/tim/quo-vadis-deutscher-radsport-a19', 'posts', 'view', '19', NULL);
INSERT INTO `routes` VALUES(254, NULL, NULL, 'POST', 20, '/a/alf/quo-vadis-deutscher-radsport', 'posts', 'view', '20', NULL);
INSERT INTO `routes` VALUES(255, NULL, NULL, 'POST', 21, '/a/tim/contadors-verhandlung-verschoben-a21', 'posts', 'view', '21', NULL);
INSERT INTO `routes` VALUES(256, NULL, NULL, 'POST', 22, '/a/tim/gespraeche-zum-tourstart-in-berlin-a22', 'posts', 'view', '22', NULL);
INSERT INTO `routes` VALUES(257, NULL, NULL, 'POST', 23, '/a/tim/tour-de-france-langsamer-spannender-und-sauberer-a23', 'posts', 'view', '23', NULL);
INSERT INTO `routes` VALUES(258, NULL, NULL, 'POST', 24, '/a/tim/htc-zukunft-scheint-gesichert-a24', 'posts', 'view', '24', NULL);
INSERT INTO `routes` VALUES(259, NULL, NULL, 'POST', 25, '/a/tim/rueckblick-licht-und-schatten-bei-der-tour-a25', 'posts', 'view', '25', NULL);
INSERT INTO `routes` VALUES(260, NULL, NULL, 'POST', 26, '/a/tim/cavendish-zum-fuenften-evans-toursieger-a26', 'posts', 'view', '26', NULL);
INSERT INTO `routes` VALUES(261, NULL, NULL, 'POST', 27, '/a/tim/freude-trauer-und-enttaeuschung-die-deutsche-bilanz-a27', 'posts', 'view', '27', NULL);
INSERT INTO `routes` VALUES(262, NULL, NULL, 'POST', 28, '/a/tim/paris-ein-gefuehl-fuer-die-ewigkeit-a28', 'posts', 'view', '28', NULL);
INSERT INTO `routes` VALUES(263, NULL, NULL, 'POST', 29, '/a/tim/etappenrueckblick-auf-drei-wochen-tour-a29', 'posts', 'view', '29', NULL);
INSERT INTO `routes` VALUES(264, NULL, NULL, 'POST', 30, '/a/tim/quo-vadis-deutscher-radsport-a30', 'posts', 'view', '30', NULL);
INSERT INTO `routes` VALUES(265, NULL, NULL, 'POST', 31, '/a/tim/contadors-verhandlung-verschoben-a31', 'posts', 'view', '31', NULL);
INSERT INTO `routes` VALUES(266, NULL, NULL, 'POST', 32, '/a/tim/gespraeche-zum-tourstart-in-berlin-a32', 'posts', 'view', '32', NULL);
INSERT INTO `routes` VALUES(267, NULL, NULL, 'POST', 33, '/a/tim/tour-de-france-langsamer-spannender-und-sauberer-a33', 'posts', 'view', '33', NULL);
INSERT INTO `routes` VALUES(268, NULL, NULL, 'POST', 34, '/a/tim/htc-zukunft-scheint-gesichert-a34', 'posts', 'view', '34', NULL);
INSERT INTO `routes` VALUES(269, NULL, NULL, 'POST', 35, '/a/tim/rueckblick-licht-und-schatten-bei-der-tour-a35', 'posts', 'view', '35', NULL);
INSERT INTO `routes` VALUES(270, NULL, NULL, 'POST', 36, '/a/tim/cavendish-zum-fuenften-evans-toursieger-a36', 'posts', 'view', '36', NULL);
INSERT INTO `routes` VALUES(271, NULL, NULL, 'POST', 37, '/a/tim/freude-trauer-und-enttaeuschung-die-deutsche-bilanz-a37', 'posts', 'view', '37', NULL);
INSERT INTO `routes` VALUES(272, NULL, NULL, 'POST', 38, '/a/tim/paris-ein-gefuehl-fuer-die-ewigkeit-a38', 'posts', 'view', '38', NULL);
INSERT INTO `routes` VALUES(273, NULL, NULL, 'POST', 39, '/a/tim/etappenrueckblick-auf-drei-wochen-tour-a39', 'posts', 'view', '39', NULL);
INSERT INTO `routes` VALUES(274, NULL, NULL, 'POST', 40, '/a/tim/quo-vadis-deutscher-radsport-a40', 'posts', 'view', '40', NULL);
INSERT INTO `routes` VALUES(275, NULL, NULL, 'POST', 41, '/a/tim/contadors-verhandlung-verschoben-a41', 'posts', 'view', '41', NULL);
INSERT INTO `routes` VALUES(276, NULL, NULL, 'POST', 42, '/a/tim/gespraeche-zum-tourstart-in-berlin-a42', 'posts', 'view', '42', NULL);
INSERT INTO `routes` VALUES(277, NULL, NULL, 'POST', 43, '/a/tim/tour-de-france-langsamer-spannender-und-sauberer-a43', 'posts', 'view', '43', NULL);
INSERT INTO `routes` VALUES(278, NULL, NULL, 'POST', 44, '/a/tim/htc-zukunft-scheint-gesichert-a44', 'posts', 'view', '44', NULL);
INSERT INTO `routes` VALUES(279, NULL, NULL, 'POST', 45, '/a/tim/rueckblick-licht-und-schatten-bei-der-tour-a45', 'posts', 'view', '45', NULL);
INSERT INTO `routes` VALUES(280, NULL, NULL, 'POST', 46, '/a/tim/cavendish-zum-fuenften-evans-toursieger-a46', 'posts', 'view', '46', NULL);
INSERT INTO `routes` VALUES(281, NULL, NULL, 'POST', 47, '/a/tim/freude-trauer-und-enttaeuschung-die-deutsche-bilanz-a47', 'posts', 'view', '47', NULL);
INSERT INTO `routes` VALUES(282, NULL, NULL, 'POST', 48, '/a/tim/paris-ein-gefuehl-fuer-die-ewigkeit-a48', 'posts', 'view', '48', NULL);
INSERT INTO `routes` VALUES(283, NULL, NULL, 'POST', 49, '/a/tim/etappenrueckblick-auf-drei-wochen-tour-a49', 'posts', 'view', '49', NULL);
INSERT INTO `routes` VALUES(284, NULL, NULL, 'POST', 50, '/a/tim/quo-vadis-deutscher-radsport-a50', 'posts', 'view', '50', NULL);
INSERT INTO `routes` VALUES(285, NULL, NULL, 'POST', 51, '/a/tim/contadors-verhandlung-verschoben-a51', 'posts', 'view', '51', NULL);
INSERT INTO `routes` VALUES(286, NULL, NULL, 'POST', 52, '/a/tim/gespraeche-zum-tourstart-in-berlin-a52', 'posts', 'view', '52', NULL);
INSERT INTO `routes` VALUES(287, NULL, NULL, 'POST', 53, '/a/tim/tour-de-france-langsamer-spannender-und-sauberer-a53', 'posts', 'view', '53', NULL);
INSERT INTO `routes` VALUES(288, NULL, NULL, 'POST', 54, '/a/tim/htc-zukunft-scheint-gesichert-a54', 'posts', 'view', '54', NULL);
INSERT INTO `routes` VALUES(289, NULL, NULL, 'POST', 55, '/a/tim/rueckblick-licht-und-schatten-bei-der-tour-a55', 'posts', 'view', '55', NULL);
INSERT INTO `routes` VALUES(290, NULL, NULL, 'POST', 56, '/a/tim/cavendish-zum-fuenften-evans-toursieger-a56', 'posts', 'view', '56', NULL);
INSERT INTO `routes` VALUES(291, NULL, NULL, 'POST', 57, '/a/tim/freude-trauer-und-enttaeuschung-die-deutsche-bilanz-a57', 'posts', 'view', '57', NULL);
INSERT INTO `routes` VALUES(292, NULL, NULL, 'POST', 58, '/a/tim/paris-ein-gefuehl-fuer-die-ewigkeit-a58', 'posts', 'view', '58', NULL);
INSERT INTO `routes` VALUES(293, NULL, NULL, 'POST', 59, '/a/tim/etappenrueckblick-auf-drei-wochen-tour-a59', 'posts', 'view', '59', NULL);
INSERT INTO `routes` VALUES(294, NULL, NULL, 'POST', 60, '/a/tim/quo-vadis-deutscher-radsport-a60', 'posts', 'view', '60', NULL);
INSERT INTO `routes` VALUES(295, NULL, NULL, 'POST', 61, '/a/tim/ich-hab-nen-langen-titel-ohne-punkte', 'posts', 'view', '61', NULL);
INSERT INTO `routes` VALUES(296, NULL, NULL, 'POST', 62, '/a/tim/hnier-aber-mit-punkten-weil-der-viel-zu-lang-ist-lang-ist-lang-ist-lang-ist-lang-ist', 'posts', 'view', '62', NULL);
INSERT INTO `routes` VALUES(297, NULL, NULL, 'POST', 63, '/a/tim/cdu-votiert-fuer-eine-allgemeine-verbindliche-lohnuntergrenze', 'posts', 'view', '63', NULL);
INSERT INTO `routes` VALUES(298, NULL, NULL, 'POST', 64, '/a/tim/rede-zum-mindestlohn', 'posts', 'view', '64', NULL);
INSERT INTO `routes` VALUES(299, NULL, NULL, 'POST', 65, '/a/tim/matthias-zimmer-berichtet-ueber-die-politische-lage-in-deutschland', 'posts', 'view', '65', NULL);
INSERT INTO `routes` VALUES(300, NULL, NULL, 'POST', 66, '/a/tim/arbeitsmarkt-muss-echte-teilhabe-bieten', 'posts', 'view', '66', NULL);
INSERT INTO `routes` VALUES(301, NULL, NULL, 'POST', 67, '/a/tim/ein-plaedoyer-fuer-die-wahrung-der-tarifautonomie-und-fuer-eine-allgemeine-lohnuntergrenze', 'posts', 'view', '67', NULL);
INSERT INTO `routes` VALUES(302, NULL, NULL, 'POST', 68, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck', 'posts', 'view', '68', NULL);
INSERT INTO `routes` VALUES(303, NULL, NULL, 'POST', 69, '/a/tim/traum-kick-gegen-niederlande-die-gefuehlten-europameister', 'posts', 'view', '69', NULL);
INSERT INTO `routes` VALUES(304, NULL, NULL, 'POST', 70, '/a/tim/kriselndeâ-grossbank-ubs-will-investmentsparte-radikal-zusammenstreichen', 'posts', 'view', '70', NULL);
INSERT INTO `routes` VALUES(305, NULL, NULL, 'POST', 71, '/a/tim/umfrage-fdp-rutscht-auf-zwei-prozent-ab', 'posts', 'view', '71', NULL);
INSERT INTO `routes` VALUES(306, NULL, NULL, 'POST', 72, '/a/tim/teurer-netzausbau-offshore-windenergie-droht-kollaps', 'posts', 'view', '72', NULL);
INSERT INTO `routes` VALUES(307, NULL, NULL, 'POST', 73, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot', 'posts', 'view', '73', NULL);
INSERT INTO `routes` VALUES(308, NULL, NULL, 'POST', 74, '/a/tim/uebergrosse-autos-viel-zu-breit', 'posts', 'view', '74', NULL);
INSERT INTO `routes` VALUES(309, NULL, NULL, 'POST', 75, '/a/tim/sieg-gegen-die-niederlande-deutschland-feiert-kroenenden-abschluss', 'posts', 'view', '75', NULL);
INSERT INTO `routes` VALUES(310, NULL, NULL, 'POST', 76, '/a/tim/eu-entwurf-zwist-verhindert-scharfes-vorgehen-gegen-rating-agenturen', 'posts', 'view', '76', NULL);
INSERT INTO `routes` VALUES(311, NULL, NULL, 'POST', 77, '/a/tim/geraeumteâ-occupy-aktivisten-trotz-ohne-kopf', 'posts', 'view', '77', NULL);
INSERT INTO `routes` VALUES(312, NULL, NULL, 'POST', 78, '/a/tim/konsumenten-als-konjunkturstuetze-hoffen-auf-otto-extremverbraucher', 'posts', 'view', '78', NULL);
INSERT INTO `routes` VALUES(313, NULL, NULL, 'POST', 79, '/a/tim/deutschlandâ-contra-niederlande-mach-mal-platz', 'posts', 'view', '79', NULL);
INSERT INTO `routes` VALUES(314, NULL, NULL, 'POST', 80, '/a/tim/regeln-fuer-bonitaetswaechter-eu-kommission-schont-rating-riesen', 'posts', 'view', '80', NULL);
INSERT INTO `routes` VALUES(315, NULL, NULL, 'POST', 81, '/a/tim/parteichef-gabriel-im-interview-spd-draengt-merkel-zum-handeln-bei-npd-verbot', 'posts', 'view', '81', NULL);
INSERT INTO `routes` VALUES(316, NULL, NULL, 'POST', 82, '/a/tim/videospezial-zukunftstechnik-tobi-mein-roboter-butler-mit-witz', 'posts', 'view', '82', NULL);
INSERT INTO `routes` VALUES(317, NULL, NULL, 'POST', 83, '/a/tim/thailand-flut-in-bangkok-abgeordnete-wollen-hauptstadt-aufgeben', 'posts', 'view', '83', NULL);
INSERT INTO `routes` VALUES(318, NULL, NULL, 'POST', 84, '/a/tim/neonazi-terrorzelle-kumpel-aus-dem-erzgebirge', 'posts', 'view', '84', NULL);
INSERT INTO `routes` VALUES(319, NULL, NULL, 'POST', 85, '/a/tim/konservative-in-der-cdu-das-letzte-gefecht', 'posts', 'view', '85', NULL);
INSERT INTO `routes` VALUES(320, NULL, NULL, 'POST', 86, '/a/tim/cdu-parteitag-partei-im-koma', 'posts', 'view', '86', NULL);
INSERT INTO `routes` VALUES(321, NULL, NULL, 'POST', 87, '/a/tim/john-paulson-hedgefonds-manager-verkauft-gold-anteileâ-fuer-zwei-milliarden-dollar', 'posts', 'view', '87', NULL);
INSERT INTO `routes` VALUES(322, NULL, NULL, 'POST', 88, '/a/tim/hohe-zinsen-spekulanten-wetten-gegen-spanien-belgien-frankreich', 'posts', 'view', '88', NULL);
INSERT INTO `routes` VALUES(323, NULL, NULL, 'POST', 89, '/a/tim/dell-contra-asus-ultrabook-schlaegt-notebook', 'posts', 'view', '89', NULL);
INSERT INTO `routes` VALUES(324, NULL, NULL, 'POST', 90, '/a/tim/kritik-am-verfassungsschutz-politikâ-will-geheimdienstspitzel-stoppen', 'posts', 'view', '90', NULL);
INSERT INTO `routes` VALUES(325, NULL, NULL, 'POST', 91, '/a/tim/schuldenkrise-in-europa-finanzprofisâ-fuerchten-um-den-deutschen-boom', 'posts', 'view', '91', NULL);
INSERT INTO `routes` VALUES(326, NULL, NULL, 'POST', 92, '/a/tim/agrarspekulation-deutsche-bankâ-laesst-rohstoffgeschaeft-durchleuchten', 'posts', 'view', '92', NULL);
INSERT INTO `routes` VALUES(327, NULL, NULL, 'POST', 93, '/a/tim/esc-amnesty-uebt-scharfe-kritik-an-aserbaidschan', 'posts', 'view', '93', NULL);
INSERT INTO `routes` VALUES(328, NULL, NULL, 'POST', 94, '/a/tim/kasachstans-praesident-nasarbajew-loest-parlament-auf', 'posts', 'view', '94', NULL);
INSERT INTO `routes` VALUES(329, NULL, NULL, 'POST', 95, '/a/tim/friedrich-will-zentralregister-fuer-gefaehrliche-neonazis', 'posts', 'view', '95', NULL);
INSERT INTO `routes` VALUES(330, NULL, NULL, 'POST', 96, '/a/tim/arabische-liga-beraet-ueber-ausschluss-syriens', 'posts', 'view', '96', NULL);
INSERT INTO `routes` VALUES(331, NULL, NULL, 'POST', 97, '/a/tim/quot-ghetto-renten-richter-quot-wendet-sich-aus-protest-an-landtag', 'posts', 'view', '97', NULL);
INSERT INTO `routes` VALUES(332, NULL, NULL, 'POST', 98, '/a/tim/irland-der-musterschueler-in-der-euro-krise', 'posts', 'view', '98', NULL);
INSERT INTO `routes` VALUES(333, NULL, NULL, 'POST', 99, '/a/tim/loja-dschirga-ratsversammlung-mit-unklarem-ziel', 'posts', 'view', '99', NULL);
INSERT INTO `routes` VALUES(334, NULL, NULL, 'POST', 100, '/a/tim/spd-und-cdu-einigen-sich-in-berlin-auf-koalition', 'posts', 'view', '100', NULL);
INSERT INTO `routes` VALUES(335, NULL, NULL, 'POST', 101, '/a/tim/new-york-demonstrieren-erlaubt-campen-nicht', 'posts', 'view', '101', NULL);
INSERT INTO `routes` VALUES(336, NULL, NULL, 'POST', 102, '/a/tim/merkel-will-npd-verfahren-auf-erfolgsaussichten-abklopfen', 'posts', 'view', '102', NULL);
INSERT INTO `routes` VALUES(337, NULL, NULL, 'POST', 103, '/a/tim/fussball-laenderspiel-deutschland-gewinnt-gegen-die-niederlande-3-0', 'posts', 'view', '103', NULL);
INSERT INTO `routes` VALUES(338, NULL, NULL, 'POST', 104, '/a/tim/cholera-im-fluechtlingslager-dadaab-ausgebrochen', 'posts', 'view', '104', NULL);
INSERT INTO `routes` VALUES(339, NULL, NULL, 'POST', 105, '/a/tim/gewalt-in-syrien-mehr-als-70-tote-innerhalb-von-24-stunden', 'posts', 'view', '105', NULL);
INSERT INTO `routes` VALUES(340, NULL, NULL, 'POST', 106, '/a/tim/assange-legt-erneut-berufung-gegen-auslieferung-ein', 'posts', 'view', '106', NULL);
INSERT INTO `routes` VALUES(341, NULL, NULL, 'POST', 107, '/a/tim/cdu-trennt-sich-in-der-bildungspolitik-von-traditionen', 'posts', 'view', '107', NULL);
INSERT INTO `routes` VALUES(342, NULL, NULL, 'POST', 108, '/a/tim/rekordzinsen-fuer-immer-mehr-europaeische-staaten', 'posts', 'view', '108', NULL);
INSERT INTO `routes` VALUES(343, NULL, NULL, 'POST', 109, '/a/tim/rechnungshof-beklagt-verschwendung-in-milliardenhoehe', 'posts', 'view', '109', NULL);
INSERT INTO `routes` VALUES(344, NULL, NULL, 'POST', 110, '/a/tim/hintergrund-das-braune-netz-in-thueringen-und-sachsen', 'posts', 'view', '110', NULL);
INSERT INTO `routes` VALUES(345, NULL, NULL, 'POST', 111, '/a/tim/eu-nimmt-ratingagenturen-und-spekulanten-ins-visier', 'posts', 'view', '111', NULL);
INSERT INTO `routes` VALUES(346, NULL, NULL, 'POST', 112, '/a/tim/kommentar-npd-verbot-der-dritte-schritt-vor-dem-ersten', 'posts', 'view', '112', NULL);
INSERT INTO `routes` VALUES(347, NULL, NULL, 'POST', 113, '/a/tim/hangames-onlinespiel-m2-versehentlich-geloescht', 'posts', 'view', '113', NULL);
INSERT INTO `routes` VALUES(348, NULL, NULL, 'POST', 114, '/a/tim/socl-microsoft-arbeitet-an-einem-social-network', 'posts', 'view', '114', NULL);
INSERT INTO `routes` VALUES(349, NULL, NULL, 'POST', 115, '/a/tim/kabel-deutschland-schnulzen-in-hd', 'posts', 'view', '115', NULL);
INSERT INTO `routes` VALUES(350, NULL, NULL, 'POST', 116, '/a/tim/grafiktreiber-catalyst-11-11-mit-beschleunigung-fuer-flash-player-11', 'posts', 'view', '116', NULL);
INSERT INTO `routes` VALUES(351, NULL, NULL, 'POST', 117, '/a/tim/427-millionen-us-dollar-vivendi-verkauft-anteile-an-activision-blizzard', 'posts', 'view', '117', NULL);
INSERT INTO `routes` VALUES(352, NULL, NULL, 'POST', 118, '/a/tim/google-verbatim-mehr-kontrolle-durch-woertliche-suche', 'posts', 'view', '118', NULL);
INSERT INTO `routes` VALUES(353, NULL, NULL, 'POST', 119, '/a/tim/memory-klage-ravensburger-gegen-apple', 'posts', 'view', '119', NULL);
INSERT INTO `routes` VALUES(354, NULL, NULL, 'POST', 120, '/a/tim/julian-assange-wikileaks-gruender-will-oberstes-britisches-gericht-anrufen', 'posts', 'view', '120', NULL);
INSERT INTO `routes` VALUES(355, NULL, NULL, 'POST', 121, '/a/tim/modern-warfare-3-durcheinander-statt-elite', 'posts', 'view', '121', NULL);
INSERT INTO `routes` VALUES(356, NULL, NULL, 'POST', 122, '/a/tim/paul-amsellem-nokia-kuendigt-ein-windows-8-tablet-fuer-juni-2012-an', 'posts', 'view', '122', NULL);
INSERT INTO `routes` VALUES(357, NULL, NULL, 'POST', 123, '/a/tim/robert-iger-apple-holt-disney-chef', 'posts', 'view', '123', NULL);
INSERT INTO `routes` VALUES(358, NULL, NULL, 'POST', 124, '/a/tim/browser-firefox-aktualisiert-sich-kuenftig-schnell-im-hintergrund', 'posts', 'view', '124', NULL);
INSERT INTO `routes` VALUES(359, NULL, NULL, 'POST', 125, '/a/tim/folio-13-hps-erstes-ultrabook-soll-9-stunden-laufen', 'posts', 'view', '125', NULL);
INSERT INTO `routes` VALUES(360, NULL, NULL, 'POST', 126, '/a/tim/apple-erste-teile-fuer-15-zoll-grosses-macbook-air', 'posts', 'view', '126', NULL);
INSERT INTO `routes` VALUES(361, NULL, NULL, 'POST', 127, '/a/tim/ricoh-cx6-kompaktkamera-soll-schneller-scharfstellen-als-eine-dslr', 'posts', 'view', '127', NULL);
INSERT INTO `routes` VALUES(362, NULL, NULL, 'POST', 128, '/a/tim/nikon-dslr-mit-kombiniertem-live-view-und-spiegelsucher', 'posts', 'view', '128', NULL);
INSERT INTO `routes` VALUES(363, NULL, NULL, 'POST', 129, '/a/tim/fahrsicherheit-assistenzsystem-beleuchtet-fussgaenger', 'posts', 'view', '129', NULL);
INSERT INTO `routes` VALUES(364, NULL, NULL, 'POST', 130, '/a/tim/gamecube-inkompatibel-neue-wii-konsolen-pakete-erhaeltlich', 'posts', 'view', '130', NULL);
INSERT INTO `routes` VALUES(365, NULL, NULL, 'POST', 131, '/a/tim/asus-rampage-iv-extreme-x79-mainboard-fuer-extremes-uebertakten', 'posts', 'view', '131', NULL);
INSERT INTO `routes` VALUES(366, NULL, NULL, 'POST', 132, '/a/tim/teamviewer-7-beta-online-meetings-mit-bis-zu-25-teilnehmern', 'posts', 'view', '132', NULL);
INSERT INTO `routes` VALUES(367, NULL, NULL, 'POST', 133, '/a/tim/barnes-and-noble-microsoft-patente-sind-quot-trivial-und-ungueltig-quot', 'posts', 'view', '133', NULL);
INSERT INTO `routes` VALUES(368, NULL, NULL, 'POST', 134, '/a/tim/open-acc-code-fuer-cpus-und-gpus-automatisch-parallelisieren', 'posts', 'view', '134', NULL);
INSERT INTO `routes` VALUES(369, NULL, NULL, 'POST', 135, '/a/tim/test-assassin-s-creed-revelations-serienmoerder-mit-routine', 'posts', 'view', '135', NULL);
INSERT INTO `routes` VALUES(370, NULL, NULL, 'POST', 136, '/a/tim/programmiersprache-go-1-soll-anfang-2012-erscheinen', 'posts', 'view', '136', NULL);
INSERT INTO `routes` VALUES(371, NULL, NULL, 'POST', 137, '/a/tim/lithium-ionen-akku-anodentechnik-ermoeglicht-mehr-kapazitaet-und-kuerzere-ladezeit', 'posts', 'view', '137', NULL);
INSERT INTO `routes` VALUES(372, NULL, NULL, 'POST', 138, '/a/tim/max-payne-3-rockstar-und-der-baertige-typ-in-brasilien', 'posts', 'view', '138', NULL);
INSERT INTO `routes` VALUES(373, NULL, NULL, 'POST', 139, '/a/tim/parallels-desktop-7-windows-8-preview-einfacher-unter-mac-os-x-installieren', 'posts', 'view', '139', NULL);
INSERT INTO `routes` VALUES(374, NULL, NULL, 'POST', 140, '/a/tim/tablet-apps-adobe-veroeffentlicht-photoshop-touch-fuer-android', 'posts', 'view', '140', NULL);
INSERT INTO `routes` VALUES(375, NULL, NULL, 'POST', 141, '/a/tim/battlefield-3-forumsperre-in-origin-fuehrt-zu-spieleverlust', 'posts', 'view', '141', NULL);
INSERT INTO `routes` VALUES(376, NULL, NULL, 'POST', 142, '/a/tim/musikdienst-spotify-in-oesterreich-gestartet', 'posts', 'view', '142', NULL);
INSERT INTO `routes` VALUES(377, NULL, NULL, 'POST', 143, '/a/tim/top-500-schnellster-supercomputer-erreicht-10-5-petaflops', 'posts', 'view', '143', NULL);
INSERT INTO `routes` VALUES(378, NULL, NULL, 'POST', 144, '/a/tim/commodore-os-vision-linux-im-brotkasten-look', 'posts', 'view', '144', NULL);
INSERT INTO `routes` VALUES(379, NULL, NULL, 'POST', 145, '/a/tim/sq-4-minidrohne-wiegt-nur-etwa-80-gramm', 'posts', 'view', '145', NULL);
INSERT INTO `routes` VALUES(380, NULL, NULL, 'POST', 146, '/a/tim/haehnel-mk-100-kleines-richtmikrofon-fuer-dslr-filmer', 'posts', 'view', '146', NULL);
INSERT INTO `routes` VALUES(381, NULL, NULL, 'POST', 147, '/a/tim/super-mario-3d-land-peta-sieht-mario-als-tierschlaechter', 'posts', 'view', '147', NULL);
INSERT INTO `routes` VALUES(382, NULL, NULL, 'POST', 148, '/a/tim/nomap-das-eigene-wlan-aus-googles-datenbank-loeschen', 'posts', 'view', '148', NULL);
INSERT INTO `routes` VALUES(383, NULL, NULL, 'POST', 149, '/a/tim/siri-fuer-alle-applidium-knackt-das-protokoll-von-apples-sprachsteuerung', 'posts', 'view', '149', NULL);
INSERT INTO `routes` VALUES(384, NULL, NULL, 'POST', 150, '/a/tim/windows-8-update-neustarts-erfolgen-nach-ansage-zwangsweise', 'posts', 'view', '150', NULL);
INSERT INTO `routes` VALUES(385, NULL, NULL, 'POST', 151, '/a/tim/super-hi-vision-olympische-spiele-mit-7-680-x-4-320-pixeln', 'posts', 'view', '151', NULL);
INSERT INTO `routes` VALUES(386, NULL, NULL, 'POST', 152, '/a/tim/ice-cream-sandwich-google-veroeffentlicht-quellcode-von-android-4-0-1', 'posts', 'view', '152', NULL);
INSERT INTO `routes` VALUES(387, NULL, NULL, 'POST', 153, '/a/tim/cdu-votiert-fuer-eine-allgemeine-verbindliche-lohnuntergrenze-a153', 'posts', 'view', '153', NULL);
INSERT INTO `routes` VALUES(388, NULL, NULL, 'POST', 154, '/a/tim/rede-zum-mindestlohn-a154', 'posts', 'view', '154', NULL);
INSERT INTO `routes` VALUES(389, NULL, NULL, 'POST', 155, '/a/tim/matthias-zimmer-berichtet-ueber-die-politische-lage-in-deutschland-a155', 'posts', 'view', '155', NULL);
INSERT INTO `routes` VALUES(390, NULL, NULL, 'POST', 156, '/a/tim/arbeitsmarkt-muss-echte-teilhabe-bieten-a156', 'posts', 'view', '156', NULL);
INSERT INTO `routes` VALUES(391, NULL, NULL, 'POST', 157, '/a/tim/ein-plaedoyer-fuer-die-wahrung-der-tarifautonomie-und-fuer-eine-allgemeine-lohnuntergrenze-a157', 'posts', 'view', '157', NULL);
INSERT INTO `routes` VALUES(392, NULL, NULL, 'POST', 158, '/a/tim/cdu-votiert-fuer-eine-allgemeine-verbindliche-lohnuntergrenze-a158', 'posts', 'view', '158', NULL);
INSERT INTO `routes` VALUES(393, NULL, NULL, 'POST', 159, '/a/tim/rede-zum-mindestlohn-a159', 'posts', 'view', '159', NULL);
INSERT INTO `routes` VALUES(394, NULL, NULL, 'POST', 160, '/a/tim/matthias-zimmer-berichtet-ueber-die-politische-lage-in-deutschland-a160', 'posts', 'view', '160', NULL);
INSERT INTO `routes` VALUES(395, NULL, NULL, 'POST', 161, '/a/tim/arbeitsmarkt-muss-echte-teilhabe-bieten-a161', 'posts', 'view', '161', NULL);
INSERT INTO `routes` VALUES(396, NULL, NULL, 'POST', 162, '/a/tim/ein-plaedoyer-fuer-die-wahrung-der-tarifautonomie-und-fuer-eine-allgemeine-lohnuntergrenze-a162', 'posts', 'view', '162', NULL);
INSERT INTO `routes` VALUES(397, NULL, NULL, 'POST', 163, '/a/tim/rede-zum-mindestlohn-a163', 'posts', 'view', '163', NULL);
INSERT INTO `routes` VALUES(398, NULL, NULL, 'POST', 164, '/a/tim/rede-zum-mindestlohn-a164', 'posts', 'view', '164', NULL);
INSERT INTO `routes` VALUES(399, NULL, NULL, 'POST', 165, '/a/tim/geraeumteâ-occupy-aktivisten-trotz-ohne-kopf-a165', 'posts', 'view', '165', NULL);
INSERT INTO `routes` VALUES(400, NULL, NULL, 'POST', 166, '/a/tim/doener-morde-sie-nannten-ihn-den-quot-kleinen-adolf-quot', 'posts', 'view', '166', NULL);
INSERT INTO `routes` VALUES(401, NULL, NULL, 'POST', 167, '/a/tim/konsumenten-als-konjunkturstuetze-hoffen-auf-otto-extremverbraucher-a167', 'posts', 'view', '167', NULL);
INSERT INTO `routes` VALUES(402, NULL, NULL, 'POST', 168, '/a/tim/parteichef-gabriel-im-interview-spd-draengt-merkel-zum-handeln-bei-npd-verbot-a168', 'posts', 'view', '168', NULL);
INSERT INTO `routes` VALUES(403, NULL, NULL, 'POST', 169, '/a/tim/neonazi-terrorzelle-kumpel-aus-dem-erzgebirge-a169', 'posts', 'view', '169', NULL);
INSERT INTO `routes` VALUES(404, NULL, NULL, 'POST', 170, '/a/tim/cdu-parteitag-partei-im-koma-a170', 'posts', 'view', '170', NULL);
INSERT INTO `routes` VALUES(405, NULL, NULL, 'POST', 171, '/a/tim/kinodrama-von-andreas-dresen-mama-papa-tumor', 'posts', 'view', '171', NULL);
INSERT INTO `routes` VALUES(406, NULL, NULL, 'POST', 172, '/a/tim/asbest-uran-der-verzweifelte-kampf-der-berufskranken', 'posts', 'view', '172', NULL);
INSERT INTO `routes` VALUES(407, NULL, NULL, 'POST', 173, '/a/tim/dell-contra-asus-ultrabook-schlaegt-notebook-a173', 'posts', 'view', '173', NULL);
INSERT INTO `routes` VALUES(408, NULL, NULL, 'POST', 174, '/a/tim/eicma-abschluss-das-beste-aus-zwei-welten', 'posts', 'view', '174', NULL);
INSERT INTO `routes` VALUES(409, NULL, NULL, 'POST', 175, '/a/tim/ermittlungen-gegen-neonazi-terroristen-die-raetsel-von-zwickau', 'posts', 'view', '175', NULL);
INSERT INTO `routes` VALUES(410, NULL, NULL, 'POST', 176, '/a/tim/geraeumteâ-occupy-aktivisten-trotz-ohne-kopf-a176', 'posts', 'view', '176', NULL);
INSERT INTO `routes` VALUES(411, NULL, NULL, 'POST', 177, '/a/tim/doener-morde-sie-nannten-ihn-den-quot-kleinen-adolf-quot-a177', 'posts', 'view', '177', NULL);
INSERT INTO `routes` VALUES(412, NULL, NULL, 'POST', 178, '/a/tim/konsumenten-als-konjunkturstuetze-hoffen-auf-otto-extremverbraucher-a178', 'posts', 'view', '178', NULL);
INSERT INTO `routes` VALUES(413, NULL, NULL, 'POST', 179, '/a/tim/parteichef-gabriel-im-interview-spd-draengt-merkel-zum-handeln-bei-npd-verbot-a179', 'posts', 'view', '179', NULL);
INSERT INTO `routes` VALUES(414, NULL, NULL, 'POST', 180, '/a/tim/neonazi-terrorzelle-kumpel-aus-dem-erzgebirge-a180', 'posts', 'view', '180', NULL);
INSERT INTO `routes` VALUES(415, NULL, NULL, 'POST', 181, '/a/tim/cdu-parteitag-partei-im-koma-a181', 'posts', 'view', '181', NULL);
INSERT INTO `routes` VALUES(416, NULL, NULL, 'POST', 182, '/a/tim/kinodrama-von-andreas-dresen-mama-papa-tumor-a182', 'posts', 'view', '182', NULL);
INSERT INTO `routes` VALUES(417, NULL, NULL, 'POST', 183, '/a/tim/asbest-uran-der-verzweifelte-kampf-der-berufskranken-a183', 'posts', 'view', '183', NULL);
INSERT INTO `routes` VALUES(418, NULL, NULL, 'POST', 184, '/a/tim/dell-contra-asus-ultrabook-schlaegt-notebook-a184', 'posts', 'view', '184', NULL);
INSERT INTO `routes` VALUES(419, NULL, NULL, 'POST', 185, '/a/tim/eicma-abschluss-das-beste-aus-zwei-welten-a185', 'posts', 'view', '185', NULL);
INSERT INTO `routes` VALUES(420, NULL, NULL, 'POST', 186, '/a/tim/ermittlungen-gegen-neonazi-terroristen-die-raetsel-von-zwickau-a186', 'posts', 'view', '186', NULL);
INSERT INTO `routes` VALUES(421, NULL, NULL, 'POST', 187, '/a/tim/geraeumteâ-occupy-aktivisten-trotz-ohne-kopf-a187', 'posts', 'view', '187', NULL);
INSERT INTO `routes` VALUES(422, NULL, NULL, 'POST', 188, '/a/tim/doener-morde-sie-nannten-ihn-den-quot-kleinen-adolf-quot-a188', 'posts', 'view', '188', NULL);
INSERT INTO `routes` VALUES(423, NULL, NULL, 'POST', 189, '/a/tim/konsumenten-als-konjunkturstuetze-hoffen-auf-otto-extremverbraucher-a189', 'posts', 'view', '189', NULL);
INSERT INTO `routes` VALUES(424, NULL, NULL, 'POST', 190, '/a/tim/parteichef-gabriel-im-interview-spd-draengt-merkel-zum-handeln-bei-npd-verbot-a190', 'posts', 'view', '190', NULL);
INSERT INTO `routes` VALUES(425, NULL, NULL, 'POST', 191, '/a/tim/neonazi-terrorzelle-kumpel-aus-dem-erzgebirge-a191', 'posts', 'view', '191', NULL);
INSERT INTO `routes` VALUES(426, NULL, NULL, 'POST', 192, '/a/tim/cdu-parteitag-partei-im-koma-a192', 'posts', 'view', '192', NULL);
INSERT INTO `routes` VALUES(427, NULL, NULL, 'POST', 193, '/a/tim/kinodrama-von-andreas-dresen-mama-papa-tumor-a193', 'posts', 'view', '193', NULL);
INSERT INTO `routes` VALUES(428, NULL, NULL, 'POST', 194, '/a/tim/asbest-uran-der-verzweifelte-kampf-der-berufskranken-a194', 'posts', 'view', '194', NULL);
INSERT INTO `routes` VALUES(429, NULL, NULL, 'POST', 195, '/a/tim/dell-contra-asus-ultrabook-schlaegt-notebook-a195', 'posts', 'view', '195', NULL);
INSERT INTO `routes` VALUES(430, NULL, NULL, 'POST', 196, '/a/tim/eicma-abschluss-das-beste-aus-zwei-welten-a196', 'posts', 'view', '196', NULL);
INSERT INTO `routes` VALUES(431, NULL, NULL, 'POST', 197, '/a/tim/ermittlungen-gegen-neonazi-terroristen-die-raetsel-von-zwickau-a197', 'posts', 'view', '197', NULL);
INSERT INTO `routes` VALUES(432, NULL, NULL, 'POST', 198, '/a/tim/geraeumte-occupy-aktivisten-trotz-ohne-kopf', 'posts', 'view', '198', NULL);
INSERT INTO `routes` VALUES(433, NULL, NULL, 'POST', 199, '/a/tim/cdu-votiert-fuer-eine-allgemeine-verbindliche-lohnuntergrenze-a199', 'posts', 'view', '199', NULL);
INSERT INTO `routes` VALUES(434, NULL, NULL, 'POST', 200, '/a/tim/rede-zum-mindestlohn-a200', 'posts', 'view', '200', NULL);
INSERT INTO `routes` VALUES(435, NULL, NULL, 'POST', 201, '/a/tim/matthias-zimmer-berichtet-ueber-die-politische-lage-in-deutschland-a201', 'posts', 'view', '201', NULL);
INSERT INTO `routes` VALUES(436, NULL, NULL, 'POST', 202, '/a/tim/arbeitsmarkt-muss-echte-teilhabe-bieten-a202', 'posts', 'view', '202', NULL);
INSERT INTO `routes` VALUES(437, NULL, NULL, 'POST', 203, '/a/tim/ein-plaedoyer-fuer-die-wahrung-der-tarifautonomie-und-fuer-eine-allgemeine-lohnuntergrenze-a203', 'posts', 'view', '203', NULL);
INSERT INTO `routes` VALUES(438, NULL, NULL, 'POST', 204, '/a/tim/geraeumteâ-occupy-aktivisten-trotz-ohne-kopf-a204', 'posts', 'view', '204', NULL);
INSERT INTO `routes` VALUES(439, NULL, NULL, 'POST', 205, '/a/tim/doener-morde-sie-nannten-ihn-den-quot-kleinen-adolf-quot-a205', 'posts', 'view', '205', NULL);
INSERT INTO `routes` VALUES(440, NULL, NULL, 'POST', 206, '/a/tim/konsumenten-als-konjunkturstuetze-hoffen-auf-otto-extremverbraucher-a206', 'posts', 'view', '206', NULL);
INSERT INTO `routes` VALUES(441, NULL, NULL, 'POST', 207, '/a/tim/parteichef-gabriel-im-interview-spd-draengt-merkel-zum-handeln-bei-npd-verbot-a207', 'posts', 'view', '207', NULL);
INSERT INTO `routes` VALUES(442, NULL, NULL, 'POST', 208, '/a/tim/neonazi-terrorzelle-kumpel-aus-dem-erzgebirge-a208', 'posts', 'view', '208', NULL);
INSERT INTO `routes` VALUES(443, NULL, NULL, 'POST', 209, '/a/tim/cdu-parteitag-partei-im-koma-a209', 'posts', 'view', '209', NULL);
INSERT INTO `routes` VALUES(444, NULL, NULL, 'POST', 210, '/a/tim/kinodrama-von-andreas-dresen-mama-papa-tumor-a210', 'posts', 'view', '210', NULL);
INSERT INTO `routes` VALUES(445, NULL, NULL, 'POST', 211, '/a/tim/asbest-uran-der-verzweifelte-kampf-der-berufskranken-a211', 'posts', 'view', '211', NULL);
INSERT INTO `routes` VALUES(446, NULL, NULL, 'POST', 212, '/a/tim/dell-contra-asus-ultrabook-schlaegt-notebook-a212', 'posts', 'view', '212', NULL);
INSERT INTO `routes` VALUES(447, NULL, NULL, 'POST', 213, '/a/tim/eicma-abschluss-das-beste-aus-zwei-welten-a213', 'posts', 'view', '213', NULL);
INSERT INTO `routes` VALUES(448, NULL, NULL, 'POST', 214, '/a/tim/ermittlungen-gegen-neonazi-terroristen-die-raetsel-von-zwickau-a214', 'posts', 'view', '214', NULL);
INSERT INTO `routes` VALUES(449, NULL, NULL, 'POST', 215, '/a/tim/geraeumteâ-occupy-aktivisten-trotz-ohne-kopf-a215', 'posts', 'view', '215', NULL);
INSERT INTO `routes` VALUES(450, NULL, NULL, 'POST', 216, '/a/tim/doener-morde-sie-nannten-ihn-den-quot-kleinen-adolf-quot-a216', 'posts', 'view', '216', NULL);
INSERT INTO `routes` VALUES(451, NULL, NULL, 'POST', 217, '/a/tim/konsumenten-als-konjunkturstuetze-hoffen-auf-otto-extremverbraucher-a217', 'posts', 'view', '217', NULL);
INSERT INTO `routes` VALUES(452, NULL, NULL, 'POST', 218, '/a/tim/parteichef-gabriel-im-interview-spd-draengt-merkel-zum-handeln-bei-npd-verbot-a218', 'posts', 'view', '218', NULL);
INSERT INTO `routes` VALUES(453, NULL, NULL, 'POST', 219, '/a/tim/neonazi-terrorzelle-kumpel-aus-dem-erzgebirge-a219', 'posts', 'view', '219', NULL);
INSERT INTO `routes` VALUES(454, NULL, NULL, 'POST', 220, '/a/tim/cdu-parteitag-partei-im-koma-a220', 'posts', 'view', '220', NULL);
INSERT INTO `routes` VALUES(455, NULL, NULL, 'POST', 221, '/a/tim/kinodrama-von-andreas-dresen-mama-papa-tumor-a221', 'posts', 'view', '221', NULL);
INSERT INTO `routes` VALUES(456, NULL, NULL, 'POST', 222, '/a/tim/asbest-uran-der-verzweifelte-kampf-der-berufskranken-a222', 'posts', 'view', '222', NULL);
INSERT INTO `routes` VALUES(457, NULL, NULL, 'POST', 223, '/a/tim/dell-contra-asus-ultrabook-schlaegt-notebook-a223', 'posts', 'view', '223', NULL);
INSERT INTO `routes` VALUES(458, NULL, NULL, 'POST', 224, '/a/tim/eicma-abschluss-das-beste-aus-zwei-welten-a224', 'posts', 'view', '224', NULL);
INSERT INTO `routes` VALUES(459, NULL, NULL, 'POST', 225, '/a/tim/ermittlungen-gegen-neonazi-terroristen-die-raetsel-von-zwickau-a225', 'posts', 'view', '225', NULL);
INSERT INTO `routes` VALUES(460, NULL, NULL, 'POST', 226, '/a/tim/geraeumteâ-occupy-aktivisten-trotz-ohne-kopf-a226', 'posts', 'view', '226', NULL);
INSERT INTO `routes` VALUES(461, NULL, NULL, 'POST', 227, '/a/tim/doener-morde-sie-nannten-ihn-den-quot-kleinen-adolf-quot-a227', 'posts', 'view', '227', NULL);
INSERT INTO `routes` VALUES(462, NULL, NULL, 'POST', 228, '/a/tim/konsumenten-als-konjunkturstuetze-hoffen-auf-otto-extremverbraucher-a228', 'posts', 'view', '228', NULL);
INSERT INTO `routes` VALUES(463, NULL, NULL, 'POST', 229, '/a/tim/parteichef-gabriel-im-interview-spd-draengt-merkel-zum-handeln-bei-npd-verbot-a229', 'posts', 'view', '229', NULL);
INSERT INTO `routes` VALUES(464, NULL, NULL, 'POST', 230, '/a/tim/neonazi-terrorzelle-kumpel-aus-dem-erzgebirge-a230', 'posts', 'view', '230', NULL);
INSERT INTO `routes` VALUES(465, NULL, NULL, 'POST', 231, '/a/tim/cdu-parteitag-partei-im-koma-a231', 'posts', 'view', '231', NULL);
INSERT INTO `routes` VALUES(466, NULL, NULL, 'POST', 232, '/a/tim/kinodrama-von-andreas-dresen-mama-papa-tumor-a232', 'posts', 'view', '232', NULL);
INSERT INTO `routes` VALUES(467, NULL, NULL, 'POST', 233, '/a/tim/asbest-uran-der-verzweifelte-kampf-der-berufskranken-a233', 'posts', 'view', '233', NULL);
INSERT INTO `routes` VALUES(468, NULL, NULL, 'POST', 234, '/a/tim/dell-contra-asus-ultrabook-schlaegt-notebook-a234', 'posts', 'view', '234', NULL);
INSERT INTO `routes` VALUES(469, NULL, NULL, 'POST', 235, '/a/tim/eicma-abschluss-das-beste-aus-zwei-welten-a235', 'posts', 'view', '235', NULL);
INSERT INTO `routes` VALUES(470, NULL, NULL, 'POST', 236, '/a/tim/ermittlungen-gegen-neonazi-terroristen-die-raetsel-von-zwickau-a236', 'posts', 'view', '236', NULL);
INSERT INTO `routes` VALUES(471, NULL, NULL, 'POST', 237, '/a/tim/geraeumteâ-occupy-aktivisten-trotz-ohne-kopf-a237', 'posts', 'view', '237', NULL);
INSERT INTO `routes` VALUES(472, NULL, NULL, 'POST', 238, '/a/tim/doener-morde-sie-nannten-ihn-den-quot-kleinen-adolf-quot-a238', 'posts', 'view', '238', NULL);
INSERT INTO `routes` VALUES(473, NULL, NULL, 'POST', 239, '/a/tim/konsumenten-als-konjunkturstuetze-hoffen-auf-otto-extremverbraucher-a239', 'posts', 'view', '239', NULL);
INSERT INTO `routes` VALUES(474, NULL, NULL, 'POST', 240, '/a/tim/parteichef-gabriel-im-interview-spd-draengt-merkel-zum-handeln-bei-npd-verbot-a240', 'posts', 'view', '240', NULL);
INSERT INTO `routes` VALUES(475, NULL, NULL, 'POST', 241, '/a/tim/neonazi-terrorzelle-kumpel-aus-dem-erzgebirge-a241', 'posts', 'view', '241', NULL);
INSERT INTO `routes` VALUES(476, NULL, NULL, 'POST', 242, '/a/tim/cdu-parteitag-partei-im-koma-a242', 'posts', 'view', '242', NULL);
INSERT INTO `routes` VALUES(477, NULL, NULL, 'POST', 243, '/a/tim/kinodrama-von-andreas-dresen-mama-papa-tumor-a243', 'posts', 'view', '243', NULL);
INSERT INTO `routes` VALUES(478, NULL, NULL, 'POST', 244, '/a/tim/asbest-uran-der-verzweifelte-kampf-der-berufskranken-a244', 'posts', 'view', '244', NULL);
INSERT INTO `routes` VALUES(479, NULL, NULL, 'POST', 245, '/a/tim/dell-contra-asus-ultrabook-schlaegt-notebook-a245', 'posts', 'view', '245', NULL);
INSERT INTO `routes` VALUES(480, NULL, NULL, 'POST', 246, '/a/tim/eicma-abschluss-das-beste-aus-zwei-welten-a246', 'posts', 'view', '246', NULL);
INSERT INTO `routes` VALUES(481, NULL, NULL, 'POST', 247, '/a/tim/ermittlungen-gegen-neonazi-terroristen-die-raetsel-von-zwickau-a247', 'posts', 'view', '247', NULL);
INSERT INTO `routes` VALUES(482, NULL, NULL, 'POST', 248, '/a/tim/geraeumteâ-occupy-aktivisten-trotz-ohne-kopf-a248', 'posts', 'view', '248', NULL);
INSERT INTO `routes` VALUES(483, NULL, NULL, 'POST', 249, '/a/tim/doener-morde-sie-nannten-ihn-den-quot-kleinen-adolf-quot-a249', 'posts', 'view', '249', NULL);
INSERT INTO `routes` VALUES(484, NULL, NULL, 'POST', 250, '/a/tim/konsumenten-als-konjunkturstuetze-hoffen-auf-otto-extremverbraucher-a250', 'posts', 'view', '250', NULL);
INSERT INTO `routes` VALUES(485, NULL, NULL, 'POST', 251, '/a/tim/parteichef-gabriel-im-interview-spd-draengt-merkel-zum-handeln-bei-npd-verbot-a251', 'posts', 'view', '251', NULL);
INSERT INTO `routes` VALUES(486, NULL, NULL, 'POST', 252, '/a/tim/neonazi-terrorzelle-kumpel-aus-dem-erzgebirge-a252', 'posts', 'view', '252', NULL);
INSERT INTO `routes` VALUES(487, NULL, NULL, 'POST', 253, '/a/tim/cdu-parteitag-partei-im-koma-a253', 'posts', 'view', '253', NULL);
INSERT INTO `routes` VALUES(488, NULL, NULL, 'POST', 254, '/a/tim/kinodrama-von-andreas-dresen-mama-papa-tumor-a254', 'posts', 'view', '254', NULL);
INSERT INTO `routes` VALUES(489, NULL, NULL, 'POST', 255, '/a/tim/asbest-uran-der-verzweifelte-kampf-der-berufskranken-a255', 'posts', 'view', '255', NULL);
INSERT INTO `routes` VALUES(490, NULL, NULL, 'POST', 256, '/a/tim/dell-contra-asus-ultrabook-schlaegt-notebook-a256', 'posts', 'view', '256', NULL);
INSERT INTO `routes` VALUES(491, NULL, NULL, 'POST', 257, '/a/tim/eicma-abschluss-das-beste-aus-zwei-welten-a257', 'posts', 'view', '257', NULL);
INSERT INTO `routes` VALUES(492, NULL, NULL, 'POST', 258, '/a/tim/ermittlungen-gegen-neonazi-terroristen-die-raetsel-von-zwickau-a258', 'posts', 'view', '258', NULL);
INSERT INTO `routes` VALUES(493, NULL, NULL, 'POST', 259, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker', 'posts', 'view', '259', NULL);
INSERT INTO `routes` VALUES(494, NULL, NULL, 'POST', 260, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot', 'posts', 'view', '260', NULL);
INSERT INTO `routes` VALUES(495, NULL, NULL, 'POST', 261, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot', 'posts', 'view', '261', NULL);
INSERT INTO `routes` VALUES(496, NULL, NULL, 'POST', 262, '/a/tim/dfb-kader-der-grosse-em-check', 'posts', 'view', '262', NULL);
INSERT INTO `routes` VALUES(497, NULL, NULL, 'POST', 263, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt', 'posts', 'view', '263', NULL);
INSERT INTO `routes` VALUES(498, NULL, NULL, 'POST', 264, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier', 'posts', 'view', '264', NULL);
INSERT INTO `routes` VALUES(499, NULL, NULL, 'POST', 265, '/a/tim/dubioser-waffenhandel-deutsche-knarrenâ-fuer-denâ-zigarrenkoenig', 'posts', 'view', '265', NULL);
INSERT INTO `routes` VALUES(500, NULL, NULL, 'POST', 266, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot', 'posts', 'view', '266', NULL);
INSERT INTO `routes` VALUES(501, NULL, NULL, 'POST', 267, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a267', 'posts', 'view', '267', NULL);
INSERT INTO `routes` VALUES(502, NULL, NULL, 'POST', 268, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a268', 'posts', 'view', '268', NULL);
INSERT INTO `routes` VALUES(503, NULL, NULL, 'POST', 269, '/a/tim/uebergrosse-autos-viel-zu-breit-a269', 'posts', 'view', '269', NULL);
INSERT INTO `routes` VALUES(504, NULL, NULL, 'POST', 270, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker-a270', 'posts', 'view', '270', NULL);
INSERT INTO `routes` VALUES(505, NULL, NULL, 'POST', 271, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot-a271', 'posts', 'view', '271', NULL);
INSERT INTO `routes` VALUES(506, NULL, NULL, 'POST', 272, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot-a272', 'posts', 'view', '272', NULL);
INSERT INTO `routes` VALUES(507, NULL, NULL, 'POST', 273, '/a/tim/dfb-kader-der-grosse-em-check-a273', 'posts', 'view', '273', NULL);
INSERT INTO `routes` VALUES(508, NULL, NULL, 'POST', 274, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt-a274', 'posts', 'view', '274', NULL);
INSERT INTO `routes` VALUES(509, NULL, NULL, 'POST', 275, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier-a275', 'posts', 'view', '275', NULL);
INSERT INTO `routes` VALUES(510, NULL, NULL, 'POST', 276, '/a/tim/dubioser-waffenhandel-deutsche-knarrenâ-fuer-denâ-zigarrenkoenig-a276', 'posts', 'view', '276', NULL);
INSERT INTO `routes` VALUES(511, NULL, NULL, 'POST', 277, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot-a277', 'posts', 'view', '277', NULL);
INSERT INTO `routes` VALUES(512, NULL, NULL, 'POST', 278, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a278', 'posts', 'view', '278', NULL);
INSERT INTO `routes` VALUES(513, NULL, NULL, 'POST', 279, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a279', 'posts', 'view', '279', NULL);
INSERT INTO `routes` VALUES(514, NULL, NULL, 'POST', 280, '/a/tim/uebergrosse-autos-viel-zu-breit-a280', 'posts', 'view', '280', NULL);
INSERT INTO `routes` VALUES(515, NULL, NULL, 'POST', 281, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker-a281', 'posts', 'view', '281', NULL);
INSERT INTO `routes` VALUES(516, NULL, NULL, 'POST', 282, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot-a282', 'posts', 'view', '282', NULL);
INSERT INTO `routes` VALUES(517, NULL, NULL, 'POST', 283, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot-a283', 'posts', 'view', '283', NULL);
INSERT INTO `routes` VALUES(518, NULL, NULL, 'POST', 284, '/a/tim/dfb-kader-der-grosse-em-check-a284', 'posts', 'view', '284', NULL);
INSERT INTO `routes` VALUES(519, NULL, NULL, 'POST', 285, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt-a285', 'posts', 'view', '285', NULL);
INSERT INTO `routes` VALUES(520, NULL, NULL, 'POST', 286, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier-a286', 'posts', 'view', '286', NULL);
INSERT INTO `routes` VALUES(521, NULL, NULL, 'POST', 287, '/a/tim/dubioser-waffenhandel-deutsche-knarrenâ-fuer-denâ-zigarrenkoenig-a287', 'posts', 'view', '287', NULL);
INSERT INTO `routes` VALUES(522, NULL, NULL, 'POST', 288, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot-a288', 'posts', 'view', '288', NULL);
INSERT INTO `routes` VALUES(523, NULL, NULL, 'POST', 289, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a289', 'posts', 'view', '289', NULL);
INSERT INTO `routes` VALUES(524, NULL, NULL, 'POST', 290, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a290', 'posts', 'view', '290', NULL);
INSERT INTO `routes` VALUES(525, NULL, NULL, 'POST', 291, '/a/tim/uebergrosse-autos-viel-zu-breit-a291', 'posts', 'view', '291', NULL);
INSERT INTO `routes` VALUES(526, NULL, NULL, 'POST', 292, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker-a292', 'posts', 'view', '292', NULL);
INSERT INTO `routes` VALUES(527, NULL, NULL, 'POST', 293, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot-a293', 'posts', 'view', '293', NULL);
INSERT INTO `routes` VALUES(528, NULL, NULL, 'POST', 294, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot-a294', 'posts', 'view', '294', NULL);
INSERT INTO `routes` VALUES(529, NULL, NULL, 'POST', 295, '/a/tim/dfb-kader-der-grosse-em-check-a295', 'posts', 'view', '295', NULL);
INSERT INTO `routes` VALUES(530, NULL, NULL, 'POST', 296, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt-a296', 'posts', 'view', '296', NULL);
INSERT INTO `routes` VALUES(531, NULL, NULL, 'POST', 297, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier-a297', 'posts', 'view', '297', NULL);
INSERT INTO `routes` VALUES(532, NULL, NULL, 'POST', 298, '/a/tim/dubioser-waffenhandel-deutsche-knarrenâ-fuer-denâ-zigarrenkoenig-a298', 'posts', 'view', '298', NULL);
INSERT INTO `routes` VALUES(533, NULL, NULL, 'POST', 299, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot-a299', 'posts', 'view', '299', NULL);
INSERT INTO `routes` VALUES(534, NULL, NULL, 'POST', 300, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a300', 'posts', 'view', '300', NULL);
INSERT INTO `routes` VALUES(535, NULL, NULL, 'POST', 301, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a301', 'posts', 'view', '301', NULL);
INSERT INTO `routes` VALUES(536, NULL, NULL, 'POST', 302, '/a/tim/uebergrosse-autos-viel-zu-breit-a302', 'posts', 'view', '302', NULL);
INSERT INTO `routes` VALUES(537, NULL, NULL, 'POST', 303, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker-a303', 'posts', 'view', '303', NULL);
INSERT INTO `routes` VALUES(538, NULL, NULL, 'POST', 304, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot-a304', 'posts', 'view', '304', NULL);
INSERT INTO `routes` VALUES(539, NULL, NULL, 'POST', 305, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot-a305', 'posts', 'view', '305', NULL);
INSERT INTO `routes` VALUES(540, NULL, NULL, 'POST', 306, '/a/tim/dfb-kader-der-grosse-em-check-a306', 'posts', 'view', '306', NULL);
INSERT INTO `routes` VALUES(541, NULL, NULL, 'POST', 307, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt-a307', 'posts', 'view', '307', NULL);
INSERT INTO `routes` VALUES(542, NULL, NULL, 'POST', 308, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier-a308', 'posts', 'view', '308', NULL);
INSERT INTO `routes` VALUES(543, NULL, NULL, 'POST', 309, '/a/tim/dubioser-waffenhandel-deutsche-knarrenâ-fuer-denâ-zigarrenkoenig-a309', 'posts', 'view', '309', NULL);
INSERT INTO `routes` VALUES(544, NULL, NULL, 'POST', 310, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot-a310', 'posts', 'view', '310', NULL);
INSERT INTO `routes` VALUES(545, NULL, NULL, 'POST', 311, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a311', 'posts', 'view', '311', NULL);
INSERT INTO `routes` VALUES(546, NULL, NULL, 'POST', 312, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a312', 'posts', 'view', '312', NULL);
INSERT INTO `routes` VALUES(547, NULL, NULL, 'POST', 313, '/a/tim/uebergrosse-autos-viel-zu-breit-a313', 'posts', 'view', '313', NULL);
INSERT INTO `routes` VALUES(548, NULL, NULL, 'POST', 314, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker-a314', 'posts', 'view', '314', NULL);
INSERT INTO `routes` VALUES(549, NULL, NULL, 'POST', 315, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot-a315', 'posts', 'view', '315', NULL);
INSERT INTO `routes` VALUES(550, NULL, NULL, 'POST', 316, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot-a316', 'posts', 'view', '316', NULL);
INSERT INTO `routes` VALUES(551, NULL, NULL, 'POST', 317, '/a/tim/dfb-kader-der-grosse-em-check-a317', 'posts', 'view', '317', NULL);
INSERT INTO `routes` VALUES(552, NULL, NULL, 'POST', 318, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt-a318', 'posts', 'view', '318', NULL);
INSERT INTO `routes` VALUES(553, NULL, NULL, 'POST', 319, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier-a319', 'posts', 'view', '319', NULL);
INSERT INTO `routes` VALUES(554, NULL, NULL, 'POST', 320, '/a/tim/dubioser-waffenhandel-deutsche-knarrenâ-fuer-denâ-zigarrenkoenig-a320', 'posts', 'view', '320', NULL);
INSERT INTO `routes` VALUES(555, NULL, NULL, 'POST', 321, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot-a321', 'posts', 'view', '321', NULL);
INSERT INTO `routes` VALUES(556, NULL, NULL, 'POST', 322, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a322', 'posts', 'view', '322', NULL);
INSERT INTO `routes` VALUES(557, NULL, NULL, 'POST', 323, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a323', 'posts', 'view', '323', NULL);
INSERT INTO `routes` VALUES(558, NULL, NULL, 'POST', 324, '/a/tim/uebergrosse-autos-viel-zu-breit-a324', 'posts', 'view', '324', NULL);
INSERT INTO `routes` VALUES(559, NULL, NULL, 'POST', 325, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker-a325', 'posts', 'view', '325', NULL);
INSERT INTO `routes` VALUES(560, NULL, NULL, 'POST', 326, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot-a326', 'posts', 'view', '326', NULL);
INSERT INTO `routes` VALUES(561, NULL, NULL, 'POST', 327, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot-a327', 'posts', 'view', '327', NULL);
INSERT INTO `routes` VALUES(562, NULL, NULL, 'POST', 328, '/a/tim/dfb-kader-der-grosse-em-check-a328', 'posts', 'view', '328', NULL);
INSERT INTO `routes` VALUES(563, NULL, NULL, 'POST', 329, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt-a329', 'posts', 'view', '329', NULL);
INSERT INTO `routes` VALUES(564, NULL, NULL, 'POST', 330, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier-a330', 'posts', 'view', '330', NULL);
INSERT INTO `routes` VALUES(565, NULL, NULL, 'POST', 331, '/a/tim/dubioser-waffenhandel-deutsche-knarrenâ-fuer-denâ-zigarrenkoenig-a331', 'posts', 'view', '331', NULL);
INSERT INTO `routes` VALUES(566, NULL, NULL, 'POST', 332, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot-a332', 'posts', 'view', '332', NULL);
INSERT INTO `routes` VALUES(567, NULL, NULL, 'POST', 333, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a333', 'posts', 'view', '333', NULL);
INSERT INTO `routes` VALUES(568, NULL, NULL, 'POST', 334, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a334', 'posts', 'view', '334', NULL);
INSERT INTO `routes` VALUES(569, NULL, NULL, 'POST', 335, '/a/tim/uebergrosse-autos-viel-zu-breit-a335', 'posts', 'view', '335', NULL);
INSERT INTO `routes` VALUES(570, NULL, NULL, 'POST', 336, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker-a336', 'posts', 'view', '336', NULL);
INSERT INTO `routes` VALUES(571, NULL, NULL, 'POST', 337, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot-a337', 'posts', 'view', '337', NULL);
INSERT INTO `routes` VALUES(572, NULL, NULL, 'POST', 338, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot-a338', 'posts', 'view', '338', NULL);
INSERT INTO `routes` VALUES(573, NULL, NULL, 'POST', 339, '/a/tim/dfb-kader-der-grosse-em-check-a339', 'posts', 'view', '339', NULL);
INSERT INTO `routes` VALUES(574, NULL, NULL, 'POST', 340, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt-a340', 'posts', 'view', '340', NULL);
INSERT INTO `routes` VALUES(575, NULL, NULL, 'POST', 341, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier-a341', 'posts', 'view', '341', NULL);
INSERT INTO `routes` VALUES(576, NULL, NULL, 'POST', 342, '/a/tim/dubioser-waffenhandel-deutsche-knarrenâ-fuer-denâ-zigarrenkoenig-a342', 'posts', 'view', '342', NULL);
INSERT INTO `routes` VALUES(577, NULL, NULL, 'POST', 343, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot-a343', 'posts', 'view', '343', NULL);
INSERT INTO `routes` VALUES(578, NULL, NULL, 'POST', 344, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a344', 'posts', 'view', '344', NULL);
INSERT INTO `routes` VALUES(579, NULL, NULL, 'POST', 345, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a345', 'posts', 'view', '345', NULL);
INSERT INTO `routes` VALUES(580, NULL, NULL, 'POST', 346, '/a/tim/uebergrosse-autos-viel-zu-breit-a346', 'posts', 'view', '346', NULL);
INSERT INTO `routes` VALUES(581, NULL, NULL, 'POST', 347, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker-a347', 'posts', 'view', '347', NULL);
INSERT INTO `routes` VALUES(582, NULL, NULL, 'POST', 348, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot-a348', 'posts', 'view', '348', NULL);
INSERT INTO `routes` VALUES(583, NULL, NULL, 'POST', 349, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot-a349', 'posts', 'view', '349', NULL);
INSERT INTO `routes` VALUES(584, NULL, NULL, 'POST', 350, '/a/tim/dfb-kader-der-grosse-em-check-a350', 'posts', 'view', '350', NULL);
INSERT INTO `routes` VALUES(585, NULL, NULL, 'POST', 351, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt-a351', 'posts', 'view', '351', NULL);
INSERT INTO `routes` VALUES(586, NULL, NULL, 'POST', 352, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier-a352', 'posts', 'view', '352', NULL);
INSERT INTO `routes` VALUES(587, NULL, NULL, 'POST', 353, '/a/tim/dubioser-waffenhandel-deutsche-knarrenâ-fuer-denâ-zigarrenkoenig-a353', 'posts', 'view', '353', NULL);
INSERT INTO `routes` VALUES(588, NULL, NULL, 'POST', 354, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot-a354', 'posts', 'view', '354', NULL);
INSERT INTO `routes` VALUES(589, NULL, NULL, 'POST', 355, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a355', 'posts', 'view', '355', NULL);
INSERT INTO `routes` VALUES(590, NULL, NULL, 'POST', 356, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a356', 'posts', 'view', '356', NULL);
INSERT INTO `routes` VALUES(591, NULL, NULL, 'POST', 357, '/a/tim/uebergrosse-autos-viel-zu-breit-a357', 'posts', 'view', '357', NULL);
INSERT INTO `routes` VALUES(592, NULL, NULL, 'POST', 358, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker-a358', 'posts', 'view', '358', NULL);
INSERT INTO `routes` VALUES(593, NULL, NULL, 'POST', 359, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot-a359', 'posts', 'view', '359', NULL);
INSERT INTO `routes` VALUES(594, NULL, NULL, 'POST', 360, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot-a360', 'posts', 'view', '360', NULL);
INSERT INTO `routes` VALUES(595, NULL, NULL, 'POST', 361, '/a/tim/dfb-kader-der-grosse-em-check-a361', 'posts', 'view', '361', NULL);
INSERT INTO `routes` VALUES(596, NULL, NULL, 'POST', 362, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt-a362', 'posts', 'view', '362', NULL);
INSERT INTO `routes` VALUES(597, NULL, NULL, 'POST', 363, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier-a363', 'posts', 'view', '363', NULL);
INSERT INTO `routes` VALUES(598, NULL, NULL, 'POST', 364, '/a/tim/dubioser-waffenhandel-deutsche-knarrenâ-fuer-denâ-zigarrenkoenig-a364', 'posts', 'view', '364', NULL);
INSERT INTO `routes` VALUES(599, NULL, NULL, 'POST', 365, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot-a365', 'posts', 'view', '365', NULL);
INSERT INTO `routes` VALUES(600, NULL, NULL, 'POST', 366, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a366', 'posts', 'view', '366', NULL);
INSERT INTO `routes` VALUES(601, NULL, NULL, 'POST', 367, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a367', 'posts', 'view', '367', NULL);
INSERT INTO `routes` VALUES(602, NULL, NULL, 'POST', 368, '/a/tim/uebergrosse-autos-viel-zu-breit-a368', 'posts', 'view', '368', NULL);
INSERT INTO `routes` VALUES(603, NULL, NULL, 'POST', 369, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker-a369', 'posts', 'view', '369', NULL);
INSERT INTO `routes` VALUES(604, NULL, NULL, 'POST', 370, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot-a370', 'posts', 'view', '370', NULL);
INSERT INTO `routes` VALUES(605, NULL, NULL, 'POST', 371, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot-a371', 'posts', 'view', '371', NULL);
INSERT INTO `routes` VALUES(606, NULL, NULL, 'POST', 372, '/a/tim/dfb-kader-der-grosse-em-check-a372', 'posts', 'view', '372', NULL);
INSERT INTO `routes` VALUES(607, NULL, NULL, 'POST', 373, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt-a373', 'posts', 'view', '373', NULL);
INSERT INTO `routes` VALUES(608, NULL, NULL, 'POST', 374, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier-a374', 'posts', 'view', '374', NULL);
INSERT INTO `routes` VALUES(609, NULL, NULL, 'POST', 375, '/a/tim/dubioser-waffenhandel-deutsche-knarren-fuer-den-zigarrenkoenig', 'posts', 'view', '375', NULL);
INSERT INTO `routes` VALUES(610, NULL, NULL, 'POST', 376, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot-a376', 'posts', 'view', '376', NULL);
INSERT INTO `routes` VALUES(611, NULL, NULL, 'POST', 377, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a377', 'posts', 'view', '377', NULL);
INSERT INTO `routes` VALUES(612, NULL, NULL, 'POST', 378, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a378', 'posts', 'view', '378', NULL);
INSERT INTO `routes` VALUES(613, NULL, NULL, 'POST', 379, '/a/tim/uebergrosse-autos-viel-zu-breit-a379', 'posts', 'view', '379', NULL);
INSERT INTO `routes` VALUES(614, NULL, NULL, 'POST', 380, '/a/tim/finanzkrise-in-fernost-in-den-faengen-der-schattenbanker-a380', 'posts', 'view', '380', NULL);
INSERT INTO `routes` VALUES(615, NULL, NULL, 'POST', 381, '/a/tim/rekord-surfer-mcnamara-quot-sie-war-hoeher-als-alles-was-ich-je-gesehen-hatte-quot-a381', 'posts', 'view', '381', NULL);
INSERT INTO `routes` VALUES(616, NULL, NULL, 'POST', 382, '/a/tim/buetikofer-twittert-quot-vergesst-diesen-joschka-fischer-quot-a382', 'posts', 'view', '382', NULL);
INSERT INTO `routes` VALUES(617, NULL, NULL, 'POST', 383, '/a/tim/dfb-kader-der-grosse-em-check-a383', 'posts', 'view', '383', NULL);
INSERT INTO `routes` VALUES(618, NULL, NULL, 'POST', 384, '/a/tim/schuldenkrise-fataler-euro-dominoeffekt-a384', 'posts', 'view', '384', NULL);
INSERT INTO `routes` VALUES(619, NULL, NULL, 'POST', 385, '/a/tim/zwickauer-terrorzelle-neonazis-hatten-auch-politiker-im-visier-a385', 'posts', 'view', '385', NULL);
INSERT INTO `routes` VALUES(620, NULL, NULL, 'POST', 386, '/a/tim/dubioser-waffenhandel-deutsche-knarrenâ-fuer-denâ-zigarrenkoenig-a386', 'posts', 'view', '386', NULL);
INSERT INTO `routes` VALUES(621, NULL, NULL, 'POST', 387, '/a/tim/jobmarkt-fuer-absolventen-quot-wir-wollen-sie-quot-a387', 'posts', 'view', '387', NULL);
INSERT INTO `routes` VALUES(622, NULL, NULL, 'POST', 388, '/a/tim/vortrag-bei-sicherheitstagung-guttenberg-kehrt-auf-die-internationale-buehne-zurueck-a388', 'posts', 'view', '388', NULL);
INSERT INTO `routes` VALUES(623, NULL, NULL, 'POST', 389, '/a/tim/ex-millionaer-rabeder-quot-fuer-viele-war-ich-das-geld-schwein-quot-a389', 'posts', 'view', '389', NULL);
INSERT INTO `routes` VALUES(624, NULL, NULL, 'POST', 390, '/a/tim/uebergrosse-autos-viel-zu-breit-a390', 'posts', 'view', '390', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rss_feeds`
--

CREATE TABLE `rss_feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Daten für Tabelle `rss_feeds`
--

INSERT INTO `rss_feeds` VALUES(27, 'http://www.spiegel.de/home/seite2/index.rss', 1, '2011-11-16 19:06:05');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rss_feeds_items`
--

CREATE TABLE `rss_feeds_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=326 ;

--
-- Daten für Tabelle `rss_feeds_items`
--

INSERT INTO `rss_feeds_items` VALUES(325, 27, 325);
INSERT INTO `rss_feeds_items` VALUES(324, 27, 324);
INSERT INTO `rss_feeds_items` VALUES(323, 27, 323);
INSERT INTO `rss_feeds_items` VALUES(322, 27, 322);
INSERT INTO `rss_feeds_items` VALUES(321, 27, 321);
INSERT INTO `rss_feeds_items` VALUES(320, 27, 320);
INSERT INTO `rss_feeds_items` VALUES(319, 27, 319);
INSERT INTO `rss_feeds_items` VALUES(318, 27, 318);
INSERT INTO `rss_feeds_items` VALUES(317, 27, 317);
INSERT INTO `rss_feeds_items` VALUES(316, 27, 316);
INSERT INTO `rss_feeds_items` VALUES(315, 27, 315);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rss_feeds_users`
--

CREATE TABLE `rss_feeds_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Daten für Tabelle `rss_feeds_users`
--

INSERT INTO `rss_feeds_users` VALUES(27, 27, 18);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rss_import_log`
--

CREATE TABLE `rss_import_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` text NOT NULL,
  `duration` int(11) NOT NULL DEFAULT '0',
  `rss_feed_id` int(11) NOT NULL DEFAULT '0',
  `posts_created` int(11) NOT NULL DEFAULT '0',
  `posts_not_created` int(11) NOT NULL DEFAULT '0',
  `rss_feeds_items_created` int(11) NOT NULL DEFAULT '0',
  `rss_feeds_items_not_created` int(11) NOT NULL DEFAULT '0',
  `rss_items_created` int(11) NOT NULL DEFAULT '0',
  `rss_items_not_created` int(11) NOT NULL DEFAULT '0',
  `rss_items_contents_created` int(11) NOT NULL DEFAULT '0',
  `rss_items_contents_not_created` int(11) NOT NULL DEFAULT '0',
  `category_paper_posts_created` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Daten für Tabelle `rss_import_log`
--

INSERT INTO `rss_import_log` VALUES(28, '[]', 7, 27, 11, 0, 11, 0, 11, 0, 83, 0, 0, '2011-11-16 19:06:28', '2011-11-16 19:06:28');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rss_items`
--

CREATE TABLE `rss_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=326 ;

--
-- Daten für Tabelle `rss_items`
--

INSERT INTO `rss_items` VALUES(315, '3411b10d0ee41013b3ae5a570f328c55e02746b2', '2011-11-16 19:06:21');
INSERT INTO `rss_items` VALUES(316, '5a2c68e26e5973b353f523edf54bd2d460db51d0', '2011-11-16 19:06:25');
INSERT INTO `rss_items` VALUES(317, '8c3c0792e67a6bc41cf59c9f7e0e284c4a40dfd5', '2011-11-16 19:06:25');
INSERT INTO `rss_items` VALUES(318, 'f302d5d6d32c51c139ad0b7a235d49dc423c99c5', '2011-11-16 19:06:25');
INSERT INTO `rss_items` VALUES(319, '7aea908024fef7cb2cdd7e88371665dd1c91999f', '2011-11-16 19:06:26');
INSERT INTO `rss_items` VALUES(320, '3729150ac1dadf13399753008f5e3d738eafec5a', '2011-11-16 19:06:26');
INSERT INTO `rss_items` VALUES(321, '51f526fd3d14f551374dbf89c83c651930459442', '2011-11-16 19:06:26');
INSERT INTO `rss_items` VALUES(322, '8e1fef4ce979780fa2229dd1cb4ad3ff27bb006a', '2011-11-16 19:06:26');
INSERT INTO `rss_items` VALUES(323, '103d5831e063e61e870183e2eb897ae616cc6bf9', '2011-11-16 19:06:27');
INSERT INTO `rss_items` VALUES(324, 'ab2759874b5b23e1fcef522d6fb2f39617a1250a', '2011-11-16 19:06:27');
INSERT INTO `rss_items` VALUES(325, '37561bdf37c67af42bf25f14a395cc105f8c91f4', '2011-11-16 19:06:27');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rss_item_content`
--

CREATE TABLE `rss_item_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2388 ;

--
-- Daten für Tabelle `rss_item_content`
--

INSERT INTO `rss_item_content` VALUES(2305, 315, 'content', '<img src="http://www.spiegel.de/images/image-170761-thumbsmall-wrqh.jpg" hspace="5" align="left" />Jahrelang hat China seine Wirtschaft mit billigem Geld gepÃ¤ppelt - und dabei eine gewaltige Kreditblase geschaffen. Nun wollen die roten Machthaber dieÂ Gefahr entschÃ¤rfen. Ein riskantes ManÃ¶ver, im schlimmsten Fall droht dem Finanzsystem der Kollaps.');
INSERT INTO `rss_item_content` VALUES(2306, 315, 'enclosure', '991b057f491c9bddfdada1f07fd57a6e');
INSERT INTO `rss_item_content` VALUES(2307, 315, 'date', '2011-11-16 15:55:00');
INSERT INTO `rss_item_content` VALUES(2308, 315, 'hash', '3411b10d0ee41013b3ae5a570f328c55e02746b2');
INSERT INTO `rss_item_content` VALUES(2309, 315, 'title', 'Finanzkrise in Fernost: In den FÃ¤ngen der Schattenbanker');
INSERT INTO `rss_item_content` VALUES(2310, 315, 'link', 'http://www.spiegel.de/wirtschaft/unternehmen/0,1518,798185,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2311, 315, 'links', 'a:2:{i:0;s:74:"http://www.spiegel.de/wirtschaft/unternehmen/0,1518,798185,00.html#ref=rss";i:1;s:66:"http://www.spiegel.de/wirtschaft/unternehmen/0,1518,798185,00.html";}');
INSERT INTO `rss_item_content` VALUES(2312, 315, 'permalink', 'http://www.spiegel.de/wirtschaft/unternehmen/0,1518,798185,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2313, 316, 'content', 'Garrett McNamara hat vermutlichÂ einenÂ Weltrekord aufgestellt: Der Profi-Surfer bezwang einen 27,4 Meter hohen Brecher. Im Interview spricht der 44-JÃ¤hrige Ã¼ber seinen Ritt auf der Monsterwelle - und den Moment, in dem gigantische Wassermassen Ã¼ber ihm zusammenbrachen.');
INSERT INTO `rss_item_content` VALUES(2314, 316, 'date', '2011-11-16 15:07:00');
INSERT INTO `rss_item_content` VALUES(2315, 316, 'hash', '5a2c68e26e5973b353f523edf54bd2d460db51d0');
INSERT INTO `rss_item_content` VALUES(2316, 316, 'title', 'Rekord-Surfer McNamara: &quot;Sie war hÃ¶her als alles, was ich je gesehen hatte&quot;');
INSERT INTO `rss_item_content` VALUES(2317, 316, 'link', 'http://www.spiegel.de/sport/sonst/0,1518,798125,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2318, 316, 'links', 'a:2:{i:0;s:63:"http://www.spiegel.de/sport/sonst/0,1518,798125,00.html#ref=rss";i:1;s:55:"http://www.spiegel.de/sport/sonst/0,1518,798125,00.html";}');
INSERT INTO `rss_item_content` VALUES(2319, 316, 'permalink', 'http://www.spiegel.de/sport/sonst/0,1518,798125,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2320, 317, 'content', '<img src="http://www.spiegel.de/images/image-283498-thumbsmall-qwox.jpg" hspace="5" align="left" />Ihre AnimositÃ¤ten sind legendÃ¤r:Â Die GrÃ¼nen Joschka Fischer und Reinhard BÃ¼tikofer haben sich schon zu ihrer aktiven Zeit in der Bundespolitik heftig beharkt - und sie tun es noch immer. Ein Interview des Ex-AuÃŸenministers zur Euro-Krise kommentierte BÃ¼tikofer per Twitter,Â kurz und gemein.Â ');
INSERT INTO `rss_item_content` VALUES(2321, 317, 'enclosure', '21c1c114867a1b7c434042f7db8a6918');
INSERT INTO `rss_item_content` VALUES(2322, 317, 'date', '2011-11-16 14:22:00');
INSERT INTO `rss_item_content` VALUES(2323, 317, 'hash', '8c3c0792e67a6bc41cf59c9f7e0e284c4a40dfd5');
INSERT INTO `rss_item_content` VALUES(2324, 317, 'title', 'BÃ¼tikofer twittert: &quot;Vergesst diesen Joschka Fischer!&quot;');
INSERT INTO `rss_item_content` VALUES(2325, 317, 'link', 'http://www.spiegel.de/politik/deutschland/0,1518,798171,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2326, 317, 'links', 'a:2:{i:0;s:71:"http://www.spiegel.de/politik/deutschland/0,1518,798171,00.html#ref=rss";i:1;s:63:"http://www.spiegel.de/politik/deutschland/0,1518,798171,00.html";}');
INSERT INTO `rss_item_content` VALUES(2327, 317, 'permalink', 'http://www.spiegel.de/politik/deutschland/0,1518,798171,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2328, 318, 'content', '<img src="http://www.spiegel.de/images/image-283060-thumbsmall-ygnu.jpg" hspace="5" align="left" />Wer darf mit zur Europameisterschaft, wer muss zu Hause bleiben? 23 PlÃ¤tze hat Bundestrainer Joachim LÃ¶w fÃ¼r den EM-Kader im kommenden Jahr zu vergeben, 18 Spieler haben ihre Nominierung schon so gut wie sicher. Einige mÃ¼ssen noch zittern, andere sind fast chancenlos.');
INSERT INTO `rss_item_content` VALUES(2329, 318, 'enclosure', '512482f49ddab6b99d1e62f1f490a518');
INSERT INTO `rss_item_content` VALUES(2330, 318, 'date', '2011-11-16 14:03:00');
INSERT INTO `rss_item_content` VALUES(2331, 318, 'hash', 'f302d5d6d32c51c139ad0b7a235d49dc423c99c5');
INSERT INTO `rss_item_content` VALUES(2332, 318, 'title', 'DFB-Kader: Der groÃŸe EM-Check');
INSERT INTO `rss_item_content` VALUES(2333, 318, 'link', 'http://www.spiegel.de/sport/fussball/0,1518,792962,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2334, 318, 'links', 'a:2:{i:0;s:66:"http://www.spiegel.de/sport/fussball/0,1518,792962,00.html#ref=rss";i:1;s:58:"http://www.spiegel.de/sport/fussball/0,1518,792962,00.html";}');
INSERT INTO `rss_item_content` VALUES(2335, 318, 'permalink', 'http://www.spiegel.de/sport/fussball/0,1518,792962,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2336, 319, 'content', 'Das Schuldendesaster in Europa greift auf immer mehr LÃ¤nder Ã¼ber. Und die Politik reagiert hilfloser denn je.Â Das Hauptproblem der KrisenbekÃ¤mpfer: Sie lernen nichtÂ aus den Fehlern anderer LÃ¤nder - und noch nicht einmal aus ihren eigenen.Â ');
INSERT INTO `rss_item_content` VALUES(2337, 319, 'date', '2011-11-16 14:00:00');
INSERT INTO `rss_item_content` VALUES(2338, 319, 'hash', '7aea908024fef7cb2cdd7e88371665dd1c91999f');
INSERT INTO `rss_item_content` VALUES(2339, 319, 'title', 'Schuldenkrise: Fataler Euro-Dominoeffekt');
INSERT INTO `rss_item_content` VALUES(2340, 319, 'link', 'http://www.spiegel.de/wirtschaft/0,1518,798118,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2341, 319, 'links', 'a:2:{i:0;s:62:"http://www.spiegel.de/wirtschaft/0,1518,798118,00.html#ref=rss";i:1;s:54:"http://www.spiegel.de/wirtschaft/0,1518,798118,00.html";}');
INSERT INTO `rss_item_content` VALUES(2342, 319, 'permalink', 'http://www.spiegel.de/wirtschaft/0,1518,798118,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2343, 320, 'content', 'Die Zwickauer Terrorzelle plante mÃ¶glicherweise auch AnschlÃ¤ge auf Politiker. Auf einer Datei der VerdÃ¤chtigen fanden Ermittler die Namen des GrÃ¼nen-Bundestagsabgeordneten Montag und des CSU-Parlamentariers Uhl. Beide zeigen sich bestÃ¼rzt.');
INSERT INTO `rss_item_content` VALUES(2344, 320, 'date', '2011-11-16 12:44:00');
INSERT INTO `rss_item_content` VALUES(2345, 320, 'hash', '3729150ac1dadf13399753008f5e3d738eafec5a');
INSERT INTO `rss_item_content` VALUES(2346, 320, 'title', 'Zwickauer Terrorzelle: Neonazis hatten auch Politiker im Visier');
INSERT INTO `rss_item_content` VALUES(2347, 320, 'link', 'http://www.spiegel.de/politik/deutschland/0,1518,798121,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2348, 320, 'links', 'a:2:{i:0;s:71:"http://www.spiegel.de/politik/deutschland/0,1518,798121,00.html#ref=rss";i:1;s:63:"http://www.spiegel.de/politik/deutschland/0,1518,798121,00.html";}');
INSERT INTO `rss_item_content` VALUES(2349, 320, 'permalink', 'http://www.spiegel.de/politik/deutschland/0,1518,798121,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2350, 321, 'content', '<img src="http://www.spiegel.de/images/image-283537-thumbsmall-uzzy.jpg" hspace="5" align="left" />Dickes GeschÃ¤ft fÃ¼r Heckler & Koch: Ein indischer ZigarrenhÃ¤ndler bestellte bei dem RÃ¼stungskonzern 17.000 Maschinenpistolen - doch der Deal kÃ¶nnteÂ gegen deutsche Ausfuhrbestimmungen verstoÃŸen. AuffÃ¤llig ist, dass StaatsanwÃ¤lte bereits in einem Ã¤hnlichen Fall gegen das schwÃ¤bische Unternehmen ermitteln.');
INSERT INTO `rss_item_content` VALUES(2351, 321, 'enclosure', '20a85caf7c4de7cf70438051005ae943');
INSERT INTO `rss_item_content` VALUES(2352, 321, 'date', '2011-11-16 11:44:00');
INSERT INTO `rss_item_content` VALUES(2353, 321, 'hash', '51f526fd3d14f551374dbf89c83c651930459442');
INSERT INTO `rss_item_content` VALUES(2354, 321, 'title', 'Dubioser Waffenhandel: Deutsche KnarrenÂ fÃ¼r denÂ ZigarrenkÃ¶nig');
INSERT INTO `rss_item_content` VALUES(2355, 321, 'link', 'http://www.spiegel.de/wirtschaft/unternehmen/0,1518,797640,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2356, 321, 'links', 'a:2:{i:0;s:74:"http://www.spiegel.de/wirtschaft/unternehmen/0,1518,797640,00.html#ref=rss";i:1;s:66:"http://www.spiegel.de/wirtschaft/unternehmen/0,1518,797640,00.html";}');
INSERT INTO `rss_item_content` VALUES(2357, 321, 'permalink', 'http://www.spiegel.de/wirtschaft/unternehmen/0,1518,797640,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2358, 322, 'content', 'Die Jugend kommt gewaltig: AngehendeÂ Absolventen haben derzeit so gute Jobchancen wie nie zuvor, das gilt selbst fÃ¼r Geisteswissenschaftler. Dennoch fÃ¼hlen sichÂ nicht alle umworben. FÃ¼nfÂ Bewerber berichten von ihren Erfahrungen - und davon, was schieflaufen kann.');
INSERT INTO `rss_item_content` VALUES(2359, 322, 'date', '2011-11-16 11:16:00');
INSERT INTO `rss_item_content` VALUES(2360, 322, 'hash', '8e1fef4ce979780fa2229dd1cb4ad3ff27bb006a');
INSERT INTO `rss_item_content` VALUES(2361, 322, 'title', 'Jobmarkt fÃ¼r Absolventen: &quot;Wir wollen Sie!&quot;');
INSERT INTO `rss_item_content` VALUES(2362, 322, 'link', 'http://www.spiegel.de/karriere/berufsstart/0,1518,796964,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2363, 322, 'links', 'a:2:{i:0;s:72:"http://www.spiegel.de/karriere/berufsstart/0,1518,796964,00.html#ref=rss";i:1;s:64:"http://www.spiegel.de/karriere/berufsstart/0,1518,796964,00.html";}');
INSERT INTO `rss_item_content` VALUES(2364, 322, 'permalink', 'http://www.spiegel.de/karriere/berufsstart/0,1518,796964,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2365, 323, 'content', '<img src="http://www.spiegel.de/images/image-183009-thumbsmall-ondv.jpg" hspace="5" align="left" />Erstmals seit seinem RÃ¼cktritt zeigt sich Karl-Theodor zu Guttenberg der Ã–ffentlichkeit. Bei einem Vortrag in Kanada will der gestrauchelte Politiker nach SPIEGEL-ONLINE-Informationen Ã¼ber die Wirtschaftskrise sprechen - angekÃ¼ndigt ist er als "angesehener Staatsmann".');
INSERT INTO `rss_item_content` VALUES(2366, 323, 'enclosure', '4beaca1f39f927911faef32ce934fd8b');
INSERT INTO `rss_item_content` VALUES(2367, 323, 'date', '2011-11-16 10:16:00');
INSERT INTO `rss_item_content` VALUES(2368, 323, 'hash', '103d5831e063e61e870183e2eb897ae616cc6bf9');
INSERT INTO `rss_item_content` VALUES(2369, 323, 'title', 'Vortrag bei Sicherheitstagung: Guttenberg kehrt auf die internationale BÃ¼hne zurÃ¼ck');
INSERT INTO `rss_item_content` VALUES(2370, 323, 'link', 'http://www.spiegel.de/politik/ausland/0,1518,798027,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2371, 323, 'links', 'a:2:{i:0;s:67:"http://www.spiegel.de/politik/ausland/0,1518,798027,00.html#ref=rss";i:1;s:59:"http://www.spiegel.de/politik/ausland/0,1518,798027,00.html";}');
INSERT INTO `rss_item_content` VALUES(2372, 323, 'permalink', 'http://www.spiegel.de/politik/ausland/0,1518,798027,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2373, 324, 'content', 'Karl Rabeder war reich. Dann stieg er aus: Er versteigerteÂ seineÂ Villa, verkaufteÂ seine Segelflieger, Luxuskarossen, Firma undÂ gab den ErlÃ¶sÂ fÃ¼r einen guten Zweck. Im Interview erzÃ¤hlt er, wieÂ es sichÂ jetztÂ mit nur 1000 EuroÂ lebt - und erklÃ¤rt, was Geld und GlÃ¼ck miteinander zu tun haben.');
INSERT INTO `rss_item_content` VALUES(2374, 324, 'date', '2011-11-16 06:31:00');
INSERT INTO `rss_item_content` VALUES(2375, 324, 'hash', 'ab2759874b5b23e1fcef522d6fb2f39617a1250a');
INSERT INTO `rss_item_content` VALUES(2376, 324, 'title', 'Ex-MillionÃ¤r Rabeder: &quot;FÃ¼r viele war ich das Geld-Schwein&quot;');
INSERT INTO `rss_item_content` VALUES(2377, 324, 'link', 'http://www.spiegel.de/panorama/0,1518,794458,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2378, 324, 'links', 'a:2:{i:0;s:60:"http://www.spiegel.de/panorama/0,1518,794458,00.html#ref=rss";i:1;s:52:"http://www.spiegel.de/panorama/0,1518,794458,00.html";}');
INSERT INTO `rss_item_content` VALUES(2379, 324, 'permalink', 'http://www.spiegel.de/panorama/0,1518,794458,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2380, 325, 'content', '<img src="http://www.spiegel.de/images/image-279076-thumbsmall-knnq.jpg" hspace="5" align="left" />WarumÂ gehen AutosÂ immer mehrÂ in die Breite? Die Ã¼bergroÃŸen Karosserien verstopfenÂ die StÃ¤dte,Â sie verursachen Chaos in WohnstraÃŸenÂ und sorgen auf AutobahnbaustellenÂ fÃ¼r Lebensgefahr.Â DabeiÂ ist das Breitenwachstum technischÂ Ã¼berflÃ¼ssig - undÂ pumptÂ meist nur das Ego der Besitzer auf.');
INSERT INTO `rss_item_content` VALUES(2381, 325, 'enclosure', 'b729f6bfb31915ef7fa9fd78f2e14938');
INSERT INTO `rss_item_content` VALUES(2382, 325, 'date', '2011-11-16 06:24:00');
INSERT INTO `rss_item_content` VALUES(2383, 325, 'hash', '37561bdf37c67af42bf25f14a395cc105f8c91f4');
INSERT INTO `rss_item_content` VALUES(2384, 325, 'title', 'ÃœbergroÃŸe Autos: Viel zu breit');
INSERT INTO `rss_item_content` VALUES(2385, 325, 'link', 'http://www.spiegel.de/auto/aktuell/0,1518,795662,00.html#ref=rss');
INSERT INTO `rss_item_content` VALUES(2386, 325, 'links', 'a:2:{i:0;s:64:"http://www.spiegel.de/auto/aktuell/0,1518,795662,00.html#ref=rss";i:1;s:56:"http://www.spiegel.de/auto/aktuell/0,1518,795662,00.html";}');
INSERT INTO `rss_item_content` VALUES(2387, 325, 'permalink', 'http://www.spiegel.de/auto/aktuell/0,1518,795662,00.html#ref=rss');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schema_migrations`
--

CREATE TABLE `schema_migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `schema_migrations`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_type` varchar(20) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `namespace` varchar(50) NOT NULL,
  `key` varchar(50) NOT NULL,
  `value` text,
  `value_data_type` varchar(50) NOT NULL DEFAULT 'string',
  `note` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=184 ;

--
-- Daten für Tabelle `settings`
--

INSERT INTO `settings` VALUES(182, 'user', 18, 'default', 'locale', 'deu', 'locale_chooser', NULL, '2011-11-10 11:14:55', '2011-11-10 11:14:55');
INSERT INTO `settings` VALUES(183, 'user', 19, 'default', 'locale', 'deu', 'locale_chooser', NULL, '2011-11-14 11:24:51', '2011-11-14 11:24:51');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paper_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `own_paper` tinyint(6) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Daten für Tabelle `subscriptions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `test_content_papers`
--

CREATE TABLE `test_content_papers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paper_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Daten für Tabelle `test_content_papers`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `enabled` int(4) NOT NULL DEFAULT '1',
  `content_paper_counttffasrere` int(11) NOT NULL,
  `subscriber_count` int(11) NOT NULL,
  `post_count` int(11) NOT NULL,
  `repost_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `topics`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `url` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `image` text CHARACTER SET latin1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `visible_home` tinyint(1) NOT NULL DEFAULT '0',
  `allow_messages` tinyint(4) NOT NULL DEFAULT '1',
  `allow_comments` tinyint(4) NOT NULL DEFAULT '1',
  `subscription_count` int(11) NOT NULL,
  `subscriber_count` int(11) NOT NULL,
  `post_count` int(11) NOT NULL,
  `repost_count` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL,
  `paper_count` int(11) NOT NULL,
  `topic_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `visible_home` (`visible_home`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` VALUES(18, 3, '', '', '', 'tim.wiegard@gmail.com', 'tim', 'da862d0b240b6a394b87e036c846912b4586c6a4', NULL, '2011-11-10 11:14:54', '2011-11-16 19:06:28', NULL, 1, 0, 1, 1, 0, 0, 14, 0, 0, 0, 0);
INSERT INTO `users` VALUES(19, 1, '', '', '', 'alf@myzeitung.de', 'alf', '9c0c8481df01555e76d178bed5c30e3a4ba7c235', NULL, '2011-11-14 11:24:50', '2011-11-15 12:37:51', NULL, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0);

-- MySQL dump 10.13  Distrib 5.5.11, for osx10.6 (i386)
--
-- Host: localhost    Database: launchpad
-- ------------------------------------------------------
-- Server version	5.5.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `description` text CHARACTER SET latin1,
  `slug` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (34,'Blog','A collection of blog posts','blog'),(37,'Slide','A collections of posts for the home page slider','slide');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `author_id` int(11) NOT NULL,
  `body` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NULL,
  `type` varchar(20) NOT NULL DEFAULT 'page',
  `status` varchar(20) NOT NULL DEFAULT 'published',
  `created` datetime NOT NULL,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `weight` int(11) NOT NULL DEFAULT '0',
  `slug` varchar(255) CHARACTER SET latin1 NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `body` longtext,
  `parent_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `template` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  KEY `category_id` (`category_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `content_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `content_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `content_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `content` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content`
--

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` VALUES (1,1,'page','published','2011-04-29 00:00:00','2011-06-27 15:51:13',0,'home','Home','<p>\r\n	Nulla facilisi. Pellentesque quis eros non ipsum sodales sagittis id vel urna. Nullam vulputate scelerisque tincidunt. Proin sagittis fringilla erat, vitae ultricies ipsum feugiat sit amet. In hac habitasse platea dictumst. In a nisi nulla. Fusce et sem mauris. Nam quis porta risus. Donec ultricies condimentum erat a congue. Praesent euismod tristique lacinia. Sed ut risus enim. Suspendisse ligula magna, tempor eu lacinia ac, adipiscing a ligula.</p>\r\n<p>\r\n	Nulla facilisi. Pellentesque quis eros non ipsum sodales sagittis id vel urna. Nullam vulputate scelerisque tincidunt. Proin sagittis fringilla erat, vitae ultricies ipsum feugiat sit amet. In hac habitasse platea dictumst. In a nisi nulla. Fusce et sem mauris. Nam quis porta risus. Donec ultricies condimentum erat a congue. Praesent euismod tristique lacinia. Sed ut risus enim. Suspendisse ligula magna, tempor eu lacinia ac, adipiscing a ligula.</p>\r\n',NULL,NULL,'home.template.php'),(2,1,'page','published','2011-04-29 12:01:21','2011-06-27 15:18:30',0,'about','About','<p>\r\n	Nulla facilisi. Pellentesque quis eros non ipsum sodales sagittis id vel urna. Nullam vulputate scelerisque tincidunt. Proin sagittis fringilla erat, vitae ultricies ipsum feugiat sit amet. In hac habitasse platea dictumst. In a nisi nulla. Fusce et sem mauris. Nam quis porta risus. Donec ultricies condimentum erat a congue. Praesent euismod tristique lacinia. Sed ut risus enim. Suspendisse ligula magna, tempor eu lacinia ac, adipiscing a ligula.</p>\r\n<div>\r\n	&nbsp;</div>\r\n<div>\r\n	<div>\r\n		Nulla facilisi. Pellentesque quis eros non ipsum sodales sagittis id vel urna. Nullam vulputate scelerisque tincidunt. Proin sagittis fringilla erat, vitae ultricies ipsum feugiat sit amet. In hac habitasse platea dictumst. In a nisi nulla. Fusce et sem mauris. Nam quis porta risus. Donec ultricies condimentum erat a congue. Praesent euismod tristique lacinia. Sed ut risus enim. Suspendisse ligula magna, tempor eu lacinia ac, adipiscing a ligula.</div>\r\n	<div>\r\n		&nbsp;</div>\r\n</div>\r\n<p>\r\n	&nbsp;</p>\r\n',NULL,NULL,NULL),(5,1,'post','published','2011-05-09 18:42:53','2011-05-19 01:07:34',0,'this-is-a-new-post','This is also a new Post',NULL,NULL,34,NULL),(6,1,'post','published','2011-05-23 10:13:38','2011-06-20 14:23:43',0,'an-interesting-post','An interesting post',NULL,NULL,34,NULL),(7,1,'post','published','2011-06-19 19:49:47','2011-06-20 02:49:54',0,'slide-1','Slide 1','<p>\r\n	This is slide 1</p>\r\n',NULL,37,NULL),(8,1,'post','published','2011-06-19 19:50:28','2011-06-20 02:50:36',0,'slide-2','Slide 2','<p>\r\n	This is slide 2</p>\r\n',NULL,37,NULL),(9,1,'page','published','2011-06-19 21:08:30','2011-06-20 04:08:57',0,'contact','Contact',NULL,NULL,NULL,'contact.template.php'),(10,1,'page','published','2011-06-19 21:09:42','2011-06-20 04:09:46',0,'blog','Blog',NULL,NULL,NULL,'blog.template.php');
/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_x_tags`
--

DROP TABLE IF EXISTS `content_x_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_x_tags` (
  `content_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`content_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `content_x_tags_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_x_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_x_tags`
--

LOCK TABLES `content_x_tags` WRITE;
/*!40000 ALTER TABLE `content_x_tags` DISABLE KEYS */;
INSERT INTO `content_x_tags` VALUES (1,37),(2,37),(1,38),(2,38),(1,44),(5,44),(1,46),(2,51),(2,57),(2,58),(5,62),(5,63),(5,64),(5,66);
/*!40000 ALTER TABLE `content_x_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER SET latin1 NOT NULL,
  `type` varchar(100) CHARACTER SET latin1 NOT NULL,
  `size` int(11) NOT NULL,
  `author_id` int(11) NULL,
  `caption` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `description` text CHARACTER SET latin1,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `media_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options`
--

LOCK TABLES `options` WRITE;
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` VALUES (1,'analytics_id','AU-XXXXXX'),(2,'site_name','Launchpad'),(3,'theme','sky'),(5,'tagline','This is a tagline'),(30,'address','555 S Elm St.'),(31,'phone','(555) 555-5555'),(34,'description','This is the site description.'),(36,'email','me@me.com');
/*!40000 ALTER TABLE `options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `snippets`
--

DROP TABLE IF EXISTS `snippets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `snippets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `snippets_ibfk_1` (`content_id`),
  CONSTRAINT `snippets_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `snippets`
--

LOCK TABLES `snippets` WRITE;
/*!40000 ALTER TABLE `snippets` DISABLE KEYS */;
INSERT INTO `snippets` VALUES (67,5,'foo','bar'),(69,5,'this is a new variable updated again','I\'m not sure what to put here foo'),(70,9,'phone','555-5555'),(71,9,'address','555 Elm');
/*!40000 ALTER TABLE `snippets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (44,'apple'),(60,'apples'),(57,'banana'),(38,'blue'),(46,'boat'),(62,'elephants'),(68,'green'),(64,'has'),(63,'it'),(37,'orange'),(61,'peaches'),(51,'purple'),(67,'red'),(58,'slop'),(66,'tags');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `username` varchar(50) CHARACTER SET latin1 NOT NULL,
  `email` varchar(50) CHARACTER SET latin1 NOT NULL,
  `bio` text CHARACTER SET latin1,
  `role` tinyint(4) NOT NULL,
  `password` varchar(50) CHARACTER SET latin1 NOT NULL,
  `image_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'super','user','super','super@user.com','This account is the default Superusers account. It has full access to the system including changing user info (including passwords). It is higly recemended that you either create a new super user acount and delete this one or change the password of this account',0,'1b3231655cebb7a1f783eddf27d254ca', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-06-29  5:10:10

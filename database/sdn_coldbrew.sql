-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: sdn_coldbrew
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

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
-- Table structure for table `t_host`
--

DROP TABLE IF EXISTS `t_host`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_host` (
  `hw_addr` varchar(30) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`hw_addr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_host`
--

LOCK TABLES `t_host` WRITE;
/*!40000 ALTER TABLE `t_host` DISABLE KEYS */;
INSERT INTO `t_host` VALUES ('00:00:00:00:00:01','h1'),('00:00:00:00:00:02','h2'),('00:00:00:00:00:03','h3');
/*!40000 ALTER TABLE `t_host` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_link`
--

DROP TABLE IF EXISTS `t_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_link` (
  `src_hw_addr` varchar(30) NOT NULL,
  `dst_hw_addr` varchar(30) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`src_hw_addr`,`dst_hw_addr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_link`
--

LOCK TABLES `t_link` WRITE;
/*!40000 ALTER TABLE `t_link` DISABLE KEYS */;
INSERT INTO `t_link` VALUES ('00:00:00:00:00:01','86:53:c0:2f:a0:23',NULL),('00:00:00:00:00:02','32:2b:40:62:fe:ad',NULL),('00:00:00:00:00:03','fe:a0:df:6f:3d:60',NULL),('0e:08:88:ca:53:12','82:2e:ff:60:eb:b3',''),('22:f6:1a:5c:a9:32','8e:9e:ef:81:c5:25',''),('32:2b:40:62:fe:ad','00:00:00:00:00:02',''),('82:2e:ff:60:eb:b3','0e:08:88:ca:53:12',''),('86:53:c0:2f:a0:23','00:00:00:00:00:01',''),('8e:9e:ef:81:c5:25','22:f6:1a:5c:a9:32',''),('fe:a0:df:6f:3d:60','00:00:00:00:00:03','');
/*!40000 ALTER TABLE `t_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_switch`
--

DROP TABLE IF EXISTS `t_switch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_switch` (
  `dpid` varchar(20) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_switch`
--

LOCK TABLES `t_switch` WRITE;
/*!40000 ALTER TABLE `t_switch` DISABLE KEYS */;
INSERT INTO `t_switch` VALUES ('0000000000000001','0000000000000001'),('0000000000000002','0000000000000002'),('0000000000000003','0000000000000003');
/*!40000 ALTER TABLE `t_switch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_app_action`
--

DROP TABLE IF EXISTS `tbl_app_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_app_action` (
  `id` varchar(100) NOT NULL,
  `index` int(11) DEFAULT '0',
  `name` varchar(100) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_app_action`
--

LOCK TABLES `tbl_app_action` WRITE;
/*!40000 ALTER TABLE `tbl_app_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_app_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_fw_board`
--

DROP TABLE IF EXISTS `tbl_fw_board`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_fw_board` (
  `id` varchar(225) NOT NULL,
  `post_id` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `content` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_fw_board`
--

LOCK TABLES `tbl_fw_board` WRITE;
/*!40000 ALTER TABLE `tbl_fw_board` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_fw_board` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_fw_message`
--

DROP TABLE IF EXISTS `tbl_fw_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_fw_message` (
  `id` varchar(255) NOT NULL,
  `from_id` varchar(255) DEFAULT NULL,
  `to_id` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` blob,
  `flag` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_fw_message`
--

LOCK TABLES `tbl_fw_message` WRITE;
/*!40000 ALTER TABLE `tbl_fw_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_fw_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_fw_news`
--

DROP TABLE IF EXISTS `tbl_fw_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_fw_news` (
  `id` varchar(225) NOT NULL,
  `post_id` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_fw_news`
--

LOCK TABLES `tbl_fw_news` WRITE;
/*!40000 ALTER TABLE `tbl_fw_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_fw_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_fw_notification`
--

DROP TABLE IF EXISTS `tbl_fw_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_fw_notification` (
  `id` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `flag` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_fw_notification`
--

LOCK TABLES `tbl_fw_notification` WRITE;
/*!40000 ALTER TABLE `tbl_fw_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_fw_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_fw_task`
--

DROP TABLE IF EXISTS `tbl_fw_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_fw_task` (
  `id` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `desc` blob,
  `progress` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_fw_task`
--

LOCK TABLES `tbl_fw_task` WRITE;
/*!40000 ALTER TABLE `tbl_fw_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_fw_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_privilege`
--

DROP TABLE IF EXISTS `tbl_privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_privilege` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_privilege`
--

LOCK TABLES `tbl_privilege` WRITE;
/*!40000 ALTER TABLE `tbl_privilege` DISABLE KEYS */;
INSERT INTO `tbl_privilege` VALUES ('admin','Admin',''),('operator','Operator','');
/*!40000 ALTER TABLE `tbl_privilege` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_privilege_action`
--

DROP TABLE IF EXISTS `tbl_privilege_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_privilege_action` (
  `privilege_id` varchar(100) NOT NULL,
  `app_action_id` varchar(100) NOT NULL,
  PRIMARY KEY (`privilege_id`,`app_action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_privilege_action`
--

LOCK TABLES `tbl_privilege_action` WRITE;
/*!40000 ALTER TABLE `tbl_privilege_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_privilege_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_setting`
--

DROP TABLE IF EXISTS `tbl_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_setting` (
  `id` varchar(255) NOT NULL,
  `val` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_setting`
--

LOCK TABLES `tbl_setting` WRITE;
/*!40000 ALTER TABLE `tbl_setting` DISABLE KEYS */;
INSERT INTO `tbl_setting` VALUES ('ryu_packaged_path','/usr/local/lib/python2.7/dist-packages/ryu/',NULL),('mininet_util_path','/home/tarom/sdn-tutorial/mininet/util/',NULL),('ryu_additional_script_path','/home/tarom/sdn-tutorial/additional-ryu-script/',NULL),('mininet_additional_topo_script_path','/home/tarom/sdn-tutorial/additional-mininet-topo-script/',NULL),('sflowrt_path','/home/tarom/sdn-tutorial/sflow-rt/',NULL),('sflowrt_url','http://localhost:8008/',NULL),('snmp_community','public',NULL),('snmp_version','2c',NULL),('ryu_script_default_selected','ofctl_rest.py,rest_conf_switch.py,rest_topology.py,/home/tarom/sdn-tutorial/additional-ryu-script/SendHeaderToController.py',NULL),('ryu_shell_capture_file','/home/tarom/sdn-tutorial/shell-capture/ryu.capture',NULL),('mininet_shell_capture_file','/home/tarom/sdn-tutorial/shell-capture/mininet.capture',NULL),('internal_service_url','http://127.0.0.1:8989/',NULL),('sflowrt_shell_capture_file','/home/tarom/sdn-tutorial/shell-capture/sflowrt.capture',NULL);
/*!40000 ALTER TABLE `tbl_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_user_account`
--

DROP TABLE IF EXISTS `tbl_user_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_user_account` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `enable` tinyint(1) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_user_account`
--

LOCK TABLES `tbl_user_account` WRITE;
/*!40000 ALTER TABLE `tbl_user_account` DISABLE KEYS */;
INSERT INTO `tbl_user_account` VALUES ('superadmin','Super Admin','17c4520f6cfd1ab53d8745e84681eb49',1,NULL,''),('superuser','Super User','0baea2f0ae20150db78f58cddac442a9',1,NULL,'');
/*!40000 ALTER TABLE `tbl_user_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_user_privilege`
--

DROP TABLE IF EXISTS `tbl_user_privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_user_privilege` (
  `user_account_id` varchar(100) NOT NULL,
  `privilege_id` varchar(100) NOT NULL,
  `enable` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`user_account_id`,`privilege_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_user_privilege`
--

LOCK TABLES `tbl_user_privilege` WRITE;
/*!40000 ALTER TABLE `tbl_user_privilege` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_user_privilege` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-23 16:12:33

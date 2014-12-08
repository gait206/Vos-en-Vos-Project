CREATE DATABASE  IF NOT EXISTS `vvtissue` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `vvtissue`;

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,' WC Papier','Katrin','Papier','WC Papier','http://i101.twenga.com/werkbenodigdheden/wc-papier/katrin-classic-toiletpapier-400-tp_2838006473490951374f.jpg',5.95,0,15),(2,' Handzeep','Tana','Reinigingsmiddelen','Reinigt uw handen bij ernstige verontreiniging','http://i101.twenga.com/mooi-gezond/handzeep/-tana-soft-sensation-tp_1941850125457611358f.jpg',8.95,0,20),(3,' Tapijt reiniger','Karcher','Schoonmaakmateriaal','Reinigt uw tapijt zo snel dat het bijna leuk word','http://www.xtra-materieel.nl/site/9BD9DCAAFA80AD79C12576F9004EBF43/$FILE/Tapijtreiniger%20huren%20-%20Karcher%20Puzzi%20100.jpg',250,0,4),(4,' Eurotissue Touchfree Handdoek','Eurotissue','Dispencers','De touchfree dispenser van Eurotissue is bijzonder functioneel.  De sensor reageert op water op de handen en niet op licht , dit voorkomt onnodig papier verbruik, bv. wanneer men voorbijloopt. De grot','http://www.eurotissue.com/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/t/o/touchfree-handdoekdispenser-zilver-eu1001-front.jpg',125.6,0,12);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;



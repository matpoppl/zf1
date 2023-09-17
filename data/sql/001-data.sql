
SET foreign_key_checks=0;

-- pass1
INSERT INTO `users` VALUES
(NULL,'user1','$2y$10$.SETJv2/6RtATRl.26pKLuqJsVv/7N3y/E5Eo.z7sV0ODpDrwQ3OG');

INSERT INTO `menus` (`site`, `locale`, `sid`, `name`) VALUES (1,1,'main','Main');

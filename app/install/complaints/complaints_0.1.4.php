$sql[] = "TRUNCATE TABLE `reasons`";
$sql[] = "INSERT INTO `reasons` (`id`, `value`, `type`) VALUES
(1, 'It is a picture of my person', 0),
(2, 'Other reason', 0),
(3, 'The picture of the user profile is inappropriate', 1),
(4, 'I am molested by this user', 1),
(5, 'Posts/Comments by this user do not match with the terms of service (extremism/glorification of violence/pornography)', 1),
(6, 'The user sends spam', 1),
(7, 'It is a hacked user profile', 1),
(8, 'Contents of this article do not match with the terms of service (extremism/glorification of violence/pornography)', 2),
(9, 'Comments of this article do not match with the terms of service (extremism/glorification of violence/pornography)', 2),
(10, 'Contents of this paper do not match with the terms of service (extremism/glorification of violence/pornography)', 3),
(11, 'The description of the paper is inappropriate', 3),
(12, 'The image of the paper is inappropriate', 3);[...]";
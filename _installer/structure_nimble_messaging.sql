CREATE TABLE `application_settings` (
  `id` int(11) NOT NULL,
  `sms_gateway` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'twilio',
  `twilio_sid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twilio_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plivo_auth_id` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plivo_auth_token` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plivo_app_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_type` int(11) NOT NULL DEFAULT '0' COMMENT '1 for admin, 2 for sub account',
  `time_zone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `append_text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `typo_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_double_optin` int(11) NOT NULL DEFAULT '0' COMMENT '1 for double optin',
  `payment_processor` int(11) NOT NULL DEFAULT '1',
  `auth_net_trans_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_net_api_login_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stripe_secret_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stripe_publishable_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_switch` int(11) NOT NULL DEFAULT '0' COMMENT '1 for live',
  `paypal_sandbox_email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `incoming_sms_charge` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `outgoing_sms_charge` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `mms_credit_charges` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `per_credit_charges` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.01',
  `email_subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_app_user_email` text COLLATE utf8_unicode_ci,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `unsub_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `app_date_format` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'Y-m-d',
  `app_logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'nimble_messaging.png',
  `enable_link_shrink` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for enable',
  `bitly_key` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bitly_token` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enable_sender_id` int(11) NOT NULL DEFAULT '0' COMMENT '1 for enabled',
  `twilio_sender_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `success_payment_email_subject` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `success_payment_email` text COLLATE utf8_unicode_ci,
  `failed_payment_email_subject` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `failed_payment_email` text COLLATE utf8_unicode_ci,
  `admin_email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_noti_subject` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_noti_email` text COLLATE utf8_unicode_ci,
  `email_subject_for_admin_notification` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_app_user_email_for_admin` text COLLATE utf8_unicode_ci,
  `banned_words` text COLLATE utf8_unicode_ci,
  `nexmo_api_key` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nexmo_api_secret` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subs_lookup` int(11) NOT NULL DEFAULT '0' COMMENT '1 for enable',
  `sidebar_color` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_key` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `android_app_server_key` text COLLATE utf8_unicode_ci,
  `device_id` int(11) NOT NULL DEFAULT '0',
  `cron_stop_time_from` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cron_stop_time_to` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_purchase_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_purchase_code_status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `settings_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apt_time` datetime DEFAULT NULL,
  `apt_message` text COLLATE utf8_unicode_ci,
  `phone_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `appointment_alerts` (
  `id` int(11) NOT NULL,
  `apt_id` int(11) NOT NULL,
  `message_time` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apt_message` text COLLATE utf8_unicode_ci,
  `media` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `appointment_followup_msgs` (
  `id` int(11) NOT NULL,
  `apt_id` int(11) NOT NULL,
  `message_time` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apt_message` text COLLATE utf8_unicode_ci,
  `media` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `area_codes` (
  `id` int(11) NOT NULL,
  `state_code` varchar(2) NOT NULL,
  `code_number` int(11) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `area_codes` (`id`, `state_code`, `code_number`, `is_active`, `status`) VALUES
(36, 'AL', 501, 1, 1),
(35, 'AR', 479, 1, 1),
(34, 'AL', 334, 1, 1),
(33, 'AL', 256, 1, 1),
(32, 'AL', 251, 1, 1),
(31, 'AL', 205, 1, 1),
(30, 'AK', 907, 0, 1),
(29, 'AB', 780, 1, 1),
(28, 'AB', 587, 0, 1),
(27, 'AB', 403, 1, 1),
(37, 'AL', 870, 1, 1),
(38, 'AS', 684, 0, 1),
(39, 'AZ', 445, 0, 1),
(40, 'AZ', 480, 1, 1),
(41, 'AZ', 520, 1, 1),
(42, 'AZ', 602, 0, 1),
(43, 'AZ', 623, 0, 1),
(44, 'AZ', 928, 1, 1),
(45, 'BC', 250, 1, 1),
(46, 'BC', 604, 1, 1),
(47, 'BC', 778, 1, 1),
(48, 'CA', 209, 1, 1),
(49, 'CA', 213, 1, 1),
(50, 'CA', 310, 1, 1),
(51, 'CA', 323, 1, 1),
(52, 'CA', 408, 1, 1),
(53, 'CA', 415, 1, 1),
(54, 'CA', 424, 1, 1),
(55, 'CA', 442, 0, 1),
(56, 'CA', 510, 1, 1),
(57, 'CA', 530, 1, 1),
(58, 'CA', 559, 1, 1),
(59, 'CA', 562, 1, 1),
(60, 'CA', 619, 1, 1),
(61, 'CA', 626, 1, 1),
(62, 'CA', 650, 1, 1),
(63, 'CA', 657, 1, 1),
(64, 'CA', 661, 1, 1),
(65, 'CA', 707, 1, 1),
(66, 'CA', 714, 1, 1),
(67, 'CA', 747, 0, 1),
(68, 'CA', 760, 1, 1),
(69, 'CA', 805, 1, 1),
(70, 'CA', 818, 1, 1),
(71, 'CA', 831, 1, 1),
(72, 'CA', 858, 1, 1),
(73, 'CA', 909, 1, 1),
(74, 'CA', 916, 1, 1),
(75, 'CA', 925, 1, 1),
(76, 'CA', 949, 1, 1),
(77, 'CA', 951, 1, 1),
(78, 'CO', 303, 1, 1),
(79, 'CO', 719, 1, 1),
(80, 'CO', 720, 1, 1),
(81, 'CO', 970, 1, 1),
(82, 'CT', 203, 1, 1),
(83, 'CT', 475, 0, 1),
(84, 'CT', 860, 1, 1),
(85, 'CT', 959, 0, 1),
(86, 'DC', 202, 1, 1),
(87, 'DE', 302, 1, 1),
(88, 'FL', 239, 1, 1),
(89, 'FL', 305, 1, 1),
(90, 'FL', 321, 1, 1),
(91, 'FL', 352, 1, 1),
(92, 'FL', 386, 1, 1),
(93, 'FL', 407, 1, 1),
(94, 'FL', 561, 1, 1),
(95, 'FL', 689, 0, 1),
(96, 'FL', 727, 1, 1),
(97, 'FL', 754, 1, 1),
(98, 'FL', 772, 1, 1),
(99, 'FL', 786, 1, 1),
(100, 'FL', 813, 1, 1),
(101, 'FL', 850, 1, 1),
(102, 'FL', 863, 1, 1),
(103, 'FL', 904, 1, 1),
(104, 'FL', 941, 1, 1),
(105, 'FL', 954, 1, 1),
(106, 'GA', 229, 1, 1),
(107, 'GA', 404, 1, 1),
(108, 'GA', 470, 0, 1),
(109, 'GA', 478, 1, 1),
(110, 'GA', 678, 1, 1),
(111, 'GA', 706, 1, 1),
(112, 'GA', 762, 1, 1),
(113, 'GA', 770, 0, 1),
(114, 'GA', 912, 1, 1),
(115, 'GU', 671, 0, 1),
(116, 'HI', 808, 0, 1),
(117, 'IA', 319, 1, 1),
(118, 'IA', 515, 1, 1),
(119, 'IA', 563, 1, 1),
(120, 'IA', 641, 0, 1),
(121, 'IA', 712, 1, 1),
(122, 'ID', 208, 1, 1),
(123, 'IL', 217, 1, 1),
(124, 'IL', 224, 1, 1),
(125, 'IL', 302, 1, 1),
(126, 'IL', 309, 1, 1),
(127, 'IL', 312, 1, 1),
(128, 'IL', 331, 1, 1),
(129, 'IL', 618, 1, 1),
(130, 'IL', 630, 1, 1),
(131, 'IL', 708, 1, 1),
(132, 'IL', 773, 1, 1),
(133, 'IL', 779, 1, 1),
(134, 'IL', 815, 1, 1),
(135, 'IL', 847, 1, 1),
(136, 'IL', 872, 0, 1),
(137, 'IN', 219, 1, 1),
(138, 'IN', 260, 1, 1),
(139, 'IN', 317, 1, 1),
(140, 'IN', 574, 1, 1),
(141, 'IN', 765, 1, 1),
(142, 'IN', 812, 1, 1),
(143, 'KS', 316, 1, 1),
(144, 'KS', 620, 1, 1),
(145, 'KS', 785, 1, 1),
(146, 'KS', 913, 1, 1),
(147, 'KY', 270, 1, 1),
(148, 'KY', 502, 1, 1),
(149, 'KY', 606, 1, 1),
(150, 'KY', 859, 1, 1),
(151, 'LA', 225, 1, 1),
(152, 'LA', 318, 1, 1),
(153, 'LA', 337, 1, 1),
(154, 'LA', 504, 1, 1),
(155, 'LA', 985, 1, 1),
(156, 'MA', 339, 1, 1),
(157, 'MA', 351, 0, 1),
(158, 'MA', 413, 1, 1),
(159, 'MA', 508, 1, 1),
(160, 'MA', 617, 1, 1),
(161, 'MA', 774, 1, 1),
(162, 'MA', 781, 1, 1),
(163, 'MA', 857, 1, 1),
(164, 'MA', 978, 1, 1),
(165, 'MB', 204, 1, 1),
(166, 'MD', 240, 1, 1),
(167, 'MD', 301, 1, 1),
(168, 'MD', 410, 1, 1),
(169, 'MD', 443, 1, 1),
(170, 'ME', 207, 1, 1),
(171, 'MI', 231, 1, 1),
(172, 'MI', 248, 1, 1),
(173, 'MI', 269, 1, 1),
(174, 'MI', 313, 1, 1),
(175, 'MI', 517, 1, 1),
(176, 'MI', 586, 1, 1),
(177, 'MI', 616, 1, 1),
(178, 'MI', 734, 1, 1),
(179, 'MI', 810, 1, 1),
(180, 'MI', 906, 1, 1),
(181, 'MI', 947, 0, 1),
(182, 'MI', 989, 1, 1),
(183, 'MN', 218, 1, 1),
(184, 'MN', 320, 1, 1),
(185, 'MN', 507, 1, 1),
(186, 'MN', 612, 1, 1),
(187, 'MN', 651, 1, 1),
(188, 'MN', 763, 1, 1),
(189, 'MN', 952, 1, 1),
(190, 'MO', 314, 1, 1),
(191, 'MO', 417, 1, 1),
(192, 'MO', 573, 1, 1),
(193, 'MO', 636, 1, 1),
(194, 'MO', 660, 1, 1),
(195, 'MO', 816, 1, 1),
(196, 'MS', 228, 1, 1),
(197, 'MS', 601, 1, 1),
(198, 'MS', 662, 1, 1),
(199, 'MS', 769, 1, 1),
(200, 'MT', 406, 1, 1),
(201, 'NB', 506, 0, 1),
(202, 'NC', 252, 1, 1),
(203, 'NC', 336, 1, 1),
(204, 'NC', 704, 1, 1),
(205, 'NC', 828, 1, 1),
(206, 'NC', 910, 1, 1),
(207, 'NC', 919, 1, 1),
(208, 'NC', 980, 1, 1),
(209, 'NC', 984, 0, 1),
(210, 'ND', 701, 1, 1),
(211, 'NE', 308, 1, 1),
(212, 'NE', 402, 1, 1),
(213, 'NE', 605, 1, 1),
(214, 'NH', 603, 1, 1),
(215, 'NJ', 201, 1, 1),
(216, 'NJ', 226, 1, 1),
(217, 'NJ', 254, 1, 1),
(218, 'NJ', 276, 1, 1),
(219, 'NJ', 289, 1, 1),
(220, 'NJ', 306, 0, 1),
(221, 'NJ', 316, 1, 1),
(222, 'NJ', 506, 0, 1),
(223, 'NJ', 551, 1, 1),
(224, 'NJ', 604, 1, 1),
(225, 'NJ', 609, 1, 1),
(226, 'NJ', 647, 1, 1),
(227, 'NJ', 705, 1, 1),
(228, 'NJ', 709, 1, 1),
(229, 'NJ', 732, 1, 1),
(230, 'NJ', 848, 1, 1),
(231, 'NJ', 856, 1, 1),
(232, 'NJ', 862, 1, 1),
(233, 'NJ', 908, 1, 1),
(234, 'NJ', 973, 1, 1),
(235, 'NL', 709, 1, 1),
(236, 'NM', 505, 1, 1),
(237, 'NM', 575, 1, 1),
(238, 'NS', 902, 1, 1),
(239, 'NT', 867, 0, 1),
(240, 'NU', 867, 0, 1),
(241, 'NV', 702, 1, 1),
(242, 'NV', 775, 1, 1),
(243, 'NY', 212, 0, 1),
(244, 'NY', 315, 1, 1),
(245, 'NY', 347, 1, 1),
(246, 'NY', 516, 1, 1),
(247, 'NY', 518, 1, 1),
(248, 'NY', 585, 1, 1),
(249, 'NY', 607, 1, 1),
(250, 'NY', 631, 1, 1),
(251, 'NY', 646, 1, 1),
(252, 'NY', 712, 1, 1),
(253, 'NY', 716, 1, 1),
(254, 'NY', 718, 1, 1),
(255, 'NY', 845, 1, 1),
(256, 'NY', 914, 1, 1),
(257, 'NY', 917, 1, 1),
(258, 'OH', 216, 1, 1),
(259, 'OH', 234, 1, 1),
(260, 'OH', 283, 0, 1),
(261, 'OH', 330, 1, 1),
(262, 'OH', 380, 0, 1),
(263, 'OH', 419, 1, 1),
(264, 'OH', 440, 1, 1),
(265, 'OH', 513, 1, 1),
(266, 'OH', 567, 1, 1),
(267, 'OH', 614, 1, 1),
(268, 'OH', 740, 1, 1),
(269, 'OH', 937, 1, 1),
(270, 'OK', 405, 1, 1),
(271, 'OK', 580, 1, 1),
(272, 'OK', 918, 1, 1),
(273, 'ON', 226, 1, 1),
(274, 'ON', 289, 1, 1),
(275, 'ON', 416, 0, 1),
(276, 'ON', 519, 1, 1),
(277, 'ON', 613, 1, 1),
(278, 'ON', 647, 1, 1),
(279, 'ON', 705, 1, 1),
(280, 'ON', 807, 0, 1),
(281, 'ON', 905, 1, 1),
(282, 'OR', 503, 1, 1),
(283, 'OR', 541, 1, 1),
(284, 'OR', 971, 1, 1),
(285, 'PA', 215, 1, 1),
(286, 'PA', 267, 1, 1),
(287, 'PA', 412, 1, 1),
(288, 'PA', 484, 1, 1),
(289, 'PA', 570, 1, 1),
(290, 'PA', 610, 1, 1),
(291, 'PA', 717, 1, 1),
(292, 'PA', 724, 1, 1),
(293, 'PA', 814, 1, 1),
(294, 'PA', 878, 0, 1),
(295, 'PE', 902, 1, 1),
(296, 'PR', 787, 0, 1),
(297, 'PR', 939, 0, 1),
(298, 'QC', 418, 1, 1),
(299, 'QC', 438, 0, 1),
(300, 'QC', 450, 1, 1),
(301, 'QC', 514, 1, 1),
(302, 'QC', 581, 0, 1),
(303, 'QC', 819, 1, 1),
(304, 'RI', 401, 1, 1),
(305, 'SC', 803, 1, 1),
(306, 'SC', 843, 1, 1),
(307, 'SC', 864, 1, 1),
(308, 'SD', 605, 1, 1),
(309, 'SK', 306, 0, 1),
(310, 'TN', 423, 1, 1),
(311, 'TN', 615, 1, 1),
(312, 'TN', 731, 1, 1),
(313, 'TN', 865, 1, 1),
(314, 'TN', 901, 1, 1),
(315, 'TN', 931, 1, 1),
(316, 'TX', 210, 1, 1),
(317, 'TX', 214, 1, 1),
(318, 'TX', 254, 1, 1),
(319, 'TX', 281, 1, 1),
(320, 'TX', 325, 1, 1),
(321, 'TX', 361, 1, 1),
(322, 'TX', 409, 1, 1),
(323, 'TX', 430, 0, 1),
(324, 'TX', 432, 1, 1),
(325, 'TX', 469, 1, 1),
(326, 'TX', 512, 1, 1),
(327, 'TX', 682, 1, 1),
(328, 'TX', 713, 0, 1),
(329, 'TX', 806, 1, 1),
(330, 'TX', 817, 1, 1),
(331, 'TX', 830, 1, 1),
(332, 'TX', 832, 1, 1),
(333, 'TX', 903, 1, 1),
(334, 'TX', 915, 1, 1),
(335, 'TX', 936, 1, 1),
(336, 'TX', 940, 1, 1),
(337, 'TX', 956, 1, 1),
(338, 'TX', 972, 1, 1),
(339, 'TX', 979, 1, 1),
(340, 'UT', 385, 1, 1),
(341, 'UT', 435, 1, 1),
(342, 'UT', 801, 1, 1),
(343, 'VA', 276, 1, 1),
(344, 'VA', 434, 1, 1),
(345, 'VA', 540, 1, 1),
(346, 'VA', 571, 1, 1),
(347, 'VA', 703, 1, 1),
(348, 'VA', 757, 1, 1),
(349, 'VA', 804, 1, 1),
(350, 'VI', 340, 0, 1),
(351, 'VT', 802, 1, 1),
(352, 'WA', 206, 1, 1),
(353, 'WA', 253, 1, 1),
(354, 'WA', 360, 1, 1),
(355, 'WA', 425, 1, 1),
(356, 'WA', 509, 1, 1),
(357, 'WA', 564, 0, 1),
(358, 'WI', 262, 1, 1),
(359, 'WA', 414, 1, 1),
(360, 'WA', 608, 1, 1),
(361, 'WA', 715, 1, 1),
(362, 'WA', 920, 1, 1),
(363, 'WV', 304, 1, 1),
(364, 'WV', 681, 0, 1),
(365, 'WY', 307, 1, 1),
(366, 'YT', 867, 0, 1);

CREATE TABLE `bound_phones` (
  `id` int(11) NOT NULL,
  `to_number` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_number` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `lease_date` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `what_is_sent` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `bulk_sms` (
  `id` int(11) NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bulk_media` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keyword` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1 for campaign, 2 for autores',
  `welcome_sms` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `already_member_msg` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `double_optin` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `media` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `get_email` int(11) NOT NULL DEFAULT '0',
  `reply_email` text COLLATE utf8_unicode_ci,
  `email_updated` text COLLATE utf8_unicode_ci,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_message` text COLLATE utf8_unicode_ci,
  `start_date` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `end_date` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expire_message` text COLLATE utf8_unicode_ci,
  `attach_mobile_device` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for mobile device',
  `direct_subscription` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for enabled',
  `double_optin_check` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for enabled',
  `get_subs_name_check` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for enabled',
  `msg_to_get_subscriber_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_received_confirmation_msg` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `campaign_expiry_check` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for enabled',
  `followup_msg_check` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for enabled',
  `double_optin_confirm_message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `chat_history` (
  `id` int(11) NOT NULL,
  `phone_id` int(11) NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direction` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `message_sid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 for unread'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `follow_up_msgs` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `delay_day` int(11) NOT NULL DEFAULT '0',
  `delay_time` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `media` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `mobile_devices` (
  `id` int(11) NOT NULL,
  `device_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_modal` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_token` text COLLATE utf8_unicode_ci,
  `app_url` text COLLATE utf8_unicode_ci,
  `device_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for enable',
  `user_id` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `package_plans` (
  `id` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sms_credits` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number_limit` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso_country` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_free_days` int(11) NOT NULL DEFAULT '0',
  `free_days` int(11) NOT NULL DEFAULT '0',
  `pkg_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sms_gateway` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'twilio'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `payment_history` (
  `id` int(11) NOT NULL,
  `business_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payer_status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payer_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `txn_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gross_payment` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_processor` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `queued_msgs` (
  `id` int(11) NOT NULL,
  `to_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci,
  `media` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) DEFAULT '1' COMMENT '1 for bulk, 2 for appointment alert / followup',
  `message_time` datetime DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for sent',
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `sms_gateway` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `send_to_user` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `rollover_credits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `credits` int(11) NOT NULL DEFAULT '0',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `schedulers` (
  `id` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scheduled_time` datetime DEFAULT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `phone_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `media` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for sent',
  `scheduler_type` int(11) NOT NULL DEFAULT '0' COMMENT '1 for scheduler, 2 for delay msg of campaign',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attach_mobile_device` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for mobile device',
  `send_immediate` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for immediate',
  `survey_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `sms_history` (
  `id` int(11) NOT NULL,
  `to_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `media` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sms_sid` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direction` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_msg_id` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_sent` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `State` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Status` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `states` (`id`, `State`, `Code`, `Status`) VALUES
(1, 'Alabama', 'AL', '1'),
(2, 'Alaska', 'AK', '1'),
(3, 'Arizona', 'AZ', '1'),
(4, 'Arkansas', 'AR', '1'),
(5, 'California', 'CA', '1'),
(6, 'Colorado', 'CO', '1'),
(7, 'Connecticut', 'CT', '1'),
(8, 'Delaware', 'DE', '1'),
(9, 'District Of Columbia', 'DC', '1'),
(10, 'Florida', 'FL', '1'),
(11, 'Georgia', 'GA', '1'),
(12, 'Hawaii', 'HI', '1'),
(13, 'Idaho', 'ID', '1'),
(14, 'Illinois', 'IL', '1'),
(15, 'Indiana', 'IN', '1'),
(16, 'Iowa', 'IA', '1'),
(17, 'Kansas', 'KS', '1'),
(18, 'Kentucky', 'KY', '1'),
(19, 'Louisiana', 'LA', '1'),
(20, 'Maine', 'ME', '1'),
(21, 'Maryland', 'MD', '1'),
(22, 'Massachusetts', 'MA', '1'),
(23, 'Michigan', 'MI', '1'),
(24, 'Minnesota', 'MN', '1'),
(25, 'Mississippi', 'MS', '1'),
(26, 'Missouri', 'MO', '1'),
(27, 'Montana', 'MT', '1'),
(28, 'Nebraska', 'NE', '1'),
(29, 'Nevada', 'NV', '1'),
(30, 'New Hampshire', 'NH', '1'),
(31, 'New Jersey', 'NJ', '1'),
(32, 'New Mexico', 'NM', '1'),
(33, 'New York', 'NY', '1'),
(34, 'North Carolina', 'NC', '1'),
(35, 'North Dakota', 'ND', '1'),
(36, 'Ohio', 'OH', '1'),
(37, 'Oklahoma', 'OK', '1'),
(38, 'Oregon', 'OR', '1'),
(39, 'Pennsylvania', 'PA', '1'),
(40, 'Rhode Island', 'RI', '1'),
(41, 'South Carolina', 'SC', '1'),
(42, 'South Dakota', 'SD', '1'),
(43, 'Tennessee', 'TN', '1'),
(44, 'Texas', 'TX', '1'),
(45, 'Utah', 'UT', '1'),
(46, 'Vermont', 'VT', '1'),
(47, 'Virginia', 'VA', '1'),
(48, 'Washington', 'WA', '1'),
(49, 'West Virginia', 'WV', '1'),
(50, 'Wisconsin', 'WI', '1'),
(51, 'Wyoming', 'WY', '1'),
(52, 'Alberta', 'CA_AB', '1'),
(53, 'British Columbia', 'CA_BC', '1'),
(54, 'Manitoba', 'CA_MB', '1'),
(55, 'New Brunswick', 'CA_NB', '1'),
(56, 'Newfoundland', 'CA_NL', '1'),
(57, 'Nova Scotia', 'CA_NS', '1'),
(58, 'Ontario', 'CA_ON', '1'),
(59, 'Prince Edward Island', 'CA_PE', '1'),
(60, 'Quebec', 'CA_QC', '1'),
(61, 'Saskatchewan', 'CA_SK', '1');

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 for active, 2 for block, 3 for deleted',
  `subs_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `unsubscribe_date` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `caller_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `caller_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `carrier_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `carrier_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_country_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_network_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_info` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `subscribers_group_assignment` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `subscriber_id` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 for active, 2 for blocked, 3 for deleted',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `surveys` (
  `id` int(11) NOT NULL,
  `survey_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `survey_desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `survey_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `survey_answers` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `question_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `question_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `answer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `survey_questions` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `question_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `question` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `answers` text COLLATE utf8_unicode_ci,
  `media` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `survey_responses` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `time_zones` (
  `id` int(11) NOT NULL,
  `time_zone` varchar(150) NOT NULL,
  `time_zone_value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `time_zones` (`id`, `time_zone`, `time_zone_value`) VALUES
(1, 'Pacific/Midway', '(GMT-11:00) Midway Island'),
(2, 'US/Samoa', '(GMT-11:00) Samoa'),
(3, 'US/Hawaii', '(GMT-10:00) Hawaii'),
(4, 'US/Alaska', '(GMT-09:00) Alaska'),
(5, 'US/Pacific', '(GMT-08:00) Pacific Time (US &amp; Canada)'),
(6, 'America/Tijuana', '(GMT-08:00) Tijuana'),
(7, 'US/Arizona', '(GMT-07:00) Arizona'),
(8, 'US/Mountain', '(GMT-07:00) Mountain Time (US &amp; Canada)'),
(9, 'America/Chihuahua', '(GMT-07:00) Chihuahua'),
(10, 'America/Mazatlan', '(GMT-07:00) Mazatlan'),
(11, 'America/Mexico_City', '(GMT-06:00) Mexico City'),
(12, 'America/Monterrey', '(GMT-06:00) Monterrey'),
(13, 'Canada/Saskatchewan', '(GMT-06:00) Saskatchewan'),
(14, 'US/Central', '(GMT-06:00) Central Time (US &amp; Canada)'),
(15, 'US/Eastern', '(GMT-05:00) Eastern Time (US &amp; Canada)'),
(16, 'US/East-Indiana', '(GMT-05:00) Indiana (East)'),
(17, 'America/Bogota', '(GMT-05:00) Bogota'),
(18, 'America/Lima', '(GMT-05:00) Lima'),
(19, 'America/Caracas', '(GMT-04:30) Caracas'),
(20, 'Canada/Atlantic', '(GMT-04:00) Atlantic Time (Canada)'),
(21, 'America/La_Paz', '(GMT-04:00) La Paz'),
(22, 'America/Santiago', '(GMT-04:00) Santiago'),
(23, 'Canada/Newfoundland', '(GMT-03:30) Newfoundland'),
(24, 'America/Buenos_Aires', '(GMT-03:00) Buenos Aires'),
(25, 'Greenland', '(GMT-03:00) Greenland'),
(26, 'Atlantic/Stanley', '(GMT-02:00) Stanley'),
(27, 'Atlantic/Azores', '(GMT-01:00) Azores'),
(28, 'Atlantic/Cape_Verde', '(GMT-01:00) Cape Verde Is.'),
(29, 'Africa/Casablanca', '(GMT) Casablanca'),
(30, 'Europe/Dublin', '(GMT) Dublin'),
(31, 'Europe/Lisbon', '(GMT) Lisbon'),
(32, 'Europe/London', '(GMT) London'),
(33, 'Africa/Monrovia', '(GMT) Monrovia'),
(34, 'Europe/Amsterdam', '(GMT+01:00) Amsterdam'),
(35, 'Europe/Belgrade', '(GMT+01:00) Belgrade'),
(36, 'Europe/Berlin', '(GMT+01:00) Berlin'),
(37, 'Europe/Bratislava', '(GMT+01:00) Bratislava'),
(38, 'Europe/Brussels', '(GMT+01:00) Brussels'),
(39, 'Europe/Budapest', '(GMT+01:00) Budapest'),
(40, 'Europe/Copenhagen', '(GMT+01:00) Copenhagen'),
(41, 'Europe/Ljubljana', '(GMT+01:00) Ljubljana'),
(42, 'Europe/Madrid', '(GMT+01:00) Madrid'),
(43, 'Europe/Paris', '(GMT+01:00) Paris'),
(44, 'Europe/Prague', '(GMT+01:00) Prague'),
(45, 'Europe/Rome', '(GMT+01:00) Rome'),
(46, 'Europe/Sarajevo', '(GMT+01:00) Sarajevo'),
(47, 'Europe/Skopje', '(GMT+01:00) Skopje'),
(48, 'Europe/Stockholm', '(GMT+01:00) Stockholm'),
(49, 'Europe/Vienna', '(GMT+01:00) Vienna'),
(50, 'Europe/Warsaw', '(GMT+01:00) Warsaw'),
(51, 'Europe/Zagreb', '(GMT+01:00) Zagreb'),
(52, 'Europe/Athens', '(GMT+02:00) Athens'),
(53, 'Europe/Bucharest', '(GMT+02:00) Bucharest'),
(54, 'Africa/Cairo', '(GMT+02:00) Cairo'),
(55, 'Africa/Harare', '(GMT+02:00) Harare'),
(56, 'Europe/Helsinki', '(GMT+02:00) Helsinki'),
(57, 'Europe/Istanbul', '(GMT+02:00) Istanbul'),
(58, 'Asia/Jerusalem', '(GMT+02:00) Jerusalem'),
(59, 'Europe/Kiev', '(GMT+02:00) Kyiv'),
(60, 'Europe/Minsk', '(GMT+02:00) Minsk'),
(61, 'Europe/Riga', '(GMT+02:00) Riga'),
(62, 'Europe/Sofia', '(GMT+02:00) Sofia'),
(63, 'Europe/Tallinn', '(GMT+02:00) Tallinn'),
(64, 'Europe/Vilnius', '(GMT+02:00) Vilnius'),
(65, 'Asia/Baghdad', '(GMT+03:00) Baghdad'),
(66, 'Asia/Kuwait', '(GMT+03:00) Kuwait'),
(67, 'Africa/Nairobi', '(GMT+03:00) Nairobi'),
(68, 'Asia/Riyadh', '(GMT+03:00) Riyadh'),
(69, 'Europe/Moscow', '(GMT+03:00) Moscow'),
(70, 'Asia/Tehran', '(GMT+03:30) Tehran'),
(71, 'Asia/Baku', '(GMT+04:00) Baku'),
(72, 'Europe/Volgograd', '(GMT+04:00) Volgograd'),
(73, 'Asia/Muscat', '(GMT+04:00) Muscat'),
(74, 'Asia/Tbilisi', '(GMT+04:00) Tbilisi'),
(75, 'Asia/Yerevan', '(GMT+04:00) Yerevan'),
(76, 'Asia/Kabul', '(GMT+04:30) Kabul'),
(77, 'Asia/Karachi', '(GMT+05:00) Karachi'),
(78, 'Asia/Tashkent', '(GMT+05:00) Tashkent'),
(79, 'Asia/Kolkata', '(GMT+05:30) Kolkata'),
(80, 'Asia/Kathmandu', '(GMT+05:45) Kathmandu'),
(81, 'Asia/Yekaterinburg', '(GMT+06:00) Ekaterinburg'),
(82, 'Asia/Almaty', '(GMT+06:00) Almaty'),
(83, 'Asia/Dhaka', '(GMT+06:00) Dhaka'),
(84, 'Asia/Novosibirsk', '(GMT+07:00) Novosibirsk'),
(85, 'Asia/Bangkok', '(GMT+07:00) Bangkok'),
(86, 'Asia/Jakarta', '(GMT+07:00) Jakarta'),
(87, 'Asia/Krasnoyarsk', '(GMT+08:00) Krasnoyarsk'),
(88, 'Asia/Chongqing', '(GMT+08:00) Chongqing'),
(89, 'Asia/Hong_Kong', '(GMT+08:00) Hong Kong'),
(90, 'Asia/Kuala_Lumpur', '(GMT+08:00) Kuala Lumpur'),
(91, 'Australia/Perth', '(GMT+08:00) Perth'),
(92, 'Asia/Singapore', '(GMT+08:00) Singapore'),
(93, 'Asia/Taipei', '(GMT+08:00) Taipei'),
(94, 'Asia/Ulaanbaatar', '(GMT+08:00) Ulaan Bataar'),
(95, 'Asia/Urumqi', '(GMT+08:00) Urumqi'),
(96, 'Asia/Irkutsk', '(GMT+09:00) Irkutsk'),
(97, 'Asia/Seoul', '(GMT+09:00) Seoul'),
(98, 'Asia/Tokyo', '(GMT+09:00) Tokyo'),
(99, 'Australia/Adelaide', '(GMT+09:30) Adelaide'),
(100, 'Australia/Darwin', '(GMT+09:30) Darwin'),
(101, 'Asia/Yakutsk', '(GMT+10:00) Yakutsk'),
(102, 'Australia/Brisbane', '(GMT+10:00) Brisbane'),
(103, 'Australia/Canberra', '(GMT+10:00) Canberra'),
(104, 'Pacific/Guam', '(GMT+10:00) Guam'),
(105, 'Australia/Hobart', '(GMT+10:00) Hobart'),
(106, 'Australia/Melbourne', '(GMT+10:00) Melbourne'),
(107, 'Pacific/Port_Moresby', '(GMT+10:00) Port Moresby'),
(108, 'Australia/Sydney', '(GMT+10:00) Sydney'),
(109, 'Asia/Vladivostok', '(GMT+11:00) Vladivostok'),
(110, 'Asia/Magadan', '(GMT+12:00) Magadan'),
(111, 'Pacific/Auckland', '(GMT+12:00) Auckland'),
(112, 'Pacific/Fiji', '(GMT+12:00) Fiji');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tcap_ctia` int(11) NOT NULL DEFAULT '0',
  `msg_and_data_rate` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1 admin, 2 for subacc',
  `parent_user_id` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 for active, 2 for block, 3 for deleted',
  `used_sms_credits` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `card_number` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cvv` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `month` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `year` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `response` text COLLATE utf8_unicode_ci,
  `response_code` text COLLATE utf8_unicode_ci,
  `subscription_id` text COLLATE utf8_unicode_ci,
  `authorize_status` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_subscriber_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customerID` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscriptionID` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscriptionData` text COLLATE utf8_unicode_ci,
  `app_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `app_secret` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `access_token` longtext COLLATE utf8_unicode_ci,
  `tw_access_token` text COLLATE utf8_unicode_ci,
  `tw_access_token_secret` text COLLATE utf8_unicode_ci,
  `tw_consumer_key` text COLLATE utf8_unicode_ci,
  `tw_consumer_secret` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `users_phone_numbers` (
  `id` int(11) NOT NULL,
  `friendly_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso_country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_sid` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1 for twilio, 2 for plivo, 3 for nexmo',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `user_package_assignment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `pkg_id` int(11) NOT NULL DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `sms_credits` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `used_sms_credits` varchar(100) COLLATE utf8_unicode_ci DEFAULT '0',
  `phone_number_limit` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso_country` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pkg_country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 for suspended',
  `assigned_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sms_gateway` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `webforms` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `webform_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `label_for_name_field` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `label_for_phone_field` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `label_for_email_field` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disclaimer_text` text COLLATE utf8_unicode_ci,
  `field_width` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_height` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subs_btn_bg_color` varchar(50) COLLATE utf8_unicode_ci DEFAULT '#FFFFFF',
  `close_btn_bg_color` varchar(50) COLLATE utf8_unicode_ci DEFAULT '#FFFFFF',
  `color_for_label` varchar(50) COLLATE utf8_unicode_ci DEFAULT '#FFFFFF',
  `frame_width` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `frame_height` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `frame_bg_color` varchar(50) COLLATE utf8_unicode_ci DEFAULT '#FFFFFF',
  `webform_type` tinyint(4) NOT NULL DEFAULT '1',
  `custom_fields` text COLLATE utf8_unicode_ci,
  `label_for_disclaimer_text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `heading_for_custom_info_panel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `showing_method` int(11) NOT NULL DEFAULT '1' COMMENT '1 for pop, 2 for on page'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `web_user_info` (
  `id` int(11) NOT NULL,
  `pkg_id` int(11) NOT NULL DEFAULT '0',
  `first_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tcap_ctia` int(11) NOT NULL DEFAULT '0',
  `msg_and_data_rate` int(11) NOT NULL DEFAULT '0',
  `parent_user_id` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `card_number` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cvv` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `month` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `year` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `response` text COLLATE utf8_unicode_ci,
  `response_code` text COLLATE utf8_unicode_ci,
  `subscription_id` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `application_settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `appointment_alerts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `appointment_followup_msgs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `area_codes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `bound_phones`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `bulk_sms`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `chat_history`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `follow_up_msgs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mobile_devices`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `package_plans`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `queued_msgs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `rollover_credits`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `schedulers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sms_history`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `subscribers_group_assignment`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `survey_answers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `survey_questions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `survey_responses`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `time_zones`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users_phone_numbers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_package_assignment`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `webforms`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `web_user_info`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `application_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `appointment_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `appointment_followup_msgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `area_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=367;

ALTER TABLE `bound_phones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `bulk_sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `chat_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `follow_up_msgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `mobile_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `package_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `payment_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `queued_msgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `rollover_credits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `schedulers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sms_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `subscribers_group_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `surveys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `survey_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `survey_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `survey_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `time_zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users_phone_numbers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user_package_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `webforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `web_user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
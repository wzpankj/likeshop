SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO `ls_config` (`id`, `shop_id`, `type`, `name`, `value`, `update_time`) VALUES (1, 0, 'recharge', 'app_status', '1', 1634712368);
INSERT INTO `ls_config` (`id`, `shop_id`, `type`, `name`, `value`, `update_time`) VALUES (2, 0, 'decoration_center', 'background_image', 'uploads/images/202110221900234ec544456.png', 1634712368);

INSERT INTO `ls_recharge_rule` (`id`, `recharge_amount`, `give_balance`, `create_time`) VALUES (11, 10.00, 1.00, 1634712368);
INSERT INTO `ls_recharge_rule` (`id`, `recharge_amount`, `give_balance`, `create_time`) VALUES (12, 20.00, 2.00, 1634712368);
INSERT INTO `ls_recharge_rule` (`id`, `recharge_amount`, `give_balance`, `create_time`) VALUES (13, 30.00, 3.00, 1634712368);
INSERT INTO `ls_recharge_rule` (`id`, `recharge_amount`, `give_balance`, `create_time`) VALUES (14, 40.00, 4.00, 1634712368);
INSERT INTO `ls_recharge_rule` (`id`, `recharge_amount`, `give_balance`, `create_time`) VALUES (15, 50.00, 5.00, 1634712368);

INSERT INTO `ls_ad` (`id`, `ad_position_id`, `title`, `terminal`, `image`, `link`, `status`, `create_time`, `update_time`, `del`) VALUES (1, 1, '首页顶部轮播图', 1, 'uploads/images/202110191501253a6942796.jpg', '', 1, 1634623226, 1634631903, 0);
INSERT INTO `ls_ad` (`id`, `ad_position_id`, `title`, `terminal`, `image`, `link`, `status`, `create_time`, `update_time`, `del`) VALUES (2, 2, '点餐顶部轮播图', 1, 'uploads/images/202110191455064c8627336.jpg', '', 1, 1634623247, 1634626510, 0);
INSERT INTO `ls_ad` (`id`, `ad_position_id`, `title`, `terminal`, `image`, `link`, `status`, `create_time`, `update_time`, `del`) VALUES (3, 2, '点餐顶部轮播2', 1, 'uploads/images/2021101914550677c802663.jpg', '', 1, 1634623267, 1634626516, 0);
INSERT INTO `ls_ad` (`id`, `ad_position_id`, `title`, `terminal`, `image`, `link`, `status`, `create_time`, `update_time`, `del`) VALUES (4, 2, '点餐3', 1, 'uploads/images/20211019145506635c81896.jpeg', '', 1, 1634626526, 1634626526, 0);
INSERT INTO `ls_ad` (`id`, `ad_position_id`, `title`, `terminal`, `image`, `link`, `status`, `create_time`, `update_time`, `del`) VALUES (5, 2, '点餐4', 1, 'uploads/images/2021101914550630ae00023.jpeg', '', 1, 1634626536, 1634626536, 0);
INSERT INTO `ls_ad` (`id`, `ad_position_id`, `title`, `terminal`, `image`, `link`, `status`, `create_time`, `update_time`, `del`) VALUES (6, 1, '首页2', 1, 'uploads/images/202110191636109edb07118.jpg', '', 1, 1634626905, 1634632573, 0);
INSERT INTO `ls_ad` (`id`, `ad_position_id`, `title`, `terminal`, `image`, `link`, `status`, `create_time`, `update_time`, `del`) VALUES (7, 1, '首页3', 1, 'uploads/images/20211019150125f78ab4754.jpg', 'www.baidu.com', 1, 1634626920, 1634639285, 0);


INSERT INTO `ls_announcement` (`id`, `content`, `create_time`, `update_time`, `del`) VALUES (1, '经典大橘，爆汁回归。皮薄汁足，延续手剥工艺，口感丰富有层次', 1634633856, 1634637194, 0);


INSERT INTO `ls_file_cate` (`id`, `shop_id`, `name`, `pid`, `type`, `level`, `sort`, `del`, `create_time`, `update_time`) VALUES (1, 0, 'logo', 0, 10, 1, 1, 0, 1634612230, 1634612230);


INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (1, '冰淇淋.png', 0, 10, 'uploads/images/20211019112204fac0e6896.png', 1634613724, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (2, '热门.png', 0, 10, 'uploads/images/20211019112204068db4886.png', 1634613724, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (3, '葡萄.png', 0, 10, 'uploads/images/20211019112204c9c992372.png', 1634613724, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (4, '菠萝.png', 0, 10, 'uploads/images/2021101911220422dc67566.png', 1634613724, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (5, '83a9843a0ee82459e827b9922ec0e0b.jpg', 0, 10, 'uploads/images/202110191127203ce4b2034.jpg', 1634614040, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (6, 'd3ba84debae6203644a9326b7201110.jpg', 0, 10, 'uploads/images/20211019112720616bb9919.jpg', 1634614040, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (7, 'af0bc5f37bb075d3bf3b4f1595529b6.jpg', 0, 10, 'uploads/images/20211019114915117190467.jpg', 1634615355, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (8, 'a766ef0c5266c59a46e742ca4b929a0.jpg', 0, 10, 'uploads/images/20211019141344671024638.jpg', 1634624024, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (9, 'b42d95677e8f2db5c122653270d81a4.jpg', 0, 10, 'uploads/images/2021101914180763cd43838.jpg', 1634624287, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (10, 'f04cf310260f66eb8cfdada77c5e93d.jpg', 0, 10, 'uploads/images/20211019142118c09498376.jpg', 1634624478, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (11, '9a881d864e552c3a9f5a834f6f6f430.jpg', 0, 10, 'uploads/images/202110191427205b9618648.jpg', 1634624840, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (12, '5c9c1226b3803903bfad527cd8dca74.jpg', 0, 10, 'uploads/images/20211019142952da0c53235.jpg', 1634624992, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (13, 'b6fe3a5e81e40d594e046a026c2621e.jpg', 0, 10, 'uploads/images/20211019143423843986710.jpg', 1634625263, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (14, '7969f3c492c23ec0aff8cc8a1fc9639.jpg', 0, 10, 'uploads/images/202110191435214eaf20114.jpg', 1634625321, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (15, '1661f684ed3a6681069f203f250c998.jpg', 0, 10, 'uploads/images/20211019143739a0c926794.jpg', 1634625459, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (16, '47eb14faeb91c4fa39804c26a82964d.jpg', 0, 10, 'uploads/images/20211019144049ea8729797.jpg', 1634625649, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (17, '8092daca6150263bb551e0b31c82a4e.jpg', 0, 10, 'uploads/images/20211019144329626799779.jpg', 1634625809, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (18, '浮图秀图片_image.baidu.com_20211019145038.jpeg', 0, 10, 'uploads/images/2021101914550630ae00023.jpeg', 1634626506, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (19, '浮图秀图片_image.baidu.com_20211019145055.jpeg', 0, 10, 'uploads/images/20211019145506635c81896.jpeg', 1634626506, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (20, '浮图秀图片_image.baidu.com_20211019145014.jpg', 0, 10, 'uploads/images/2021101914550677c802663.jpg', 1634626506, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (21, '浮图秀图片_image.baidu.com_20211019145020.jpg', 0, 10, 'uploads/images/202110191455064c8627336.jpg', 1634626506, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (22, '浮图秀图片_image.baidu.com_20211019150037.jpg', 0, 10, 'uploads/images/202110191501253a6942796.jpg', 1634626885, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (23, '浮图秀图片_image.baidu.com_20211019145833.jpg', 0, 10, 'uploads/images/202110191501256f46b2060.jpg', 1634626885, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (24, '浮图秀图片_image.baidu.com_20211019145859.jpg', 0, 10, 'uploads/images/20211019150125f78ab4754.jpg', 1634626885, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (25, '浮图秀图片_image.baidu.com_20211019162122.jpg', 0, 10, 'uploads/images/202110191621533e0666379.jpg', 1634631713, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (26, '浮图秀图片_image.baidu.com_20211019162415.jpg', 0, 10, 'uploads/images/202110191624299a9871427.jpg', 1634631869, 1, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (27, '浮图秀图片_image.baidu.com_20211019162546.jpg', 0, 10, 'uploads/images/20211019162600989f88258.jpg', 1634631960, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (28, '浮图秀图片_image.baidu.com_20211019163550.jpg', 0, 10, 'uploads/images/202110191636109edb07118.jpg', 1634632570, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (29, '冰淇淋 (1).png', 1, 10, 'uploads/images/20211019164302d42553566.png', 1634632982, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (30, '牛油果.png', 0, 10, 'uploads/images/20211019164411775078373.png', 1634633051, 0, 0, 0);
INSERT INTO `ls_file` (`id`, `name`, `cid`, `type`, `uri`, `create_time`, `del`, `shop_id`, `user_id`) VALUES (31, 'my_head_bg.png', 0, 10, 'uploads/images/202110221900234ec544456.png', 1634633099, 0, 0, 0);



INSERT INTO `ls_goods_category` (`id`, `name`, `sort`, `image`, `create_time`, `update_time`, `del`) VALUES (1, '当季限定', 250, 'uploads/images/20211019112204c9c992372.png', 1634613729, 1634631352, 0);
INSERT INTO `ls_goods_category` (`id`, `name`, `sort`, `image`, `create_time`, `update_time`, `del`) VALUES (2, '人气必喝榜', 251, 'uploads/images/20211019112204068db4886.png', 1634613782, 1634631360, 0);
INSERT INTO `ls_goods_category` (`id`, `name`, `sort`, `image`, `create_time`, `update_time`, `del`) VALUES (3, '水果家族', 252, 'uploads/images/20211019164411775078373.png', 1634613795, 1634633055, 0);
INSERT INTO `ls_goods_category` (`id`, `name`, `sort`, `image`, `create_time`, `update_time`, `del`) VALUES (4, '美味雪糕', 255, 'uploads/images/20211019164302d42553566.png', 1634613833, 1634632984, 0);


INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (1, '10112', 'PLA可降解吸管(推荐)', 1, 0.00, 50, 1634612594, 1634613620, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (2, '10113', '不使用吸管', 1, 0.00, 50, 1634612641, 1634612641, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (3, '10114', '需要配勺', 1, 0.00, 50, 1634612731, 1634612968, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (4, '10115', '环保不配勺', 1, 0.00, 50, 1634612985, 1634612985, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (5, '10116', '爆多双倍果肉', 4, 9.00, 50, 1634613030, 1634613030, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (6, '10117', '胶原弹力波波脆', 4, 3.00, 50, 1634613052, 1634613052, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (7, '10118', '益生菌', 4, 3.00, 50, 1634613234, 1634613234, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (8, '10119', '正常(推荐)', 5, 0.00, 50, 1634613291, 1634613607, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (9, '10120', '少冰', 5, 0.00, 50, 1634613307, 1634613307, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (10, '10121', '少少冰', 5, 0.00, 50, 1634613328, 1634613328, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (11, '10122', '雪山奶油顶(推荐)', 6, 0.00, 50, 1634613666, 1634613666, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (12, '10123', '换芝士', 6, 0.00, 50, 1634613682, 1634613682, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (13, '010124', '标准甜(推荐)', 7, 0.00, 50, 1634625956, 1634625974, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (14, '10125', '多黑糖', 7, 0.00, 50, 1634626025, 1634626025, 0);
INSERT INTO `ls_material` (`id`, `code`, `name`, `material_category_id`, `price`, `sort`, `create_time`, `update_time`, `del`) VALUES (15, '10126', '不另外加黑糖', 7, 0.00, 50, 1634626059, 1634626059, 0);

INSERT INTO `ls_material_category` (`id`, `name`, `create_time`, `update_time`, `del`) VALUES (1, '绿色奶茶', 1634612535, 1634612769, 0);
INSERT INTO `ls_material_category` (`id`, `name`, `create_time`, `update_time`, `del`) VALUES (2, '配料', 1634612541, 1634612808, 1);
INSERT INTO `ls_material_category` (`id`, `name`, `create_time`, `update_time`, `del`) VALUES (3, '是', 1634612545, 1634612549, 1);
INSERT INTO `ls_material_category` (`id`, `name`, `create_time`, `update_time`, `del`) VALUES (4, '加料', 1634612823, 1634612823, 0);
INSERT INTO `ls_material_category` (`id`, `name`, `create_time`, `update_time`, `del`) VALUES (5, '冰量', 1634612905, 1634612905, 0);
INSERT INTO `ls_material_category` (`id`, `name`, `create_time`, `update_time`, `del`) VALUES (6, '做法', 1634613631, 1634613631, 0);
INSERT INTO `ls_material_category` (`id`, `name`, `create_time`, `update_time`, `del`) VALUES (7, '甜度', 1634625916, 1634625916, 0);

INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (1, '青提暴柠', '202110191212', 1, 1, 'uploads/images/202110191127203ce4b2034.jpg', '优选新鲜阳光玫瑰青提，手剥去皮。新鲜香水柠檬，35次暴打出香，融合定制绿研茶汤，新鲜青提果肉为底，顶部投掷暴风冰球。', 0, 1, 32.00, 32.00, 37.00, 1634615169, 1634615169, 0);
INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (2, '雪山多肉青提', '202110191213', 1, 1, 'uploads/images/20211019114915117190467.jpg', '一杯约42颗青提（含青提果汁部分），优选新鲜阳光玫瑰青提，覆盖雪山奶油顶；中层铺满一层青提冰沙，爽感十足；', 0, 1, 32.00, 32.00, 37.00, 1634615472, 1634615472, 0);
INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (3, '浓暴柠', '202110191214', 2, 1, 'uploads/images/20211019141344671024638.jpg', '原创粤红茶底，经典红茶的浓醇饱满强强联手香水柠檬的香浓酸爽，更香更浓更上头。', 0, 1, 21.00, 21.00, 28.00, 1634624068, 1634624068, 0);
INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (4, '双榨杨桃油柑', '202110191215', 2, 1, 'uploads/images/2021101914180763cd43838.jpg', '油柑鲜果现压现榨，黄金比例拼配双榨', 0, 1, 33.00, 33.00, 39.00, 1634624315, 1634624315, 0);
INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (5, '爆汁大橘', '202110191216', 3, 1, 'uploads/images/20211019142118c09498376.jpg', '经典大橘，爆汁回归。皮薄汁足，延续手剥工艺，口感丰富有层次', 0, 1, 28.00, 28.00, 32.00, 1634624798, 1634624798, 0);
INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (6, '芝芝桃桃', '202110191217', 3, 1, 'uploads/images/202110191427205b9618648.jpg', '当季水蜜桃邂逅全新琥珀兰茶底，桃我喜欢', 0, 1, 30.00, 30.00, 36.00, 1634624951, 1634624951, 0);
INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (7, '冰山葡萄冻', '202110191218', 3, 1, 'uploads/images/20211019142952da0c53235.jpg', '首创多肉葡萄新成员，超 “冻” 感', 0, 1, 29.00, 29.00, 37.00, 1634625147, 1634625147, 0);
INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (8, '芝芝莓莓', '202110191219', 3, 1, 'uploads/images/20211019143423843986710.jpg', '产地鲜采草莓搭配订制奶妍茶底，一口烦恼莓了', 0, 1, 32.00, 32.00, 36.00, 1634625293, 1634625293, 0);
INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (9, '冰山葡萄脆筒', '202110191220', 4, 1, 'uploads/images/202110191435214eaf20114.jpg', '“三零” 葡萄雪芭，还原首创多肉葡萄风味', 0, 1, 9.00, 9.00, 12.00, 1634625401, 1634625401, 0);
INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (10, '冰山葡萄杯', '202110191221', 4, 1, 'uploads/images/20211019143739a0c926794.jpg', '胶原弹力波波脆+葡萄果肉+葡萄冻，口感大满足', 0, 1, 19.00, 19.00, 19.00, 1634625541, 1634625541, 0);
INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (11, '椰椰脆筒', '202110191222', 4, 1, 'uploads/images/20211019144049ea8729797.jpg', '添加优质椰浆打制，浓浓椰香质地细滑', 0, 1, 9.00, 9.00, 9.00, 1634625671, 1634625671, 0);
INSERT INTO `ls_goods` (`id`, `name`, `code`, `goods_category_id`, `status`, `image`, `remark`, `sort`, `spec_type`, `max_price`, `min_price`, `market_price`, `create_time`, `update_time`, `del`) VALUES (12, '波波雪糕杯', '202110191223', 4, 1, 'uploads/images/20211019144329626799779.jpg', 'Q弹黑波波跳入椰椰冰淇淋中，吃完心情 会变好椰', 0, 1, 13.00, 13.00, 13.00, 1634625883, 1634625883, 0);

INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (1, 1, '默认');
INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (2, 2, '默认');
INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (3, 3, '默认');
INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (4, 4, '默认');
INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (5, 5, '默认');
INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (6, 6, '默认');
INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (7, 7, '默认');
INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (8, 8, '默认');
INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (9, 9, '默认');
INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (10, 10, '默认');
INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (11, 11, '默认');
INSERT INTO `ls_goods_spec` (`id`, `goods_id`, `name`) VALUES (12, 12, '默认');

INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (1, 1, 1, '默认');
INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (2, 2, 2, '默认');
INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (3, 3, 3, '默认');
INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (4, 4, 4, '默认');
INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (5, 5, 5, '默认');
INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (6, 6, 6, '默认');
INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (7, 7, 7, '默认');
INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (8, 8, 8, '默认');
INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (9, 9, 9, '默认');
INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (10, 10, 10, '默认');
INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (11, 11, 11, '默认');
INSERT INTO `ls_goods_spec_value` (`id`, `goods_id`, `spec_id`, `value`) VALUES (12, 12, 12, '默认');

INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (1, 1, '1', '默认', 37.00, 32.00);
INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (2, 2, '2', '默认', 37.00, 32.00);
INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (3, 3, '3', '默认', 28.00, 21.00);
INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (4, 4, '4', '默认', 39.00, 33.00);
INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (5, 5, '5', '默认', 32.00, 28.00);
INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (6, 6, '6', '默认', 36.00, 30.00);
INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (7, 7, '7', '默认', 37.00, 29.00);
INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (8, 8, '8', '默认', 36.00, 32.00);
INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (9, 9, '9', '默认', 12.00, 9.00);
INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (10, 10, '10', '默认', 19.00, 19.00);
INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (11, 11, '11', '默认', 9.00, 9.00);
INSERT INTO `ls_goods_item` (`id`, `goods_id`, `spec_value_ids`, `spec_value_str`, `market_price`, `price`) VALUES (12, 12, '12', '默认', 13.00, 13.00);



INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (1, 1, 1, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (2, 1, 5, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (3, 1, 4, 1);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (4, 2, 1, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (5, 2, 5, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (6, 2, 4, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (7, 3, 1, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (8, 3, 5, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (9, 4, 1, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (10, 4, 5, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (11, 5, 1, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (12, 5, 5, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (13, 5, 4, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (14, 6, 1, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (15, 6, 5, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (16, 6, 4, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (17, 7, 1, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (18, 7, 5, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (19, 7, 4, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (20, 8, 1, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (21, 8, 4, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (22, 8, 5, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (23, 10, 1, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (24, 12, 1, 0);
INSERT INTO `ls_goods_material_category` (`id`, `goods_id`, `category_id`, `all_choice`) VALUES (25, 12, 6, 0);

INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (1, 1, 1, 1);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (2, 1, 1, 2);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (3, 2, 1, 8);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (4, 2, 1, 9);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (5, 2, 1, 10);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (6, 3, 1, 5);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (7, 3, 1, 6);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (8, 3, 1, 7);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (9, 4, 2, 1);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (10, 4, 2, 2);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (11, 5, 2, 8);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (12, 5, 2, 9);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (13, 5, 2, 10);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (14, 6, 2, 5);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (15, 6, 2, 7);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (16, 7, 3, 1);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (17, 7, 3, 2);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (18, 8, 3, 8);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (19, 8, 3, 9);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (20, 8, 3, 10);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (21, 9, 4, 1);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (22, 9, 4, 2);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (23, 10, 4, 8);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (24, 10, 4, 9);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (25, 10, 4, 10);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (26, 11, 5, 1);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (27, 11, 5, 2);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (28, 12, 5, 8);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (29, 12, 5, 9);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (30, 12, 5, 10);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (31, 13, 5, 5);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (32, 13, 5, 7);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (33, 14, 6, 1);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (34, 14, 6, 2);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (35, 15, 6, 8);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (36, 15, 6, 9);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (37, 15, 6, 10);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (38, 16, 6, 5);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (39, 16, 6, 6);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (40, 16, 6, 7);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (41, 17, 7, 1);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (42, 17, 7, 2);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (43, 18, 7, 8);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (44, 18, 7, 9);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (45, 18, 7, 10);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (46, 19, 7, 5);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (47, 19, 7, 6);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (48, 19, 7, 7);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (49, 20, 8, 1);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (50, 20, 8, 2);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (51, 21, 8, 5);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (52, 21, 8, 6);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (53, 21, 8, 7);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (54, 22, 8, 8);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (55, 22, 8, 9);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (56, 22, 8, 10);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (57, 23, 10, 3);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (58, 23, 10, 4);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (59, 24, 12, 3);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (60, 24, 12, 4);
INSERT INTO `ls_goods_material` (`id`, `material_category_id`, `goods_id`, `material_id`) VALUES (61, 25, 12, 11);


INSERT INTO `ls_shop` (`id`, `shop_sn`, `name`, `contact`, `phone`, `province_id`, `city_id`, `district_id`, `address`, `longitude`, `latitude`, `business_start_time`, `business_end_time`, `weekdays`, `delivery_distance`, `delivery_buy_limit`, `delivery_freight`, `status`, `pricing_policy`, `delivery_type`, `create_time`, `update_time`, `del`) VALUES (1, '168', '西西茶饮', '小西西', '13000013000', 440000, 440100, 440113, '广雅路120号', '113.384099', '22.937488', '10:00', '23:00', '1,2,3,4,5,6,7', 50.00, 25.00, 20.00, 1, 1, '1,2', 1634630661, 1634633820, 0);
INSERT INTO `ls_shop` (`id`, `shop_sn`, `name`, `contact`, `phone`, `province_id`, `city_id`, `district_id`, `address`, `longitude`, `latitude`, `business_start_time`, `business_end_time`, `weekdays`, `delivery_distance`, `delivery_buy_limit`, `delivery_freight`, `status`, `pricing_policy`, `delivery_type`, `create_time`, `update_time`, `del`) VALUES (2, '666', '蜜雪冰冰', '小冰', '13000013000', 440000, 440100, 440106, '阅江西路220号', '113.323621', '23.106339', '09:00', '23:00', '1,2,3,4,5,6,7', 50.00, 20.00, 10.00, 1, 2, '1,2', 1634633338, 1634633338, 0);

INSERT INTO `ls_shop_admin` (`id`, `root`, `shop_id`, `name`, `account`, `password`, `salt`, `role_id`, `create_time`, `update_time`, `login_time`, `login_ip`, `disable`, `del`) VALUES (1, 1, 1, '西西茶饮', '666666', 'bfa97ce1575784cdd1b860899862a109', '6ea8', 0, 1634630661, 1634630661, 1634630693, '113.67.9.221', 0, 0);
INSERT INTO `ls_shop_admin` (`id`, `root`, `shop_id`, `name`, `account`, `password`, `salt`, `role_id`, `create_time`, `update_time`, `login_time`, `login_ip`, `disable`, `del`) VALUES (2, 1, 2, '蜜雪冰冰', '777777', '3bf207feb1a99b88e377d8528fdfa836', '77dc', 0, 1634633338, 1634633338, 1634633369, '113.67.9.221', 0, 0);


INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (1, 12, 1, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (2, 11, 1, 0, 1, 657);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (3, 10, 1, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (4, 9, 1, 0, 1, 659);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (5, 8, 1, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (6, 7, 1, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (7, 6, 1, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (8, 5, 1, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (9, 4, 1, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (10, 3, 1, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (11, 2, 1, 0, 1, 663);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (12, 1, 1, 0, 1, 662);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (13, 12, 2, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (14, 11, 2, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (15, 10, 2, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (16, 9, 2, 0, 1, 665);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (17, 8, 2, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (18, 7, 2, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (19, 6, 2, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (20, 5, 2, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (21, 4, 2, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (22, 3, 2, 0, 1, 665);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (23, 2, 2, 0, 1, 666);
INSERT INTO `ls_shop_goods` (`id`, `goods_id`, `shop_id`, `sales_sum`, `status`, `total_stock`) VALUES (24, 1, 2, 0, 1, 666);


INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (1, 1, 3, 10, 3, 21.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (2, 1, 4, 9, 4, 33.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (3, 1, 5, 8, 5, 28.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (4, 1, 6, 7, 6, 30.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (5, 1, 7, 6, 7, 29.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (6, 1, 8, 5, 8, 32.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (7, 1, 9, 4, 9, 9.00, 659);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (8, 1, 10, 3, 10, 19.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (9, 1, 11, 2, 11, 9.00, 657);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (10, 1, 12, 1, 12, 13.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (11, 1, 1, 12, 1, 32.00, 662);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (12, 1, 2, 11, 2, 32.00, 663);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (13, 2, 3, 22, 3, 21.00, 665);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (14, 2, 4, 21, 4, 33.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (15, 2, 5, 20, 5, 28.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (16, 2, 6, 19, 6, 30.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (17, 2, 7, 18, 7, 29.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (18, 2, 8, 17, 8, 32.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (19, 2, 9, 16, 9, 9.00, 665);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (20, 2, 10, 15, 10, 19.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (21, 2, 11, 14, 11, 9.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (22, 2, 12, 13, 12, 13.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (23, 2, 1, 24, 1, 32.00, 666);
INSERT INTO `ls_shop_goods_item` (`id`, `shop_id`, `goods_id`, `shop_goods_id`, `item_id`, `price`, `stock`) VALUES (24, 2, 2, 23, 2, 32.00, 666);

INSERT INTO `ls_coupon` VALUES (1, '5元券', 2, 5.00, 1, 0.00, 1, 0, 2, 0, 0, 7, 1, 2, 1, 1, 2, 1642987942, 1642988044, 0);
INSERT INTO `ls_coupon` VALUES (2, '满20减6', 2, 6.00, 2, 20.00, 1, 0, 2, 0, 0, 7, 1, 2, 2, 1, 2, 1642988025, 1642988036, 0);
INSERT INTO `ls_coupon` VALUES (3, '新人券', 1, 6.00, 1, 0.00, 1, 0, 2, 0, 0, 7, 1, 2, 1, 1, 2, 1642990265, 1642990319, 0);
INSERT INTO `ls_coupon` VALUES (4, '雪糕脆筒券', 1, 8.00, 2, 30.00, 1, 0, 2, 0, 0, 15, 1, 2, 2, 2, 2, 1642990950, 1642990958, 0);

INSERT INTO `ls_coupon_goods` VALUES (1, 4, 12, 1642990950);
INSERT INTO `ls_coupon_goods` VALUES (2, 4, 11, 1642990950);
INSERT INTO `ls_coupon_goods` VALUES (3, 4, 9, 1642990950);

SET FOREIGN_KEY_CHECKS = 1;
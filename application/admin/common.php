<?php
function MenuAll() {
    return [
        [
            'label' => '后台管理',
            'url' => 'admin_grades_index',
            'id' => 'admin_grades_index',
            'children' => [
                [
                    'title' => '会员等级配置',
                    'css' => 'glyphicon glyphicon-cog',
                    'children' => [
                        [
                            'label' => '会员参数配置',
                            'id' => 'admin_grades_index',
                            'url' => 'admin_grades_index',
                        ],
                        [
                            'label' => '缓冲配置',
                            'id' => 'admin_buffers_index',
                            'url' => 'admin_buffers_index',
                        ],
                    ],
                ],
                [
                    'title' => '商品管理',
                    'css' => 'glyphicon glyphicon-home',
                    'children' => [
                        [
                            'label' => '商品列表',
                            'id' => 'admin_waress_index',
                            'url' => 'admin_waress_index',
                        ],
                        [
                            'label' => '商品分类管理',
                            'id' => 'admin_types_index',
                            'url' => 'admin_types_index',
                        ],
                        [
                            'label' => '商品品牌管理',
                            'id' => 'admin_brands_index',
                            'url' => 'admin_brands_index',
                        ],
                        [
                            'label' => '标签管理',
                            'id' => 'admin_labels_index',
                            'url' => 'admin_labels_index',
                        ],
                    ],
                ],
                [
                    'title' => '会员管理',
                    'css' => 'glyphicon glyphicon-home',
                    'children' => [
                        [
                            'label' => '会员列表',
                            'id' => 'admin_users_index',
                            'url' => 'admin_users_index',
                        ],
                        [
                            'label' => '会员族谱',
                            'id' => 'admin_users_genealogy',
                            'url' => 'admin_users_genealogy',
                        ],
                        [
                            'label' => '等级提拔管理',
                            'id' => 'admin_users_level',
                            'url' => 'admin_users_level',
                        ],
                    ],
                ],
                [
                    'title' => '财务管理',
                    'css' => 'glyphicon glyphicon-home',
                    'children' => [
                        [
                            'label' => '事业统计',
                            'id' => 'admin_causes_index',
                            'url' => 'admin_causes_index',
                        ],
                        [
                            'label' => '结算管理',
                            'id' => 'admin_settlements_index',
                            'url' => 'admin_settlements_index',
                        ],
                    ],
                ],
                [
                    'title' => '素材管理',
                    'css' => 'glyphicon glyphicon-home',
                    'children' => [
                        [
                            'label' => '评论管理',
                            'id' => 'admin_comments_index',
                            'url' => 'admin_comments_index',
                        ],
                        [
                            'label' => '素材',
                            'id' => 'admin_materials_index',
                            'url' => 'admin_materials_index',
                        ],
                    ],
                ],
                [
                    'title' => '页面管理',
                    'css' => 'glyphicon glyphicon-home',
                    'children' => [
                        [
                            'label' => '首页配置',
                            'id' => 'admin_homes_index',
                            'url' => 'admin_homes_index',
                        ],
                        [
                            'label' => '我的配置',
                            'id' => 'admin_homes_my',
                            'url' => 'admin_homes_my',
                        ],
                    ],
                ],
                [
                    'title' => '订单管理',
                    'css' => 'glyphicon glyphicon-home',
                    'children' => [
                        [
                            'label' => '订单列表',
                            'id' => 'admin_orders_index',
                            'url' => 'admin_orders_index',
                        ],
                    ],
                ],
            ]
        ],
    ];
}

function manageTop() {
    foreach (MenuAll() as $data) {
        unset($data['children']);
        $arr[] = $data;
    }
    return $arr;
}

function manageLeft($id) {
    echo 123;
    exit();
    foreach (MenuAll() as $data) {
        if ($data['id'] == $id) {
            $value = $data['children'];
        }
    }
    return $value;
}
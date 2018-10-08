<?php

namespace app\admin\model;

use think\Model;
use think\Db;

class Menu extends Model {

    static $config = [
        [
            'title' => '数据分析',
            'css' => 'glyphicon glyphicon-home',
            'id' => 'datas',
            'active' => '',
            'children' => [
                [
                    'label' => '数据大看板',
                    'id' => 'admin_datas_index',
                    'url' => 'admin_datas_index',
                    'css' => '',
                    'display' => '',
                ],
                [
                    'label' => '项目分析',
                    'id' => 'admin_datas_project',
                    'url' => 'admin_datas_project',
                    'css' => '',
                    'display' => '',
                ],
                [
                    'label' => '游戏分析',
                    'id' => 'admin_datas_game',
                    'url' => 'admin_datas_game',
                    'css' => '',
                    'display' => '',
                ],
                [
                    'label' => '品牌商分析',
                    'id' => 'admin_datas_wares',
                    'url' => 'admin_datas_wares',
                    'css' => '',
                    'display' => '',
                ],
                [
                    'label' => '合作商分析',
                    'id' => 'admin_datas_partner',
                    'url' => 'admin_datas_partner',
                    'css' => '',
                    'display' => '',
                ]
            ],
        ],
        [
            'title' => '会员管理',
            'css' => 'glyphicon glyphicon-home',
            'id' => 'users',
            'active' => '',
            'children' => [
                [
                    'label' => '会员列表',
                    'id' => 'admin_users_index',
                    'url' => 'admin_users_index',
                    'css' => '',
                    'display' => '',
                ],
                [
                    'label' => '会员游戏信息',
                    'id' => 'admin_users_game',
                    'url' => 'admin_users_game',
                    'css' => '',
                    'display' => '',
                ],
                [
                    'label' => '中奖信息',
                    'id' => 'admin_users_lucky',
                    'url' => 'admin_users_lucky',
                    'css' => '',
                    'display' => '',
                ],
                [
                    'label' => '黑名单',
                    'id' => 'admin_users_blacklist',
                    'url' => 'admin_users_blacklist',
                    'css' => '',
                    'display' => '',
                ],
            ],
        ],
        [
            'title' => '游戏类型管理',
            'css' => 'glyphicon glyphicon-home',
            'id' => 'gamear',
            'active' => '',
            'children' => [
                [
                    'label' => 'ar扫描',
                    'id' => 'admin_gamear_index',
                    'url' => 'admin_gamear_index',
                    'css' => '',
                    'display' => '',
                ]
            ],
        ],
        [
            'title' => '游戏管理',
            'css' => 'glyphicon glyphicon-home',
            'id' => 'games',
            'active' => '',
            'children' => [
                [
                    'label' => '游戏列表',
                    'id' => 'admin_games_index',
                    'url' => 'admin_games_index',
                    'css' => '',
                    'display' => '',
                ]
            ],
        ],
        [
            'title' => '合作商管理',
            'css' => 'glyphicon glyphicon-home',
            'id' => 'partners',
            'active' => '',
            'children' => [
                [
                    'label' => '合作商列表',
                    'id' => 'admin_partners_index',
                    'url' => 'admin_partners_index',
                    'css' => '',
                    'display' => '',
                ]
            ],
        ],
        [
            'title' => '项目管理',
            'css' => 'glyphicon glyphicon-home',
            'id' => 'projects',
            'active' => '',
            'children' => [
                [
                    'label' => '项目列表',
                    'id' => 'admin_projects_index',
                    'url' => 'admin_projects_index',
                    'css' => '',
                    'display' => '',
                ]
            ],
        ],
        [
            'title' => '品牌商管理',
            'css' => 'glyphicon glyphicon-home',
            'id' => 'brands',
            'active' => '',
            'children' => [
                [
                    'label' => '品牌列表',
                    'id' => 'admin_brands_index',
                    'url' => 'admin_brands_index',
                    'css' => '',
                    'display' => '',
                ],
            ],
        ],
        [
            'title' => '系统管理',
            'id' => 'system',
            'active' => '',
            'css' => 'glyphicon glyphicon-cog',
            'children' => [
                [
                    'label' => '管理员列表',
                    'id' => 'admin_admins_index',
                    'url' => 'admin_admins_index',
                    'css' => '',
                    'display' => '',
                ],
                [
                    'label' => '系统参数管理',
                    'id' => 'admin_system_parameter',
                    'url' => 'admin_system_parameter',
                    'css' => '',
                    'display' => '',
                ],
            ],
        ]
    ];

    public static function manageLeft($id, $left_menu_active) {
        $arr = [];
        if (count(self::$config) > 0) {
            foreach (self::$config as $key => $value) {
                if ($value['id'] == $id) {
                    $value['active'] = 'active';
                    foreach ($value['children'] as $k => $data) {
                        if (is_array($data) && $data['id'] == $left_menu_active) {
                            $value['children'][$k]['css'] = 'current-page';
                            $value['display'] = 'block';
                            //$data['visible'] = Yii::$app->user->can($data['id']);
                        } else {
                            //$data['visible'] = Yii::$app->user->can($data['id']);
                            $value['children'][$k]['css'] = '';
                            //$value['display'] = 'style="display: none;"';
                        }
                    }
                }
                $arr[$key] = $value;
            }
        }
        //dump($arr);
        return $arr;
    }

}

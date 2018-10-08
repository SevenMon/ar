<?php

namespace app\admin\controller;

use think\facade\Request;
use app\model\UserStatistics;
use app\model\UserStatisticsCard;
use app\model\UserStatisticsHistory;
use app\model\Card;
use app\model\User;
use think\Db;

class Accessdatatracking extends Base {

    public function initialize() {
        parent::initialize();
    }

    /*
     * 访问数据追踪
     */

    public function index() {
        //$this->aid = 23;
        if (empty(input('get.endday')) || empty(input('get.startday'))) {
            $endday = strtotime(date("Y-m-d"));
            $startday = $endday - 7 * 86400;
        } else {
            $endday = strtotime(input('get.endday'));
            $startday = strtotime(input('get.startday'));
        }

        $sql = "SELECT type,
                       Sum(num) AS allnum
                FROM   cn_user_statistics_history
                WHERE  day BETWEEN $startday AND $endday
                   AND aid = " . $this->aid . "
                GROUP  BY type";

        $list = Db::query($sql);
        $retrunlist = ['1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'9'=>0,'10'=>0,'11'=>0,'12'=>0,'13'=>0];
        foreach ($list as $key => $value) {
            $retrunlist[$value['type']] = $value['allnum'];
        }
        //转发名片次数
        $endday = $endday + 86399;
        $counts = Db::query("SELECT  count(1) AS allnum
                                    FROM   cn_user_statistics
                                    WHERE  add_time BETWEEN $startday AND $endday
                                       AND aid = " . $this->aid . " AND type=12 AND card_id!=0");
        $retrunlist[12] = $counts[0]['allnum'];

        ajaxJsonReturn(1, '获取成功', ['list' => $retrunlist, 'endday' => date("Y-m-d", $endday), 'startday' => date("Y-m-d", $startday)]);
    }

    /*
     * 首页详情页
     */

    public function indexdetail() {
        $Model = new UserStatistics();
        //$this->aid = 23;
        $type = intval(input('get.type'));
        $endday = strtotime(input('get.endday')) + 86399;
        $startday = strtotime(input('get.startday'));
        $pageLimit = getPageLimit($this->aid);
        $page = empty(input('get.page')) ? 1 : input('get.page');
        if ($type == 12) {
            $where = ['card_id' => ['<>', 0], 'cn_user_statistics.aid' => $this->aid, 'cn_user_statistics.type' => $type, 'cn_user_statistics.add_time' => ['between', $startday, $endday]];
        } else {
            $where = ['cn_user_statistics.aid' => $this->aid, 'cn_user_statistics.type' => $type, 'cn_user_statistics.add_time' => ['between', $startday, $endday]];
        }
        $counts = $Model
                ->leftJoin('cn_user', 'cn_user_statistics.user_id=cn_user.id')
                ->where($where)
                ->order('cn_user_statistics.add_time desc')
                ->count();
        $list = $Model
                ->leftJoin('cn_user', 'cn_user_statistics.user_id=cn_user.id')
                ->where($where)
                ->order('cn_user_statistics.add_time desc')
                ->page($page, $pageLimit)
                ->field('cn_user_statistics.*,cn_user.nickname,cn_user.avatar_url')
                ->select();
        ajaxJsonReturn(1, '获取成功', ['list' => $list, 'counts' => $counts, 'typename' => getStatisticsName($type), 'page' => $page]);
    }

    /*
     * 用户分析
     */

    public function analysis() {
        //用户感兴趣的品类    
        $endday = strtotime(date("Y-m-d"));
        $startday = $endday - 30 * 86400;
        $list4 = Db::query("SELECT type_detail,
                               Sum(num) AS allnum,
                               Sum(all_back_num) as allbacknum
                        FROM   cn_user_statistics_history
                        WHERE  aid = " . $this->aid . "
                           AND type = 4 AND day BETWEEN $startday AND $endday
                        GROUP  BY type");
        $backlist4 = [];
        for ($i = 0; $i < count($list4); $i++) {
            if ($i < 5) {
                $backlist4[$i]['allnum'] = $list4[$i]['allnum'];
                $backlist4[$i]['name'] = '商品';
            } else {
                $backlist4[5]['name'] = '其他';
                $backlist4[5]['allnum'] = intval($backlist4[5]['allnum']) + $list4[$i]['allnum'];
            }
        }
        if (empty($backlist4)) {
            $backlist4[0]['name'] = '暂无';
            $backlist4[0]['allnum'] = 0;
        }

        //关键字
        $list11 = Db::query("SELECT `key`,
                               Sum(num) AS allnum
                        FROM   cn_user_statistics_history
                        WHERE  aid = " . $this->aid . "
                           AND type = 11 AND day BETWEEN $startday AND $endday
                        GROUP  BY `key`
                        ORDER  BY allnum DESC");

        $backlist11 = [];
        for ($i = 0; $i < count($list11); $i++) {
            if ($i < 5) {
                $backlist11[$i]['allnum'] = $list11[$i]['allnum'];
                $backlist11[$i]['name'] = $list11[$i]['key'];
            } else {
                $backlist11[5]['name'] = '其他';
                $backlist11[5]['allnum'] = intval($backlist11[5]['allnum']) + $list11[$i]['allnum'];
            }
        }
        if (empty($backlist11)) {
            $backlist11[0]['name'] = '暂无';
            $backlist11[0]['allnum'] = 0;
        }

        //热卖产品
        $list6 = Db::query("SELECT `type_detail`,
                               Sum(num) AS allnum,
                               GROUP_CONCAT(DISTINCT(content)) as content
                        FROM   cn_user_statistics_history
                        WHERE  aid = " . $this->aid . "
                           AND type = 6 AND day BETWEEN $startday AND $endday
                        GROUP  BY `type_detail`
                        ORDER  BY allnum DESC");

        $backlist6 = [];
        for ($i = 0; $i < count($list6); $i++) {
            if ($i < 5) {
                $backlist6[$i]['allnum'] = $list6[$i]['allnum'];
                $tmp = [];
                $tmp = explode(',', $list6[$i]['content']);
                $content = json_decode($tmp[0], true);
                $backlist6[$i]['name'] = WSTMSubstr(htmlspecialchars_decode($content['product_name']), 0, 9);
            } else {
                $backlist6[5]['name'] = '其他';
                $backlist6[5]['allnum'] = intval($backlist6[5]['allnum']) + $list6[$i]['allnum'];
            }
        }
        if (empty($backlist6)) {
            $backlist6[0]['name'] = '暂无';
            $backlist6[0]['allnum'] = 0;
        }

        //进入方式
        $list9001 = Db::query("SELECT `type_detail`,
                                   Sum(num) AS allnum
                            FROM   cn_user_statistics_history
                            WHERE  aid = " . $this->aid . "
                               AND type = 9001 AND  type_detail!=0 AND day BETWEEN $startday AND $endday
                            GROUP  BY `type_detail`
                            ORDER  BY allnum DESC");

        $backlist9001 = [];
        for ($i = 0; $i < count($list9001); $i++) {
            if ($i < 5) {
                $backlist9001[$i]['allnum'] = $list9001[$i]['allnum'];
                $backlist9001[$i]['name'] = getScenesName($list9001[$i]['type_detail']);
                if (empty($backlist9001[$i]['name'])) {
                    $backlist9001[$i]['name'] = '未知来源';
                }
            } else {
                $backlist9001[5]['name'] = '其他';
                $backlist9001[5]['allnum'] = intval($backlist9001[5]['allnum']) + $list9001[$i]['allnum'];
            }
        }
        if (empty($backlist9001)) {
            $backlist9001[0]['name'] = '暂无';
            $backlist9001[0]['allnum'] = 0;
        }

        //跳出率
        $listout = Db::query("SELECT `type`,
                                   Sum(num) AS allnum, 
                                   Sum(all_back_num)  as allbacknum
                            FROM   cn_user_statistics_history
                            WHERE  aid = " . $this->aid . "
                            AND type != 4 AND type!=9001 AND day BETWEEN $startday AND $endday
                            GROUP  BY `type`
                            ORDER  BY allbacknum DESC");

        $backout = $this->arraySort($list4, $listout);
        //质询
        $zixunlist = $this->arrayZixun($list4, $listout);

        ajaxJsonReturn(1, '获取成功', [
            'list4' => $backlist4,
            'list11' => $backlist11,
            'list6' => $backlist6,
            'list9001' => $backlist9001,
            'backout' => $backout,
            'zixunlist' => $zixunlist,
        ]);
    }

    /*
     * 用户分析详情页
     */

    public function analysisdetail() {
        $type = intval(input('get.type'));
        $page = empty(input('get.page')) ? 1 : input('get.page');
        $pageLimit = getPageLimit($this->aid);
        if (empty(input('get.endday')) || empty(input('get.startday'))) {
            $endday = strtotime(date("Y-m-d"));
            $startday = $endday - 30 * 86400;
        } else {
            $endday = strtotime(input('get.endday'));
            $startday = strtotime(input('get.startday'));
        }
        switch ($type) {
            case '1':
                $typename = '用户感兴趣的品类';
                $list =Db::query("SELECT type_detail,
                                                           Sum(num) AS allnum,
                                                           Sum(all_time) as alltime,
                                                           Sum(all_back_num) as allbacknum
                                                    FROM   cn_user_statistics_history
                                                    WHERE  aid = " . $this->aid . "
                                                       AND type = 4 AND day BETWEEN $startday AND $endday
                                                    GROUP  BY type_detail
                                                    ORDER  BY allnum DESC");
                $backlist = [];
                $allnum = 0;
                $allbacknum = 0;
                for ($i = 0; $i < count($list); $i++) {
                    $backlist[$i]['allnum'] = $list[$i]['allnum'];
                    $backlist[$i]['alltime'] = $list[$i]['alltime'];
                    $backlist[$i]['allbacknum'] = $list[$i]['allbacknum'];
                    $backlist[$i]['name'] = getCatgreyName($list[$i]['type_detail']);
                    $allnum = $allnum + intval($list[$i]['allnum']);
                    $allbacknum = $allbacknum + intval($list[$i]['allbacknum']);
                }
                break;
            case '2':
                $typename = '用户搜索最多的关键字';
                $list =Db::query("SELECT `key`,
                                                           Sum(num) AS allnum,
                                                           Sum(all_time) as alltime,
                                                           Sum(all_back_num) as allbacknum
                                                    FROM   cn_user_statistics_history
                                                    WHERE  aid = " . $this->aid . "
                                                       AND type = 11 AND day BETWEEN $startday AND $endday
                                                    GROUP  BY `key`
                                                    ORDER  BY allnum DESC");

                $firstRow = $pageLimit * ($page - 1);
                $backlist = [];
                $k = 0;
                for ($i = 0; $i < count($list); $i++) {
                    if ($i >= $firstRow && $k < $pageLimit) {
                        $k = $k + 1;
                        $backlist[$i]['allnum'] = $list[$i]['allnum'];
                        $backlist[$i]['alltime'] = $list[$i]['alltime'];
                        $backlist[$i]['allbacknum'] = $list[$i]['allbacknum'];
                        $backlist[$i]['name'] = $list[$i]['key'];
                    }
                    $allnum = $allnum + intval($list[$i]['allnum']);
                    $allbacknum = $allbacknum + intval($list[$i]['allbacknum']);
                }
                break;
            case '3':
                $typename = '用户感兴趣的产品';
                $list =Db::query("SELECT `type_detail`,
                                                   Sum(num) AS allnum,
                                                   Sum(all_time) as alltime,
                                                   Sum(all_back_num) as allbacknum,
                                                   GROUP_CONCAT(DISTINCT(content)) as content
                                            FROM   cn_user_statistics_history
                                            WHERE  aid = " . $this->aid . "
                                               AND type = 6 AND day BETWEEN $startday AND $endday
                                            GROUP  BY `type_detail`
                                            ORDER  BY allnum DESC");
                $firstRow = $pageLimit * ($page - 1);
                $backlist = [];
                $k = 0;
                for ($i = 0; $i < count($list); $i++) {
                    if ($i >= $firstRow && $k < $pageLimit) {
                        $k = $k + 1;
                        $backlist[$i]['allnum'] = $list[$i]['allnum'];
                        $backlist[$i]['alltime'] = $list[$i]['alltime'];
                        $backlist[$i]['allbacknum'] = $list[$i]['allbacknum'];
                        $tmp = [];
                        $tmp = explode(',', $list[$i]['content']);
                        $content = json_decode($tmp[0], true);
                        $backlist[$i]['name'] = htmlspecialchars_decode($content['product_name']);
                    }
                    $allnum = $allnum + intval($list[$i]['allnum']);
                    $allbacknum = $allbacknum + intval($list[$i]['allbacknum']);
                }
                break;
            case '4':
                $typename = '小程序用户来源';
                $list =Db::query("SELECT `type_detail`,
                                                           Sum(num) AS allnum,
                                                           Sum(all_time) as alltime,
                                                           Sum(all_back_num) as allbacknum                       
                                                    FROM   cn_user_statistics_history
                                                    WHERE  aid = " . $this->aid . "
                                                       AND type = 9001 AND day BETWEEN $startday AND $endday
                                                    GROUP  BY `type_detail`
                                                    ORDER  BY allnum DESC");

                $backlist = [];
                $allnum = 0;
                $allbacknum = 0;
                for ($i = 0; $i < count($list); $i++) {
                    $backlist[$i]['allnum'] = $list[$i]['allnum'];
                    $backlist[$i]['alltime'] = $list[$i]['alltime'];
                    $backlist[$i]['allbacknum'] = $list[$i]['allbacknum'];
                    $backlist[$i]['name'] = getScenesName($list[$i]['type_detail']);
                    $allnum = $allnum + intval($list[$i]['allnum']);
                    $allbacknum = $allbacknum + intval($list[$i]['allbacknum']);
                }
                break;
            case '5':
                $typename = '页面跳失率';
                $list =Db::query("SELECT type_detail,
                                                           Sum(num) AS allnum,
                                                           Sum(all_time) as alltime,
                                                           Sum(all_back_num) as allbacknum
                                                    FROM   cn_user_statistics_history
                                                    WHERE  aid = " . $this->aid . "
                                                       AND type = 4 AND day BETWEEN $startday AND $endday
                                                    GROUP  BY type_detail
                                                    ORDER  BY allnum DESC");
                $backlist = [];
                $allnum = 0;
                $allbacknum = 0;
                $tmpi = 0;
                for ($i = 0; $i < count($list); $i++) {
                    $backlist[$i]['allnum'] = $list[$i]['allnum'];
                    $backlist[$i]['alltime'] = $list[$i]['alltime'];
                    $backlist[$i]['allbacknum'] = $list[$i]['allbacknum'];
                    $backlist[$i]['name'] = getCatgreyName($list[$i]['type_detail']);
                    $allnum = $allnum + intval($list[$i]['allnum']);
                    $allbacknum = $allbacknum + intval($list[$i]['allbacknum']);
                    $volume[$tmpi] = $list[$i]['allbacknum'];
                    $tmpi = $tmpi + 1;
                }
                $list1 =Db::query("SELECT `type`,
                                                               Sum(num) AS allnum, 
                                                               Sum(all_time) as alltime,
                                                               Sum(all_back_num)  as allbacknum
                                                        FROM   cn_user_statistics_history
                                                        WHERE  aid = " . $this->aid . "
                                                        AND type != 4 AND day BETWEEN $startday AND $endday
                                                        GROUP  BY `type`
                                                        ORDER  BY allbacknum DESC");
                for ($i = 0; $i < count($list1); $i++) {
                    $tmparray = [];
                    $tmparray['allnum'] = $list1[$i]['allnum'];
                    $tmparray['alltime'] = $list1[$i]['alltime'];
                    $tmparray['allbacknum'] = $list1[$i]['allbacknum'];
                    $tmparray['name'] = getStatisticsName($list1[$i]['type']);
                    array_push($backlist, $tmparray);
                    $allnum = $allnum + intval($list1[$i]['allnum']);
                    $allbacknum = $allbacknum + intval($list1[$i]['allbacknum']);
                    $volume[$tmpi] = $list1[$i]['allbacknum'];
                    $tmpi = $tmpi + 1;
                }
                array_multisort($volume, SORT_DESC, $backlist);
                break;
            case '6':
                $typename = '咨询占比';
                $list =Db::query("SELECT `type`,
                                                               Sum(num) AS allnum
                                                        FROM   cn_user_statistics_history
                                                        WHERE  aid = " . $this->aid . "
                                                        AND day BETWEEN $startday AND $endday
                                                        GROUP  BY `type`
                                                        ORDER  BY allnum DESC");
                $backlist = [];
                $allnum = 0;
                $tmpnum = 0;
                foreach ($list as $key => $value) {
                    $tmp = [];
                    if ($value['type'] == 2) {
                        $tmp['name'] = getStatisticsName($value['type']);
                        $tmp['allnum'] = $value['allnum'];
                        array_push($backlist, $tmp);
                    } elseif ($value['type'] == 9) {
                        $tmp['name'] = getStatisticsName($value['type']);
                        $tmp['allnum'] = $value['allnum'];
                        array_push($backlist, $tmp);
                    } elseif ($value['type'] == 10) {
                        $tmp['name'] = getStatisticsName($value['type']);
                        $tmp['allnum'] = $value['allnum'];
                        array_push($backlist, $tmp);
                    } else {
                        $tmpnum = $tmpnum + $value['allnum'];
                    }
                    $allnum = $allnum + intval($value['allnum']);
                }
                $tmp['name'] = '未咨询';
                $tmp['allnum'] = $tmpnum;
                array_push($backlist, $tmp);

                break;
            default:
                ajaxJsonReturn(-3, '数据不存在！');
                # code...
                break;
        }
        ajaxJsonReturn(1, '获取成功', [
            'end_time' => date("Y-m-d", $endday),
            'start_time' => date("Y-m-d", $startday),
            'allnum' => $allnum,
            'allbacknum' => $allbacknum,
            'list' => $backlist,
            'typename' => $typename,
            'page' => $page
        ]);
    }

    /*
     * 合并数组，排序
     */

    private function arraySort($list4, $listout) {
        $tmparray = [];
        $tmpi = 0;
        foreach ($list4 as $key => $value) {
            $tmp = [];
            $tmp['name'] = getCatgreyName($value['type_detail']);
            $tmp['allnum'] = $value['allbacknum'];
            $volume[$tmpi] = $value['allbacknum'];
            $tmpi = $tmpi + 1;
            array_push($tmparray, $tmp);
        }
        foreach ($listout as $key => $value) {
            $tmp = [];
            $tmp['name'] = getStatisticsName($value['type']);
            $tmp['allnum'] = $value['allbacknum'];
            $volume[$tmpi] = $value['allbacknum'];
            $tmpi = $tmpi + 1;
            array_push($tmparray, $tmp);
        }
        if (empty($volume)) {
            $backarray[0]['name'] = '暂无';
            $backarray[0]['allnum'] = 0;
            return $backarray;
        }
        array_multisort($volume, SORT_DESC, $tmparray);
        $backarray = [];
        for ($i = 0; $i < count($tmparray); $i++) {
            if ($tmparray[$i]['allnum'] == 0) {
                break;
            }
            if ($i < 5) {
                $backarray[$i]['allnum'] = $tmparray[$i]['allnum'];
                $backarray[$i]['name'] = $tmparray[$i]['name'];
            } else {
                $backarray[5]['name'] = '其他';
                $backarray[5]['allnum'] = intval($backarray[5]['allnum']) + $tmparray[$i]['allnum'];
            }
        }
        if (empty($backarray)) {
            $backarray[0]['name'] = '暂无';
            $backarray[0]['allnum'] = 0;
        }
        if ($backarray[5]['allnum'] == 0) {
            unset($backarray[5]);
        }
        return $backarray;
    }

    /*
     * 咨询统计
     */

    private function arrayZixun($list4, $listout) {
        $tmparray = [];
        $tmpnum = 0;
        foreach ($list4 as $key => $value) {
            $tmpnum = $tmpnum + $value['allnum'];
        }
        foreach ($listout as $key => $value) {
            $tmp = [];
            if ($value['type'] == 2) {
                $tmp['name'] = getStatisticsName($value['type']);
                $tmp['allnum'] = $value['allnum'];
                array_push($tmparray, $tmp);
            } elseif ($value['type'] == 9) {
                $tmp['name'] = getStatisticsName($value['type']);
                $tmp['allnum'] = $value['allnum'];
                array_push($tmparray, $tmp);
            } elseif ($value['type'] == 10) {
                $tmp['name'] = getStatisticsName($value['type']);
                $tmp['allnum'] = $value['allnum'];
                array_push($tmparray, $tmp);
            } else {
                $tmpnum = $tmpnum + $value['allnum'];
            }
        }
        if ($tmpnum > 0) {
            $tmp['name'] = '未咨询';
            $tmp['allnum'] = $tmpnum;
            array_push($tmparray, $tmp);
        }
        if (empty($tmparray)) {
            $tmparray[0]['name'] = '暂无';
            $tmparray[0]['allnum'] = 0;
        }
        return $tmparray;
    }

    /*
     * 用户数据追踪
     */

    public function cards() {
        $CardModel = new Card();
        $pageLimit = getPageLimit($this->aid);
        $cardid = intval(input('get.cardid'));
        $page = empty(input('get.page')) ? 1 : input('get.page');
        //获得名片列表
        $cardlist = $CardModel->field('id,name')->where(['aid' => $this->aid, 'status' => 1])->select();

        if (empty(input('get.endday')) || empty(input('get.startday'))) {
            $endday = strtotime(date("Y-m-d")) + 86399;
            $startday = $endday - 7 * 86400;
        } else {
            $endday = strtotime(input('get.endday'))+ 86399;
            $startday = strtotime(input('get.startday'));
        }

        if ($cardid == 0) {
            $where = "";
            $where1 = "";
        } else {
            $where = "AND card_sourse_id = $cardid";
            $where1 = "AND card_id = $cardid";
        }
        //获得总访客人数
        $counts = Db::query("SELECT Count(DISTINCT( user_id )) AS allcount
                                        FROM   cn_user_statistics
                                        WHERE  user_id != 0 AND aid=" . $this->aid . "
                                           AND add_time BETWEEN $startday AND $endday $where");

//        dump("SELECT Count(DISTINCT( user_id )) AS allcount
//                                        FROM   cn_user_statistics
//                                        WHERE  user_id != 0 AND aid=" . $this->aid . "
//                                           AND add_time BETWEEN $startday AND $endday $where");exit;
        //总访问次数
        $countvs = Db::query("SELECT Count(1) AS allcount,Sum(time_num) as alltime_num
                                        FROM   cn_user_statistics
                                        WHERE   aid=" . $this->aid . "
                                           AND add_time BETWEEN $startday AND $endday $where");

        if (!empty($countvs[0]['allcount'])) {
            $timenum = secToTime(intval($countvs[0]['alltime_num'] / $countvs[0]['allcount']));
        } else {
            $timenum = '00:00:00';
        }

        //访问比例
        $acounts = Db::query("SELECT Sum(num) AS allnum
                                    FROM   cn_user_statistics_history
                                    WHERE  aid = " . $this->aid . "
                                       AND type != 9001 AND day BETWEEN $startday AND $endday");
        if (empty($acounts[0]['allnum'])) {
            $allbilv = '-';
        } else {
            $allbilv = round($counts[0]['allcount'] / $acounts[0]['allnum'] * 100, 2) . '%';
        }

        //分享转发次数
        $fencounts = Db::query("SELECT Count(1) AS allcount
                                        FROM   cn_user_statistics
                                        WHERE    aid=" . $this->aid . " AND type=12
                                           AND add_time BETWEEN $startday AND $endday $where1");
        $firstRow = $pageLimit * ($page - 1);
        $list = Db::query("SELECT t1.*,
                           t2.add_time,
                           t2.sourses,
                           t3.nickname,
                           t3.avatar_url
                    FROM   (SELECT Count(1)      AS allcount,
                                   user_id,
                                   Sum(time_num) AS alltimenum
                            FROM   cn_user_statistics
                            WHERE   user_id != 0 $where
                               AND aid = " . $this->aid . "
                               AND add_time BETWEEN $startday AND $endday
                            GROUP  BY user_id) t1
                           LEFT JOIN (SELECT user_id,
                                             Min(add_time) AS add_time,
                                             GROUP_CONCAT(DISTINCT(sourse) ORDER BY id ASC) as sourses
                                      FROM   cn_user_statistics
                                      WHERE  aid = " . $this->aid . "
                                         $where
                                      GROUP  BY user_id) t2
                                  ON t1.user_id = t2.user_id
                           LEFT JOIN cn_user t3
                                  ON t1.user_id = t3.id
                            ORDER BY t2.add_time ASC LIMIT " . $firstRow . "," . $pageLimit);

        ajaxJsonReturn(1, '获取成功', [
            'cardlist' => $cardlist,
            'endday' => date("Y-m-d", $endday),
            'startday' => date("Y-m-d", $startday),
            'allvnum' => $counts[0]['allcount'],
            'allnum' => $countvs[0]['allcount'],
            'timenum' => $timenum,
            'allbilv' => $allbilv,
            'list' => $list,
            'fencount' => $fencounts[0]['allcount'],
            'page' => $page
        ]);
    }

    /*
     * 用户数据追踪详情
     */

    public function userlist() {
        $CardModel = new Card();
        $UserModel = new User();
        $Model = new UserStatistics();
        $cardid = intval(input('get.cardid'));
        $userid = intval(input('get.userid'));
        $pageLimit = getPageLimit($this->aid);
        $page = empty(input('get.page')) ? 1 : input('get.page');
        $uinfo = $UserModel->where(['id' => $userid])->field('nickname,avatar_url')->find();
        $cinfo = $CardModel->where(['id' => $cardid, 'aid' => $this->aid])->field('name')->find();
        if (empty($uinfo)) {
            ajaxJsonReturn(-3, '数据不存在！');
        }

        if ($cardid == 0) {
            $where = ['aid' => $this->aid, 'user_id' => $userid];
        } else {
            $where = ['card_sourse_id' => $cardid, 'aid' => $this->aid, 'user_id' => $userid];
        }

        $count = $Model->where($where)->count();
        $list = $Model->where($where)->order('add_time ASC')->page($page, $pageLimit)->select();

        ajaxJsonReturn(1, '获取成功', ['uinfo' => $uinfo, 'cinfo' => $cinfo, 'list' => $list, 'count' => $count, 'page' => $page]);
    }

}

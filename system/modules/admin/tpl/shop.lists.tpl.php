<?php defined('G_IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<!-- 高级骨架屏系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
<!-- 统一后台页面美化样式（参考webcfg标准） -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
<!-- 后台通用页面增强样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
<!-- 通知消息系统样式 -->
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $this->getPageTitle('商品列表'); ?></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/layui/css/layui.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_PLUGIN_PATH; ?>/messageModal/message.js"></script>
    <script src="<?php echo G_PLUGIN_PATH; ?>/layer/layer.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layer/layer.js"></script>
    <script type="text/javascript" src="<?php echo G_TEMPLATES_JS; ?>/layui/layui.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/layui/layui.js"></script>
    <style>
        body {
            background-color: #fff
        }

        tr {
            text-align: center
        }

        .synchronousnew {
            background-color: #ffffff;
            color: #444;
            border: 1px solid #ccc;
            padding: 8px;
            border-radius: 5px;
            margin: 0 5px 0 0;
            transition: background-color 0.2s ease-out 0s;
        }

        .synchronousnew:hover {
            background-color: #3C8DBC;
            color: #fff;
        }

        .w50 {
            width: 50px !important;
        }

        .layui-form-switch {
            margin-top: 0;
        }
        .posttime {
            height: 46px;
            border: 2px solid #e5e7eb !important;
        }
    </style>
</head>

<body>
    <div class="header lr10">
        <?php echo $this->headerment(); ?>
        <div class="exportBtn" style="margin-top:4px;background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);padding: 0 20px;line-height: 32px;font-size: 14px;"
              onclick="window.location.href = '<?php echo G_ADMIN_PATH; ?>/import/index/3'">
              <?php $this->lan("collection","采集"); ?>
          </div>
    </div>
    <div class="bk10"></div>
    <div class="header-data lr10">
        <b><?php $this->lan("Tips"); ?></b>
        <?php $this->lan("Tips1"); ?>
        <!--<div data-title="<?php $this->lan("Tips1"); ?>" style="display:inline-block;width:200px" class="tip">-->
        <!--                            <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">-->
        <!-- </div>-->
        <br />
        <b><?php $this->lan("Tips"); ?></b>
        <?php $this->lan("Tips2"); ?>
        <!--  <div data-title="<?php $this->lan("Tips2"); ?>" style="display:inline-block;width:200px" class="tip">-->
        <!--                           <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">-->
        <!--</div>-->
    </div>
    <div class="bk10"></div>
    <div class="header-data lr10">
        <form action="#" method="post" class="posttimeform">
            <?php $this->lan("Belonging_country");?>
            <select name="language" style="margin-left: 5px!important;width: auto!important;">
                <option value=""><?php $this->lan("All"); ?></option>
                <?php
                foreach ($languages as $vl) {
                    $sel = '';
                    if($vl['lg_value'] == $get_data['language']) $sel = 'selected="selected"';
                    echo "<option value='{$vl['lg_value']}' $sel>{$vl['country']}</option>";
                }
                ?>
            </select>
            <?php $this->lan("Add_time"); ?>&nbsp;&nbsp;
            <div class="posttime">
                <input name="posttime1" type="text" id="posttime1" class="input-text" placeholder="<?php $this->lan("tips3"); ?>" readonly="readonly" value="<?php echo $posttime1_str; ?>" /> -
                <input name="posttime2" type="text" id="posttime2" class="input-text" placeholder="<?php $this->lan("tips4"); ?>" readonly="readonly" value="<?php echo $posttime2_str; ?>" />
            </div>
            <script type="text/javascript">
                date = new Date();
                Calendar.setup({
                    inputField: "posttime1",
                    ifFormat: "%Y-%m-%d %H:%M:%S",
                    showsTime: true,
                    timeFormat: "24"
                });
                Calendar.setup({
                    inputField: "posttime2",
                    ifFormat: "%Y-%m-%d %H:%M:%S",
                    showsTime: true,
                    timeFormat: "24"
                });
            </script>
            <script type="text/javascript">
                function r_submit() {
                    $.get('<?php echo G_ADMIN_PATH; ?>/content/r_submit', function(res) {
                        window.location.href = "<?php echo G_ADMIN_PATH; ?>/content/goods_list"
                    })
                }
            </script>
            <select name="cate" style="width: auto!important;">
                <option value=""><?php $this->lan("All"); ?></option>
                <?php foreach($cate_list as $vl){
                    $sel = '';
                    if($vl['cateid'] == $cate){ $sel = 'selected="selected"';}
                    echo "<option value='{$vl['cateid']}' $sel >{$vl['name']}</option>";
                } ?>
            </select>
            <!--<br><span style="display: block;height: 10px;width: 10px;"></span>-->
            <select name="sotype" style="width: auto!important;">
                <option value="title" <?php echo $sotype == 'title' ? 'selected' : ''; ?>><?php $this->lan("Product_title"); ?></option>
                <option value="id" <?php echo $sotype == 'id' ? 'selected' : ''; ?>><?php $this->lan("Product_ID"); ?></option>
                <option value="cateid" <?php echo $sotype == 'cateid' ? 'selected' : ''; ?>><?php $this->lan("Column_ID"); ?></option>
                <option value="catename" <?php echo $sotype == 'catename' ? 'selected' : ''; ?>><?php $this->lan("Column_name"); ?></option>
                <option value="brandid" <?php echo $sotype == 'brandid' ? 'selected' : ''; ?>><?php $this->lan("Brand_ID"); ?></option>
                <option value="brandname" <?php echo $sotype == 'brandname' ? 'selected' : ''; ?>><?php $this->lan("Brand_name"); ?></option>
            </select>
            <input type="text" name="sosotext" placeholder="<?php $this->lan("Search_content"); ?>" class="input-text wid100" value="<?php echo $sosotext ? $sosotext : ''; ?>" style="max-width:200px!important;"/>
            <input class="button" type="submit" name="sososubmit" value="<?php $this->lan("search"); ?>">
            <input class="button" type="button" value="<?php $this->lan("reset"); ?>" onclick="r_submit()">
            <div data-title="<?php $this->lan("Tips5"); ?>" style="display:inline-block;width:200px;display:flex;" class="tip">
                <img src="../../../../statics/uploads/photo/gan.png" width="20" height="20">
            </div>
        </form>
    </div>
    <div class="bk10"></div>
    <form action="#" method="post" name="myform">
        <div class="table-list lr10">
            <?php if ($this->segment(4) == 'money' || $this->segment(4) == 'moneyasc'): ?>
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%"><?php $this->lan("sort"); ?></th>
                            <th width="5%"><?php $this->lan("id"); ?></th>
                            <th width="5%"><?php $this->lan("sid"); ?></th>
                            <th width="25%"><?php $this->lan("Product_title"); ?></th>
                            <th width="8%"><?php $this->lan("Belonging_column"); ?></th>
                            <th width="10%"><?php $this->lan("participated_total"); ?></th>
                            <th width="5%"><?php $this->lan("Unit_price"); ?></th>
                            <th width="10%"><?php $this->lan("Number_periods"); ?></th>
                            <th width="10%"><?php $this->lan("Commodity_price"); ?></th>
                            <th width="15%"><?php $this->lan("operation"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shoplist as $v) { ?>
                            <tr>
                                <td align='center'><input name='listorders[<?php echo $v['id']; ?>]' onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" oninput="if(value.length>3)value=value.slice(0,3)" type='text' size='3' value='<?php echo $v['order']; ?>' class='input-text-c'></td>
                                <td><?php echo $v['id']; ?></td>
                                <td><?php echo $v['sid']; ?></td>
                                <td><span><?php echo _strcut($v['title'], 30); ?></span>
                                    <?php if ($v['xsjx_time']) echo "<font color='red'>限时</font>"; ?>
                                </td>
                                <td><a href="<?php echo G_ADMIN_PATH; ?>/content/goods_list/<?php echo $v['cateid']; ?>"><?php echo $this->categorys[$v['cateid']]['name']; ?></a></td>
                                <td>
                                    <font color="#ff0000"><?php echo $v['canyurenshu']; ?></font> / <?php echo $v['zongrenshu']; ?>
                                </td>
                                <td><?php echo $v['yunjiage']; ?></td>
                                <td><?php echo $v['qishu']; ?>/<?php echo $v['maxqishu']; ?></td>
                                <td><?php echo $v['money']; ?></td>
                                <td class="action">
                                    <a href="<?php echo G_ADMIN_PATH; ?>/content/goods_edit/<?php echo $v['id']; ?>"><?php $this->lan("modify"); ?></a>
                                    <span class='span_fenge lr5'>|</span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <?php if ($this->segment(4) == 'maxqishu'): ?>
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%"><?php $this->lan("sort"); ?></th>
                            <th width="5%"><?php $this->lan("id"); ?></th>
                            <th width="5%"><?php $this->lan("sid"); ?></th>
                            <th width="25%"><?php $this->lan("Product_title"); ?></th>
                            <th width="8%"><?php $this->lan("Belonging_column"); ?></th>
                            <th width="10%"><?php $this->lan("participated_total"); ?></th>
                            <th width="5%"><?php $this->lan("Unit_price"); ?></th>
                            <th width="10%"><?php $this->lan("Number_periods"); ?></th>
                            <th width="25%"><?php $this->lan("operation"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shoplist as $v1) { ?>
                            <tr>
                                <td align='center'><input name='listorders[<?php echo $v1['id']; ?>]' onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" oninput="if(value.length>3)value=value.slice(0,3)" type='text' size='3' value='<?php echo $v1['order']; ?>' class='input-text-c'></td>
                                <td><?php echo $v1['id']; ?></td>
                                <td><?php echo $v1['sid']; ?></td>
                                <td><span><?php echo _strcut($v1['title'], 30); ?></span>
                                    <?php if ($v1['xsjx_time']) echo "<font color='red'>限时</font>"; ?>
                                </td>
                                <td><a href="<?php echo G_ADMIN_PATH; ?>/content/goods_list/<?php echo $v1['cateid']; ?>"><?php echo $this->categorys[$v1['cateid']]['name']; ?></a></td>
                                <td>
                                    <font color="#ff0000"><?php echo $v1['canyurenshu']; ?></font> / <?php echo $v1['zongrenshu']; ?>
                                </td>
                                <td><?php echo $v1['yunjiage']; ?></td>
                                <td><?php echo $v1['qishu']; ?>/<?php echo $v1['maxqishu']; ?></td>
                                <td class="action">
                                    <a href="javascript:void(0)" gonemoney="<?php echo $v1['yunjiage']; ?>" gmoney="<?php echo $v1['money']; ?>" gqishu="<?php echo $v1['maxqishu']; ?>" gid="<?php echo $v1['id']; ?>" gtitle="<?php echo _strcut($v1['title'], 30); ?>" class="pay_window_show butaoin"><?php $this->lan("Reset_product"); ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <?php if ($this->segment(4) == 'xianshi'): ?>
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%"><?php $this->lan("sort"); ?></th>
                            <th width="5%"><?php $this->lan("id"); ?></th>
                            <th width="5%"><?php $this->lan("sid"); ?></th>
                            <th width="25%"><?php $this->lan("Product_title"); ?></th>
                            <th width="10%"><?php $this->lan("participated_total"); ?></th>
                            <th width="5%"><?php $this->lan("Unit_price"); ?></th>
                            <th width="10%"><?php $this->lan("Number_periods"); ?></th>
                            <th width="20%"><?php $this->lan("Limited_time"); ?></th>
                            <th width="15%"><?php $this->lan("operation"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shoplist as $v2) { ?>
                            <tr>
                                <td align='center'><input name='listorders[<?php echo $v2['id']; ?>]' onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" oninput="if(value.length>3)value=value.slice(0,3)" type='text' size='3' value='<?php echo $v2['order']; ?>' class='input-text-c'></td>
                                <td><?php echo $v2['id']; ?></td>
                                <td><?php echo $v2['sid']; ?></td>
                                <td><span><?php echo _strcut($v2['title'], 30); ?></span></td>
                                <td>
                                    <font color="#ff0000"><?php echo $v2['canyurenshu']; ?></font> / <?php echo $v2['zongrenshu']; ?>
                                </td>
                                <td><?php echo $v2['yunjiage']; ?></td>
                                <td><?php echo $v2['qishu']; ?>/<?php echo $v2['maxqishu']; ?></td>
                                <td><?php echo ($v2['xsjx_time'] != 0) ? date("Y-m-d H", $v2['xsjx_time']) : '否'; ?></td>
                                <td class="action">
                                    <a href="<?php echo G_ADMIN_PATH; ?>/content/goods_edit/<?php echo $v2['id']; ?>"><?php $this->lan("modify"); ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <?php if ($this->segment(4) != 'xianshi' && $this->segment(4) != 'maxqishu' && $this->segment(4) != 'moneyasc' && $this->segment(4) != 'money'): ?>
                <table width="100%" cellspacing="0" style="border-width: 1px 1px 0px 0px;table-layout: fixed">
                    <!-- <input type="button" value="同步数据" class="synchronous button" style="margin-bottom: 10px;"> -->
                    <!-- class="synchronousnew button display:none" -->
                    <a href="<?php echo G_ADMIN_PATH; ?>/content/goods_tb" style="display:none;">
                        <input type="button" value="<?php $this->lan("Product_collection"); ?>" class="button" style="margin-bottom: 10px;">
                    </a>
                    <input type="button" value="<?php $this->lan("batch"); ?>" class="batch_reset button" style="margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <th width="5%"><input id="checkAll" type="checkbox" style="margin-right:0!important;"></th>
                            <th width="5%"><?php $this->lan("sort"); ?></th>
                            <th width="5%"><?php $this->lan("id"); ?></th>
                            <th width="5%"><?php $this->lan("sid"); ?></th>
                            <th width="25%"><?php $this->lan("Product_title"); ?></th>
                            <th width="10%"><?php $this->lan("language"); ?></br><?php $this->lan("Belonging_column"); ?></th>
                            <th width="10%"><?php $this->lan("participated_total"); ?></th>
                            <th width="10%"><?php $this->lan("Unit_price"); ?></br><?php echo $is_preferential == 1 ? $this->lgs['Special_price'] : $this->lan("Total_price"); ?></th>
                            <!--<th width="8%"><?php $this->lan("Number_periods"); ?></th>-->
                            <!--<th width="10%"><?php echo $is_preferential == 1 ? $this->lgs['Special_price'] : $this->lan("Total_price"); ?></th>-->
                            <!--<th width="5%"><?php $this->lan("language"); ?></th>-->
                            <th width="10%"><?php $this->lan("home_recommend"); ?></br><?php $this->lan("popularity"); ?></th>
                            <!--<th width="6%"><?php $this->lan("popularity"); ?></th>-->
                            <th width="35%"><?php $this->lan("operation"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shoplist ?: [] as $v3) { ?>
                            <tr style="border-left: 1px solid #E2E2E2;">
                                <td align="center"><input class="checkOne" type="checkbox" data-id="<?php echo $v3['id']; ?>"></td>
                                <td align='center'><input name='listorders[<?php echo $v3['id']; ?>]' onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" oninput="if(value.length>3)value=value.slice(0,3)" type='text' size='3' value='<?php echo $v3['order']; ?>' class='input-text-c' style="border:0!important;padding:0!important;text-align:center!important;"></td>
                                <td><?php echo $v3['id']; ?></td>
                                <td><?php echo $v3['sid']; ?></td>
                                <td align="left" style=" padding-left:20px;word-break: break-all;"><span style=""><a target="_blank" href="/index/pages/latest_winning/item/index?sid=<?php echo $v3['id']; ?>"><span style="color:red;">(<?php echo $v3['qishu']; ?>)</span><?php echo _strcut($v3['title'], 200); ?></a></span></td>
                                <td><a href="<?php echo G_ADMIN_PATH; ?>/content/goods_list/<?php echo $v3['cateid']; ?>">
                                    <?php echo $v3['lan'] ?: $this->lgs['General']; ?></br>
                                    <?php echo $v3['fl']['name']; ?></a></td>
                                <td>
                                    <font color="#ff0000"><?php echo $v3['canyurenshu']; ?></font> / <?php echo $v3['zongrenshu']; ?>
                                </td>
                                <td><?php echo $v3['yunjiage']; ?></br><?php echo $is_preferential == 1 ? $v3['preferential_price'] . "/" : ''; ?><?php echo $v3['money'] ?></td>
                                <!--<td><?php echo $v3['oderQishu']; ?>/<?php echo $v3['qishu']; ?>/<?php echo $v3['maxqishu']; ?></td>-->
                                <!--<td><?php echo $is_preferential == 1 ? $v3['preferential_price'] . "/" : ''; ?><?php echo $v3['money'] ?></td>-->
                                <!--<td><?php echo $v3['lan'] ?: $this->lgs['General']; ?></td>-->
                                <td class="layui-form">
                                    <input type="checkbox" style="" class="is_state" name="pos" <?php if ($v3['pos'] == 1) {
                                                                                                    echo 'checked=""';
                                                                                                } ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v3['id'] ?>" lay-text="<?php $this->lan("home_recommend"); ?>|<?php $this->lan("close"); ?>">
                                    </br>
                                    <input type="checkbox" style="" class="is_state" name="renqi" <?php if ($v3['renqi'] == 1) {
                                                                                                        echo 'checked=""';
                                                                                                    } ?> lay-skin="switch" lay-filter="is_state" data-state="<?php echo $v3['id'] ?>" lay-text="<?php $this->lan("popularity"); ?>|<?php $this->lan("close"); ?>">
                                </td>
                                <!--<td class="layui-form">-->
                                    
                                <!--</td>-->
                                <td class="action">
                                    <a class="butaoin" href="<?php echo G_ADMIN_PATH; ?>/content/goods_set_money/<?php echo $v3['id']; ?>"><?php $this->lan("Reset_price"); ?></a>
                                    <a class="butaoin" href="<?php echo G_ADMIN_PATH; ?>/qishu/qishu_list/<?php echo $v3['id']; ?>"><?php $this->lan("Previous_period"); ?></a>
                                    <a class="butaoin" href="/index/pages/latest_winning/item/index?sid=<?php echo $v3['id']; ?>" target="_blank"><?php $this->lan("preview"); ?></a>
                                    <a class="butaoin" href="<?php echo G_ADMIN_PATH; ?>/content/goods_edit/<?php echo $v3['id']; ?>"><?php $this->lan("modify"); ?></a>
                                    <a class="butaoin butaish a_form_noparam" data-confirm="<?php $this->lan("confirm");?>" data-cancel="<?php $this->lan("cancel");?>" href="<?php echo G_ADMIN_PATH; ?>/content/goods_del/<?php echo $v3['id']; ?>"><?php $this->lan("Removed"); ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="9" style="border-width: 0px;display: flex;gap: 10px;" align='left'>
                                <input type="button" value="<?php $this->lan("Batch_deletion"); ?> " class="batchDel button">
                                <input type="button" class="button" value="<?php $this->lan("sort"); ?> " onclick="myform.action='<?php echo G_MODULE_PATH; ?>/content/goods_listorder/dosubmit';myform.submit();" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
    </form>
    <div id="pages" style="margin-top: -35px;">
        <ul>
            <li><?php $this->lan("common"); ?> <?php echo $total; ?> <?php $this->lan("strip"); ?></li><?php echo $page->show('three', 'li'); ?>
        </ul>
    </div>
    </div>
    <?php if ($this->segment(4) == 'maxqishu'): ?>
        <!--期数修改弹出框-->
        <style>
            #paywindow {
                position: absolute;
                z-index: 999;
                display: none
            }

            #paywindow_b {
                width: 372px;
                height: 360px;
                background: #2a8aba;
                filter: alpha(opacity=60);
                opacity: 0.6;
                position: absolute;
                left: 0px;
                top: 0px;
                display: block
            }

            #paywindow_c {
                width: 360px;
                height: 338px;
                background: #fff;
                display: block;
                position: absolute;
                left: 6px;
                top: 6px;
            }

            .p_win_title {
                line-height: 40px;
                height: 40px;
                background: #f8f8f8;
            }

            .p_win_title b {
                float: left
            }

            .p_win_title a {
                float: right;
                padding: 0px 10px;
                color: #e42d6a
            }

            .p_win_content h1 {
                font-size: 25px;
                font-weight: bold;
            }

            .p_win_but,
            .p_win_mes,
            .p_win_ctitle,
            .p_win_text {
                margin: 10px 20px;
            }

            .p_win_mes {
                border-bottom: 1px solid #eee;
                line-height: 35px;
            }

            .p_win_mes span {
                margin-left: 10px;
            }

            .p_win_ctitle {
                overflow: hidden;
            }

            .p_win_x_b {
                float: left;
                width: 73px;
                height: 68px;
                background-repeat: no-repeat;
            }

            .p_win_x_t {
                font-size: 18px;
                font-weight: bold;
                font-family: "Helvetica Neue", \5FAE\8F6F\96C5\9ED1, Tohoma;
                color: #f00;
                text-align: center
            }

            .p_win_but {
                height: 40px;
                line-height: 40px;
            }

            .p_win_but a {
                padding: 8px 15px;
                background: #e42d6a;
                color: #fff;
                border: 1px solid #f50;
                margin: 0px 15px;
                font-family: "Helvetica Neue", \5FAE\8F6F\96C5\9ED1, Tohoma;
                font-size: 15px;
            }

            .p_win_but a:hover {
                background: #f50
            }

            .p_win_text a {
                font-size: 13px;
                color: #e42d6a
            }

            .pay_window_quit:hover {
                color: #f00
            }
        </style>
        <div id="paywindow">
            <div id="paywindow_b"></div>
            <div id="paywindow_c">
                <div class="p_win_title"><a href="javascript:void();" class="pay_window_quit">[关闭]</a><b>：：：重设期数</b></div>
                <div class="p_win_content">
                    <div class="p_win_mes">
                        <b>标题:　　　</b><span id="max_title">...</span>
                    </div>
                    <div class="p_win_mes">
                        <b>原最大期数:</b><span id="max_qishu">0</span>　期
                    </div>
                    <div class="p_win_mes">
                        <b>新最大期数:</b><input type="text" id="max_new_qishu" class="input-text" style="width:50px;margin-left:10px;">需要大于原最大期数
                    </div>
                    <div class="p_win_mes">
                        <b>新商品售价:</b><input type="text" id="max_one_money" class="input-text" style="width:50px;margin-left:10px;">商品每一次购买的价格
                    </div>
                    <div class="p_win_mes">
                        <b>新商品总价:</b><input type="text" id="max_new_money" class="input-text" style="width:50px;margin-left:10px;">商品的总价格
                    </div>
                    <div class="p_win_mes">
                        <input type="button" value=" 更新并新建一期 " class="button" id="max_button" onclick="set_shop_qishu(this)">
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(function() {
                var width = ($(window).width() - 372) / 2;
                var height = ($(window).height() - 360) / 2;
                $("#paywindow").css("left", width);
                $("#paywindow").css("top", height);
                $(".pay_window_quit").click(function() {
                    $("#paywindow").hide();
                });
                $(".pay_window_show").click(function() {
                    var gid = $(this).attr("gid");
                    var gtitle = $(this).attr("gtitle");
                    var gqishu = $(this).attr("gqishu");
                    var gmoney = $(this).attr("gmoney");
                    var gonemoney = $(this).attr("gonemoney");
                    $("#max_one_money").val(gonemoney);
                    $("#max_new_money").val(gmoney);
                    $("#max_new_qishu").val(parseInt(gqishu) + 100);
                    $("#max_qishu").text(gqishu);
                    $("#max_title").text(gtitle);
                    $("#max_button").attr("onclick", "set_shop_qishu(" + gid + ")");
                    $("#paywindow").show();
                });
            });

            function set_shop_qishu(T) {
                var yqishu = parseInt($("#max_qishu").text());
                var tqishu = parseInt($("#max_new_qishu").val());
                var money = parseInt($("#max_new_money").val());
                var onemoney = parseInt($("#max_one_money").val());
                if (!money || !onemoney || (money < onemoney)) {
                    window.parent.message("商品价格填写不正确!", 8, 2);
                    return;
                }
                if (tqishu <= yqishu) {
                    window.parent.message("新期数不能小于原来的商品期数", 8, 2);
                    return;
                }
                if (!tqishu) {
                    window.parent.message("新期数不能为空", 8, 2);
                    return;
                }
                $.post("<?php echo G_MODULE_PATH; ?>/content/goods_max_qishu/dosubmit/", {
                    "gid": T,
                    "qishu": tqishu,
                    "money": money,
                    "onemoney": onemoney
                }, function(datas) {
                    var data = jQuery.parseJSON(datas);
                    if (data.err == '-1') {
                        window.parent.message(data.meg, 8, 2);
                        return;
                    } else {
                        window.parent.message(data.meg, 8, 2);
                        window.parent.btn_iframef5();
                        return;
                    }
                });
            }
        </script>
        <!--期数修改弹出框-->
    <?php endif; ?>
    <script type="text/javascript">
        //商品批量删除
        $("#checkAll").on('change', function() {
            if ($(this).is(":checked")) { // 全选
                $(".checkOne").prop("checked", true);
            } else { // 反选
                $(".checkOne").prop("checked", false);
            }
        });
        $('.batchDel').click(function() {
            //获取所有选中的CheckBox的id
            var ids = "";
            $(".checkOne").each(function(i) {
                if ($(this).prop('checked')) {
                    var id = $(this).attr("data-id");
                    console.log("id", id);
                    if (ids == "") {
                        ids = id;
                    } else {
                        ids += ',' + id;
                    }
                }
                // ajax批量删除
            });
            // console.log(ids);
            $.ajax({
                type: 'POST',
                url: '<?php echo G_WEB_PATH; ?>/<?php echo G_ADMIN_DIR; ?>/content/shop_del',
                data: {
                    ids: ids
                },
                dataType: 'json',
                success: function(data) {
                    if (data == 0) {
                        layer.msg("删除成功!");
                        setTimeout(function() {
                            location.reload(false);
                        }, 1500);
                        // $(window).attr('location','http://bata.yungoucms.cn/admin/content/goods_list');
                    } else {
                        layer.msg("删除失败!");
                    }
                }
            });
            ids = "";
        });
        var index;
        //批量重置价格
        var isclick = false;
        $(".batch_reset").click(function() {
            var resstids = ''
            $(".checkOne").each(function(i) {
                if ($(this).prop('checked')) {
                    var id = $(this).attr("data-id");
                    resstids += +id + ',';
                }
            });
            if (resstids == '') {
                toast('请先选择商品')
                return false
            }
            if (isclick) {
                toast('请不要重复点击')
                return
            }
            isclick = true
            confirm({
                title: '温馨提示',
                content: '此操作会将已购买的钱退给用户',
                doneText: '确认',
                cancelText: '取消'
            }).then(() => {
                index = layer.load(1);
                batch_resst(resstids)
                resstids = "";
            }).catch(() => {
                console.log('已取消')
                isclick = false
            })
        })

        function batch_resst(resstids) {
            $.post('<?php echo G_MODULE_PATH; ?>/content/goods_batch_reset', {
                ids: resstids
            }, function(data) {
                isclick = false
                if (data.code == 0) {
                    toast(data.msg)
                } else {
                    toast('重置成功')
                    setTimeout(() => {
                        console.log('准备跳转刷新')
                        $(window).attr('location', '<?php echo G_MODULE_PATH; ?>/content/goods_list');
                    }, 1500)
                }
            }, 'JSON');
        }
        //synchronous
        layui.use('layer', function() {
            $('.synchronous').on('click', function() {
                var layer = layui.layer;
                layer.open({
                    type: 2,
                    title: '同步云商品库',
                    shadeClose: true,
                    shade: false,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['30%', '35%'],
                    content: '<?php echo "/" . ROUTE_M . "/" . ROUTE_C . "/synchronous"; ?>'
                });
            })
        });
        // 修改过
        layui.use(['form', 'jquery'], function() {
            var form = layui.form,
                $ = layui.jquery;
            form.render('checkbox');
            form.on('switch(is_state)', function(data) {
                var id = $(this).attr('data-state');
                var name = $(this).attr('name');
                var zhi = '';
                if (data.elem.checked == true) {
                    zhi = 1;
                } else if (data.elem.checked == false) {
                    zhi = 2;
                }
                $.post('<?php echo G_MODULE_PATH ?>/content/goods_set/' + name + '/' + id, {
                    id: id,
                    state: zhi
                }, function(data) {
                    data = JSON.parse(data);
                    if (data.code == 200) {
                        layer.msg(data.msg, {
                            icon: 1
                        });
                        // setTimeout(function () {
                        //     window.location.href = "<?php echo WEB_PATH . G_ADMIN_DIR; ?>/wap/set_logistics";
                        // }, 2000);
                    } else {
                        layer.msg(data.msg, {
                            icon: 2
                        });
                    }
                });
            });
        })
    </script>

<!-- 高级骨架屏系统脚本 -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/advanced-skeleton.js"></script>
<!-- 通知消息系统脚本（必须在layer之后加载） -->
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/notification-system.js"></script>
</body>

</html>
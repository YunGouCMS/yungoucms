<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户位置信息</title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/admin.js"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100%;
        }
        #mapContainer {
            position: relative;
            height: 100%;
            width: 100%;
        }
    </style>
</head>
<script charset="utf-8" src="https://map.qq.com/api/gljs?v=1.exp&key=PRTBZ-6YMO4-7G7U2-XLW65-DNPAZ-PBBNX"></script>
<body onload="init()">
<div id='mapContainer'></div>
<script>
    var map;
    function init() {
        var drawContainer = document.getElementById('mapContainer');
        var center = new TMap.LatLng(29.52168,106.56256);//设置中心点坐标
        map = new TMap.Map(drawContainer, {
            zoom: 5,
            pitch:40,
            center: center,
            draggable: true,
            scrollable: true,
            mapStyleId: "style 1"
        });
        // 创建点聚合实例
        var markerCluster = new TMap.MarkerCluster({
            id: 'cluster',
            map: map,
            enableDefaultStyle: true, // 启用默认样式
            minimumClusterSize: 2, // 形成聚合簇的最小个数
            geometries: [
                <?php foreach ($addreslist as $v): ?>
                { // 点数组
                position: new TMap.LatLng( <?php echo $v['lat'];?>, <?php echo $v['lng'];?>)
                },
                <?php endforeach; ?>
            ],
            zoomOnClick: true, // 点击簇时放大至簇内点分离
            gridSize: 60, // 聚合算法的可聚合距离
            averageCenter: false, // 每个聚和簇的中心是否应该是聚类中所有标记的平均值
            maxZoom: 20  // 采用聚合策略的最大缩放级别
        });
    }
</script>
</body>
</html>
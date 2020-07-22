<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"D:\phpstudy_pro\WWW\CQCQ\back_end\public/../application/index\view\record\records.html";i:1595413180;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>查寝统计</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/cqcq/back_end/public/lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="https://cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="/cqcq/back_end/public/css/public.css" media="all">
    <style>
        body {
            background-color: #f2f2f2;
            margin: 30px 0px;
            padding-left: 110px;
        }

        .dv {
            width: 950px;
            height: 50px;
        }

        .inputdiv {
            width: 140px;
            display: flex;
            border: 1px solid #009688 !important;
            background-color: #fff;
            margin-left: 20px;
            float: right;
            height: 28px;
        }

        .inp {
            width: 110px;
            height: 28px;
            border: 1px solid lightgray;
            padding-left: 8px;
            border-style: none;
            float: right;
        }

        .date-input-icon {
            color: grey;
            height: 28px;
            line-height: 28px;
            width: 30px;
        }

        .yuan {
            border-radius: 10px;
            width: 950px;
            padding-top: 8px;
            border: 1px solid lightgrey;
            background-color: white;
        }

        .box {
            width: 920px;
            padding: 10px 15px;
            border-bottom: 1px solid lightgrey;
            background-color: white;
        }

        .pb {
            width: 880px;
            height: 20px;
            line-height: 20px;
        }

        #box {
            margin-left: 30px;
            padding: 30px;
            width: 880px;
        }

        table {
            width: 100%;
            text-align: center;
            border-bottom: 1px solid lightgrey;
        }

        table tr {
            height: 30px;
            background-color: #fff;
            font-weight: normal;
        }

        thead {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- 右上角 -->
    <div class="dv">
        <div class="layui-btn-group" style="float:right;margin-left:20px;margin-right:10px;">
            <button type="button" id="btn_all" class="layui-btn layui-btn-sm" onclick="changeAll()">全部</button>
        </div>

        <div class="layui-inline" style="float:right;background-color: #009688;">
            <button type="submit" class="layui-btn layui-btn-sm" onclick="search()"><i
                    class="layui-icon"></i>搜索</button>
        </div>
        <div class="inputdiv">
            <input type="text" class="inp" name="date" id="test" placeholder="请选择时间" lay-verify="title">
            <i class="fa fa-calendar-o date-input-icon"></i>
        </div>
    </div>

    <!-- 查寝记录 -->
    <div class="yuan" name="st_yuan" id="st_yuan"></div>

    <script src="/cqcq/back_end/public/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
    <script type="text/javascript">
        function changeAll() {
            location.reload();
        }

        function search() {
            var date = document.getElementById("test").value;
            if (date == "") {
                alert("请选择时间");  //无输入情况
            } else {
                layui.use(['jquery'], function () {
                    var $ = layui.jquery;
                    var layer = layui.layer;
                    $.ajax({
                        url: "index.php?s=index/record/search_date",
                        data: { 'date': date },
                        type: "Post",
                        dataType: "json",
                        success: function (msg) {
                            // console.log(msg); 
                            if (!msg) {
                                alert("未搜索到记录");  //无输入情况
                            }
                            else {
                                $('#st_yuan').empty();
                                let bd = document.getElementsByName('st_yuan')[0];
                                for (var i = 0; i < msg.count; i++) {
                                    let div1 = document.createElement("div");
                                    let div2 = document.createElement("div");
                                    let d1 = document.createElement("p");
                                    let i1 = document.createElement("i");

                                    d1.innerHTML = msg.data[i].start_time + " - " + msg.data[i].end_time;

                                    div1.className = "box";
                                    i1.className = "fa fa-sort-down"; //箭头
                                    d1.className = "pd";  //日期
                                    div2.className = "content";  //表格

                                    i1.style.float = 'right';
                                    div2.style.display = "none";
                                    div2.setAttribute("id", "content");////给div2添加id名

                                    //动态添加点击收缩事件
                                    div1.addEventListener('click', function (e) {
                                        if (div2.style.display == "block") {
                                            div2.style.display = "none";
                                        } else {
                                            div2.style.display = "block";
                                        }
                                    });

                                    // 构建表格，取出未签到的学生情况
                                    $.ajax({
                                        type: "POST",
                                        url: "index.php?s=index/record/statistics",
                                        async: false,
                                        data: {
                                            'start_time': msg.data[i].start_time,
                                            'end_time': msg.data[i].end_time,
                                        },
                                        success: function (msg) {
                                            // console.log(msg);
                                            if (msg.error_code == 1) {
                                                let p = document.createElement("p");
                                                p.innerHTML = "该查寝尚未开始！";
                                                p.style.textAlign = "center";
                                                div2.appendChild(p);
                                            }
                                            else if (msg.error_code == 2) {
                                                let p = document.createElement("p");
                                                p.innerHTML = "该查寝尚未结束！";
                                                p.style.textAlign = "center";
                                                div2.appendChild(p);
                                            } else if (msg.data.unsign_num != 0) {
                                                //构建表头
                                                let table = document.createElement("table");
                                                let thead = document.createElement("thead");
                                                // let caption = document.createElement("caption");
                                                let tr = document.createElement("tr");
                                                let th1 = document.createElement("th");
                                                let th2 = document.createElement("th");
                                                let th3 = document.createElement("th");
                                                let th4 = document.createElement("th");

                                                th1.innerHTML = "学号";
                                                th2.innerHTML = "用户名";
                                                th3.innerHTML = "宿舍楼";
                                                th4.innerHTML = "宿舍号";
                                                // caption.innerHTML = "未签到"

                                                tr.appendChild(th1);
                                                tr.appendChild(th2);
                                                tr.appendChild(th3);
                                                tr.appendChild(th4);
                                                thead.appendChild(tr);
                                                table.appendChild(thead);
                                                // table.appendChild(caption);
                                                div2.appendChild(table);

                                                // caption.style.color = "blue";
                                                // tr.style.borderBottom="1.5px solid lightgrey";

                                                //构建表格内容
                                                for (var j = 0; j < msg.data.unsign_num; j++) {
                                                    let tr = document.createElement("tr");
                                                    let th1 = document.createElement("th");
                                                    let th2 = document.createElement("th");
                                                    let th3 = document.createElement("th");
                                                    let th4 = document.createElement("th");

                                                    th1.innerHTML = msg.data.unsign_list[j].id;
                                                    th2.innerHTML = msg.data.unsign_list[j].username;
                                                    th3.innerHTML = msg.data.unsign_list[j].block;
                                                    th4.innerHTML = msg.data.unsign_list[j].room;

                                                    th1.style.fontWeight = "normal";
                                                    th2.style.fontWeight = "normal";
                                                    th3.style.fontWeight = "normal";
                                                    th4.style.fontWeight = "normal";

                                                    tr.appendChild(th1);
                                                    tr.appendChild(th2);
                                                    tr.appendChild(th3);
                                                    tr.appendChild(th4);
                                                    table.appendChild(tr);
                                                }
                                            } else {
                                                let p = document.createElement("p");
                                                p.innerHTML = "该次抽查已全部签到！";
                                                p.style.textAlign = "center";
                                                div2.appendChild(p);
                                            }
                                        }
                                    });

                                    d1.appendChild(i1);
                                    div1.appendChild(d1);
                                    div1.appendChild(div2);
                                    bd.appendChild(div1);
                                }
                            }
                        },
                    });
                })
            }
        }

        layui.use(['jquery', 'laypage', 'laydate'], function () {
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#test'//指定元素
                , eventElem: '.date-input-icon'
                , trigger: 'click'
            });

            var $ = layui.jquery;
            var laypage = layui.laypage;

            $.ajax({
                type: "POST",
                url: "index.php?s=index/record/get_date",
                // data: { 'grade': 2017, 'department': "计算机工程系" },
                async: false,
                success: function (msg) {
                    // console.log(msg);
                    let bd = document.getElementsByName('st_yuan')[0];
                    for (var i = 0; i < msg.count; i++) {
                        let div1 = document.createElement("div");
                        let div2 = document.createElement("div");
                        let d1 = document.createElement("p");
                        let i1 = document.createElement("i");

                        d1.innerHTML = msg.data[i].start_time + " - " + msg.data[i].end_time;

                        div1.className = "box";
                        i1.className = "fa fa-sort-down"; //箭头
                        d1.className = "pd";  //日期
                        div2.className = "content";  //表格

                        i1.style.float = 'right';
                        div2.style.display = "none";
                        div2.setAttribute("id", "content");////给div2添加id名

                        //动态添加点击收缩事件
                        div1.addEventListener('click', function (e) {
                            if (div2.style.display == "block") {
                                div2.style.display = "none";
                            } else {
                                div2.style.display = "block";
                            }
                        });

                        // 构建表格，取出未签到的学生情况
                        $.ajax({
                            type: "POST",
                            url: "index.php?s=index/record/statistics",
                            async: false,
                            data: {
                                'start_time': msg.data[i].start_time,
                                'end_time': msg.data[i].end_time,
                            },
                            success: function (msg) {
                                // console.log(msg);
                                if (msg.error_code == 1) {
                                    let p = document.createElement("p");
                                    p.innerHTML = "该查寝尚未开始！";
                                    p.style.textAlign = "center";
                                    div2.appendChild(p);
                                }
                                else if (msg.error_code == 2) {
                                    let p = document.createElement("p");
                                    p.innerHTML = "该查寝尚未结束！";
                                    p.style.textAlign = "center";
                                    div2.appendChild(p);
                                } else if (msg.data.unsign_num != 0) {
                                    //构建表头
                                    let table = document.createElement("table");
                                    let thead = document.createElement("thead");
                                    // let caption = document.createElement("caption");
                                    let tr = document.createElement("tr");
                                    let th1 = document.createElement("th");
                                    let th2 = document.createElement("th");
                                    let th3 = document.createElement("th");
                                    let th4 = document.createElement("th");

                                    th1.innerHTML = "学号";
                                    th2.innerHTML = "用户名";
                                    th3.innerHTML = "宿舍楼";
                                    th4.innerHTML = "宿舍号";
                                    // caption.innerHTML = "未签到"

                                    tr.appendChild(th1);
                                    tr.appendChild(th2);
                                    tr.appendChild(th3);
                                    tr.appendChild(th4);
                                    thead.appendChild(tr);
                                    table.appendChild(thead);
                                    // table.appendChild(caption);
                                    div2.appendChild(table);

                                    // caption.style.color = "blue";
                                    // tr.style.borderBottom="1.5px solid lightgrey";

                                    //构建表格内容
                                    for (var j = 0; j < msg.data.unsign_num; j++) {
                                        let tr = document.createElement("tr");
                                        let th1 = document.createElement("th");
                                        let th2 = document.createElement("th");
                                        let th3 = document.createElement("th");
                                        let th4 = document.createElement("th");

                                        th1.innerHTML = msg.data.unsign_list[j].id;
                                        th2.innerHTML = msg.data.unsign_list[j].username;
                                        th3.innerHTML = msg.data.unsign_list[j].block;
                                        th4.innerHTML = msg.data.unsign_list[j].room;

                                        th1.style.fontWeight = "normal";
                                        th2.style.fontWeight = "normal";
                                        th3.style.fontWeight = "normal";
                                        th4.style.fontWeight = "normal";

                                        tr.appendChild(th1);
                                        tr.appendChild(th2);
                                        tr.appendChild(th3);
                                        tr.appendChild(th4);
                                        table.appendChild(tr);
                                    }
                                } else {
                                    let p = document.createElement("p");
                                    p.innerHTML = "该次抽查已全部签到！";
                                    p.style.textAlign = "center";
                                    div2.appendChild(p);
                                }
                            }
                        });

                        d1.appendChild(i1);
                        div1.appendChild(d1);
                        div1.appendChild(div2);
                        bd.appendChild(div1);
                    }
                },
            });
        })

    </script>
</body>

</html>
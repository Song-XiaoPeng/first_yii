<?php
echo $this->set('title','数据统计');
$url_pre = Yii::$app->controller->module->appkey.'/'.Yii::$app->controller->id;
?>
<?php $this->beginBlock('cssText','append')?>
<style>
.table-title{
	padding: 15px 0px;
    padding-left: 20px;
    position: relative;
}
.table-title-icon {
    width: 8px;
    height: 22px;
    border-radius: 4px;
    background: #00c79c;
    position: absolute;
    left: 0px;
    vertical-align: middle;
}
.table-title span {
    vertical-align: middle;
    font-size: 16px;
    color: #2b425b;
}
.person-sex {
    display: inline-block;
    width: 99%;
    border: 1px solid #ddd;
}
.table-content-title {
    background: #f2f5f9;
    text-align: center;
    padding: 15px;
    font-size: 16px;
    border-bottom: 1px solid #ddd;
    color: #737f8f;
}
.person-detaile {
    height: 300px;
    box-sizing: border-box;
}
</style>
<?php $this->endBlock()?>
<div class="panel">
    <div class="panel-heading top">
        <h3 class="panel-title">数据统计</h3>
    </div>
    <div class="panel-body">
        <div class="row">
        	<div class="table-title">
                <div class="table-title-icon"></div>
                <span>总体</span>
            </div>
            <div class="row">
            	<div class="col-md-6">
            		<div class="person-sex">
		                <div class="table-content-title">
		                    领卡人数
		                </div>
		                <div class="person-detaile" id="person-pnum-detaile" >
		                </div>
            		</div>
            	</div>
            	<div class="col-md-6">
            		<div class="person-sex">
		                <div class="table-content-title">
		                    有效期
		                </div>
		                <div class="person-detaile" id="person-alltime-detaile" >
		                </div>
            		</div>
            	</div>
            </div>
        </div>
         <div class="row">
        	<div class="table-title">
                <div class="table-title-icon"></div>
                <span>正式卡</span>
            </div>
            <div class="row">
            	<div class="col-md-6">
            		<div class="person-sex">
		                <div class="table-content-title">
		                    领卡人数
		                </div>
		                <div class="person-detaile" id="person-num-detaile" >
		                </div>
            		</div>
            	</div>
            	<div class="col-md-6">
            		<div class="person-sex">
		                <div class="table-content-title">
		                    有效期
		                </div>
		                <div class="person-detaile" id="person-time-detaile" >
		                </div>
            		</div>
            	</div>
            </div>
        </div>
         <div class="row">
        	<div class="table-title">
                <div class="table-title-icon"></div>
                <span>临时卡</span>
            </div>
            <div class="row">
            	<div class="col-md-6">
            		<div class="person-sex">
		                <div class="table-content-title">
		                    有效期
		                </div>
		                <div class="person-detaile" id="person-ltime-detaile" >
		                </div>
            		</div>
            	</div>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('jsFile','append')?>
<script type="text/javascript" src="/backend/js/echarts.js"></script>
<?php $this->endBlock()?>
<?php $this->beginBlock('jsText','append')?>
<script type="text/javascript">
var myChartpNum = echarts.init(document.getElementById('person-pnum-detaile'));
var myChartNum = echarts.init(document.getElementById('person-num-detaile'));
var myChartTime = echarts.init(document.getElementById('person-time-detaile'));
var myChartlTime = echarts.init(document.getElementById('person-ltime-detaile'));
var myChartallTime = echarts.init(document.getElementById('person-alltime-detaile'));

var allTime = {
    tooltip : {
        trigger: 'axis'
    },
    legend: {
        data:['有效','已失效']
    },
     color: ['#00c79c', '#67ACF0','#ccc'],
    toolbox: {
        show : true,
        feature : {
            dataView : {show: true, readOnly: false},
            magicType : {show: true, type: ['line', 'bar']},
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    xAxis : [
        {
            type : 'category',
            data : ['正式卡','临时卡']
        }
    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    barGap:'1%',
     barWidth : 30,
    series : [
        {
            name:'有效',
            type:'bar',
            data:[10, 49],
        },
        {
            name:'已失效',
            type:'bar',
            data:[26, 59],
        }
    ]
};

var optionpNum =  {
    toolbox: {
        show:true,
        right: "30",
        top: "top",
        zlevel: 10,
        feature: {
            saveAsImage: {
                show: true
            },
            restore: {
                show: true
            },
            dataView: {show: true, readOnly: true},  
        }
    },
    color: ['#00c79c', '#67ACF0','#ccc'],
    title: {
        text: '',
        x: 'center',
        bottom: '20px',
        textStyle: {
            fontWeight: 'normal',
            fontSize: '14',
            color: '#8b98a7'
        }
    },
    tooltip: {
        trigger: 'item',
        formatter: "{b} : {c} "
    },
    series: [
        {
            top: '0px',
            type: 'pie',
            radius: '55%',
            center: ['50%', '46%'],
            data: [
                {value: 10, name: '正式卡'},
                {value: 20, name: '临时卡'},
            ],
            label: {
                normal: {
                    textStyle: {
                        fontSize: 14
                    },
                    formatter: function (param)
                    {
                        return param.name + '有' + param.value + '人' ;
                    }
                }
            },
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
}
var optionNum =  {
    toolbox: {
        show:true,
        right: "30",
        top: "top",
        zlevel: 10,
        feature: {
            saveAsImage: {
                show: true
            },
            restore: {
                show: true
            },
            dataView: {show: true, readOnly: true},  
        }
    },
    color: ['#00c79c', '#67ACF0','#ccc'],
    title: {
        text: '',
        x: 'center',
        bottom: '20px',
        textStyle: {
            fontWeight: 'normal',
            fontSize: '14',
            color: '#8b98a7'
        }
    },
    tooltip: {
        trigger: 'item',
        formatter: "{b} : {c})"
    },
    series: [
        {
            top: '0px',
            type: 'pie',
            radius: '55%',
            center: ['50%', '46%'],
            data: [
                {value: 10, name: '教师卡'},
                {value: 20, name: '学生卡'},
                {value: 25, name: '未知卡'},
            ],
            label: {
                normal: {
                    textStyle: {
                        fontSize: 14
                    },
                    formatter: function (param)
                    {
                        return param.name + '有' + param.value + '人' ;
                    }
                }
            },
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
}
var optionTime =  {
    toolbox: {
        show:true,
        right: "30",
        top: "top",
        zlevel: 10,
        feature: {
            saveAsImage: {
                show: true
            },
            restore: {
                show: true
            },
            dataView: {show: true, readOnly: true},  
        }
    },
    color: ['#00c79c', '#67ACF0','#ccc'],
    title: {
        text: '',
        x: 'center',
        bottom: '20px',
        textStyle: {
            fontWeight: 'normal',
            fontSize: '14',
            color: '#8b98a7'
        }
    },
    tooltip: {
        trigger: 'item',
        formatter: "{b} : {c}"
    },
    series: [
        {
            top: '0px',
            type: 'pie',
            radius: '55%',
            center: ['50%', '46%'],
            data: [
                {value: 10, name: '有效期'},
                {value: 20, name: '无效期'},
            ],
            label: {
                normal: {
                    textStyle: {
                        fontSize: 14
                    },
                    formatter: function (param)
                    {
                        return param.name + '有' + param.value + '天';
                    }
                }
            },
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
}
var optionlTime =  {
    toolbox: {
        show:true,
        right: "30",
        top: "top",
        zlevel: 10,
        feature: {
            saveAsImage: {
                show: true
            },
            restore: {
                show: true
            },
            dataView: {show: true, readOnly: true},  
        }
    },
    color: [ '#00c79c','#67ACF0','#ccc'],
    title: {
        text: '',
        x: 'center',
        bottom: '20px',
        textStyle: {
            fontWeight: 'normal',
            fontSize: '14',
            color: '#8b98a7'
        }
    },
    tooltip: {
        trigger: 'item',
        formatter: "{b} : {c}"
    },
    series: [
        {
            top: '0px',
            type: 'pie',
            radius: '55%',
            center: ['50%', '46%'],
            data: [
                {value: 10, name: '有效期'},
                {value: 20, name: '无效期'},
            ],
            label: {
                normal: {
                    textStyle: {
                        fontSize: 14
                    },
                    formatter: function (param)
                    {
                        return param.name + '有' + param.value + '天' ;
                    }
                }
            },
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
}


myChartpNum.setOption(optionpNum); //总体的领卡人数
myChartallTime.setOption(allTime);//总体的柱状图
myChartNum.setOption(optionNum);//正式卡的领取人数
myChartTime.setOption(optionTime);//正式卡的有效期
myChartlTime.setOption(optionlTime);//临时卡的有效期
$(window).resize(function ()
{
    myChartpNum.resize();
    myChartNum.resize();
    myChartTime.resize();
    myChartlTime.resize();
    myChartlTime.resize();
});
</script>
<?php $this->endBlock()?>
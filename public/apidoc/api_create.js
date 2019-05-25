var gaze = require("gaze");
var exec = require('child_process').exec;
var fs = require("fs");


function init(){
    fs.mkdirSync('./api');
    fs.mkdirSync('./doc');
    createConfigureFile();
    beginWatch();
}

function createConfigureFile(){
    var configure = {
        "name": "API接口-捣蛋熊猫",
        "version": "1.0.0",
        "title": "捣蛋熊猫接口文档",
        "url": "http://dd.ddxm661.com",
        "template": {
            "withCompare":false,
            "forceLanguage":"zh_cn",
            "withGenerator":false
        }
    };
    fs.writeFileSync('./api/apidoc.json',JSON.stringify(configure));
}

function beginWatch(){
    gaze('../../app/admin/*.php',function(error,watcher){
        this.on('all', function(event, filepath) {
            console.log(filepath + ' was ' + event);
            runGeneartion();
        })
    });
}

function runGeneartion(){
    var com = exec('apidoc -i ../../app/admin/ -o ./doc -t ./tpl')
    com.stdout.on('data', function (data) {
        console.log("生成Api->"+data);
    });

    com.stderr.on('data', function (data) {
        console.log('生成错误啦->' + data);
    });
}

if(fs.existsSync('./api') && fs.existsSync('./doc')){
    beginWatch();
}else{
    init();
}

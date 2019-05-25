<?php

/********************定义公共头start****************/
/**
 * 分页
 * @apiDefine ApiPageWithParam
 * @apiParam {number} page 请求分页
 * @apiParam {number} pageSize 分页条数
 * @apiSuccess {number} totalnumber 查询结果条数
 * @apiSuccess {number} totalPage 总分页数
 * @apiSuccess {number} pageSize 请求数量
 * @apiSuccess {number} currentPage 当前分页
 */






/********************定义公共头end****************/

/**
 * @apiGroup Api
 * @api / 返回码
 * @apiName 返回码集合
 * @apiVersion 1.0.0

 * @apiError {number} -1001 请求参数方式错误
 * @apiError {number} -1002 参数格式错误
 * @apiError {number} -1003 手机号未注册
 * @apiError {number} -1004 手机号格式错误
 * @apiError {number} -1005 手机号已注册
 * @apiError {number} -1006 失效令牌
 * @apiError {number} -1007 发送验证码时间不够1分钟
 * @apiError {number} -1008 验证码过期
 * @apiError {number} -1009 验证码错误
 * @apiError {number} -1010 注册失败
 * @apiError {number} -1011 获取令牌失败
 * @apiError {number} -1012 重置密码失败
 * @apiError {number} -1013 密码不能小于6位
 * @apiError {number} -1014 密码只能包含数字和字母
 * @apiError {number} -2000 用户未登陆
 * @apiError {number} -2004 操作失败
 * @apiError {number} -3000 搜索服务不可用
 * @apiError {number} -4000 请求错误
 * @apiError {number} -4001 服务错误
 * @apiError {number} -4004 数据已被删除
 **/
/********************公共信息start****************/


/**
 * @api {GET} /users/:id 获取用户信息
 * @apiGroup Test
 * @apiName testtestests
 * @apiVersion 1.0.0
 * @apiDescription 获取用户信息
 * @apiSuccess (200) {String} msg 信息
 * @apiSuccess (200) {int} code 0 代表无错误 1代表有错误
 * @apiSuccess (200) {String} name 真实姓名
 * @apiSuccess (200) {String} mobile 手机号
 * @apiSuccess (200) {String} birthday 生日
 * @apiSuccess (200) {String} email 邮箱
 * @apiSuccess (200) {String} summary 简介
 * @apiSuccess (200) {String} recommendCode 我的推荐码
 * @apiSuccess (200) {String} idCardNo 身份证号
 * @apiSuccess (200) {String} memberState 会员状态 0普通用户 1VIP 2账户冻结
 * @apiSuccess (200) {String} address 家庭住址
 * @apiSuccess (200) {String} money 账户现金
 * @apiSuccessExample {json} 返回样例:
 * {
 *   "code": 0,
 *   "msg": "",
 *   "name": "真实姓名",
 *   "mobile": 15808544477,
 *   "birthday": "1990-03-05",
 *   "email": "slocn@gamil.com",
 *   "summary": "简介",
 *   "recommendCode": "我的推荐码",
 *   "idCardNo": "身份证号",
 *   "memberState": 1,
 *   "address": "家庭住址",
 *   "money": "30.65"
 * }
 */


/**
 * @api {POST} /Comment/getListByShowType 根据展示类型获取评论
 * @apiVersion 3.2.0
 * @apiName getListByShowType
 * @apiGroup 评论
 * @apiUse ApiPageWithParam
 * @apiDescription 这是一个详细的描述
 * @apiParam (test) {String} [id=1] 文章Id 或是 (评论,回复) id
 * @apiParam {String} type=chenjing 评论类型 评论类型 [ 1=知识,2=百科,3=图集,4=惠发现 ,5=评论,6=回复,7视频,8音频,9手记 ]
 * @apiParam {String} showType 展示方式 [ 1=最新评论列表 ,2=回复最多评论列表, 3=点赞最多评论列表 ]
 * @apiSuccess {string} test  hehe
 * @apiParamExample {json} Request-Example:
 *     {
 *       "id": 4711
 *     }
 * @apiSuccessExample Success-Response:
 * {
 * "code": 0,
 * "msg": "成功",
 * "data": {}
 * }
 * @apiError (error){string} test hehe
 * @apiSampleRequest /test
 **/

/**
 * @api {POST} /Comment/getListByShowType 根据展示类型获取评论
 * @apiVersion 3.2.0
 * @apiName getListByShowType
 * @apiGroup Test
 * @apiParam {String} rule
 *
 * - 规则1：不能使用小数
 * - 规则2：不能相加
 */

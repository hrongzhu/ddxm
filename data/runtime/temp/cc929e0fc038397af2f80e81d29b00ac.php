<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:53:"themes/admin_simpleboot3/admin\member\add_update.html";i:1554867178;}*/ ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>添加或更新</title>
        <link rel="stylesheet" href="__STATIC__/pintuer/pintuer.css">
        <link href="/plugins/layer/2.4/skin/layer.css" rel="stylesheet">
    </head>
    <body>
    <div class="admin">
        <div class="panel-body">
        <div class="panel active">
        <div class="panel-head">详情</div>
        <br>
		<div class="panel body">
        <div class="form-x">
            <br>
            <input type="hidden" id="id" name="id" value="<?php echo (isset($id) && ($id !== '')?$id:0); ?>">
            <div class="form-group" id="f_1540542756230">
                <div class="label">
                    <label for="mobile">
                        手机号
                    </label>
                </div>
                <div class="field">
                    <?php if(empty($userInfo)): ?>
                        <input type="text" class="input" id="mobile" name="mobile" maxlength="11" value="<?php echo (isset($userInfo['mobile']) && ($userInfo['mobile'] !== '')?$userInfo['mobile']:''); ?>" data-validate="required:请填写内容" placeholder="输入手机号码">
        				<?php else: if($isAdmin==1): ?>
                            <input type="text" class="input" id="mobile" name="mobile" maxlength="11" value="<?php echo (isset($userInfo['mobile']) && ($userInfo['mobile'] !== '')?$userInfo['mobile']:''); ?>" data-validate="required:请填写内容" placeholder="输入手机号码">
        					<?php else: ?>
                            <input type="text" class="input" disabled="disabled" id="mobile" name="mobile" maxlength="11" value="<?php echo (isset($userInfo['mobile']) && ($userInfo['mobile'] !== '')?$userInfo['mobile']:''); ?>" data-validate="required:请填写内容" placeholder="输入手机号码">
        				<?php endif; endif; ?>
                </div>
            </div>
            <div class="form-group" id="f_1540543453998">
                <div class="label">
                    <label for="shop_code">
                        会员绑定门店
                    </label>
                </div>
                <div class="field">
                    <!--***************************userinfo为空时执行添加操作**************-->
        			<?php if(empty($userInfo)): if($isAdmin==1): ?>
            					<select class="input" id="shop_code" name="shop_code" data-validate="required:请选择,length#>=1:至少选择1项">
            						<?php foreach($shopInfo as $v): ?>
            							<option value="<?php echo $v['code']; ?>"><?php echo $v['code'].$v['name']; ?></option>
            						<?php endforeach; ?>
            					</select>
        					<?php else: ?>
            					<select class="input" id="shop_code" name="shop_code" data-validate="required:请选择,length#>=1:至少选择1项" disabled="disabled">
            						<?php foreach($shopInfo as $v): ?>
            							<option value="<?php echo $v['code']; ?>" <?php echo $shopId==$v['id']?"selected=selected":""; ?>><?php echo $v['code']; ?> <?php echo $v['name']; ?></option>
            						<?php endforeach; ?>
            					</select>
        					<input type="hidden" name="shop_code" value="<?php echo $v['code']; ?>">
        				<?php endif; else: ?>
            				<!--***************************userinfo为空时执行修改操作**************-->
            				<select class="input" id="shop_code" name="shop_code" data-validate="required:请选择,length#>=1:至少选择1项" <?php echo $isAdmin==1?"":"disabled=disabled"; ?>>
                				<option value="0" <?php if($userInfo['shop_code']['code'] == '0'): ?>selected<?php endif; ?>>未绑定</option>
                				<?php foreach($shopInfo as $v): ?>
                					<option value="<?php echo $v['code']; ?>" <?php echo $userInfo['shop_code']['code']==$v['code']?"selected=selected":""; ?> ><?php echo $v['code'].$v['name']; ?></option>
                				<?php endforeach; ?>
            				</select>
        			<?php endif; ?>
                </div>
            </div>
            <div class="form-group" id="f_1540542751389">
                <div class="label">
                    <label for="nickname">
                        昵称
                    </label>
                </div>
                <div class="field">
                    <input type="text" class="input" id="nickname" name="nickname" maxlength="50" value="<?php echo (isset($userInfo['nickname']) && ($userInfo['nickname'] !== '')?$userInfo['nickname']:''); ?>" data-validate="required:请填写昵称,nickname:请输入英文字母开头的字母、下划线、数字,length#<50:字数在0-50个"
                        placeholder="中文、英文字母字母、下划线、数字">
                </div>
            </div>
            <div class="form-group" id="f_1540542751389">
                <div class="label">
                    <label for="level_id">
                        会员等级
                    </label>
                </div>
                <div class="field">
                    <?php if(empty($userInfo)): ?>
        				<select class="input" id="level_id" name="level_id" data-validate="required:请选择,length#>=1:至少选择1项" <?php echo $isAdmin==1?"":"disabled=disabled"; ?>>
        					<?php foreach($levelInfo as $v): ?>
        						<option value="<?php echo $v['id']; ?>"><?php echo $v['level_name']; ?></option>
        					<?php endforeach; ?>
        				</select>
        				<?php else: ?>
        				<select class="input" id="level_id" name="level_id" data-validate="required:请选择,length#>=1:至少选择1项" <?php echo $isAdmin==1?"":"disabled=disabled"; ?> >
        					<?php foreach($levelInfo as $v): ?>
        						<option value="<?php echo $v['id']; ?>" <?php echo $userInfo['level_id']==$v['id']?"selected=selected":""; ?>>
        						<?php echo $v['level_name']; ?>
        						</option>
        					<?php endforeach; ?>
        				</select>
        			<?php endif; ?>
        			<input type="hidden" name="level_id" value="">
                </div>
            </div>
            <div class="form-group" id="f_1540544527135">
                <div class="label">
                    <label for="openid">
                        微信OPENID
                    </label>
                </div>
                <div class="field">
                    <input type="text" class="input" disabled id="openid" name="openid" maxlength="128" value="<?php echo (isset($userInfo['openid']) && ($userInfo['openid'] !== '')?$userInfo['openid']:'暂未绑定'); ?>" >
                </div>
            </div>
            <?php if($id): ?>
                <div class="form-group">
                    <div class="label">
                        <label for="score_server">
                            服务积分(竹子)
                        </label>
                    </div>
                    <div class="field input-inline clearfix">
                        <input type="text" disabled class="input" id="score_server" name="score_server" value="<?php echo (isset($userInfo['score_server']) && ($userInfo['score_server'] !== '')?$userInfo['score_server']:0); ?>"/>
                        <?php if($isAdmin==1): ?>
                            <button class="button edit-type" type="button" data-type = "1">
                                <span class="icon-edit text-blue"></span>调整
                            </button>
                        <?php endif; ?>
                    </div>
            	</div>
                <div class="form-group">
                    <div class="label">
                        <label for="score_item">
                            商品积分(笋子)
                        </label>
                    </div>
                    <div class="field input-inline clearfix">
                        <input type="text" disabled class="input" id="score_item" name="score_item" value="<?php echo (isset($userInfo['score_item']) && ($userInfo['score_item'] !== '')?$userInfo['score_item']:0); ?>"/>
                        <?php if($isAdmin==1): ?>
                            <button class="button edit-type" type="button" data-type = "2" >
                                <span class="icon-edit text-blue"></span>调整
                            </button>
                        <?php endif; ?>
                    </div>
            	</div>
                <div class="form-group" id="f_1540544527135">
                    <div class="label">
                        <label for="amount">
                            累积充值
                        </label>
                    </div>
                    <div class="field input-inline clearfix">
                        <input type="text" class="input" disabled="disabled" id="amount" name="amount" maxlength="10" value="<?php echo (isset($userInfo['amount']) && ($userInfo['amount'] !== '')?$userInfo['amount']:''); ?>" data-validate="required:请填写总金额,amount:累积充值金额,如：197.11,length#<12:不能超过十位数" placeholder="">
                        <?php if($isAdmin==1): ?>
                            <button class="button edit-type" type="button" data-type = "3">
                                <span class="icon-edit text-blue"></span>调整
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group" id="f_1540544527135">
                    <div class="label">
                        <label for="money">
                            储值余额
                        </label>
                    </div>
                    <div class="field input-inline clearfix">
                        <input type="text" class="input" disabled="disabled" id="money" name="money" value="<?php echo (isset($userInfo['money']) && ($userInfo['money'] !== '')?$userInfo['money']:''); ?>" data-validate="required:请填写总金额,amount:累积充值金额,如：197.11" placeholder="">
                        <?php if($isAdmin==1): ?>
                            <button class="button edit-type" type="button" data-type = "4">
                                <span class="icon-edit text-blue"></span>调整
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="form-group" id="f_1540544527135">
                <div class="label">
                    <label for="regtime">
                        注册时间
                    </label>
                </div>
                <div class="field">
                    <input type="text" class="input" disabled id="regtime" name="regtime"  value="<?php echo (isset($userInfo['regtime']) && ($userInfo['regtime'] !== '')?$userInfo['regtime']:''); ?>" >
                </div>
            </div>
            <?php if($isAdmin): ?>
                <div class="form-group" id="f_1540542803125">
                    <div class="label">
                        <label for="status">
                            状态设置
                        </label>
                    </div>
                    <div class="field">
                        <?php $checked0 = (isset($userInfo) && $userInfo['status'] == 1)?"checked='checked'":""; $checked1 = (isset($userInfo) && $userInfo['status'] == 0)?"checked='checked'":""; ?>
                        <div class="button-group radio">
                            <label class="button <?php echo !empty($checked0)?'active':''; ?>">
                                <input name="status" value="1" <?php echo $checked0; ?> type="radio" data-validate="required:请选择,length#>=1:至少选择1项"><span class="icon icon-check"></span> 启用
                            </label>
                            <label class="button <?php echo !empty($checked1)?'active':''; ?>">
                                <input name="status" value="0" <?php echo $checked1; ?> type="radio" data-validate="required:请选择,length#>=1:至少选择1项"><span class="icon icon-check"></span> 禁用
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="f_1540542803125">
                    <div class="label">
                        <label for="is_staff">
                            是否员工
                        </label>
                    </div>
                    <div class="field">
                        <?php $checked2 = (isset($userInfo) && $userInfo['is_staff'] == 1)?"checked='checked'":""; $checked3 = (isset($userInfo) && $userInfo['is_staff'] == 0)?"checked='checked'":""; ?>
                        <div class="button-group radio">
                            <label class="button <?php echo !empty($checked2)?'active':''; ?>">
                                <input name="is_staff" value="1" <?php echo $checked2; ?> type="radio" data-validate="required:请选择,length#>=1:至少选择1项"><span class="icon icon-check"></span> 是
                            </label>
                            <label class="button <?php echo !empty($checked3)?'active':''; ?>">
                                <input name="is_staff" value="0" <?php echo $checked3; ?> type="radio" data-validate="required:请选择,length#>=1:至少选择1项"><span class="icon icon-check "></span> 否
                            </label>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="form-button">
				<button id="save" class="button bg-main">提交</button>
			</div>
            <br>
        </div>
        </div>
        </div>
        </div>
    <div>
    </body>
    <script src="__TMPL__/public/assets/js/jquery-1.10.2.min.js"></script>
    <script src="/plugins/layer/2.4/layer.js"></script>
    <script src="__STATIC__/pintuer/pintuer.js"></script>
    <script src="/plugins/h-ui.admin/js/H-ui.admin.page.js"></script>
    <script src="__STATIC__/js/admin.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.edit-type').click(function(){
                let id = $('#id').val();
                let type = $(this).attr('data-type');
                let urls = '/admin/Member/updateScoreOrMoney/type/'+type+'/id/'+id;
                layer_show('调整',urls);
            })
            $('input[name="level_id"]').val($('select[name="level_id"] option:selected').val())
            $('select[name="shop_code"]').on('change',function(){
                $('input[name="shop_code"]').val($(this).find('option:selected').val())
            })
            $('select[name="level_id"]').on('change',function(){
                $('input[name="level_id"]').val($(this).find('option:selected').val())
            })

            $('#save').click(function () {
                var data = {
                    id:0,
                    mobile:'',
                    shop_code:'',
                    nickname:'',
                    level_id:0,
                    status:0,
                    is_staff:0
                };
                data.id = $('#id').val();
                data.nickname = $('#nickname').val();
                data.mobile = $('#mobile').val();
                data.shop_code = $('#shop_code').val();
                data.level_id = $('#level_id').val();
                data.status = $('input:radio[name=status]:checked').val();
                data.is_staff = $('input:radio[name=is_staff]:checked').val();

                if (data.nickname === ''){
                    layer.msg('请输入会员姓名',{icon:0,time:1500});
                    return false;
                }
                if (data.mobile == ''){
                    layer.msg('请填写手机号',{icon:0,time:1500});
                    return false;
                }
                $.post("<?php echo url('Member/saveUser'); ?>",{data},function (res) {
                    if (res.code === 200){
                        layer.msg(res.msg,{icon:1,time:2000},function () {
                            window.parent.location.reload();
                            // parent.layer.close(parent.layer.getFrameIndex(window.name));
                        });
                    }else if(res.code === 300){
                        $('#mobile').val(res.data.mobile)
                        $('#nickname').val(res.data.nickname).attr('disabled',true)
                        $('select[name="shop_code"]').html('<option value="'+res.data.shop_code.code+'" selected="selected">'+res.data.shop_code.code+res.data.shop_code.name+'</option>');
                        let level_name = $("select option[value=" + res.data.level_id + "]").text();;
                        $('select[name="level_id"]').html('<option value="'+res.data.level_id+'" selected="selected">'+level_name+'</option>');
                        layer.msg(res.msg,{icon:2,closeBtn:1,time:false,end:function(){
                                window.location.reload()
                            }})
                    }else{
                        layer.msg(res.msg,{icon:0,time:1500});
                    }
                });
            });
        });
    </script>
</html>

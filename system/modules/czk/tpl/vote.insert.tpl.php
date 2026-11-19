<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
        <title></title>
        <!-- 高级骨架屏系统样式 -->
        <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/advanced-skeleton.css" type="text/css">
        <!-- 统一后台页面美化样式（参考webcfg标准） -->
        <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/unified-backend-style.css" type="text/css">
        <!-- 后台通用页面增强样式 -->
        <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/common-page-enhancements.css" type="text/css">
        <!-- 通知消息系统样式 -->
        <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/notification-system.css" type="text/css">
        <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
            <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
                <link href="<?php echo G_TEMPLATES_CSS; ?>/vote/table_form.css" rel="stylesheet" type="text/css" />
                <link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css"> 
                    <script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
                    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<style>
	.table_form tr {border-width: 0px 1px 0px 1px;border-style: solid;border-color: #E2E2E2;}
	.btnorder{
		border:1px solid #E2E2E2;
	
	}
	/* 针对Chrome浏览器 */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
 
/* 针对Firefox浏览器 */
input[type="number"] {
    -moz-appearance: textfield;
}
#sckh,#scmm {
    background-color: #3C8DBC;
    display: inline-block;
    padding: 0px 15px;
    cursor: pointer;
    color: #fff;
    border-radius: 5px;
    height: 29px;
    line-height: 29px;
}
.input-text {
    width: 350px;
}
</style>
                    </head>
                    <body>
                        <style type="text/css">
                            html{_overflow-y:scroll}
                        </style>

                        <div class="header lr10">
                            <?php echo $this->headerment(); ?>
                        </div>
                        <div class="pad_10" style="margin: 10px;">
                            <form action="#" method="post" enctype="multipart/form-data" id="myform">
                                <table class="table_form" style="border-width: 1px 0px 0px 0px;">

                                    <tr>
                                        <!--<th style="text-align:left;" width="10">卡号</th>-->
                                        <th style="display:flex;width: 100%;align-items: center;white-space: nowrap;gap: 10px;box-sizing: border-box;" width="10"> <?php $this->lan("card_start");?>
											<input type="text" maxlength="5" onKeyUp="value=value.replace(/[^a-zA-Z]/g,'')" id="kh-t" placeholder="<?php $this->lan('tip5');?>" class="input-text" style="width:150px;height: 24px;vertical-align: baseline;">
											<a id="sckh" href="javascript:void(0);"  > <?php $this->lan("tips1");?></a>
										</th>
                                        <th style="text-align:center;" width="10"><a id="scmm" href="javascript:void(0);"  > <?php $this->lan("tips2");?></a></th>
                                        <!--<th style="text-align:left;">密码</th>-->
                                        <th style="text-align:center;" colspan=2> <?php $this->lan("Par_value");?></th>
                                        <th style="text-align:center;" width="200" > <?php $this->lan("remarks");?></th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" id="ka0"  maxlength="12" placeholder="<?php $this->lan("tips3");?>" name="czknum[]"size="30" class="input-text"></td>
                                        <td><input type="text" id="mm0"  maxlength="12" placeholder="<?php $this->lan("tips4");?>" name="password[]"size="30" class="input-text"></td>
                                        
										<td>
											<!--<input type="number" min="1" max="99999" list="myOptions1" value="" name="mianzhi[]" class="inputext"/>-->
											<!--<datalist id="myOptions1" name="mianzhi[]">-->
											<!--	<option value="1">1</option>-->
											<!--	<option value="10">10</option>-->
											<!--	<option value="50">50</option>-->
											<!--	<option value="100">100</option>-->
											<!--</datalist>-->
											<select name="mianzhi[]" style="width: 100%;">
												<option value="1">1</option>
												<option value="10">10</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select>
										</td>
                                        <td>
											<select name="type[]">
												<option value="1"><?php $this->lan("disposable");?></option>
												<option value="2"><?php $this->lan("experience");?></option>
											</select>
										</td>
										<td><input type="text"  placeholder="<?php $this->lan("remarks");?>" name="desc[]"size="30" class="input-text" /></td>
                                    </tr>		
                                    <tr>
                                        <td><input type="text" id="ka1" placeholder="<?php $this->lan("tips3");?>" name="czknum[]"size="30" class="input-text"></td>
                                        <td><input type="text" id="mm1" placeholder="<?php $this->lan("tips4");?>" name="password[]"size="30" class="input-text"></td>
                                        <td>
											<select name="mianzhi[]" style="width: 100%;">
												<option value="1">1</option>
												<option value="10">10</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select>
										</td>
										<td>
											<select name="type[]">
												<option value="1"><?php $this->lan("disposable");?></option>
												<option value="2"><?php $this->lan("experience");?></option>
											</select>
										</td>
										<td><input type="text"  placeholder="<?php $this->lan("remarks");?>" name="desc[]"size="30" class="input-text" /></td>

                                    </tr>		
                                    <tr>
                                        <td><input type="text" id="ka2" placeholder="<?php $this->lan("tips3");?>" name="czknum[]"size="30" class="input-text"></td>
                                        <td><input type="text" id="mm2" placeholder="<?php $this->lan("tips4");?>" name="password[]"size="30" class="input-text"></td>
                                        <td>
											<select name="mianzhi[]" style="width: 100%;">
												<option value="1">1</option>
												<option value="10">10</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select>
										</td>
										<td>
											<select name="type[]">
												<option value="1"><?php $this->lan("disposable");?></option>
												<option value="2"><?php $this->lan("experience");?></option>
											</select>
										</td>
										<td><input type="text"  placeholder="<?php $this->lan("remarks");?>" name="desc[]"size="30" class="input-text" /></td>

                                    </tr>		
                                    <tr>
                                        <td><input type="text" id="ka3" placeholder="<?php $this->lan("tips3");?>" name="czknum[]"size="30" class="input-text"></td>
                                        <td><input type="text" id="mm3" placeholder="<?php $this->lan("tips4");?>" name="password[]"size="30" class="input-text"></td>
                                        <td>
											<select name="mianzhi[]" style="width: 100%;">
												<option value="1">1</option>
												<option value="10">10</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select>
										</td>
										<td>
											<select name="type[]">
												<option value="1"><?php $this->lan("disposable");?></option>
												<option value="2"><?php $this->lan("experience");?></option>
											</select>
										</td>
										<td><input type="text"  placeholder="<?php $this->lan("remarks");?>" name="desc[]"size="30" class="input-text" /></td>
                                    </tr>		
                                    <tr>
                                        <td><input type="text" id="ka4" placeholder="<?php $this->lan("tips3");?>" name="czknum[]"size="30" class="input-text"></td>
                                        <td><input type="text" id="mm4" placeholder="<?php $this->lan("tips4");?>" name="password[]"size="30" class="input-text"></td>
                                        <td>
											<select name="mianzhi[]" style="width: 100%;">
												<option value="1">1</option>
												<option value="10">10</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select>
										</td>
										<td>
											<select name="type[]">
												<option value="1"><?php $this->lan("disposable");?></option>
												<option value="2"><?php $this->lan("experience");?></option>
											</select>
										</td>
										<td><input type="text"  placeholder="<?php $this->lan("remarks");?>" name="desc[]"size="30" class="input-text" /></td>
                                    </tr>		
                                    <tr>
                                        <td><input type="text" id="ka5" placeholder="<?php $this->lan("tips3");?>" name="czknum[]"size="30" class="input-text"></td>
                                        <td><input type="text" id="mm5" placeholder="<?php $this->lan("tips4");?>" name="password[]"size="30" class="input-text"></td>
                                        <td>
											<select name="mianzhi[]" style="width: 100%;">
												<option value="1">1</option>
												<option value="10">10</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select>
										</td>
										<td>
											<select name="type[]">
												<option value="1"><?php $this->lan("disposable");?></option>
												<option value="2"><?php $this->lan("experience");?></option>
											</select>
										</td>
										<td><input type="text"  placeholder="<?php $this->lan("remarks");?>" name="desc[]"size="30" class="input-text" /></td>
                                    </tr>		
                                    <tr>
                                        <td><input type="text" id="ka6" placeholder="<?php $this->lan("tips3");?>" name="czknum[]"size="30" class="input-text"></td>
                                        <td><input type="text" id="mm6" placeholder="<?php $this->lan("tips4");?>" name="password[]"size="30" class="input-text"></td>
                                        <td>
											<select name="mianzhi[]" style="width: 100%;">
												<option value="1">1</option>
												<option value="10">10</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select>
										</td>
										<td>
											<select name="type[]">
												<option value="1"><?php $this->lan("disposable");?></option>
												<option value="2"><?php $this->lan("experience");?></option>
											</select>
										</td>
										<td><input type="text"  placeholder="<?php $this->lan("remarks");?>" name="desc[]"size="30" class="input-text" /></td>
                                    </tr>		
                                    <tr>
                                        <td><input type="text" id="ka7" placeholder="<?php $this->lan("tips3");?>" name="czknum[]"size="30" class="input-text"></td>
                                        <td><input type="text" id="mm7" placeholder="<?php $this->lan("tips4");?>" name="password[]"size="30" class="input-text"></td>
                                        <td>
											<select name="mianzhi[]" style="width: 100%;">
												<option value="1">1</option>
												<option value="10">10</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select>
										</td>
										<td>
											<select name="type[]">
												<option value="1"><?php $this->lan("disposable");?></option>
												<option value="2"><?php $this->lan("experience");?></option>
											</select>
										</td>
										<td><input type="text"  placeholder="<?php $this->lan("remarks");?>" name="desc[]"size="30" class="input-text" /></td>
                                    </tr>		
                                    <tr>
                                        <td><input type="text" id="ka8" placeholder="<?php $this->lan("tips3");?>" name="czknum[]"size="30" class="input-text"></td>
                                        <td><input type="text" id="mm8" placeholder="<?php $this->lan("tips4");?>" name="password[]"size="30" class="input-text"></td>
                                        <td>
											<select name="mianzhi[]" style="width: 100%;">
												<option value="1">1</option>
												<option value="10">10</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select>
										</td>
										<td>
											<select name="type[]">
												<option value="1"><?php $this->lan("disposable");?></option>
												<option value="2"><?php $this->lan("experience");?></option>
											</select>
										</td>
										<td><input type="text"  placeholder="<?php $this->lan("remarks");?>" name="desc[]"size="30" class="input-text" /></td>
                                    </tr>		
                                    <tr>
                                        <td><input type="text" id="ka9" placeholder="<?php $this->lan("tips3");?>" name="czknum[]"size="30" class="input-text"></td>
                                        <td><input type="text" id="mm9" placeholder="<?php $this->lan("tips4");?>" name="password[]"size="30" class="input-text"></td>
                                        <td>
											<select name="mianzhi[]" style="width: 100%;">
												<option value="1">1</option>
												<option value="10">10</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select>
										</td>
										<td>
											<select name="type[]">
												<option value="1"><?php $this->lan("disposable");?></option>
												<option value="2"><?php $this->lan("experience");?></option>
											</select>
										</td>
										<td><input type="text"  placeholder="<?php $this->lan("remarks");?>" name="desc[]"size="30" class="input-text" /></td>
                                    </tr>		
                                    <tr>
                                        <td><input type="text" id="ka10" placeholder="<?php $this->lan("tips3");?>" name="czknum[]"size="30" class="input-text"></td>
                                        <td><input type="text" id="mm10" placeholder="<?php $this->lan("tips4");?>" name="password[]"size="30" class="input-text"></td>
                                        <td>
											<select name="mianzhi[]" style="width: 100%;">
												<option value="1">1</option>
												<option value="10">10</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select>
										</td>
										<td>
											<select name="type[]">
												<option value="1"><?php $this->lan("disposable");?></option>
												<option value="2"><?php $this->lan("experience");?></option>
											</select>
										<td><input type="text"  placeholder="<?php $this->lan("remarks");?>" name="desc[]"size="30" class="input-text" /></td>
										</td>
                                    </tr>		
                                    <tr style="border: 0;">
										<td style="border: 0;">
										    <input type="submit" style="margin-left: -10px;" class="batchDel button btnorder" name="submit" id="submit" value="<?php $this->lan("Submit");?> "></td>
									</tr>
                                </table>
                            </form>
                        </div>
                    </body>
                    </html>

                    <script language="javascript" type="text/javascript">
                        function AdsType(adstype) {
                            $('#SizeFormat').css('display', 'none');
                            if (adstype == '0') {

                            } else if (adstype == '1') {
                                $('#SizeFormat').css('display', '');
                            }
                        }
                        $('#AlignBox').click(function () {
                            if ($('#AlignBox').attr('checked')) {
                                $('#PaddingLeft').attr('disabled', true);
                                $('#PaddingTop').attr('disabled', true);
                            } else {
                                $('#PaddingLeft').attr('disabled', false);
                                $('#PaddingTop').attr('disabled', false);
                            }
                        });
                    </script>

                    <script language="javascript">
                        var i = 1;
                        function add_option() {
                            //var i = 1;
                            var htmloptions = '';
                            htmloptions += '<div id=' + i + '><span><br><input type="text" name="option[]" size="40" msg="必填" value="" class="input-text"/><input type="button" value="删除"  onclick="del(' + i + ')" class="button"/><br></span></div>';
                            $(htmloptions).appendTo('#new_option');
                            var htmloptions = '';
                            i = i + 1;
                        }
                        function del(o) {
                            $("div [id=\'" + o + "\']").remove();
                        }

                        /* function load_file_list(id) {
                         $.getJSON('?m=admin&c=category&a=public_tpl_file_list&style='+id+'&module=vote&templates=vote_tp&name=vote_subject&pc_hash='+pc_hash, function(data){$('#show_template').html(data.vote_tp_template);});
                         } */
                    </script>
                        <script type="text/javascript">
                             //生成卡号
                            $('#sckh').click(function () {
								var kt = $("#kh-t").val()
								console.log()
								$.getJSON("/czk/vote_admin/sckh", function(data){
								  $.each(data, function(i,item){
                                        $(data).each(function (i, val) {
											if(kt.length){
												val = kt + val.substring(kt.length)
											}
                                            $('#ka' + i).val(val);
                                        });
								  });
								});
                            });
                            //生成密码
                            $('#scmm').click(function () {
								$.getJSON("/czk/vote_admin/sckh", function(data){
								  $.each(data, function(i,item){
                                        $(data).each(function (i, val) {
                                            $('#mm' + i).val(val);
                                        });
								  });
								});
                            });
							
					
							$('.input-text').on('change', function() {
                                var regex = /^[a-z0-9]+$/;
                                var val = $(this).val();
								console.log(123,val);
                                if (!regex.test(val)) {
                                //   $(this).val(val.replace(/[^a-z0-9]/, ''));
								//   console.log('请输入正确');
								  $(this).val('');
                                 }
                            });
							$('.inputext').on('change', function() {
                                var val = $(this).val();
								console.log(456,val);
								if(Number(val)<1){
									$(this).val('');
								}
								if(Number(val)>99999){
									$(this).val('');
								}
                            });

                        </script>
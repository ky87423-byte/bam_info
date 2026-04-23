<input type="hidden" name="param_opt_1"     value="payment_process" />
<input type="hidden" name="param_opt_2"     value="<?php echo $this->get_html($arr['pno']);?>" />
<input type="hidden" name="param_opt_3"     value="<?php echo $_SERVER['REQUEST_URI'];?>" />
<!-- 주문정보 세팅 -->
<input type="hidden" name="ordr_idxx" value="<?php echo $this->get_html($arr['pay_oid']);?>" maxlength="40" />
<input type="hidden" name="good_name" value="<?php echo $this->get_html($arr['gname']);?>" />
<input type="hidden" name="good_mny" value="<?php echo intval($arr['price']);?>" maxlength="9" />
<input type="hidden" name="buyr_name" value="<?php echo $this->get_html($arr['mb_name']);?>" />
<input type="hidden" name="buyr_tel2" value="<?php echo $this->get_html($arr['mb_phone']);?>" />
<input type="hidden" name="buyr_mail" value="<?php echo $this->get_html($arr['mb_email']);?>" />
<!-- 신용카드 -->
<input type="hidden" name="pay_method" value="<?php echo $this->kcp_api[$_POST['pay_methods']];?>" />
<!-- 가맹점 정보 설정-->
<input type="hidden" name="site_cd" value="<?php echo $this->pg_config[$this->pg]['id'];?>" />
<input type="hidden" name="site_name" value="<?php echo $this->get_html($env['site_name']);?>" />
<!-- 인증데이터 처리-->
<input type="hidden" name="res_cd" value=""/>
<input type="hidden" name="res_msg" value=""/>
<input type="hidden" name="enc_info" value=""/>
<input type="hidden" name="enc_data" value=""/>
<input type="hidden" name="ret_pay_method" value=""/>
<input type="hidden" name="tran_cd" value=""/>
<input type="hidden" name="use_pay_method" value=""/>
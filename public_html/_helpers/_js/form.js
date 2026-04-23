/**
* /application/_helpers/_js/form.js
* @author Netfu
* @since 2012/04/22
* @last update 2014/04/28
* @Module v4.0 ( SNOW )
* @Brief :: Form Validate Script
* @Comment :: Form 검증 및 필드 확인 Javascript
*/

/// 에러메시지 포멧 정의 ///
var NO_BLANK = "{name+을를} 입력하여 주십시오.";
var NO_CHECK = "{name+을를} 선택하여 주십시오.";
var NOT_VALID = "{name+이가} 올바르지 않습니다.";
var TOO_LONG = "{name}의 길이가 초과되었습니다. (최대 {maxbyte}바이트)";
var SPACE = (navigator.appVersion.indexOf("MSIE")!=-1) ? "          " : "";

/// 스트링 객체에 메소드 추가 ///
String.prototype.trim = function(str) { 
	str = this != window ? this : str; 
	return str.replace(/^\s+/g,'').replace(/\s+$/g,''); 
}
String.prototype.text_trim = function(){
   return this.replace(/^\s+|\s+$/g, "");
 }
 

String.prototype.hasFinalConsonant = function(str) {
	str = this != window ? this : str; 
	var strTemp = str.substr(str.length-1);
	return ((strTemp.charCodeAt(0)-16)%28!=0);
}

String.prototype.bytes = function(str) {
	str = this != window ? this : str;
	var len = 0;
	for(var j=0; j<str.length; j++) {
		var chr = str.charAt(j);
		len += (chr.charCodeAt() > 128) ? 2 : 1
	}
	return len;
}

String.prototype.number_format=function(){
	var num = this.replace(/,/g,'');
	return num.replace(/(\d)(?=(?:\d{3})+(?!\d))/g,'$1,');
}

Array.prototype.shuffle = function() { 
	return this.concat().sort(function() {
		return Math.random() - Math.random();
	});
}

function in_array(value, array, similar) {
	for(var i=0; i<array.length; i++) {
		if(similar==true) {
			if(value.indexOf(array[i]) != -1) return true; // 비슷한 값
		} else {
			if(array[i]==value) return true; // 동일한 값
		}
	}
	return false;
} 

function validate(form, skip) {
	for (var i=0; i<form.elements.length; i++) {
		var el = form.elements[i];
		if (el.tagName == "FIELDSET") continue;
		if(skip && in_array(el.name, skip.split('|'), true) === true) continue;	// 추가
		if(el.type.toLowerCase() != "file" && el.value) el.value = el.value.trim();		// 수정 :: 파폭 보안 문제

		var _type = $(el).attr("type");
		if(_type=='editor') {
			//el.value = _editor_use[el.name].outputBodyHTML();
			//var _content = _editor_use[el.name].trimSpace(el.value.replace(/(<([^>]+)>)/gi, ""));
			var _content = _editor_use[el.name].outputBodyHTML();
		}

		var PATTERN = el.getAttribute("PATTERN");
		var minbyte = el.getAttribute("MINBYTE");
		var maxbyte = el.getAttribute("MAXBYTE");
		var minval = el.getAttribute("MINVAL");
		var maxval = el.getAttribute("MAXVAL");
		var option = el.getAttribute("OPTION");
		var match = el.getAttribute("MATCHING"); // 수정 :: Prototype JS 와 충돌하여 'MATCH' 에서 'MATCHING' 으로 변경
		var glue = el.getAttribute("GLUE");
		var unit = el.getAttribute("UNIT");
		var or = el.getAttribute("OR");
		var message = el.getAttribute("message");
		if(unit == null) unit = '';

		if (el.getAttribute("needed") != null) {
			var ERR_MSG = (el.getAttribute("MESSAGE") != null) ? el.getAttribute("MESSAGE") : null;
			if ((el.type.toLowerCase() == "radio" || el.type.toLowerCase() == "checkbox") && !checkMultiBox(el)) return (ERR_MSG) ? doError(el,ERR_MSG) : doError(el,NO_CHECK);
			if (el.tagName.toLowerCase() == "select" && (el.value == null || el.value == "")) {
				return (ERR_MSG) ? doError(el,ERR_MSG) : doError(el,NO_CHECK);
			}
			if (el.value == null || el.value == "" ) {
				if(el.tagName.toLowerCase()!='textarea' || _type=='hidden' || (el.tagName.toLowerCase()=='textarea' && _type!='editor')) {
					return (ERR_MSG) ? doError(el,ERR_MSG) : doError(el,NO_BLANK);
				}
			}
			if(_type=='editor' && _editor_use[el.name] && !_content) {
				return doError(el,NO_BLANK);
			}
		}

		if (minbyte != null && el.value != "" && el.value.bytes() < parseInt(minbyte)) {
			if(unit=='') unit = "바이트";
			return doError(el,"{name+은는} 최소 "+minbyte+" "+unit+" 이상 입력해야 합니다.");
		}
		if (maxbyte != null && el.value != "" && el.value.bytes() > parseInt(maxbyte)) {
			if(unit=='') unit = "바이트";
			return doError(el,"{name+은는} 최대 "+maxbyte+" "+unit+" 이하로 입력해야 합니다.");
		}
		if (minval != null && el.value != "" && el.value < parseInt(minval)) return doError(el,"{name+은는} 최저 "+minval+" "+unit+" 이상 입력해야 합니다.");
		if (maxval != null && el.value != "" && el.value > parseInt(maxval)) return doError(el,"{name+은는} 최고 "+maxval+" "+unit+" 이하로 입력해야 합니다.");
		if (PATTERN != null && el.value != "" && !PATTERN(el,pattern)) return false;
		if (match != null && (el.value != form.elements[match].value)) return doError(el,"{name+이가} 일치하지 않습니다.");
		if (or != null && (el.value == null || el.value == "") && (form.elements[or].value==null || form.elements[or].value == "")) {
			var name2 = (hname = form.elements[or].getAttribute("HNAME")) ? hname : form.elements[or].getAttribute("NAME");
			return doError(el,"{name+} 또는 "+name2+" 중 하나는 입력해야 합니다.");
		}
		if (option != null && el.value != "") {
			if (el.getAttribute('SPAN') != null) {
				var _value = new Array();
				for (span=0; span<el.getAttribute('SPAN');span++ ) _value[span] = form.elements[i+span].value;
				var value = _value.join(glue == null ? '' : glue);
				if (!funcs[option](el,value)) return false;
			} else {
				try{
					if (!funcs[option](el)) return false;
				} catch(e) {
					//
				}
			}
		}
	}

	return true;
}

function josa(str,tail) {
	return (str.hasFinalConsonant()) ? tail.substring(0,1) : tail.substring(1,2);
}

function checkMultiBox(el) {
	var obj = document.getElementsByName(el.name);
	for (var i=0; i<obj.length; i++) if(obj[i].checked==true) return true;
	return false;
}

function doError(el,type,action) {
	var _type = $(el).attr("type");
	var pattern = /{([a-zA-Z0-9_]+)\+?([가-힝]{2})?}/;
	var name = (hname = el.getAttribute("HNAME")) ? hname : el.getAttribute("NAME");
	pattern.exec(type);
	var tail = (RegExp.$2) ? josa(eval(RegExp.$1),RegExp.$2) : "";
	try {
		var tail = (RegExp.$2) ? josa(eval(RegExp.$1),RegExp.$2) : '';
		alert((type.replace(pattern,eval(RegExp.$1) + tail)));
	} catch(e) { // checkbox 에서 오류가 발생할 가능성이 높다
		var message = el.getAttribute("message");
		if(message!=null) alert(message);
		else doError(el,NO_CHECK);
		return false;
	}
	try{
		if (action == "sel") el.select();
		else if (action == "del")	el.value = "";
		if (el.getAttribute("NOFOCUS") == null) el.focus();
		if(el.getAttribute("SETFOCUS") != null && el.getAttribute("SETFOCUS") !='') el.form.elements[el.getAttribute("SETFOCUS")].focus();
		if(_type=='editor') {
			// : cheditor는 어떻게 focus해야하나..
			_editor_use[el.name].editArea.focus();
			//_editor_use[el.name].returnFalse();
		}
	} catch(e){
		return false;
	}

	return false;
}

/// 특수 패턴 검사 함수 매핑 ///
var funcs = new Array();
funcs['domain'] = isValidDomain;
funcs['email'] = isValidEmail;
funcs['hphone'] = isValidHPhone;
funcs['phone'] = isValidPhone;
funcs['tel'] = isValidTel;
funcs['userid'] = isValidUserid;
funcs['userpw'] = isValidUserpw;
funcs['number'] = isNumeric;
funcs['float'] = isFloat;
funcs['engonly'] = alphaOnly;
funcs['engnumonly'] = alphanumberOnly;
funcs['jumin'] = isValidJumin;
funcs['bizno'] = isValidBizNo;
funcs['image'] = isValidImage;

/// 패턴 검사 함수들 ///
function isValidDomain(el,value) {
	var value = value ? value : el.value;
	var pattern = /^[_a-zA-Z가-힝0-9-]+\.[a-zA-Z가-힝0-9-\.]+[a-zA-Z]+$/;
	return (pattern.test(value)) ? true : doError(el,NOT_VALID);
}

function isValidEmail(el,value) {
	var value = value ? value : el.value;
	var pattern = /^[_a-zA-Z0-9-\.]+@[\.a-zA-Z0-9-]+\.[a-zA-Z]+$/;
	return (pattern.test(value)) ? true : doError(el,NOT_VALID);
}

function isValidUserid(el) {
	var pattern = /^[a-z]{1}[a-z0-9_]{4,19}$/;
	return (pattern.test(el.value)) ? true : doError(el,"\n죄송합니다. 입력하신 아이디는 입력규칙에 어긋나므로 사용하실 수 없습니다.\n\n{name+은는} 영문자로 시작하는 5~20자의 영문 소문자와 숫자의 조합만 사용할 수 있습니다.");
}

function isValidUserpw(el) {
	var pattern = /^[a-zA-Z0-9_.]{7,19}$/;
	var pattern = /^(?=.*[a-zA-Z])(?=.*[0-9]).{7,19}$/;
	var pattern = /^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*\(\)\-\=_\+\[\]\{\}\\\|;':",.//<>\?])[a-zA-Z0-9!@#$%^&*\(\)\-\=_\+\[\]\{\}\\\|;':",.//<>\?]{5,20}$/;

	var num = el.value.search(/[0-9]/g);
	var eng = el.value.search(/[a-z]/ig);
	var spe = el.value.search(/[`~!@#$%^&*\(\)\-\=_\+\[\]\{\}\\\|;':",.//<>\?]/gi);
	var length = el.value.length>=5 && el.value.length<=20 ? 1 : 0;

	return (((num >= 0 && eng >= 0) || (eng >= 0 && spe >= 0) || (spe >= 0 && num >= 0)) && length>0) ? true : doError(el,"\n죄송합니다. 입력하신 비밀번호는 입력규칙에 어긋나므로 사용하실 수 없습니다."+SPACE+"\n\n{name+은는} 5~20자의 영문 소문자와 숫자의 조합만 사용할 수 있습니다.");
}

function hasHangul(el) {
	var pattern = /[가-힝]/;
	return (pattern.test(el.value)) ? true : doError(el,"{name+은는} 반드시 한글을 포함해야 합니다.");
}

function alphaOnly(el) {
	var pattern = /^[a-zA-Z]+$/;
	return (pattern.test(el.value)) ? true : doError(el,NOT_VALID);
}

function alphanumberOnly(el) {
	var pattern = /^[a-zA-Z0-9]+$/;
	return (pattern.test(el.value)) ? true : doError(el,"{name+은는} 반드시 영문이나 숫자로만 입력해야합니다.");
}

function isNumeric(el) {
	var pattern = /^[0-9]+$/;
	return (pattern.test(el.value)) ? true : doError(el,"{name+은는} 반드시 숫자로만 입력해야 합니다.");
}

function isFloat(el) {
	var pattern = /^[0-9]+(\.[0-9]{1,4})?$/;
	return (pattern.test(el.value)) ? true : doError(el,"{name+은는} 반드시 정수 또는 소수 넷째 자리까지만 입력해야 합니다.");
}

function isValidImage(el) {
	var pattern = /(.+)(gif|jpeg|jpg|png)+$/i;
	return (pattern.test(el.value)) ? true : doError(el,"{name+은는} 이미지 형식만 가능합니다.");
}

function isValidJumin(el,value) {
    var pattern = /^([0-9]{6})-?([0-9]{7})$/; 
	var num = value ? value : el.value;
    if (!pattern.test(num)) return doError(el,NOT_VALID); 
    num = RegExp.$1 + RegExp.$2;

	var sum = 0;
	var last = num.charCodeAt(12) - 0x30;
	var bases = "234567892345";
	for (var i=0; i<12; i++) {
		if (isNaN(num.substring(i,i+1))) return doError(el,NOT_VALID);
		sum += (num.charCodeAt(i) - 0x30) * (bases.charCodeAt(i) - 0x30);
	}
	var mod = sum % 11;
	return ((11 - mod) % 10 == last) ? true : doError(el,NOT_VALID);

	/* 상위 계산방식에 걸리는 주민등록번호가 있을 경우에 아래와 같이 처리
	var num = value ? value : el.value;
	num = num.replace(/[^0-9]/g,'');
	num = num.substr(0,13);
	if(num.length<13) doError(el, NOT_VALID);
	else {
		num = num.replace(/([0-9]{6})([0-9]{7}$)/,"$1-$2"); 
		el.value = num;
		return true;
	}
	*/
}

function isValidBizNo(el, value) { 
    var pattern = /([0-9]{3})-?([0-9]{2})-?([0-9]{5})/; 
	var bizID = value ? value : el.value;
    if (!pattern.test(bizID)) return doError(el,NOT_VALID); 
    bizID = RegExp.$1 + RegExp.$2 + RegExp.$3;
	var checkID = new Array(1, 3, 7, 1, 3, 7, 1, 3, 5, 1);
	var i, Sum=0, c2, remander;
	for (i=0; i<=7; i++) Sum += checkID[i] * bizID.charAt(i);

	c2 = "0" + (checkID[8] * bizID.charAt(8));
	c2 = c2.substring(c2.length - 2, c2.length);
	Sum += Math.floor(c2.charAt(0)) + Math.floor(c2.charAt(1));
	remander = (10 - (Sum % 10)) % 10 ;
	if (Math.floor(bizID.charAt(9)) != remander) {
		return doError(el,NOT_VALID);
	}else{
		return true;
	}

}

function isValidPhone(el,value) {
	var pattern = /^([0]{1}[0-9]{1,2})-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/;
	var num = value ? value : el.value;
	if (pattern.exec(num)) {				// 2007-09-30 전화번호 추가(03, 067) by 이창우
		var phones = new Array("020","021","022","023","024","025","026","027","028","029","030","034","035","036","037","038","039","02","03","031","032","033","041","042","043","051","052","053","054","055","061","062","063","064","067", "070", "060");
		if(in_array(RegExp.$1, phones, false)) {
			if(!el.getAttribute('SPAN')) el.value = RegExp.$1 + "-" + RegExp.$2 + "-" + RegExp.$3;
			return true;
		}
	}
	return doError(el,NOT_VALID);
}

function isValidHPhone(el,value, flag) {
	var pattern = /^([0]{1}[0-9]{1,2})-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/;
	var num = value ? value : el.value;
	if (pattern.exec(num)) {
		var hphones = new Array("011","016","017","018","019","010", "070", "060");
		if(in_array(RegExp.$1, hphones, false)) {
			if(!el.getAttribute('SPAN')) el.value = RegExp.$1 + "-" + RegExp.$2 + "-" + RegExp.$3;
			return true;
		}
	}
	if(flag)
		return false;
	else
		return doError(el,NOT_VALID);
}

function isValidTel(el, value){
	
	var result = false;
	var result2 = false;
	var pattern = /^([0]{1}[0-9]{1,2})-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/;
	try{
	var num = value ? value : el.value;
	} catch(e){
		alert(e.message);
	}
	if (pattern.exec(num)) {
		var hphones = new Array("011","016","017","018","019","010", "070", "060");
		if(in_array(RegExp.$1, hphones, false)) {
			if(!el.getAttribute('SPAN')) el.value = RegExp.$1 + "-" + RegExp.$2 + "-" + RegExp.$3;
			return true
		}
	}
	
	if(!result) {
		var pattern = /^([0]{1}[0-9]{1,2})-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/;
		var num = value ? value : el.value;
		if (pattern.exec(num)) {				// 2007-09-30 전화번호 추가(03, 067) by 이창우
			var phones = new Array("020","021","022","023","024","025","026","027","028","029","030","034","035","036","037","038","039","02","03","031","032","033","041","042","043","051","052","053","054","055","061","062","063","064","067", "070", "060");
			if(in_array(RegExp.$1, phones, false)) {
				if(!el.getAttribute('SPAN')) el.value = RegExp.$1 + "-" + RegExp.$2 + "-" + RegExp.$3;
				return true;
			}
		}
	}

	
	return doError(el,NOT_VALID);	

}